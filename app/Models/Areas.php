<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    use HasFactory;

    protected $table = 'TBL_AREAS';
    
    protected $fillable = [
        'name',
        'slug',
        'city_id',
        'state_id',
        'country_id',
        'status'
    ];


    public function cities() {
        return $this->belongsTo(Cities::class, 'city_id', 'id');
    }

    public function states() {
        return $this->belongsTo(States::class, 'state_id', 'id');
    }

    public function countries() {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
}
