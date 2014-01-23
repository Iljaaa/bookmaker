<?php

class Pagenator extends CWidget
{
	/**
	 * Проект если надо искать в польвателях проекта
	 *
	 * @var string
	 */
	public $url = null;

	public $count = 0;
	public $pagesize = 20;

	public $page = 0;


	public function init()
	{

	}


	public function run()
	{
		$pagesCount = ceil($this->count / $this->pagesize);

		if ($pagesCount > 0) {
			$this->render ('Pagenator', array('pagesCount' => $pagesCount));
		}


	}

}