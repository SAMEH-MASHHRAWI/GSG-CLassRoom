<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    public $timestamp = false;
    protected $fillable=[
        'name',
        'classroom',
        'user_id'
    ];
    public function Classworks()
    {
        return $this->hasMany(Classwork::class, 'topic_id', 'id');
    }
}
