<?php

namespace App\Http\Controllers;
use App\Services\TMDBService;
use App\Models\UserMedia;
use Illuminate\Http\Request;

class UserMediaController extends Controller
{
    public function index(string $type)
    {
        $items = auth('api')
        ->user()
        ->userMedia()
        ->where([
            'list_type' => $type
            ])
        ->latest()
        ->get();

        return response()->json([
            'results' => $items
        ]);
    }

    public function store(Request $request, string $list_type, TMDBService $tmdb)
    {
        $data = $request->validate([
            'media_type' => 'required|in:movie,tv',
            'tmdb_id' => 'required|integer'
        ]);

        $listCount = auth('api')->user()->userMedia()->where('list_type', $list_type)->count();

        if($listCount < 20) {
            $media = $tmdb->details($data['tmdb_id'], $data['media_type']);
    
            UserMedia::updateOrCreate(
                [
                    'user_id' => auth('api')->id(),
                    'list_type' => $list_type,
                    'media_type' => $data['media_type'],
                    'tmdb_id' => $data['tmdb_id']
                ],
                [
                    'title' => $media['title'] ?? $media['name'],
                    'poster_path' => $media['poster_path'],
                    'vote_average' => $media['vote_average']
                ]
            );
    
            return response()->json([
                'message' => 'Added',
                'result' => $listCount + 1
            ]);
        }

        return response()->json([
            'message' => 'limit reached'
        ]);
    }

    public function destroy(string $list_type, string $media_type, int $tmdb_id)
    {
        auth('api')->user()
        ->userMedia()
        ->where([
            'list_type' => $list_type,
            'media_type' => $media_type,
            'tmdb_id' => $tmdb_id
            ])
        ->delete();

        return response()->json(['message' => 'Successfully Removed']);
    }
}
