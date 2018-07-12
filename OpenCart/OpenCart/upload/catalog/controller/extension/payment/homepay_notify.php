<?php 

function check_ip()
{
	if(empty($_SERVER['REMOTE_ADDR']))
		return false;
		
		if(ini_get('allow_url_fopen') != 1)
			return gethostbyname("get.homepay.pl") == $_SERVER['REMOTE_ADDR'];
			
			$handle = fopen('http://get.homepay.pl/index.htm', 'r');
			$data = trim(stream_get_contents($handle));
			fclose($handle);
			return in_array($_SERVER['REMOTE_ADDR'], explode(',', $data));
}

if(!check_ip()) die;
if(!isset($_POST['json']) || empty($_POST['json'])) die;
$json = json_decode($_POST['json']);

$result = array();
foreach($json as $payment)
{
	$payment->tr_id;
	$payment->tr_usr_id;
	$payment->tr_time;
	$payment->tr_status;
	$payment->tr_mode;
	$payment->tr_amount;
	$payment->tr_provision;
	$payment->tr_fee;
	$payment->tr_user_data;
	$payment->tr_email;
	$payment->tr_merchant_label;
	$payment->tr_merchant_data;
	
	array_push($result, array(
			'tr_id' => $payment->tr_id,
			'tr_return' => 1
	));
}

echo json_encode($result);