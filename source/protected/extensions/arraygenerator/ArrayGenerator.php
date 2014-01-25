<?php
/**
 * Класс сохранения данных в файслах с массивами php
 * сейчас класс поддерживать только один уровень вложения массивов
 *
 * User: ilja
 * Date: 09.01.14
 * Time: 11:02
 */

class ArrayGenerator
{

	public function init() {

	}

	/**
	 * Сохраняет массив в файл
	 *
	 * @param array $data
	 * @param string $filePath
	 * @return void
	 */
	public static function saveToFile ($filePath, $data)
	{
        if (!is_writable($filePath)) return false;
		return file_put_contents($filePath, self::generateDataContent($data));
	}

	/**
	 * Добавить запись в ФАЙЛ
	 *
	 *
	 */
	public static function pushToFile ($filePath, $data) {
		if (!file_exists($filePath)){
			static::saveToFile($filePath, array(0=>$data));
			return;
		}

		$fileData = require ($filePath);
		$fileData[] = $data;
		static::saveToFile($filePath, $fileData);
	}

	/**
	 * Генерирем тестовое представление массива
	 *
	 * @param array $data
	 * @return string
	 */
	protected static function generateDataContent ($data)
	{
		$str  = "<?php ";
		$str .= "\r\n/*\r\n * Auto Generate File by ArrayGenerator";
		$str .= "\r\n * generate time : ".date('d.m.Y H:i:s');
		$str .= "\r\n */";
		$str .= "\r\n return array ( ";

		foreach ($data as $key => $val) {
			$key = str_replace("'", "\'", $key);
			$val = str_replace("'", "\'", $val);

			$str .= "\r\n\t'".$key."' => '".$val."', ";
		}

		$str .= "\r\n);";

		return $str;
	}


} 