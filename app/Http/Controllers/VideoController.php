<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    /**
     * Display a listing of the videos for admin view.
     */
    public function index(): Response
    {
        $videos = Video::latest()->get()->map(function ($video) {
            return [
                'id' => $video->id,
                'category' => $video->category,
                'title' => $video->title,
                'speaker' => $video->speaker,
                'description' => $video->description,
                'bible_reference' => $video->bible_reference,
                'video_url' => $video->video_url,
                'video_path' => $video->video_path,
                'resolved_video_url' => $video->resolved_video_url,
                'likes' => $video->likes,
                'shares' => $video->shares,
                'comments_count' => $video->comments_count,
                'date' => $video->created_at->diffForHumans(),
            ];
        });

        return Inertia::render('videos/Index', [
            'videos' => $videos,
        ]);
    }

    /**
     * Store a newly created video in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:predication,prieres,temoignages',
            'title' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bible_reference' => 'nullable|string|max:255',
            'source_type' => 'required|in:file,url',
            'video_url' => 'required_if:source_type,url|nullable|url',
            'video_file' => 'required_if:source_type,file|nullable|file|mimes:mp4,webm,ogg,mov,qt|max:51200', // max 50MB
        ]);

        $videoPath = null;
        if ($request->input('source_type') === 'file' && $request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos', 'public');
        }

        Video::create([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'speaker' => $validated['speaker'],
            'description' => $validated['description'] ?? null,
            'bible_reference' => $validated['bible_reference'] ?? null,
            'video_url' => $request->input('source_type') === 'url' ? $validated['video_url'] : null,
            'video_path' => $videoPath,
        ]);

        return redirect()->route('admin-videos.index')->with('success', 'Vidéo ajoutée avec succès.');
    }

    /**
     * Update the specified video in database.
     */
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'category' => 'required|in:predication,prieres,temoignages',
            'title' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bible_reference' => 'nullable|string|max:255',
            'source_type' => 'required|in:file,url',
            'video_url' => 'required_if:source_type,url|nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov,qt|max:51200',
        ]);

        $videoPath = $video->video_path;
        $videoUrl = $video->video_url;

        if ($request->input('source_type') === 'file') {
            if ($request->hasFile('video_file')) {
                // Delete old local file if it exists
                if ($video->video_path) {
                    Storage::disk('public')->delete($video->video_path);
                }
                $videoPath = $request->file('video_file')->store('videos', 'public');
            }
            $videoUrl = null;
        } else {
            // Source is URL: Delete old file if present
            if ($video->video_path) {
                Storage::disk('public')->delete($video->video_path);
                $videoPath = null;
            }
            $videoUrl = $validated['video_url'];
        }

        $video->update([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'speaker' => $validated['speaker'],
            'description' => $validated['description'] ?? null,
            'bible_reference' => $validated['bible_reference'] ?? null,
            'video_url' => $videoUrl,
            'video_path' => $videoPath,
        ]);

        return redirect()->route('admin-videos.index')->with('success', 'Vidéo modifiée avec succès.');
    }

    /**
     * Remove the specified video from database and local storage.
     */
    public function destroy(Video $video)
    {
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }
        $video->delete();

        return redirect()->route('admin-videos.index')->with('success', 'Vidéo supprimée avec succès.');
    }
}
