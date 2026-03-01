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

    /**
     * UPDATED: Added append_to_response
     */
    public function details(int $id, string $type)
    {
        if (!in_array($type, ['movie', 'tv'])) {
            return ['error' => 'Invalid media type'];
        }

        return Http::get("{$this->baseUrl}/{$type}/{$id}", [
            'api_key'            => $this->apiKey,
            'append_to_response' => 'credits,recommendations,videos'
        ])->json();
    }

    public function popular(int $page = 1, $type)
    {
        $response = Http::get("{$this->baseUrl}/{$type}/popular", [
            'api_key' => $this->apiKey,
            'page'    => $page,
        ])->json();

        return [
            'results'     => $response['results'] ?? [],
            'total_pages' => $response['total_pages'] ?? 1,
        ];
    }

    public function trendingAll(int $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/trending/all/day", [
            'api_key' => $this->apiKey,
            'page'    => $page,
        ])->json();

        return [
            'results'     => $response['results'] ?? [],
            'total_pages' => $response['total_pages'] ?? 1 // Fixed typo 'paages'
        ];
    }

    public function searchMulti(string $query, int $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/search/multi", [
            'api_key' => $this->apiKey,
            'query'   => $query,
            'page'    => $page,
        ])->json();

        return [
            'results'     => $response['results'] ?? [],
            'total_pages' => $response['total_pages'] ?? 1 // Fixed typo 'paages'
        ];
    }

    public function discoverByGenre(string $type, int $genreId, int $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/discover/{$type}", [
            'api_key'     => $this->apiKey,
            'with_genres' => $genreId,
            'page'        => $page,
        ])->json();

        return [
            'results'     => $response['results'] ?? [],
            'total_pages' => $response['total_pages'] ?? 1 // Fixed typo 'paages'
        ];
    }

    public function genres(string $type = 'movie')
    {
        return Http::get("{$this->baseUrl}/genre/{$type}/list", [
            'api_key' => $this->apiKey,
        ])->json()['genres'] ?? [];
    }
}