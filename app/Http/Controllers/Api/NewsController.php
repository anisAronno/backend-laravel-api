<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\NewsRequest;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;

class NewsController extends BaseController
{
    protected $newsapi;

    public function __construct()
    {

        $this->newsapi = new NewsApi(config('services.news_api.token') ? env('NEWS_API_TOKEN') : '');

    }

    public function fetchUserNews(Request $request)
    {
        $user =  $request->user()->load('preferences');

        $data = [];

        $data['key'] = $request->key ?? "BBC";

        try {
            if(count($user->preferences) > 0) {
                $data['authors'] = $user->preferences[array_rand($user->preferences->pluck('id')->toArray())]->authors ?? null;
                $data['sources'] = $user->preferences[array_rand($user->preferences->pluck('id')->toArray())]->sources ?? null;
                $data['categories'] = $user->preferences[array_rand($user->preferences->pluck('id')->toArray())]->categories ?? null;

                $response = $this->newsapi->getEverything($data['key'], $data['sources'], $data['authors']);
            } else {

                $response = $this->newsapi->getEverything($data['key']);
            }

            if (!empty($response)) {
                return $this->sendResponse($response, 'Article Retrieved Successfully');
            } else {
                return $this->sendError(['error' => 'No articles found.']);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $response = $e->response();
            $errorMessage = $response->json('message');
            return $this->sendError($response, $errorMessage, $response->status());
        }
    }

    public function fetchNews(NewsRequest $request)
    {
        $key =  $request->key ?? 'BBC';

        try {
            $response = $this->newsapi->getEverything($key);

            if (!empty($response)) {
                return $this->sendResponse($response, 'Article Retrieved Successfully');
            } else {
                return $this->sendError(['error' => 'No articles found.']);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $response = $e->response();
            $errorMessage = $response->json('message');
            return $this->sendError($response, $errorMessage, $response->status());
        }
    }
}
