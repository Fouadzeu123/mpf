<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeedController extends Controller
{
    /**
     * Affiche le flux vertical de vidéos spirituelles.
     */
    public function index(Request $request): Response
    {
        $videos = Video::latest()->get()->map(function ($video) {
            return [
                'id' => $video->id,
                'category' => $video->category,
                'title' => $video->title,
                'speaker' => $video->speaker,
                'description' => $video->description,
                'bible_reference' => $video->bible_reference,
                'video_url' => $video->resolved_video_url,
                'likes' => $video->likes,
                'shares' => $video->shares,
                'comments_count' => $video->comments_count,
                'date' => $video->created_at->diffForHumans(),
            ];
        });

        return Inertia::render('Feed', [
            'videos' => $videos,
            'isMember' => $request->is('membre/*'),
        ]);
    }
}
