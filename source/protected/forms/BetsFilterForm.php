<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 2/17/14
 * Time: 7:33 PM
 */

class BetsFilterForm
{
    public $month = 0;

    public $begin = 0;
    public $finish = 0;

    public function setMonth ($month)
    {
        $this->month = $month;

        $beginMon = $month - 1;
        $year = floor($beginMon / 12);
        $mon = $beginMon - ($year * 12);

        $this->begin = mktime(0, 0, 0, $mon, 1, $year);

        yii::app()->firephp->log(date('d.m.Y H:i', $this->begin), 'begi');

        $this->finish = mktime(0, 0, 0, $mon+2, 1, $year);
        yii::app()->firephp->log(date('d.m.Y H:i', $this->finish), '$this->finish');

        if ($this->finish > time()) {
            $this->finish = time();
        }
    }

    public static function getCurrentYear () {
        return date('n') + (12 * date('Y'));
    }

} 