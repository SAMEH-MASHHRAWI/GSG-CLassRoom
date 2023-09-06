<?php

namespace App\Models;

use App\Models\User;
use App\Models\Topic;
use App\Models\Comment;
use App\Enums\ClassworkType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Classwork extends Model
{
    use HasFactory;
    const TYPE_ASSIGNMENT = ClassworkType::ASSIGNMENT;
    const TYPE_MATERIAL = ClassworkType::MATIRIAL;
    const TYPE_QUESTION = ClassworkType::QUSTION;

    const STATUS_PUBLISHED = 'pubished';
    const STATUS_DRAFT = 'draft';


    protected $fillable = [
        'classroom_id', 'user_id', 'topic_id', 'title',
        'description', 'type', 'status', 'published_at', 'options'
    ];
    public $casts = [
        'options' => 'json',
        'classroom_id' => 'int',
        'published_at' => 'datetime:Y-m-d',
        'type' => ClassworkType::class,
    ];

    public static function booted()
    {
        static::creating(function (Classwork $classwork) {
            if (!$classwork->published_at) {
                $classwork->published_at = now();
            }
        });
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['search'] ?? ' ', function ($builder, $value) {
            $builder->where(function ($builder) use ($value) {
                $builder->where('title', 'LIKE', "%{$value}%")
                    ->orwhere('description', 'LIKE', "%{$value}%");
            });
        })
            ->when($filters['type'] ?? '', function ($builder, $value) {
                $builder->where('type', 'LIKE', "%{$value}%");
            });
    }


    public function getPublishedDateAttribute()
    {
        if ($this->published_at) {
            return $this->published_at->format('Y-m-d');
        }
    }

    public function Classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['grade', 'submited_at', 'status', 'created_at'])
            ->using(ClassworkUser::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }
    // public function comments(){
    //   return $this->morphMany(Comment::class,'commentable');
    // }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
    public function profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'id')
            ->withDefault();
    }
}
