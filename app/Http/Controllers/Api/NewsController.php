<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\NewsRequest;
use jcobhams\NewsApi\NewsApi;

class NewsController extends BaseController
{
    protected $newsapi;

    public function __construct()
    {

        $this->newsapi = new NewsApi(config('services.news_api.token') ? env('NEWS_API_TOKEN') : '');

    }




    public function fetchNews(NewsRequest $request)
    {

        try {
            $response = $this->newsapi->getEverything($request->key, $request->sources, $request->domains, $request->exclude_domains, $request->from, $request->to, $request->language, $request->sort_by, $request->page_size, $request->page);

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
