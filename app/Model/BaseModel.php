<?php

namespace App\Model;

use Nette\Utils\Strings;

use App\Components\DateUtils;


/**
 * Description of TableModel
 *
 * @author Milan Machacek <machacek76@gmail.com>
 */



class BaseModel  {

	/** @var Nette\Database\Context */
	public $database;

	/** @var string */
	protected $tableName;
	
	/** @var translator */
	protected $translator;


	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(\Nette\Database\Context $db, \Nette\Localization\ITranslator $translator) {
		$this->database = $db;
		$this->translator = $translator;
		if ($this->tableName === NULL) {
			$class = get_class($this);
			throw new Nette\InvalidStateException("Název tabulky musí být definován v $class::\$tableName.");
		}
	}

	
	public function getTable() {
		return $this->database->table($this->tableName);
	}
	
	/**
	 * Vrací všechny záznamy z databáze
	 * @return \Nette\Database\Table\Selection
	 */
	public function findAll() {
		return $this->getTable();
	}

	/**
	 * Vrací vyfiltrované záznamy na základě vstupního pole
	 * (pole array('name' => 'David') se převede na část SQL dotazu WHERE name = 'David')
	 * @param array $by
	 * @return \Nette\Database\Table\Selection
	 */
	public function findBy(array $by) {
		return $this->getTable()->where($by);
	}

	/**
	 * To samé jako findBy akorát vrací vždy jen jeden záznam
	 * @param array $by
	 * @return \Nette\Database\Table\ActiveRow|FALSE
	 */
	public function findOneBy(array $by) {
		return $this->findBy($by)->limit(1)->fetch();
	}

	/**
	 * Vrací záznam s daným primárním klíčem
	 * @param int $id
	 * @return \Nette\Database\Table\ActiveRow|FALSE
	 */
	public function find($id) {
		return $this->getTable()->select('*')->get($id);
	}

	/**
	 * Upraví záznam
	 * @param array $data
	 */
	public function update($data) {
		return $this->findBy(array('id' => $data['id']))->update($data);
	}

	/**
	 * Upraví záznam
	 * @param array $data
	 */
	public function updateBy($find, $data) {
		return $this->findBy($find)->update($data);
	}

	/**
	 * Vloží nový záznam a vrátí jeho ID
	 * @param array $data
	 * @return \Nette\Database\Table\ActiveRow
	 */
	public function insert($data) {
		return $this->getTable()->insert($data);
	}

	/**
	 * vymaže záznam z daným id
	 * @param type $id
	 */
	public function delete($id) {
		$this->getTable()->where('id', $id)->delete();
	}



	/**
	 * najde a ulozi data do db pokud
	 * pokud je $onlyInsert true, data vlozi pokud neexistuje zaznam
	 * @param array $find
	 * @param array $data
	 * @param bool $onlyInsert
	 * @return type
	 */
	public function save ( array $data, array $find = NULL, $onlyInsert = false ){
		
		if(!$find){
			$res = $this->insert($data);
			return $res;
		}
		
		
		$res = $this->findBy($find);
		$res = $res->fetch();
		
		
		if(!$res){
			$res = $this->insert($data);
		}else if (!$onlyInsert){
			$res = $this->updateBy($find, $data);
		}
		
		
		return $res;
	}

}
