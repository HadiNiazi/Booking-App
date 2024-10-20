<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected function name() : Attribute
    {
        return Attribute::make(
            get: fn (string $name) => ucfirst($name),
            set: fn (string $name) => strtolower($name)
        );
    }
}
