<?php
session_start();
error_reporting(E_ALL & ~ E_NOTICE);
require ('CustomAPI.class.php');

class Controller
{
    function __construct() {
        $this->processmophoneVerification();
    }
    function processmophoneVerification()
    {
        switch ($_POST["action"]) {
            case "send_otp":
                
                $mophone_number = $_POST['mophone_number'];
                
                $apiKey = urlencode('YOUR_API_KEY');
                $CustomAPI = new CustomAPI(false, false, $apiKey);
                
                $numbers = array(
                    $mophone_number
                );
                $sender = 'PHPPOT';
                $otp = rand(100000, 999999);
                $_SESSION['session_otp'] = $otp;
                $message = "Your One Time Password is " . $otp;
                
                try{
                    $response = $CustomAPI->sendSms($numbers, $message, $sender);
                    require_once ("verification-form.php");
                    exit();
                }catch(Exception $e){
                    die('Error: '.$e->getMessage());
                }
                break;
                
            case "verify_otp":
                $otp = $_POST['otp'];
                
                if ($otp == $_SESSION['session_otp']) {
                    unset($_SESSION['session_otp']);
                    echo json_encode(array("type"=>"success", "message"=>"Your mophone number is verified!"));
                } else {
                    echo json_encode(array("type"=>"error", "message"=>"mophone number verification failed"));
                }
                break;
        }
    }
}
$controller = new Controller();
?>
