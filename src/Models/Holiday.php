<?php

namespace Balea\Holiday\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Holiday extends Model
{
    protected $connection = "mongodb";
    protected $collection = 'holiday_collection';

    protected $fillable = [
        "title",
        "month",
        "year",
        "shamsi",
        "gregorian",
        "ghamari",
        "event"
    ];
}
