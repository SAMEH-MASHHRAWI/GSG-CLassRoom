<?php

namespace App\Models;

use App\Concerns\HasPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, HasPrice;
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_feature')
            ->withPivot(['feature_value']);
    }


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }
}
