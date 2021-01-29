<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyCategory extends Model
{
    use HasFactory;

    protected $table = 'tbl_property_category';
    protected $fillable = [
        'name',
        'slug'
    ];

    public function propertySubCategory() {
        return $this->hasMany(PropertySubCategory::class, 'cat_id', 'id');
    }
    
}
