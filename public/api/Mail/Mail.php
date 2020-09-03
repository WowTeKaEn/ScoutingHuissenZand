<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer {
    private static $instance = null;
    private static $mail = null;

    private function __construct() {
        self::$mail = new PHPMailer(true);
        self::$mail->IsSMTP();
        self::$mail->Host = 'smtp.yandex.com';
        self::$mail->Port = 465;
        self::$mail->SMTPAuth = true;
        self::$mail->SMTPSecure = 'ssl';
        self::$mail->SMTPOptions = array (
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true));
        self::$mail->Username = $_ENV["ADMIN_MAIL_FROM"];
        self::$mail->Password = $_ENV["ADMIN_MAIL_PASSWORD"];
        self::$mail->From = $_ENV["ADMIN_MAIL_FROM"];
        self::$mail->FromName = $_ENV["ADMIN_NAME_FROM"];
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Mailer();
        }

        return self::$instance;
    }

    public function sendMail($subject, $content, $toMail = null, $toName = null, $tries = 0) {
        if($toMail == null){
            $toMail = $_ENV["ADMIN_MAIL_TO"];
        }
        if($toName == null){
            $toName = $_ENV["ADMIN_NAME_TO"];
        }
        try {
            $email = self::$mail;
            $email->AddAddress($toMail, $toName);
            $email->isHTML(true); 
            $email->Subject = $subject;
            $email->Body = "<font size='4'>".$subject."</font><br><br>".$content."<br/><br/><hr/><br/>Dit is een automatisch gegenereerd bericht u kan hier niet op reageren.";
            $email->send();
            return 202;
        }
        catch (Exception $e) {
            if($tries < $_ENV["MAX_MAILER_RETRIES"]){
                return $this->sendmail($subject,$content,$toMail,$toName,$tries + 1);
            }else{
                return 500;
            }
            
        }
    }
}
// pass 07f414bf-cde7-49bc
?>