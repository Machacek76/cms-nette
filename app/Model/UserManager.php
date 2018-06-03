<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	const
		TABLE_NAME 				= 'users',
		COLUMN_ID 				= 'id',
		COLUMN_NAME 			= 'name',
		COLUMN_USERNAME			= 'username',
		COLUMN_PASSWORD_HASH	= 'password',
		COLUMN_EMAIL 			= 'email',
		COLUMN_STATUS 			= 'status';


	/** @var Nette\Database\Context */
	private $database;

	/** @var \Nette\Localization\ITranslator $translator */
	private $translator;

	private $roleModel;


	public function __construct(Nette\Database\Context $database, \Nette\Localization\ITranslator $translator,
								\App\Model\UserRoleModel $roleModel 
								){
		$this->database = $database;
		$this->translator = $translator;

		$this->roleModel = $roleModel;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_USERNAME, $username)
			->fetch();



		if (!$row) {
			throw new Nette\Security\AuthenticationException($this->translator->translate('admin.signIn.inccorrectName'), self::IDENTITY_NOT_FOUND);

		}else if($row[self::COLUMN_STATUS] !== 1 ) {
			throw new Nette\Security\AuthenticationException($this->translator->translate('admin.signIn.blockAccount'), self::INVALID_CREDENTIAL);
			
		} elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException($this->translator->translate('admin.signIn.inccorrectPassword'), self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);

		$role_arr = $this->roleModel->getRoles( $row[self::COLUMN_ID] );

		return new Nette\Security\Identity($row[self::COLUMN_ID], $role_arr, $arr);
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return void
	 * @throws DuplicateNameException
	 */
	public function add($username, $name, $email, $password, $status)
	{
		try {
			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_USERNAME => $username,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
				self::COLUMN_EMAIL => $email,
				self::COLUMN_NAME => $name,
				self::COLUMN_STATUS => $status,
				
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}


	public function resetPassword ($password, $userId){
		$this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_ID . ' = ?',  $userId)
			->update([self::COLUMN_PASSWORD_HASH => Passwords::hash($password)]);
	}



	/**
	 * get User
	 *
	 * @param string $find username or email
	 * @return active row
	 * @throws UserNotFoundException
	 */
	public function get ( string $find ){
		
		$res =  $this->database->table(self::TABLE_NAME)->select('username, name, email, id')
				->where( self::COLUMN_EMAIL . ' = ? OR ' . self::COLUMN_USERNAME . ' = ?', [$find, $find] )->fetch();
		
		if($res){
			return $res;
		}else{
			throw new UserNotFoundException;
		}
	}


	/**
	 *  Gravatar link
	 * @string
	 */
	public function getAvatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
		$url = '//www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email)));
		$url .= "?s=$s&d=$d&r=$r";
		if ($img) {
			$url = '<img src="' . $url . '"';
			foreach ($atts as $key => $val)
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
}



class DuplicateNameException extends \Exception
{
}

class UserNotFoundException extends \Exception
{
}
