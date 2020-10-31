<?php

namespace App\Http\Controllers;

use App\Models\MovieVotes;
use App\Models\SharedMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SharedMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if (isset($request->rows)) {
            $movies = SharedMovie::with('user', 'movieVotes')->paginate($request->rows);
            return $movies;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, ['youtubeURL' => ['required', 'max:255']]);
        $movie = $this->_videoDetails($request->youtubeURL);
        $result = json_decode($movie);
        $dot = Arr::dot($result);
        $snippet =  Arr::get($dot, 'items.0')->snippet;

        if (isset($snippet)) {
            $ytmovie = SharedMovie::create(
                [
                    'video_id' => $request->youtubeURL,
                    'title' => $snippet->title,
                    'description' => $snippet->description,
                    'thumbnails' => json_encode($snippet->thumbnails),
                    'user_id' => $request->user,
                ]
            );
            $added = SharedMovie::with('movieVotes', 'user')->where('id', $ytmovie->id)->first();
            return $added;
        }
    }

    protected function _videoDetails($id)
    {
        $part = 'snippet';
        $apiKey = config('services.youtube.api_key');
        $youtubeEndPoint = config('services.youtube.video_endpoint');
        $url = "$youtubeEndPoint?part=$part&id=$id&key=$apiKey";
        $response = Http::get($url);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SharedMovie  $sharedMovie
     * @return \Illuminate\Http\Response
     */
    public function show(SharedMovie $sharedMovie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SharedMovie  $sharedMovie
     * @return \Illuminate\Http\Response
     */
    public function edit(SharedMovie $sharedMovie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SharedMovie  $sharedMovie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SharedMovie $sharedMovie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SharedMovie  $sharedMovie
     * @return \Illuminate\Http\Response
     */
    public function destroy(SharedMovie $sharedMovie)
    {
        //
    }
}
