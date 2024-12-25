<?php

//error_reporting(E_ERROR | E_PARSE); // Suppress warnings and notices
//header('Content-Type: application/json');

//function to send email 
function do_send_mail($app_title, $from, $to, $subj, $msg) {
	include_once('Mail.php'); // includes the PEAR Mail class, already on your server.
	
	//The email headers	
	$cont_type	= "text/html; charset=UTF-8\r\n";
	$headers 	= array ('From' => $app_title.' <'.$from.'>', 'To' => $to, 'Subject' => $subj, "Content-Type" => $cont_type); 
	
	// SMTP protocol with the username and password of 
	//an existing email account in your hosting account
	$smtp = Mail::factory(
		'smtp', array (
		'host'=>'tankotech.com.ng', 
		'auth'=>true, 
		'username'=>'tankote5', 
		'password'=>'w2E;32-TElub6T', 
		'port'=>'25')
	);
	
	//Do send mail
	$mail = $smtp->send($to, $headers, $msg);
	
	if (PEAR::isError($mail)){
		return 'Email error : '.$mail->getMessage();
	}else {
		return NULL;
	}
}

$response = ["success" => false, "message" => "Invalid request."];

//echo $response['message'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {         
		$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);     
		$email 		= filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
		$phone	 	= filter_var($_POST['phone'], FILTER_SANITIZE_STRING); 
  		$subject	= filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
		$message 	= filter_var($_POST['message'], FILTER_SANITIZE_STRING); 
		$ip			= $_SERVER['REMOTE_ADDR'];
		$date_add	= date("d/m/Y h:m A");
	
		$title = "My Portfolio";
		$mymail = "tankojunaidu@gmail.com";
		$msg = "Sender Name: " . $name . "<br>";
		$msg .= "Sender Phone: " . $phone . "<br>";
		$msg .= "Sender Email: " . $email . "<br>";
		$msg .= "IP Address: " . $ip . "<br>";
		$msg .= "Sent Date: " . $date_add . "<br>";
		$msg .= "Subject: " . $subject . "<br>";
		$msg .= "Message: " . $message . "<br>";

	
		$sent = do_send_mail($title, $email, $mymail, $subject, $msg);
		if($sent == NULL){
			$response = ["success" => true, "message" => "Your enquiry has been sent! Please wait while we are responding to it."];
		}else{
			$response = ["success" => false, "message" => $sent];
		}
	
	echo json_encode($response);
	exit;
}

?>