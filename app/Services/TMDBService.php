<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class TMDBService
{
        private string $apiKey;
        private string $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct()
    {
        $this->apiKey = env('TMDB_API_KEY');
    }

    public function popular(int $page = 1,$type)
    {
        return Http::get("{$this->baseUrl}/{$type}/popular", [
            'api_key' => $this->apiKey,
            'page' => $page,
        ])->json()['results'];
    }

    public function trendingAll(int $page = 1)
    {
        return Http::get("{$this->baseUrl}/trending/all/week", [
            'api_key' => $this->apiKey,
            'page' => $page,
        ])->json()['results'];
    }

     public function searchMulti(string $query, int $page = 1)
    {
        return Http::get("{$this->baseUrl}/search/multi", [
            'api_key' => $this->apiKey,
            'query' => $query,
            'page' => $page,
        ])->json()['results'];
    }

    public function genres(string $type = 'movie')
    {
        return Http::get("{$this->baseUrl}/genre/{$type}/list", [
            'api_key' => $this->apiKey,
        ])->json()['genres'];
    }

    public function discoverByGenre(string $type, int $genreId, int $page = 1)
    {
        return Http::get("{$this->baseUrl}/discover/{$type}", [
            'api_key' => $this->apiKey,
            'with_genres' => $genreId,
            'page' => $page,
        ])->json()['results'];
    }

    public function details(int $id, string $type)
    {
        if (!in_array($type, ['movie', 'tv'])) {
            return response()->json(['error' => 'Invalid media type'], 400);
        }
        return Http::get("{$this->baseUrl}/{$type}/{$id}", [
            'api_key' => $this->apiKey,
        ])->json();
    }
}
