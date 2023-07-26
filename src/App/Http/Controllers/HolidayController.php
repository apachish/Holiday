<?php

namespace Balea\Holiday\App\Http\Controllers;

use Balea\Holiday\App\Http\Resources\HolidayCollection;
use Balea\Holiday\App\Jobs\HolidayJob;
use Balea\Holiday\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HolidayController extends Controller
{
    public function getHoliday($year, $month = null)
    {
        $holidayes = $this->getData((int)$year, (int)$month);
        if (!$holidayes->count()) {
            if ($month) {
                dispatch(new HolidayJob($year, $month));
                sleep(5);
            } else {
                for ($i = 1; $i <= 12; $i++) {
                    dispatch(new HolidayJob($year, $i));
                    sleep(5);
                }
            }
            $holidayes = $this->getData((int)$year, (int)$month);
        }

        $data = [
            "count" => $holidayes->count(),
            "holidayes" => new HolidayCollection($holidayes)
        ];

        $message = "تعطیلات " . $year;
        if ($month)
            $message .= " ماه " . $month;
        return response()->json([
            'status' => "Success",
            'meta' => [
                'code' => 200,
                'message' => $message,
            ],
            'data' => $data,
        ], 200, []);
    }

    /**
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getData($year, $month)
    {
        $holidayes = Holiday::where("year", $year);
        if ($month)
            $holidayes->where("month", $month);
        $holidayes = $holidayes->orderBy("month","ASC")->orderBy("shamsi","ASC")->get();
        return $holidayes;
    }
}
