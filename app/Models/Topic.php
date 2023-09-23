<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'classroom_id', 'user_id'
    ]; // تحديد المسموح (white list)



    public function classworks(): HasMany
    {
        return $this->hasMany(classwork::class, 'topic_id', 'id');
    }
}
