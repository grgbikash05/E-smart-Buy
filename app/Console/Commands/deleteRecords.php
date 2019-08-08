<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class deleteRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to delete products and top searches today which are 24hours older';

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
     * @return mixed
     */
    public function handle()
    {
        DB::table('products')->where('created_at', '<=', Carbon::now()->subDays())->delete();

        DB::table('top_searches_todays')->where('created_at', '<=', Carbon::now()->subDays())->delete();
    }
}
