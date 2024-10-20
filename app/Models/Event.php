<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // protected $fillable = ['name', 'description', 'category_id', 'location', 'type',
    //     'price', 'start_date', 'end_date', 'max_attendees'
    // ];

    protected $guarded = [];

    protected function price() : Attribute
    {
        return Attribute::make(
            get: fn (int $price) => $price / 100,
            set: fn (int $price) => $price * 100
        );
    }

    protected function location() : Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value)
        );
    }

    protected function type() : Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtolower($value)
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
