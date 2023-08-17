<?php

namespace App\Models;

use App\Enums\ClassworkType;
use App\Models\Topic;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classwork extends Model
{
    use HasFactory;
    const TYPE_ASSIGNMENT='assignment';
    const TYPE_MATERIAL='matirial';
    const TYPE_QUESTION='question';

    const STATUS_PUBLISHED='pubished';
    const STATUS_DRAFT='draft';


    protected $fillable = [
        'classroom_id', 'user_id', 'topic_id', 'title',
        'description', 'type', 'status', 'published_at', 'options'
    ];
    public $casts=[
        'options'=>'json',
        'classroom_id'=>'int',
        'published_at'=>'datetime:Y-m-d',
        'type'=>ClassworkType::class,
    ];

    public static function booted()
    {
        static::creating(function (Classwork $classwork){
            if(!$classwork->published_at){
                $classwork->published_at =now();
            }
        });
    }

    public function getPublishedDateAttribute()
    {
        if($this->published_at){
            return $this->published_at->format('Y-m-d');
        }
    }

    public function Classroom(): BelongsTo
    {
        return $this->brlongsTo(Classroom::class,'classroom_id','id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)
        ->withPivot(['grade','submited_at','status','created_at'])
        ->using(ClassworkUser::class);
    }

    // public function comments(){
    //   return $this->morphMany(Comment::class,'commentable');
    // }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }
}
