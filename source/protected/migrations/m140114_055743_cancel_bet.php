<?php

/**
 *
 *
 * Class m140114_055743_cancel_bet
 */

class m140114_055743_cancel_bet extends CDbMigration
{
	public function up()
	{
		$this->addColumn('balances', 'canceltime', "INT UNSIGNED NULL DEFAULT NULL COMMENT  'время отмены баланса' AFTER  `time`");
		$this->addColumn('bets', 'canceltime', "INT UNSIGNED NULL DEFAULT NULL COMMENT  'время отмены ставки' AFTER  `time`");
	}

	public function down()
	{
		$this->dropColumn('balances', 'canceltime');
		$this->dropColumn('bets', 'canceltime');

		return true;

		//echo "m140114_055743_cancel_bet does not support migration down.\n";
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