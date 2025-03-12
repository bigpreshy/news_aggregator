<?php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;

class ArticleFilter
{
    public static function apply(Builder $query, array $filters): Builder
    {
        return $query
            ->when(! empty($filters['search']), fn($q) =>
                $q->whereFullText(['title', 'content'], $filters['search']))
            ->when(! empty($filters['sources']), fn($q) =>
                $q->whereIn('source_name', $filters['sources']))
            ->when(! empty($filters['categories']), fn($q) =>
                $q->whereIn('category', $filters['categories']))
            ->when(! empty($filters['start_date']), fn($q) =>
                $q->whereBetween('published_at', [
                    $filters['start_date'],
                    $filters['end_date'] ?? now(),
                ]));
    }
}
