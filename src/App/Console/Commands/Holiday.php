<?php

namespace Balea\Holiday\App\Console\Commands;

use DiDom\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;
use Balea\Holiday\Models\Holiday as HolidayModels;

class Holiday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'armanbroker:holiday
                                {--year=1400}
                            {--month=1}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get Holiday';

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
        $year = (int)$this->option('year') ?: now()->format("Y");
        $month = (int)$this->option('month') ?: now()->format("m");
        logger("get month yaer", [
            $year,
            $month
        ]);
        $client = new \GuzzleHttp\Client([
            "base_uri" => env("BASE_URL"),
            'timeout' => 6,
            'connect_timeout' => 6,
            'headers' => []]);
        $url = "https://www.time.ir/";
        $response = $client->post($url, [
            'form_params' => [
                "Year" => $year,
                "Month" => $month,
                "Base1" => 0,
                "Base2" => 1,
                "Base3" => 2,
//                "Responsive" => true,
            ],
        ]);

        $html = $response->getBody()->getContents();

        $document = new Document($html);


        $elements = $document->find('.eventHoliday ');

        $events = [];

        foreach ($elements as $i => $element) {
            $key = Str::slug(convertNumber($element->children()[1]->text()));
            $array = explode("-", $key);
            $events[$array[0]] = trim($element->children()[2]->text());
        }
        $elements = $document->find('.holiday');


        foreach ($elements as $i => $element) {
            $days = [];
            foreach ($element->children()[0]->children() as $day)
                $days[] = Str::slug(convertNumber($day->text()));
            $this->info($days[0]);
            $holiday = HolidayModels::updateOrCreate([
                "month" => $month,
                "year" => $year,
                "shamsi" => (int)$days[0],
            ], [
                    "title" => (new Jalalian($year, $month, $days[0]))->format("%A"),
                    "month" => (int)$month,
                    "year" => (int)$year,
                    "shamsi" => (int)$days[0],
                    "gregorian" => (int)$days[1],
                    "ghamari" => (int)$days[2],
                    "event" => data_get($events, $days[0], null)
                ]
            );
            logger("holiday",[$holiday]);

        }


        return Command::SUCCESS;
    }
}
