<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\TMDBService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(TMDBService $tmdb)
    {
        $favorites = Favorite::where('user_id', auth()->id())->get();

        $data = $favorites->map(function ($fav) use ($tmdb) {
            $item = $tmdb->details($fav->tmdb_id, $fav->type);

            return [
                'id' => $item['id'],
                'type' => $fav->type,
                'title' => $item['title'] ?? $item['name'] ?? '',
                'poster' => $item['poster_path'] ?? null,
                'rating' => $item['vote_average'] ?? null,
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,tv',
            'tmdb_id' => 'required|integer',
        ]);

        Favorite::firstOrCreate([
            'user_id' => auth()->id() ?? 1,
            'type' => $data['type'],
            'tmdb_id' => $data['tmdb_id'],
        ]);

        return response()->json(['message' => 'Added to favorites'], 201);
    }

    public function destroy(string $type, int $tmdb_id)
    {
        Favorite::where([
            'user_id' => auth()->id(),
            'type' => $type,
            'tmdb_id' => $tmdb_id,
        ])->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }
}
