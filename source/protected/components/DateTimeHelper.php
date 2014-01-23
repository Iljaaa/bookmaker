<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ilja
 * Date: 03.11.13
 * Time: 15:14
 * To change this template use File | Settings | File Templates.
 */

class DateTimeHelper {

	public static function dateToTimeStamp ($date) {
		$date = date_parse($date);
		return mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
	}

}