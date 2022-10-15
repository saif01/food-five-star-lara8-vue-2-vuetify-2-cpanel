<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Food\Admin\Setting\ScheduleTimerController;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        // SentOwnerDailyMessage
        // $schedule->command('SentOwnerDailyMessage')->dailyAt('23:00');

        // $schedule->command('backup:run --only-db --disable-notifications')
        // ->withoutOverlapping()
        // ->everyMinute()
        // ->runInBackground()
        // ->onFailure(function () { 
        //     $msg ="DB Backup Schedule Error";
        //     \Log::info($msg);
        //     //ScheduleCheck::ErrorMsgOnLine($msg);
        // })
        // ->onSuccess(function () {
        //     $msg = "5star DB Backup Successfull";
        //     \Log::info($msg); 
        //     //ScheduleCheck::ErrorMsgOnLine($msg);
        // });

        // scheduleTester
        $schedule->command('scheduleTester')
        ->everyMinute();
        // ->dailyAt('08:00');

        
        // SentOwnerDailyMessage
        $alltimeSentOwnerDailyMessage = ScheduleTimerController::scheduleTimeGetByTitleKey('owner-sms-send');
        if(!empty($alltimeSentOwnerDailyMessage)){
            //\Log::info('Running SentOwnerDailyMessage');
            foreach ($alltimeSentOwnerDailyMessage as $time) {
                $schedule->command('SentOwnerDailyMessage')->dailyAt($time)->withoutOverlapping()
                ->onSuccess(function () {
                    $msg ="SentOwnerDailyMessage schedule Success at".$time;
                    \Log::info($msg);
                })
                ->onFailure(function () {
                    $msg ="SentOwnerDailyMessage schedule Error at".$time;
                    \Log::info($msg);
                });
            }
        }

        // ScheduleStoreInvoiceData
        $alltimeScheduleStoreInvoiceData = ScheduleTimerController::scheduleTimeGetByTitleKey('invoice-sync');
        if(!empty($alltimeScheduleStoreInvoiceData)){
            //\Log::info('Running ScheduleStoreInvoiceData');
            foreach ($alltimeScheduleStoreInvoiceData as $time) {
                $schedule->command('ScheduleStoreInvoiceData')->dailyAt($time)->withoutOverlapping()
                ->onSuccess(function () {
                    $msg ="ScheduleStoreInvoiceData schedule Success at".$time;
                    \Log::info($msg);
                })
                ->onFailure(function () {
                    $msg ="ScheduleStoreInvoiceData schedule Error at".$time;
                    \Log::info($msg);
                });
            }
        }

        // alltimeScheduleStoreProductData
        $alltimeScheduleStoreProductData = ScheduleTimerController::scheduleTimeGetByTitleKey('product-sync');
        if(!empty($alltimeScheduleStoreProductData)){
            //\Log::info('Running SyncProductPrice');
            foreach ($alltimeScheduleStoreProductData as $time) {
                $schedule->command('SyncProductPrice')->dailyAt($time)->withoutOverlapping()
                ->onSuccess(function () {
                    $msg ="SyncProductPrice schedule Success at".$time;
                    \Log::info($msg);
                })
                ->onFailure(function () {
                    $msg ="SyncProductPrice schedule Error at".$time;
                    \Log::info($msg);
                });
            }
        }
        // $schedule->command('SyncProductPrice')->dailyAt('23:00')->withoutOverlapping()->onFailure(function () {
        //     $msg ="ScheduleSyncProductPriceData schedule Error";
        //     \Log::info($msg);
        // });



        
    }


    

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
