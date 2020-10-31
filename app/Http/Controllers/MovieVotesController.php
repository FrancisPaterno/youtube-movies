<?php

namespace App\Http\Controllers;

use App\Models\MovieVotes;
use App\Models\SharedMovie;
use Illuminate\Http\Request;

class MovieVotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function vote(Request $request)
    {
        if (isset($request->movie_id) && isset($request->user_id) && isset($request->vote)) {
            $currentVote = MovieVotes::where('shared_movie_id', $request->movie_id)->where('user_id', $request->user_id)->first();
            //return $currentVote;
            if (isset($currentVote)) {
                if ($request->vote == $currentVote->vote) {
                    $currentVote->delete();
                } else {
                    $currentVote->update(['vote' => $request->vote]);
                }
            } else {
                MovieVotes::create([
                    'shared_movie_id' => $request->movie_id,
                    'user_id' => $request->user_id,
                    'vote' => $request->vote
                ]);
            }
        }
        $movie = SharedMovie::with('user', 'movieVotes')->where('id', $request->movie_id)->first();
        return $movie;
    }

    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MovieVotes  $movieVotes
     * @return \Illuminate\Http\Response
     */
    public function show(MovieVotes $movieVotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MovieVotes  $movieVotes
     * @return \Illuminate\Http\Response
     */
    public function edit(MovieVotes $movieVotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MovieVotes  $movieVotes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovieVotes $movieVotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MovieVotes  $movieVotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovieVotes $movieVotes)
    {
        //
    }
}
