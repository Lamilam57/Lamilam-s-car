<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarView extends Model
{
    use HasFactory;
    
    protected $fillable = ['car_id', 'owner_id', 'user_id', 'views'];

    public function car(){
        return $this->belongsTo(Car::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
