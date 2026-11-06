<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiInsightService;
use App\Services\SalesAnalyticsService;
use App\Services\ForecastService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function __construct(
    private SalesAnalyticsService $analytics,
    private AiInsightService $ai,
    private ForecastService $forecast
    ) {
    }

    private function resolveDays(Request $request): int
    {
        $days = (int) $request->input('days', 30);
        return in_array($days, [7, 30, 90], true) ? $days : 30;
    }

    public function index(Request $request)
    {
        $days = $this->resolveDays($request);

        return view('admin.insights.index', [
            'days'        => $days,
            'totals'      => $this->analytics->totals($days),
            'bestSellers' => $this->analytics->bestSellers($days, 10),
            'lowStock'    => $this->analytics->lowStock($days),
            'ruleSummary' => $this->ai->ruleBased($days),
            'aiEnabled'   => $this->ai->enabled(),
            'aiCached'    => $this->ai->cached($days),
            'aiStale'     => $this->ai->isStale($days),
            'forecasts'   => $this->forecast->forecastAll(),
        ]);
    }

    public function aiSummary(Request $request)
    {
        $days = $this->resolveDays($request);

        try {
            $result = $this->ai->generate($days);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 502);
        }

        return response()->json([
            'summary' => $result['text'],
            'at'      => Carbon::parse($result['at'])->timezone(config('app.timezone'))->format('d M Y H:i'),
        ]);
    }
}