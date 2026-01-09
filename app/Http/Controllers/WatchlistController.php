<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Services\TMDBService;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index(TMDBService $tmdb)
    {
        $watchlist = Watchlist::where('user_id', auth()->id())->get();

        $data = $watchlist->map(function ($item) use ($tmdb) {
            $details = $tmdb->details($item->tmdb_id, $item->type);

            return [
                'id' => $details['id'],
                'type' => $item->type,
                'title' => $details['title'] ?? $details['name'] ?? '',
                'poster' => $details['poster_path'] ?? null,
                'rating' => $details['vote_average'] ?? null,
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

        Watchlist::firstOrCreate([
            'user_id' => auth()->id(),
            'type' => $data['type'],
            'tmdb_id' => $data['tmdb_id'],
        ]);

        return response()->json(['message' => 'Added to watchlist'], 201);
    }

    public function destroy(string $type, int $tmdb_id)
    {
        Watchlist::where([
            'user_id' => auth()->id(),
            'type' => $type,
            'tmdb_id' => $tmdb_id,
        ])->delete();

        return response()->json(['message' => 'Removed from watchlist']);
    }
}
