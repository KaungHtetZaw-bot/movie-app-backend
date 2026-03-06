<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TMDBService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.themoviedb.org/3';
    private string $imageBaseUrl = 'https://image.tmdb.org/t/p/';

    public function __construct()
    {
        $this->apiKey = env('TMDB_API_KEY');
    }

    /**
     * UPDATED: Added append_to_response
     */
    public function details(int $id, string $type)
    {
        return Cache::remember(
            "tmdb_{$type}_{$id}", 
            now()->addHours(12), 
            function () use ($id, $type) {
                if (!in_array($type, ['movie', 'tv'])) {
                    return ['error' => 'Invalid media type'];
                }

                return Http::get("{$this->baseUrl}/{$type}/{$id}", [
                    'api_key'            => $this->apiKey,
                    'append_to_response' => 'credits,recommendations,videos'
                ])->json();
            }
        );
    }

    public function popular(int $page = 1, $type)
    {
        return Cache::remember(
            "tmdb_popular_{$type}_page_{$page}", 
            now()->addHours(12), 
            function () use ($page, $type) {
                if (!in_array($type, ['movie', 'tv'])) {
                    return ['error' => 'Invalid media type'];
                }

                $response = Http::get("{$this->baseUrl}/{$type}/popular", [
                    'api_key' => $this->apiKey,
                    'page'    => $page,
                ])->json();

                return [
                    'results'     => $response['results'] ?? [],
                    'total_pages' => $response['total_pages'] ?? 1,
                ];
            }
        );
    }

    public function trendingAll(int $page = 1)
    {
        return Cache::remember(
            "tmdb_trending_all_page_{$page}", 
            now()->addHours(12), 
            function () use ($page) {
                $response = Http::get("{$this->baseUrl}/trending/all/day", [
                    'api_key' => $this->apiKey,
                    'page'    => $page,
                ])->json();

                return [
                    'results'     => $response['results'] ?? [],
                    'total_pages' => $response['total_pages'] ?? 1
                ];
            }
        );
    }

    public function searchMulti(string $query, int $page = 1)
    {
        return Cache::remember(
            "tmdb_search_multi_{$query}_page_{$page}", 
            now()->addHours(12), 
            function () use ($query, $page) {
                $response = Http::get("{$this->baseUrl}/search/multi", [
                    'api_key' => $this->apiKey,
                    'query'   => $query,
                    'page'    => $page,
                ])->json();

                return [
                    'results'     => $response['results'] ?? [],
                    'total_pages' => $response['total_pages'] ?? 1
                ];
            }
        );
    }

    public function discoverByGenre(string $type, int $genreId, int $page = 1)
    {
        return Cache::remember(
            "tmdb_discover_{$type}_genre_{$genreId}_page_{$page}", 
            now()->addHours(12), 
            function () use ($type, $genreId, $page) {
                if (!in_array($type, ['movie', 'tv'])) {
                    return ['error' => 'Invalid media type'];
                }

                $response = Http::get("{$this->baseUrl}/discover/{$type}", [
                    'api_key'     => $this->apiKey,
                    'with_genres' => $genreId,
                    'page'        => $page,
                ])->json();

                return [
                    'results'     => $response['results'] ?? [],
                    'total_pages' => $response['total_pages'] ?? 1
                ];
            }
        );
    }

    public function genres(string $type = 'movie')
    {
        return Cache::remember(
            "tmdb_genres_{$type}", 
            now()->addHours(24), 
            function () use ($type) {
                if (!in_array($type, ['movie', 'tv'])) {
                    return ['error' => 'Invalid media type'];
                }
                $response = Http::get("{$this->baseUrl}/genre/{$type}/list", [
                    'api_key' => $this->apiKey,
                ])->json();

                return $response['genres'] ?? [];
            }
        );
    }
}