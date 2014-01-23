<?php

class m140121_064149_descriptions extends CDbMigration
{
	public function up()
	{
		$this->addColumn('champs', 'parent', "INT UNSIGNED NULL DEFAULT NULL COMMENT 'родительский чамп' AFTER  `id`");
		$this->addColumn('champs', 'description', "VARCHAR(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'время отмены баланса' AFTER  `id`");
		$this->addColumn('teams', 'description', "VARCHAR(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'описание' AFTER  `name`");
	}

	public function down()
	{
		$this->dropColumn('champs', 'parent');
		$this->dropColumn('champs', 'description');
		$this->dropColumn('teams', 'description');
		return false;
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