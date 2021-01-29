<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $table = 'tbl_cities';
	
	protected $fillable = [
        'name',
        'slug',
        'country_id',
        'state_id'
    ];
}
