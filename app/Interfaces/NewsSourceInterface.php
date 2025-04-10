<?php
namespace App\Interfaces;

interface NewsSourceInterface {
    public function fetchArticles();
    public function normalizeData(array $rawData);
}