<?php

namespace App\Models;

use App\Concerns\HasPrice;
use DeepCopy\f013\C;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasPrice;
    protected $fillable = [
        'plan_id', 'user_id', 'price', 'expires_at', 'status'
    ];
    protected $casts=[
        'expires_at'=>'datetime',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

}
