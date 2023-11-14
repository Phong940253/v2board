<?php

namespace App\Console\Commands;

use App\Models\StatServer;
use App\Services\TelegramService;
use Illuminate\Console\Command;
use App\Http\Controllers\V1\Admin\StatController;

class SendTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:sendtraffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->getStatServer();
        return 0;
    }

    public function getStatServer() {
        $statcontroller = new StatController;
        $data = $statcontroller->getServerLastRankArray();
        $result = "🚥 Báo cáo dung lượng VPN sử dụng hôm qua\n🌞 Powered By: Nguyễn Văn Phong\n🌞 Website: https://phongnguyen.tech\n";
        if ($data) {
            $statistics = $data['data'];
            $telegramService = new TelegramService();
            foreach ($statistics as $row) {
                if (array_key_exists('server_name', $row)) {
                    $up_format = number_format($row['u'] / pow(1024, 3), 2);
                    $down_format = number_format($row['d'] / pow(1024, 3), 2);
                    $total_format = number_format($row['total'], 2);
                    $result .= "{$row['server_name']}: Tải lên {$up_format}Gb, Tải xuống {$down_format}Gb, Tất cẳ {$total_format}Gb\n";
                }
                // $this->info($row['server_id'], $row['u'], $row['d'], $row['total']);
            }
            $telegramService->sendMessage('-1001712589741' ,$result);
            // $this->info($result);
        }
    }
}
