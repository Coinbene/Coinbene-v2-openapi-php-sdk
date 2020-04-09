<?php

class Utils
{
    const apiKey = 'XXX'; // 修改成自己的apiId
    const apiSecret = 'XXXX'; // 修改成自己的secret

const SPOT_API_URL = 'http://openapi-exchange.coinbene.com';
const SERVER_TIMESTAMP_URL = '/api/exchange/v2/time';

public  static  function request($requestPath, $params, $method)
{
    if (strtoupper($method) == 'GET') {
        $requestPath .= $params ? '?'.http_build_query($params) : '';
        $params = [];
    }

    $url = self::SPOT_API_URL.$requestPath;

    $body = $params ? json_encode($params, JSON_UNESCAPED_SLASHES) : '';
//    $timestamp = self::getServerTimestamp();
    $timestamp = self::getTimestamp();
    $sign = self::signature($timestamp, $method, $requestPath, $body, self::apiSecret);

    $headers = self::getHeader(self::apiKey, $sign, $timestamp);

    $ch= curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    if($method == "POST") {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);

    $return = json_decode($return,true);

    return $return;
}

public static function getHeader($apiKey, $sign, $timestamp)
{
    $headers = array();

    $headers[] = "Content-Type: application/json";
    $headers[] = "ACCESS-KEY: $apiKey";
    $headers[] = "ACCESS-SIGN: $sign";
    $headers[] = "ACCESS-TIMESTAMP: $timestamp";

    return $headers;
}

public static function getTimestamp()
{
    return date("Y-m-d\TH:i:s"). substr((string)microtime(), 1, 4) . 'Z';
}

public static function getServerTimestamp(){
    try{
        $response = file_get_contents(self::FUTURE_API_URL.self::SERVER_TIMESTAMP_URL);
        $response = json_decode($response,true);

        return $response['iso'];
    }catch (Exception $e){
        return '';
    }
}

public static function signature($timestamp, $method, $requestPath, $body, $secretKey)
{
    $message = (string) $timestamp.strtoupper($method).$requestPath.(string)$body;
    return hash_hmac('sha256', $message, $secretKey, false);
}


}

