<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;

class NewsController extends BaseController
{
    /**
     * @var PendingRequest
     */
    protected $httpClient;

    public function __construct(PendingRequest $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->httpClient->baseUrl(
            sprintf('https://newsapi.org/v2')
        )->contentType('appilcation/json')
            ->acceptJson()
            ->withToken(config('services.news_api.token') ? env('NEWS_API_TOKEN') : '');
    }


  
    
    public function fetchNews(Request $request)
    {

        try {
            $response = $this->httpClient->get('/everything', [
                'q' => $request->key,
                'from' => $request->from,
                'sortBy' => 'publishedAt',
            ]);

            $articles = $response->json('articles');

            if (!empty($articles)) {
                return $this->sendResponse($articles, 'Article Retrieved Successfully');
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
