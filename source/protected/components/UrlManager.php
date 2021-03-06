<?php


class UrlManager extends CUrlManager
{
    public function createUrl($route,$params=array(),$ampersand='&')
    {

        if (!isset($params['language'])) {
            if (Yii::app()->user->hasState('language'))
                Yii::app()->language = Yii::app()->user->getState('language');
            else if(isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
            $params['language']=Yii::app()->language;
        }

        if (substr($route, 0, 1) == '/'){
            $route = substr($route, 1);
        }

        return '/'.$params['language'].'/'.$route;
        // return parent::createUrl($route, $params, $ampersand);
    }
} 