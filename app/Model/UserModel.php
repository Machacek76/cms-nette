<?php

namespace App\Model;

class UserModel extends BaseModel{
    



    /** @var tableName */
    public $tableName = 'users';



    
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


