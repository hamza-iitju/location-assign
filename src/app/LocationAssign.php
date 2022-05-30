<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class LocationAssign extends Model
{
    public $table = 'location_assigns';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = ['location_name', 'location', 'created_at', 'updated_at',];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
