<?php

namespace App\Http\Controllers;

use App\Models\Recentlist;
use App\Services\TMDBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class RecentlistController extends Controller
{
    public function index(TMDBService $tmdb)
    {
         $recentlist = Recentlist::where('user_id', auth()->id())
            ->latest()
            ->take(20)
            ->get();

        return response()->json([
            'results' => $recentlist,
            'page' => 1,
            'total_pages' => 1,
            'total_results' => $recentlist->count(),
        ]);
    }

    public function store(Request $request, TMDBService $tmdb)
{
    $data = $request->validate([
        'type' => 'required|in:movie,tv',
        'tmdb_id' => 'required|integer',
    ]);

    $details = $tmdb->details($data['tmdb_id'], $data['type']);

    Recentlist::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'tmdb_id' => $data['tmdb_id'],
            'type' => $data['type'],
        ],
        [
            'title' => $details['title'] ?? $details['name'] ?? null,
            'poster_path' => $details['poster_path'] ?? null,
            'vote_average' => $details['vote_average'] ?? null,
        ]
    );

    return response()->json([
        'message' => 'Added',
        'details' => $details['title'] ?? $details['name'] ?? null,
    ]);
}

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,tv',
            'tmdb_id' => 'required|integer',
        ]);
        Recentlist::where([
            'user_id' => auth()->id(),
            'type' => $data['type'],
            'tmdb_id' => $data['tmdb_id'],
        ])->delete();

        return response()->json(['message' => 'Removed from recentlist']);
    }
}
