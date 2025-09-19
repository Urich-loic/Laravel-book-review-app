<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Book extends Model
{
    //
    use HasFactory;
    protected $guarded = [];

    public function Reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function Title(Builder $query, string $title)
    {
        return $query->where('title', 'Like', '%' . $title . '%')->get();
    }
}
