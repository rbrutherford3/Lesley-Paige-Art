<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'aws_sas.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function sendemail($address, $subject, $body) {
    $mail = new PHPMailer();
    $credentials = aws_sas();

    // Settings
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';

    $mail->Host       = $credentials['HOST'];    // SMTP server example
    $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = $credentials['PORT'];                    // set the SMTP port for the GMAIL server
    $mail->Username   = $credentials['USERNAME'];            // SMTP account username example
    $mail->Password   = $credentials['PASSWORD'];            // SMTP account password example

    // Content
    $mail->isHTML(false);                       // Set email format to HTML
    $mail->SetFrom('no-reply@spiffindustries.com');
    $mail->Subject = $subject;
    $mail->Body = $body;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->AddAddress($address);

    $mail->send();
}

?>