<?php

class App_Admin_Model_Mail
{
    public static function send($to, $subject, $body)
    {
        $mail = new Lib_Mail("smtp.gmail.com", "587", "inspectworld@gmail.com", "vLRqw{myma", "tls");
        
        if($mail->isLogin) {
            $mail->send('inspectworld@gmail.com', $to, $subject, $body);
        } else {
            throw new Exception("Gmail SMTP login failed");
        }
    }
}