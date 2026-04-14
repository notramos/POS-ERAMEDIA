<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class Controller
{
    protected $service;

    public function __construct()
    {
        if (method_exists($this, 'getService')) {
            $this->service = $this->getService();
        }
    }

    protected function getService()
    {
        return null;
    }

    protected function validateRequest(Request $request): array
    {
        $rules = $this->rules();

        return $request->validate($rules);
    }

    protected function rules(): array
    {
        return [];
    }

    protected function respond($data, int $status = 200)
    {
        return response()->json([
            'data' => $data,
        ], $status);
    }

    protected function successResponse($data, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse(?array $errors, string $message = 'Error', int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
