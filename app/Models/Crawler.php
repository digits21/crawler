<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    use HasFactory;
    protected $fillable=[
    	'url',
        'serial_number',
        'body',
    	'title',
    	'description',
    	'status',
    	'screenshot',
    ];	
}
