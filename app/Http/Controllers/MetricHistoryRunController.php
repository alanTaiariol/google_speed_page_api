<?php

namespace App\Http\Controllers;

use App\Models\MetricHistoryRun;
use App\Models\Category;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MetricHistoryRunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            $strategies = Strategy::all();
            $metricHistoryRun = MetricHistoryRun::all();
            return view('metrics', ["categories" => $categories, "strategies" => $strategies, "metricHistoryRun" => $metricHistoryRun] );
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    

    public function create(Request $request)
    {
        try {
            $url = $request->input('url');
            $strategy = $request->input('strategy');
            $category = $request->input('scores');
            $array_scores = ['accesibility_metric' => null, 'pwa_metric' => null, 'performance_metric' => null, 'seo_metric' => null, 'best_practices_metric' => null ];

            foreach($category as $c) {
                if($c['title'] == 'Accessibility') {
                    $array_scores['accesibility_metric'] = $c['score'];
                }
                if($c['title'] == 'PWA') {
                    $array_scores['pwa_metric'] = $c['score'];
                }
                if($c['title'] == 'Performance') {
                    $array_scores['performance_metric'] = $c['score'];
                }
                if($c['title'] == 'SEO') {
                    $array_scores['seo_metric'] = $c['score'];
                }
                if($c['title'] == 'Best Practices') {
                    $array_scores['best_practices_metric'] = $c['score'];
                }
            }

            $metricHistoryRun = MetricHistoryRun::create([
                'url' => $url,
                'accesibility_metric' => $array_scores['accesibility_metric'], 
                'pwa_metric' => $array_scores['pwa_metric'],
                'performance_metric' => $array_scores['performance_metric'],
                'seo_metric' => $array_scores['seo_metric'],
                'best_practices_metric' => $array_scores['best_practices_metric'],
                'strategy_id' => $strategy
            ]);

            $metricHistoryRun = MetricHistoryRun::latest('created_at')->first();

            return response()->json(['message' => 'Record created successfully', "metricHistoryRun" => $metricHistoryRun], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}
