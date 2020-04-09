<?php

require 'Utils.php';

class SpotApi extends Utils {


# spot
const SPOT_ACCOUNT_INFO = '/api/exchange/v2/account/list';
const SPOT_COIN_ACCOUNT_INFO = '/api/exchange/v2/account/one';
const SPOT_TICKER = '/api/exchange/v2/market/ticker/list';
const SPOT_ORDER = '/api/exchange/v2/order/place';
const SPOT_CANCEL_ORDER = '/api/exchange/v2/order/cancel';

/**
 * FuturesApi constructor.
 */
public function __construct()
{
}

// 币币账户信息
public function getAccountInfo()
{
    return $this->request(self::SPOT_ACCOUNT_INFO, [], 'GET');
}

// 单一币种账户信息
public function getCoinAccountInfo($symbol)
{
    return $this->request(self::SPOT_COIN_ACCOUNT_INFO, $symbol, 'GET');
}

// 下单
public function takeOrder($symbol, $direction, $price, $quantity, $orderType, $notional = '')
{
    $params = [
        'symbol' => $symbol,
        'direction' => $direction,
        'price' => $price,
        'quantity' => $quantity,
        'orderType' => $orderType,
        'notional'  => $notional
    ];

    return $this->request(self::SPOT_ORDER, $params, 'POST');
}

//撤销指定订单
public function cancelOrder($oid)
{
    $params = [
        'orderId' => $oid
    ];

   return $this->request(self::SPOT_CANCEL_ORDER, $params, 'POST');
}

// 获取全部ticker信息
public function getTicker()
{
    return $this->request(self::SPOT_TICKER, [], 'GET');
}



}


// test
$spot = new SpotApi();
$ret =$spot->getTicker();
//$ret =$spot->getAccountInfo();
//$ret =$spot->takeOrder('BTC/USDT', '1', '10000', '10', '1');
//$ret =$spot->cancelOrder('2021328530083885056');


var_dump($ret);
