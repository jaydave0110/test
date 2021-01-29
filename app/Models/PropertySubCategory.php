<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertySubCategory extends Model
{
    use HasFactory;
     protected $table = 'tbl_property_sub_category';
    protected $fillable = [
        'name',
        'slug',
        'cat_id',
        'menu_order',
        'status'
    ];

    public function propertyCategory() {
        return $this->belongsTo(PropertyCategory::class, 'cat_id', 'id');
    }
}
