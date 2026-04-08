<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GoogleIndexingService;
use Illuminate\Http\Request;

class GoogleIndexingController extends Controller
{
    public function recent(Request $request)
    {
        $minutes = (int) $request->query('minutes', 60);
        $limit = (int) $request->query('limit', 100);

        $records = GoogleIndexingService::recentSubmissions($minutes, 'blog', $limit);

        return response()->json([
            'success' => true,
            'minutes' => max(1, min($minutes, 1440)),
            'count' => count($records),
            'data' => $records,
        ]);
    }
}
