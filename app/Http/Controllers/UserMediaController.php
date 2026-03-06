<?php

namespace App\Http\Controllers;
use App\Services\TMDBService;
use App\Models\UserMedia;
use Illuminate\Http\Request;

class UserMediaController extends Controller
{
    public function index(string $type)
    {
        $items = UserMedia::where([
            'user_id' => auth()->id(),
            'list_type' => $type
        ])
        ->latest()
        ->take(20)
        ->get();

        return response()->json([
            'results' => $items
        ]);
    }

    public function store(Request $request, string $type, TMDBService $tmdb)
    {
        $data = $request->validate([
            'media_type' => 'required|in:movie,tv',
            'tmdb_id' => 'required|integer'
        ]);

        $details = $tmdb->details($data['tmdb_id'], $data['media_type']);

        UserMedia::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'list_type' => $type,
                'media_type' => $data['media_type'],
                'tmdb_id' => $data['tmdb_id']
            ],
            [
                'title' => $details['title'] ?? $details['name'],
                'poster_path' => $details['poster_path'],
                'vote_average' => $details['vote_average']
            ]
        );

        return response()->json(['message' => 'Added']);
    }

    public function destroy(string $type, string $media_type, int $tmdb_id)
    {
        UserMedia::where([
            'user_id' => auth()->id(),
            'list_type' => $type,
            'media_type' => $media_type,
            'tmdb_id' => $tmdb_id
        ])->delete();

        return response()->json(['message' => 'Removed']);
    }
}
