<?php
session_start();

$id_token = getToken();
$_SESSION['id_token'] = $id_token;
function getToken(){
    $env = parse_ini_file('.env');
    $_SESSION['id_token'] = null;
    $post_token = array(
        'app_key' => $env["APPKEY"],
        'app_secret' => $env["APPSECRET"]
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $env["BASEURL"].'/tokenized/checkout/token/grant',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($post_token),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'username: '.$env["USERNAME"],
        'password: '.$env["PASSWORD"]
    ),
    ));

    $result_data  = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($result_data, true);

    return $response['id_token'];

}

function createPayment($id_token){
    $env = parse_ini_file('.env');
    $curl = curl_init();

    $payment_data = array(
        "mode" => "0011",
        "payerReference" => "SixeR",
        "callbackURL" => "https://sixerpaybkash.carboncodes.net/callback.php",
        "amount" => $_GET["amount"],
        "currency" => "BDT",
        "intent" => "sale",
        "merchantInvoiceNumber" => "SixeR".uniqid()
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => $env["BASEURL"].'/tokenized/checkout/create',
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

    curl_close($curl);
    $response = json_decode($responsedata, true);
    header("Location: ".$response['bkashURL']);
    die();
}
createPayment($id_token)
?>