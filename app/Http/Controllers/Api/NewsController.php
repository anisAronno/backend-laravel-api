<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\NewsRequest;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;

class NewsController extends BaseController
{
    protected $newsapi;
    protected $dummySearch;

    public function __construct()
    {
        $this->newsapi = new NewsApi(config('services.news_api.token') ? env('NEWS_API_TOKEN') : '');

        $array = [ 'Food', 'Sports', 'climate', 'politics'];
        $this->dummySearch = $array[array_rand($array)];

    }

    public function fetchUserNews(NewsRequest $request)
    {
        $user =  $request->user()->load('preferences');
        $data = [];

        if($request->has('key')) {
            $data['key'] = $request->key;
            $this->storeSearchItem($request);
        } else {
            $data['key'] = $this->search();

        }

        $data['key'] = $request->key ?? $this->search();

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
        $key =  $request->key ?? $this->dummySearch;

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


    protected function search()
    {
        try {
            $user = auth()->user()
            ->load(['searches' => function ($query) {
                $query->orderByRaw('RAND()')->take(1);
            }]);

            $search = $user->searches->first();
            return $search->key;
        } catch (\Throwable $th) {
            return $this->dummySearch;
        }
    }

    protected function storeSearchItem(Request $request)
    {
        try {
            auth()->user()->searches()->create($request->only('key'));
        } catch (\Throwable $th) {
            logger()->info($th->getMessage());
        }
    }
}
