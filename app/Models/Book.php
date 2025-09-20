<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Scope as EloquentScope;


class Book extends Model
{
    //
    use HasFactory;
    protected $guarded = [];


    public function Reviews()
    {
        return $this->hasMany(Review::class);
    }


    private function dateRangeFilter(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($to) {
            $query->where('created_at', '<=', $to);
        }
        return $query;
    }

    public function Title(Builder $query, string $title): Builder | QueryBuilder
    {
        return $query->with('reviews')->where('title', 'like', "%{$title}%")->withCount('reviews')->orderBy('reviews_count', 'desc');
    }


    public function scopePopular(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $q->whereBetween('created_at', [now()->subMonth(), now()])->orderBy('reviews_count', 'desc')
        ]);
    }

    public function scopeHighestRated(Builder $query): Builder | QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $q->whereBetween('created_at', [now()->subMonth(), now()])
        ], 'rating');
    }

    public function scopePopularLastMonth($query): Builder | QueryBuilder
    {
        return $query->Popular()->whereBetween('created_at', [now()->subMonth(), now()])->HighestRated();
    }

    public function scopePopularLast6Months(Builder $query): Builder | QueryBuilder
    {
        return $query->Popular()->whereBetween('created_at', [now()->subMonths(6), now()])
            ->HighestRated()->orderBy('reviews_count', 'desc');
    }



    public function scopeHighestRatedLastMonth(Builder $query): Builder | QueryBuilder
    {
        return $query->HighestRated()->whereBetween('created_at', [now()->subMonth(), now()]);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder | QueryBuilder
    {
        return $query->HighestRated()->whereBetween('created_at', [now()->subMonths(6), now()])->orderBy('reviews_avg_rating', 'desc');
    }
}
