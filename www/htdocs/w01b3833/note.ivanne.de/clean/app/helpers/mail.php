<?php

require APPROOT . '/external/PHPMailer/PHPMailerAutoload.php';

function smtpMail($to, $from, $from_name, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = MAIL_HOST;
    $mail->Port = MAIL_PORT;
    $mail->Username = MAIL_NAME;
    $mail->Password = MAIL_PASS;
    $mail->IsHTML(true);
    $mail->From = MAIL_NAME;
    $mail->FromName = $from_name;
    $mail->Sender = $from;
    $mail->AddReplyTo($from, $from_name);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    $mail->AltBody = strip_tags($mail->Body);
    $mail->addBcc(MAIL_NAME);
    return $mail->Send();
}
