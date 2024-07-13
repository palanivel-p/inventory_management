<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../includes/src/Exception.php';
require '../../includes/src/PHPMailer.php';
require '../../includes/src/SMTP.php';

$email = isset($_GET["email"]) ? $_GET["email"] : ""; // Check if email parameter is set
$password = isset($_GET["password"]) ? $_GET["password"] : ""; // Check if password parameter is set

$user_link = 'https://erp.aecindia.net/field_app/';
$subject = 'AEC LOGIN DETAILS';

$smtpUsername = "noreply@aecindia.net";
$smtpPassword = "Aec_indianet1";

$emailFrom = "noreply@aecindia.net";
$emailFromName = "AEC";

$emailTo = $email; // Use the provided email address
$emailToName = "AEC Staff";

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = "smtp.hostinger.com";
$mail->Port = 587; // Use TLS port
$mail->SMTPSecure = 'tls'; // Use TLS encryption
$mail->SMTPAuth = true;
$mail->Username = $smtpUsername;
$mail->Password = $smtpPassword;
$mail->setFrom($emailFrom, $emailFromName);
$mail->addAddress($emailTo, $emailToName);
$mail->Subject = 'Staff Login Details From AEC';

// Build the URL for the email content
$website = "https://erp.aecindia.net/";
$url = $website . "People/Users/mail.php?user_link=$user_link&email=$email&password=$password&subject=$subject";

$url = str_replace(" ", "%20", $url);
$mail->msgHTML(file_get_contents($url));

try {
    // Attempt to send the email
    $mail->send();
     "Email sent successfully!";
} catch (Exception $e) {
    // Catch any exceptions that occur during sending and display the error message
     "Mailer Error: " . $mail->ErrorInfo;
}
?>
