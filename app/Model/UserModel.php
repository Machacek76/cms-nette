<?php

namespace App\Model;

class UserModel extends BaseModel{
    



    /** @var tableName */
    public $tableName = 'users';

	/**
	 * get user coll 
	 *
	 * @param array $find
	 * @param string $coll
	 * @return void
	 */
	public function getUser (array $find){
		$res = $this->findOneBy($find);
		return $res;
	}
	
	public function getApiUser ($id){
		$out = [];

		if($id === 'all'){
			$res = $this->findAll();//->select('username', 'name', 'status', 'email');
			$rows = $res->fetchAll();
			$arr = [];
			foreach($rows as $row){
				$arr = $row->toArray();
				unset($arr['password']);
				$out[] = $arr;
				unset($arr);
			}
		}else{
			$res = $this->findOneBy(['id'=>$id]);
			if(!$res){
				$out = NULL;
			}else{
				$out = $res->toArray();
				unset($out['password']);
			}
		}
		return $out;
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


