<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StorePreferenceRequest;
use App\Http\Requests\UpdatePreferenceRequest;
use App\Models\Preference;

class PreferenceController extends BaseController
{ 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePreferenceRequest $request)
    {
        try {
            $response = auth()->user()->preferences()->create($request->all());

            return $this->sendResponse($response, 'Preference stored successfully.');

        } catch (\Throwable $th) {
            return $this->sendError([$th->getMessage()], '', $th->getCode());
        }
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preference $preference)
    {
        try {
            $response = $preference->delete();

            return $this->sendResponse($response, 'Preference deleted successfully.');

        } catch (\Throwable $th) {
            return $this->sendError([$th->getMessage()], '', $th->getCode());
        }
    }
}
