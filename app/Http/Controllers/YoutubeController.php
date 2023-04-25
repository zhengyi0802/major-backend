<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alaouy\Youtube\Facades\Youtube;

class YoutubeController extends Controller
{

    //public static $globallySearchable = false;

    function search(Request $request)
    {
        $type       = $request->input('type');
        if ($type == null) {
            $type = 'video';
        }
        $part       = $request->input('part');
        if ($part == null) {
            $part = 'snippet';
        }
        $maxResults = $request->input('maxResults');
        if ($maxResults == null) {
            $naxResults = 20;
        }
        $q          = $request->input('keywords');

        $key        = $request->input('key');
        if ($key) {
            $key = Youtube::setApiKey($key);
        }

        $pageToken = $request->input('pageToken');

        $params = [
            'q'             => $q,
            'type'          => $type,
            'part'          => $part,
            'maxResults'    => $maxResults,
            'pageToken'     => $pageToken,
        ];

        $search = Youtube::searchForApi($params);

        return $search;

    }

    function home()
    {
         $results = null;
        return view('youtube.home', compact('results'));
    }

    function commit(Request $request)
    {
        $q          = $request->input('keyword');
        $maxResults = $request->input('maxResults');

        $params = [
            'q'             => $q,
            'type'          => 'video',
            'part'          => 'snippet',
            'maxResults'    => $maxResults,
        ];

        // An array to store page tokens so we can go back and forth
        $pageTokens = [];

        // Make inital search
        $search = Youtube::paginateResults($params, null);

        // Store token
        $pageTokens[] = $search['info']['nextPageToken'];
        if (false) {
            // Go to next page in result
            $search = Youtube::paginateResults($params, $pageTokens[0]);

            // Store token
            $pageTokens[] = $search['info']['nextPageToken'];

            // Go to next page in result
            $search = Youtube::paginateResults($params, $pageTokens[1]);

            // Store token
            $pageTokens[] = $search['info']['nextPageToken'];

            // Go back a page
            $search = Youtube::paginateResults($params, $pageTokens[0]);
            $results = json_encode($search);
        } else {
            $search = Youtube::searchForApi($params);
            $results = $search;
        }

        return view('youtube.home', compact('results'));
    }

}
