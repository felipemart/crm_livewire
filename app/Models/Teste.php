<?php

declare(strict_types = 1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Teste extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = ['performer', 'venue', 'genres', 'ticketsSold', 'performanceDate'];

    protected $casts = ['performanceDate' => 'datetime'];
}
