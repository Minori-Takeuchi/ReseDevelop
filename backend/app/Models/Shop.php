<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    public function manager()
    {
        return $this->belongsTo(User::class);
    }
}
