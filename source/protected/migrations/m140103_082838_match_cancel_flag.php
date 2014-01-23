<?php

// ALTER TABLE  `matches` ADD  `resulttime` INT UNSIGNED NULL DEFAULT NULL COMMENT  'время ввода результата' AFTER  `begintime` ,
// ADD  `is_canceled` BOOLEAN NOT NULL DEFAULT FALSE COMMENT  'флаг отмены матча' AFTER  `resulttime` ;

class m140103_082838_match_cancel_flag extends CDbMigration
{
	public function up()
	{
		$this->addColumn('matches', 'resulttime', "INT UNSIGNED NULL DEFAULT NULL COMMENT  'время ввода результата' AFTER  `begintime`");
		$this->addColumn('matches', 'canceltime', "INT UNSIGNED NULL DEFAULT NULL COMMENT  'время отмены' AFTER  `resulttime`");
	}

	public function down()
	{
		$this->dropColumn('matches', 'resulttime');
		$this->dropColumn('matches', 'canceltime');

		//echo "m140103_082838_match_cancel_flag does not support migration down.\n";
		//return false;
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