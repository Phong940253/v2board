<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;

class UnBind extends Telegram {
    public $command = '/unbind';
    public $description = '将Telegram账号从网站解绑';

    public function handle($message, $match = []) {
        if (!$message->is_private) return;
        $user = User::where('telegram_id', $message->chat_id)->first();
        $telegramService = $this->telegramService;
        if (!$user) {
            $telegramService->sendMessage($message->chat_id, 'Thông tin người dùng của bạn chưa được truy vấn, trước tiên hãy liên kết tài khoản của bạn', 'markdown');
            return;
        }
        $user->telegram_id = NULL;
        if (!$user->save()) {
            abort(500, '解绑失败');
        }
        $telegramService->sendMessage($message->chat_id, 'Hủy liên kết thành công', 'markdown');
    }
}
