<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 31.12.13
 * Time: 9:57
 */

class TestController extends Controller
{
	public function actionIndex ()
	{
		$path = YiiBase::getPathOfAlias('application.config').'/dota2loungeparser.teams.php';
		var_dump(require ($path));
	}

	public function actionTestemail()
	{
		Yii::log("ssss", "matchupdateemail", "aaaa");
		echo "333";

		var_dump(mail("the.ilja@gmail.com", "test", "test"));
		echo "end";
	}



	public function actionTestparser ()
	{

		Yii::import('application.parsers.*');
		Yii::import('application.extensions.simple_html_dom');
		$path = YiiBase::getPathOfAlias('application.extensions.simple_html_dom').'.php';
		Yii::import('application.extensions.simple_html_dom');
		require_once ($path);

		$parser = yii::app()->getRequest()->getParam('parser', '');
		if ($parser != ''){
			echo '<h2>Parser log</h2><pre>';
			$a = Parser::factory($parser);
			// $a->setEcho(false);
			$games = $a->parse();
			echo '</pre>';

			echo '<h2>Parser return data</h2><pre>';
			print_r ($games);
			echo '</pre>';

		}


		echo $this->renderPartial('testparser');
	}
} 