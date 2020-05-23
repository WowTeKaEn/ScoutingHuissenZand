<?php

use SendGrid\Mail\From;
use SendGrid\Mail\To;


class SendGridMail {
    private static $instance = null;
    private static $sendGrid = null;

    private function __construct() {
        require "sendgrid-php/sendgrid-php.php";
        self::$sendGrid = new \SendGrid("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");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new SendGridMail();
        }

        return self::$instance;
    }

    public function sendMail($subject, $content, $template = [], $toMail = "woutkn@gmail.com", $toName = "Scouting Huissen Zand Admin",  $fromMail = "admin@scoutinghuissenzand.space", $fromName = "Scouting Huissen Zand Admin") {
        try {
            if(isset($template["id"])){
                $email = new \SendGrid\Mail\Mail(new From($fromMail, $fromName),[new To($toMail,$toName,$template)]);
                $email->setTemplateId($template["id"]);
            }else{
                $email = new \SendGrid\Mail\Mail(new From($fromMail, $fromName),[new To($toMail,$toName,["subject" => $subject, "content" => $content])]);
                $email->setTemplateId("d-b4cba69c9784445b994212a46050df9d");
            } 
            return self::$sendGrid->send($email)->statusCode();
        } catch (Exception $e) {
            return 500;
        }
    }
}

?>