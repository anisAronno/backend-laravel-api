<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreSearchRequest;

class SearchController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSearchRequest $request)
    {
        try {
            $response = auth()->user()->searches()->create($request->all());

            return $this->sendResponse($response, 'Preference stored successfully.');

        } catch (\Throwable $th) {
            return $this->sendError([$th->getMessage()], '', $th->getCode());
        }
    }

}
