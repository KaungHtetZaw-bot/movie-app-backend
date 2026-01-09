<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use App\Transformers\MediaTransformer;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(private TMDBService $tmdb) {}

    public function trending(Request $request)
    {
         $page = (int) $request->query('page', 1);
        return response()->json($this->tmdb->trendingAll($page));
    }

    public function popular(Request $request,$type)
    {
        $page = (int) $request->query('page', 1);
        if (!in_array($type, ['movie', 'tv'])) {
            return response()->json(['error' => 'Invalid media type'], 400);
        }
        return response()->json($this->tmdb->popular($page,$type));
    }

    public function search(Request $request)
    {
        $page = (int) $request->query('page', 1);
         $query = $request->query('query');
        return response()->json($this->tmdb->searchMulti($query,$page));
    }

    public function genres(Request $request, string $type)
    {
        if (!in_array($type, ['movie', 'tv'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json(
            $this->tmdb->genres($type)
        );
    }

    public function byGenre(Request $request, string $type, int $genreId)
    {
        if (!in_array($type, ['movie', 'tv'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $page = (int) $request->query('page', 1);

        return response()->json(
            $this->tmdb->discoverByGenre($type, $genreId, $page)
        );
    }

    public function details($type, $id)
    {
        if (!in_array($type, ['movie', 'tv'])) {
            return response()->json(['error' => 'Invalid media type'], 400);
        }

        return response()->json($this->tmdb->details($id, $type));
    }


}
