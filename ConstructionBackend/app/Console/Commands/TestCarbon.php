<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class TestCarbon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-carbon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("start");

        $start_date = "2023-10-12";
        $end_date = "2024-02-17";

        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        dump($start_date->year);
        dump($end_date->day);
        dump($end_date->month);

        dump($end_date->diffInDays($start_date));

        dump($end_date->addDays(10));

        dump($end_date->startOfMonth());
        dump($end_date->endOfMonth());

        $this->error("error ....");
    }
}
