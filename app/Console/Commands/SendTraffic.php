<?php

namespace App\Console\Commands;

use App\Models\StatServer;
use Illuminate\Console\Command;
use App\Http\Controllers\Admin\StatController;

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
    public function escapeJsonString($value) {
        $escapers = array("\'");
        $replacements = array("\\/");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }

    public function getStatServer() {
        $statcontroller = new StatController;
        $data = $statcontroller->getServerLastRankArray();
        if ($data) {
            foreach ($data as $row)
            $this->info($row['server_id']);
        }
    }
}
