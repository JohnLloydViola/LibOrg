<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'author',
        'category',
        'publication_year',
        'quantity',
        'available_quantity',
    ];
}
