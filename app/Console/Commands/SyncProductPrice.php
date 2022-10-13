<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Food\API\ProductPriceController;

class SyncProductPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncProductPrice';

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
        $obj = new ProductPriceController();

       $obj->productPriceSync();

       return Command::SUCCESS;
    }
}
