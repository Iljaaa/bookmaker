<?php

class m131219_060822_main extends CDbMigration
{
	public function up()
	{
		$this->createTable('balances', array(
            'id' 		=> 'int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'uid' 		=> 'int(11) UNSIGNED NOT NULL COMMENT \'user id\'',
            'betid'		=> 'int(11) UNSIGNED',
            'cost'		=> 'float NOT NULL COMMENT \'размер ставки\'',
            'time'		=> 'int(11) UNSIGNED NOT NULL COMMENT \'время занесения\'',
            'type' 		=> 'varchar(10) NOT NULL COMMENT \'in - ручное занесение; bet ставка\'',
        ));
        
        $this->createTable('bets', array(
            'id' 		=> 'int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'uid' 		=> 'int(11) UNSIGNED NOT NULL COMMENT \'user id\'',
            'matchid'	=> 'int(11) UNSIGNED NOT NULL',
            'teamid'	=> 'int(11) UNSIGNED NOT NULL',
            'cost'		=> 'float NOT NULL COMMENT \'размер ставки\'',
            'time'		=> 'int(11) UNSIGNED NOT NULL COMMENT \'время ставки\'',
            'type' 		=> 'VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL',
        ));
        
        $this->createTable('champs', array(
            'id' 		=> 'int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' 		=> 'varchar(256) NOT NULL',
        ));
        
        $this->createTable('matches', array(
            'id' 		=> 'int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'champid' 	=> 'int(11) UNSIGNED',
            'team1' 	=> 'int(11) UNSIGNED NOT NULL',
            'team2' 	=> 'int(11) UNSIGNED NOT NULL',
            'factor1'	=> 'float NOT NULL',
            'factor2'	=> 'float NOT NULL',
            'result1' 	=> 'int(11) UNSIGNED NULL DEFAULT NULL',
            'result2' 	=> 'int(11) UNSIGNED NULL DEFAULT NULL',
            'begintime' => 'int(11) UNSIGNED NULL DEFAULT NULL COMMENT \'баланс пользователя\'',
        ));
        
         $this->createTable('teams', array(
            'id' 		=> 'int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name' 		=> 'varchar(256) NOT NULL',
            'shortname' => 'varchar(30) NOT NULL',
        ));
        
        $this->createTable('users', array(
            'id' 			=> 'int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'login'			=> 'varchar(100) NOT NULL',
            'name'			=> 'varchar(100) NOT NULL',
            'email'			=> 'varchar(100) NOT NULL',
            'password'		=> 'varchar(100) NOT NULL',
            'status'		=> 'varchar(100) NULL COMMENT \'user status\'',
            'balance'		=> 'float NOT NULL COMMENT \'баланс пользователя\'',
            'last_activity'	=> 'int(11) UNSIGNED NOT NULL COMMENT \'последняя активность\'',
        ));


        // teams
        // users
    }

	public function down()
	{
		//  echo "m131219_060822_main does not support migration down.\n";
		// return false;
		
		$this->dropTable('balances');
		$this->dropTable('bets');
		$this->dropTable('champs');
		$this->dropTable('matches');
		$this->dropTable('teams');
		$this->dropTable('users');
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}