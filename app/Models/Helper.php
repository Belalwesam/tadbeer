<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'sku', 'nationality', 'age', 'video', 'avatar', 'resume', 'category_id'];
}
