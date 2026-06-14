<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Send success response.
     */
    protected function sendResponse($result, ?string $message = null, int $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    /**
     * Send error response.
     */
    protected function sendError(string $error, $errorMessages = [], int $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
