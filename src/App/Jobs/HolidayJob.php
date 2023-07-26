<?php

namespace Balea\Holiday\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class HolidayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $year;
    protected $month;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year,$month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            logger("get month yaer",[
                '--year' => $this->year, "--month" => $this->month
            ]);
            Artisan::call('armanbroker:holiday', ['--year' => $this->year, "--month" => $this->month]);
        }catch (\Exception $exception) {
            Log::error("create holiday", [
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getTrace(),
                $exception->getLine()
            ]);
        }
    }
}
