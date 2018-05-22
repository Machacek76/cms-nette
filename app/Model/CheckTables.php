<?php

namespace App\Model;

/**
 *
 * @author Mlan machacek <machacek76@gmail.com>
 * 
 */




class CheckTables  extends BaseModel {
	
	
	
	/** @var string */
	protected $tableName = '';
	
	
	
	public function getTables(){
		
		$table = $this->database->query('SELECT table_name AS tab FROM  information_schema.tables WHERE  table_schema = DATABASE()')->fetchAll();
		
		$arr = array();
		foreach ($table as $k=>$v){
			$arr[] = $v->tab;
		}
		
		if (in_array('users', $arr) === FALSE){
			$this->createUser();
		}
		
		if (in_array('user_meta', $arr) === FALSE){
			$this->createUserMeta();
		}
		
		if (in_array('user_role', $arr) === FALSE){
			$this->createUserMeta();
		}
		
		if (in_array('media', $arr) === FALSE){
			$this->createMedia();
		}
		
		
	}
	
	
	
	
	private function createUser(){
		$this->database->query('
			DROP TABLE IF EXISTS `users`;
			CREATE TABLE `users` (
			  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
			  `username` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `password` varchar(4096) COLLATE utf8_czech_ci NOT NULL,
			  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `role` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT \'redaktor\',
			  `status` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  `phone` varchar(24) COLLATE utf8_czech_ci NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

			INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `role`, `status`, `phone`) VALUES
			(1,	\'Admin\',	\'$2y$10$wLSYTxHKdGlccOqcOVqhi.89UmvfXDyZWOROSZbMnLqGIyyGl9oh.\',	\'Admin\',	\'admin@restapi.cz\',	\'admin\',	1,	\'123456789\'),
			(2,	\'TestUser\',	\'$2y$10$TbGLg1BgpBa1HSuB6TcCreYbrWrihFSUlInEDxj0wZqzSlPp/.qo6\',	\'Test User\',	\'testuser@restapi.cz\',	\'editor\',	1,	\'987654321\');
		');
	}
	
	private function createUserMeta() {
		$this->database->query('DROP TABLE IF EXISTS `user_meta`;
			CREATE TABLE `user_meta` (
			  `user_id` int(11) NOT NULL,
			  `key` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `meta` text COLLATE utf8_czech_ci NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;'
		);
	}


	private function createUserRole () {
		$this->database->query('
			DROP TABLE IF EXISTS `user_role`;
			CREATE TABLE `user_role` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_user` int(5) unsigned NOT NULL,
			`role` varchar(25) COLLATE utf8_czech_ci NOT NULL,
			`active` tinyint(1) NOT NULL,
			PRIMARY KEY (`id`),
			KEY `id_user` (`id_user`),
			CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
		');
	}
	
	private function createMedia(){
		$this->database->query("
			DROP TABLE IF EXISTS `media`;
			CREATE TABLE `media` (
			  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
			  `pubdate` datetime NOT NULL,
			  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `alt` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `description` tinytext COLLATE utf8_czech_ci NOT NULL,
			  `author` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `uploader` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `path` varchar(255) COLLATE utf8_czech_ci NOT NULL,
			  `width` int(10) unsigned NOT NULL,
			  `height` int(10) unsigned NOT NULL,
			  `mime` varchar(60) COLLATE utf8_czech_ci NOT NULL,
			  `deleted` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`),
			  KEY `id` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
			"
		);
	}
	
	
	
	
	
	
}
