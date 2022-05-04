<?php

namespace App\Payments;

use App\Models\Order;

class VietQR {
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function form()
    {
        return [
            'paygate_url' => [
                'label' => 'Cổng thanh toán',
                'description' => 'Link cổng thanh toán Ngân hàng https://.......',
                'type' => 'input',
            ],
            'name_bank' => [
                'label' => 'Ngân hàng',
                'description' => 'Loại tài khoản ngân hàng',
                'type' => 'input',
            ],
            'account' => [
                'label' => 'Số tài khoản',
                'description' => 'Số tài khoản ngân hàng',
                'type' => 'input',
            ],
            'name' => [
                'label' => 'Tên',
                'description' => 'Tên chủ sở hữu tài khoản',
                'type' => 'input',
            ],
            'cipher_key' => [
                'label' => 'Cipher key',
                'description' => 'Key mã hóa',
                'type' => 'input',
            ]
        ];
    }

    public function pay($order)
    {
        $order['total_amount'] = $order['total_amount'];
        $order['paygate_url'] = $this->config['paygate_url'];
        $order['name_bank'] = $this->config['name_bank'];
        $order['account'] = $this->config['account'];
        $order['name'] = $this->config['name'];
        $order['cipher_key'] = $this->config['cipher_key'];


        $cipher_method = 'aes-128-ctr';
        $enc_key = $order['cipher_key'];
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
        $crypted_token = openssl_encrypt(json_encode($order), $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
        unset($token, $cipher_method, $enc_key, $enc_iv);
        $sig = bin2hex($crypted_token);

        return [
            'type' => 1, // 0:qrcode 1:url
            'data' => $this->config['paygate_url']."/?sig=".$sig
        ];
    }

    public function notify($params)
    {
        $token = $params['token'];
		if($this->config['cipher_key'] != $token)
			return false;


        return [
            'trade_no' => $params['trade_no'],
            'callback_no' => $params['out_trade_no']
        ];
    }


}
