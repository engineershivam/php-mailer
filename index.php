<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// for cross origin
// header('Access-Control-Allow-Origin: *'); 
// header("Access-Control-Allow-Credentials: true");
// header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');



require 'mailer/Exception.php';
require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';

$input_data = file_get_contents('php://input');
$data = json_decode($input_data);

$response_arr = [];
$response_arr['status'] = true;

function validateString($inputString, $pattern) {
    return preg_match($pattern, $inputString) === 1;
}


$textValidat = '/^[a-zA-Z\s]+$/';
$numberValidate = '/^\d{10}$/';



if(!validateString($data->name, $textValidat)) {
    $response_arr['status'] = false;
    $response_arr['name'] = "Enter Valid Name";
}

if(!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    $response_arr['status'] = false;
    $response_arr['email'] = "Enter Valid Email";
}

if(!validateString($data->phone, $numberValidate)) {
    $response_arr['status'] = false;
    $response_arr['phone'] = "Enter Valid Phone";
}

if($data->message=='') {
    $response_arr['status'] = false;
    $response_arr['message'] = "Enter Message";
}

if($response_arr['status']==false){
    header('Content-Type: application/json');
    http_response_code(500); 
    echo json_encode($response_arr);
    die();
}else{
$msg = '';
$msg .='<table cellspacing="0" cellpadding="10" border="1">';
foreach ($data as $key => $value) {
    $msg .='<tr><td><b>'.$key.'</b></td><td>'.$value.'</td></tr>';
}
$msg .='</table>';

$mail = new PHPMailer(true);
try {
    //Server settings


    // $mail->SMTPDebug = false;
    // $mail->isSMTP();
    // $mail->Host       = 'aamazingdeal.com';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = 'emailtest@aamazingdeal.com';
    // $mail->Password   = 'Shivam@123';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    // $mail->Port       = 465;


    $mail->SMTPDebug = false;
    $mail->isSMTP();
    $mail->Host       = '';
    $mail->SMTPAuth   = true;
    $mail->Username   = '';
    $mail->Password   = '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    //Recipients
    // $mail->setFrom('emailtest@aamazingdeal.com', 'New Request');
    $mail->setFrom('', 'New Request');
    $mail->addAddress('shivam50091@gmail.com', 'From website');
    //$mail->addCC('cc@example.com');
    $mail->isHTML(true);
    $mail->Subject = 'New Request from website';
    $mail->Body    = $msg;
    $mail->send();
    header('Content-Type: application/json');
    http_response_code(200); 
    $response_arr['status'] = true;
    echo json_encode($response_arr);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500); 
    $response_arr['status'] = false;
    $response_arr['error'] = $mail->ErrorInfo;
    echo json_encode($response_arr);
    die();
}



}