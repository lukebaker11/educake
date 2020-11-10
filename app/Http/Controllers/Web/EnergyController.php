<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\GuzzleHttpService;

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

    public function index()
    {
        return view('energy', [
            'data' => $this->guzzleHttpService->fetchCurrentData()
        ]);
    }
}
