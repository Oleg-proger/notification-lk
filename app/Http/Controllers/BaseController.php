<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function sendResponse($result, int $code = 200): JsonResponse
    {
        $response = [
            'success'  => true,
            'data'     => $result,
        ];

        return response()->json($response, $code);
    }

    public function sendError(string $error, $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'data' => $error,
        ];

        return response()->json($response, $code);
    }
}
