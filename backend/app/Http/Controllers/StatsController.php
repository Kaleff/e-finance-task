<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatsService;

class StatsController extends Controller
{

    public function __construct(private StatsService $statsService)
    {}

    public function index()
    {
        $stats = $this->statsService->getStats();
        return response()->json($stats);
    }

    public function projectOverview()
    {
        $stats = $this->statsService->getProjectOverview();
        return response()->json($stats);
    }
}
