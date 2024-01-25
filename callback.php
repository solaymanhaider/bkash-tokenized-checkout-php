<?php
session_start();
$status = $_GET['status'];
$paymentid = $_GET['paymentID'];
$id_token = $_SESSION['id_token'];



if( $status == 'failure' || $status == 'cancel' ){
    header("Location: /failed.php");
} elseif( $status == "success"){
    $env = parse_ini_file('.env');
    $curl = curl_init();

    $payment_data = array(
        "paymentID" => $paymentid
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => $env["BASEURL"].'/tokenized/checkout/execute',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payment_data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'authorization: '.$id_token,
            'x-app-key: '.$env["APPKEY"]
        ),
    ));

    $responsedata = curl_exec($curl);
    $response = json_decode($responsedata, true);

    curl_close($curl);

    if($response["transactionStatus"] == "Completed"){
        header("Location: /success.php");
    } else {
        header("Location: /failed.php");
    }

}