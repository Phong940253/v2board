<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;
use App\Utils\Helper;

class Traffic extends Telegram {
    public $command = '/traffic';
    public $description = '查询流量信息';

    public function handle($message, $match = []) {
        $telegramService = $this->telegramService;
        if (!$message->is_private) return;
        $user = User::where('telegram_id', $message->chat_id)->first();
        if (!$user) {
            $telegramService->sendMessage($message->chat_id, 'Thông tin người dùng của bạn chưa được truy vấn, trước tiên hãy liên kết tài khoản của bạn', 'markdown');
            return;
        }
        $transferEnable = Helper::trafficConvert($user->transfer_enable);
        $up = Helper::trafficConvert($user->u);
        $down = Helper::trafficConvert($user->d);
        $remaining = Helper::trafficConvert($user->transfer_enable - ($user->u + $user->d));
        $text = "🚥Truy vấn lưu lượng\n———————————————\nTổng lưu lượng：`{$transferEnable}`\nTải lên：`{$up}`\nTải xuống：`{$down}`\nCòn lại：`{$remaining}`";
        $telegramService->sendMessage($message->chat_id, $text, 'markdown');
    }
}
