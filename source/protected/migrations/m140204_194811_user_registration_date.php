<?php

class m140204_194811_user_registration_date extends CDbMigration
{
	public function up()
	{
        $this->addColumn('users', 'regisrtime', "INT UNSIGNED NULL DEFAULT NULL COMMENT  'время регистрации' AFTER  `balance`");
	}

	public function down()
	{
        $this->dropColumn('users', 'regisrtime');
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