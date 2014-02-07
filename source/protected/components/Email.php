<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 2/7/14
 * Time: 7:31 PM
 */

class Email {

    public static function sendEmail (){

    }

    public static function sendSystemEMail ($to, $subject, $body, $sender = '')
    {
        if ($sender == '') {
            $sender = Yii::app()->params['robotEmail'];
        }

        $name='=?UTF-8?B?'.base64_encode($to).'?=';
        $subject='=?UTF-8?B?'.base64_encode($subject).'?=';

        $headers =  "From: ".$sender."\r\n".
                    "MIME-Version: 1.0\r\n".
                    "Content-Type: text/html; charset=UTF-8";

        return mail($to, $subject, $body, $headers);
    }

} 