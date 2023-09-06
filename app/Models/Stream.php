<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stream extends Model
{

    use HasFactory, HasUuids;

    protected $keyType = 'string';
    protected $guarded = [];
    // protected $fillable = [
    //     'classroom_id', 'user_id', 'content', 'link'
    // ];

    public function getUpdatedAtColumn()
    {
    }

    public function setUpdatedAt($value)
    {
        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function classwork()
    {
        return $this->belongsTo(classwork::class);
    }
}
