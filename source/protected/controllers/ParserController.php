<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 09.01.14
 * Time: 9:45
 */

class ParserController extends Controller
{

	/**
	 *
	 *
	 *
	 */
	public function actionCompare ()
	{
		$path = yii::getPathOfAlias('application.parsers.compare');
		$dirs = scandir($path);

		// update file
		$this->updateFiles();

		$files = array ();
		foreach ($dirs as $dir) {
			if ($dir == '.' || $dir == '..') continue;
			$file = array (
				'name'      => $dir,
				'fullPatch' => $path.'/'.$dir,
				'touch'     => filemtime($path.'/'.$dir)
			);

			$file['data'] = require ($file['fullPatch']);

			$files[] = $file;
		}


		$data = array (
			'files' => $files
		);

		$this->render ('compare', $data);
	}

	/**
	 * Обновление файлов
	 *
	 */
	protected function updateFiles ()
	{
		$data = yii::app()->getRequest()->getParam('data', array());
		$newRows = yii::app()->getRequest()->getParam('newrow', array());

		$val = (is_array($data) && count($data) > 0);
		if (!$val) return;

		$path = yii::getPathOfAlias('application.parsers.compare');
		foreach ($data as $fileName => $fileData)
		{
			//
			$filePath = $path.'/'.$fileName;
			if (!file_exists($filePath)) {
				yii::app()->user->setFlash('parser-compare-bad', 'File "'.$filePath.'" not found ');
				$this->refresh();
				return;
			}

			// делаем бекап файла
			if (!$this->makeFileBackUp($filePath)){
				yii::app()->user->setFlash('parser-compare-bad', 'File "'.$filePath.'" can not be backup');
				$this->refresh();
				return;
			}

			// обрабатываем ранее добавленые строки
			$filtredData = array();
			foreach ($fileData as $it)
			{
				$key = '';
				if (isset($it['key'])) $key = trim($it['key']);


				$val = '';
				if (isset($it['value'])) $val = trim($it['value']);

				if ($key == '' || $val == '') continue;
				$filtredData[$key] = $val;
			}

			// добавляем новые поля
			foreach ($newRows as $newRow)
			{
				$key = trim($newRow['key']);
				$val = trim($newRow['value']);

				if ($key == '' || $val == '') continue;
				$filtredData[$key] = $val;
			}

			// сохраняем данные
			yii::import('application.extensions.arraygenerator.ArrayGenerator');
			if (ArrayGenerator::saveToFile($filePath, $filtredData)) {
				yii::app()->user->setFlash('parser-compare-good', 'File "'.$filePath.'" saved');
			}
			else {
				yii::app()->user->setFlash('parser-compare-bad', 'File "'.$filePath.'" NOT saved');
			}

			$this->refresh();
		}
	}

	/**
	 * Создаем бекап файла
	 *
	 */
	protected function makeFileBackUp ($filePath)
	{
		if (!file_exists($filePath)) return;

		$fileInfo = pathinfo($filePath);

		$backupFileName = $fileInfo['basename'].'_backup_'.date('d.m.Y_H.i.s');

		$backupPath = yii::getPathOfAlias('application.runtime.backup');

		// пробуем создать директорию для бекапа
		if (!file_exists($backupPath)) mkdir($backupPath);

		//
		$backUpFilePath = $backupPath.'/'.$backupFileName;

		return copy($filePath, $backUpFilePath);
	}

}