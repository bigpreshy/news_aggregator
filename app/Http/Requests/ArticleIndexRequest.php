<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleIndexRequest extends FormRequest
{
    public function rules()
    {
        return [
            'search'       => 'sometimes|string|max:255',
            'sources'      => 'sometimes|array',
            'sources.*'    => 'string',
            'categories'   => 'sometimes|array',
            'categories.*' => 'string|in:business,sports,technology',
            'start_date'   => 'sometimes|date',
            'end_date'     => 'sometimes|date|after_or_equal:start_date',
        ];
    }
}
