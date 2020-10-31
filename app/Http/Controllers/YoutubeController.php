<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    //
    protected function _videoLists($keywords)
    {
        $part = 'snippet';
        $country = 'PH';
        $apiKey = config('services.youtube.api_key');
        $maxResults = 12;
        $youtubeEndPoint = config('services.youtube.search_endpoint');
        $type = 'video';

        $url = "$youtubeEndPoint?part=$part&maxResults=$maxResults&regionCode=$country&type=$type&key=$apiKey&q=$keywords";
        $response = Http::get($url);
        $results = $response;

        //File::put(storage_path() . 'app/public/results.json', $response->body());
        return $results;
    }

    protected function results(Request $request)
    {
        session(['search_query' => $request->search_query]);
        $videoLists = $this->_videoLists($request->search_query);
        return $videoLists;
    }
}
