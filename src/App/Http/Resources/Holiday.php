<?php

namespace Balea\Holiday\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class Holiday extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = (new Jalalian($this->year, $this->month, $this->shamsi));
        return [
            "month" => $this->month,
            "year" => $this->year,
            "title" => $this->title,
            "day-shamsi" => $this->shamsi,
            "day-gregorian" => $this->gregorian,
            "day-ghamari" => $this->ghamari,
            "date-persian" => $date->format('%A, %d %B %Y'),
            "date-gregorian" => $date->toCarbon()->format('Y-m-d'),
            "title_month" => $date->format('%B'),
            "event" => trim($this->event),

        ];
    }
}
