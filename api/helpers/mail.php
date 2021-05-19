<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer {
    private static $instance = null;

    private function createMail() {
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->Host = 'smtp.yandex.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPOptions = array (
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true));
        $mail->Username = $_ENV["ADMIN_MAIL_FROM"];
        $mail->Password = $_ENV["ADMIN_MAIL_PASSWORD"];
        $mail->From = $_ENV["ADMIN_MAIL_FROM"];
        $mail->FromName = $_ENV["ADMIN_NAME_FROM"];
        return $mail;
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
            $email = self::createMail();
            $email->AddAddress($toMail, $toName);
            $email->isHTML(true); 
            $email->Subject = $subject;
            $email->Body = "<font size='4'>".$subject."</font><br/>".$content."<br/><hr/><br/>Dit is een automatisch gegenereerd bericht u kan hier niet op reageren.";
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