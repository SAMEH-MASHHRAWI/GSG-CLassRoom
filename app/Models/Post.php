<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Post extends Model
{
    use HasFactory;
    public function comments()
    {
        Relation::enforceMorphMap([
        'post'=>Post::class,
        'classwork'=>Classwork::class,
        ]);
    }
}
