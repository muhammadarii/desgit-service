<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
  public static function jsonResponse($success, $message, $data, $tatusCode): JsonResponse
  {
    return response()->json([
      'success' => $success,
      'message' => $message,
      'data' => $data
    ], $tatusCode);
  }
}