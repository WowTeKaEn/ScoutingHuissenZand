<?php

use SendGrid\Mail\From;
use SendGrid\Mail\To;


class SendGridMail {
    private static $instance = null;
    private static $sendGrid = null;

    private function __construct() {
        self::$sendGrid = new \SendGrid($_ENV["SENDGRID_API_KEY"]);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new SendGridMail();
        }

        return self::$instance;
    }

    public function sendMail($subject, $content, $template = [], $toMail = null, $toName = null,  $fromMail = null, $fromName = null) {
        if($toMail == null){
            $toMail = $_ENV["ADMIN_MAIL_TO"];
        }
        if($toName == null){
            $toName = $_ENV["ADMIN_NAME_TO"];
        }
        if($fromMail == null){
            $fromMail = $_ENV["ADMIN_MAIL_FROM"];
        }
        if($fromName == null){
            $fromName = $_ENV["ADMIN_NAME_FROM"];
        }

        try {
            if(isset($template["id"])){
                $email = new \SendGrid\Mail\Mail(new From($fromMail, $fromName),[new To($toMail,$toName,$template)]);
                $email->setTemplateId($template["id"]);
            }else{
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom($fromMail, $fromName);
                $email->setSubject($subject);
                $email->addTo($toMail,$toName);
                $email->addContent("text/html","<font size='4'>".$subject."</font><br><br>".$content);
            } 
            return self::$sendGrid->send($email)->statusCode();
        } catch (Exception $e) {
            return 500;
        }
    }
}

?>