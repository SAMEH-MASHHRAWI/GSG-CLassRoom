<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
       'id', 'user_id', 'classwork_id', 'content', 'type',
    ];
    public function classwork(){
        return $this->belongsTo(Classwork::class);
    }

    public function getUpdatedAtColumn()
    {

    }

    public function getCreatedAtColumn()
    {

    }
}
