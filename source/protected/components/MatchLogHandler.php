<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 19.01.14
 * Time: 10:40
 */

class MatchLogHandler {


	public static function pushMessage ($matchId, $data)
	{
		// серилизованая дата
		$dataStr = json_encode($data);

		// путь к файлу
		$filePath = static::getFilePath ($matchId);

		//
		yii::app()->arraygenerator->pushToFile($filePath, $dataStr);
	}

	public static function getMessages ($matchId) {
		$filePath = static::getFilePath ($matchId);

		if (!file_exists($filePath)){
			return array();
		}

		$fileData = require ($filePath);
		foreach ($fileData as $key => $val){
			$val = json_decode($val, true);
			$fileData[$key] = $val;
		}

		return $fileData;
	}

	/**
	 * Путь к файлу с логами
	 *
	 * @param $matchId
	 */
	protected static function getFilePath ($matchId) {
		return yii::getPathOfAlias('application.runtime.matches').'/'.$matchId.'.tech.php';
	}

} 