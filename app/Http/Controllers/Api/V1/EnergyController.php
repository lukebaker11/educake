<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\GuzzleHttpService;
use Illuminate\Http\Request;

class EnergyController extends Controller
{
    /** @var GuzzleHttpService */
    var $guzzleHttpService;

    /**
     * EnergyController constructor.
     * @param GuzzleHttpService $guzzleHttpService
     */
    public function __construct(GuzzleHttpService $guzzleHttpService)
    {
        $this->guzzleHttpService = $guzzleHttpService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dates(Request $request)
    {
        $attributes = Helper::unserializeForm($request->get('data'));

        // I need to add a minute to the from datetime or it gets the previous 30 min batch too :/
        $data = $this->guzzleHttpService->fetchDataBetween(
            Helper::formDatePickerToDateTime($attributes['from'])->addMinutes(1)->format(GuzzleHttpService::TIME_FORMAT),
            Helper::formDatePickerToDateTime($attributes['to'])->format(GuzzleHttpService::TIME_FORMAT)
        );

        return response()->json($data);
    }
}
