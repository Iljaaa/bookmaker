<?php

class m140103_074539_userRole extends CDbMigration
{
	public function up()
	{
		$this->addColumn('users', 'role', "VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT  'роль пользователя' AFTER  `password`");
	}

	public function down()
	{
		// echo "m140103_074539_userRole does not support migration down.\n";
		//return false;

		$this->dropColumn('users', 'role');
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