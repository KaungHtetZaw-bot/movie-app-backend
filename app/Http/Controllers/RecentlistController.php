<?php

namespace App\Http\Controllers;

use App\Models\Recentlist;
use App\Services\TMDBService;
use Illuminate\Http\Request;


class RecentlistController extends Controller
{
    public function index(TMDBService $tmdb)
    {
        $recentlist = Recentlist::where('user_id', auth()->id())->get();

        $data = $recentlist->map(function ($item) use ($tmdb) {
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

        Recentlist::firstOrCreate([
            'user_id' => auth()->id(),
            'type' => $data['type'],
            'tmdb_id' => $data['tmdb_id'],
        ]);

        return response()->json(['message' => 'Added to recentlist'], 201);
    }

    public function destroy(string $type, int $tmdb_id)
    {
        Recentlist::where([
            'user_id' => auth()->id(),
            'type' => $type,
            'tmdb_id' => $tmdb_id,
        ])->delete();

        return response()->json(['message' => 'Removed from recentlist']);
    }
}
