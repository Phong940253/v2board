<?php

namespace App\Payments;

use App\Models\Order;

class MomoSv3 {
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function form()
    {
        return [
            'paygate_url' => [
                'label' => 'Gmail',
                'description' => 'Gmail liên kết momo',
                'type' => 'input',
            ],
            'momo_phone' => [
                'label' => 'Password gmail',
                'description' => 'Mật khẩu gmail momo',
                'type' => 'input',
            ],
        ];
    }

    public function pay($order)
    {
        // IPN notifications are slow, when the transaction is successful, we should return to the user center to avoid user confusion
        $parseUrl = parse_url($order['return_url']);
        $port = isset($parseUrl['port']) ? ":{$parseUrl['port']}" : '';
        $successUrl = "{$parseUrl['scheme']}://{$parseUrl['host']}{$port}";

        $params = [
            'amountf' => sprintf('%.2f', $order['total_amount'] / 100),
            'notify_url' => $order['notify_url'],
            'return_url' => $order['return_url'],
            'ipn_url' => $order['notify_url']
        ];

        $params_string = http_build_query($params);

        return [
            'type' => 1, // Redirect to url
            'data' =>  "https://me.momo.vn/MRIBTbsmuesoC9tXsOuzIm"
        ];
    }

    public function notify($params)
    {
        $token = $params['token'];
		if($this->config['skyhtpw_key'] != $token)
			return false;


        return [
            'trade_no' => $params['trade_no'],
            'callback_no' => $params['out_trade_no']
        ];
    }


}
