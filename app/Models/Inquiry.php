<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;
    protected $table = 'tbl_inquiry';

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
