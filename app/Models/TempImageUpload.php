<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempImageUpload extends Model
{
    use HasFactory;
    protected $table = 'tmp_image_upload';
	public $timestamps = false;
	
	protected $fillable = [
        'id',
        'image_name',
        'image_category',
        'datecreated'
    ];
}
