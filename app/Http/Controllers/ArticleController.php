<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Http\Requests\ArticleIndexRequest;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleRepository $repository
    ) {}



    public function index(ArticleIndexRequest $request)
    {
        try {
            return $this->repository->getPaginatedArticles(
                $request->validated()
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}