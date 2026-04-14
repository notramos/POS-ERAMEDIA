<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = $this->getService();
    }

    abstract protected function getService();

    public function index(Request $request)
    {
        $filters = $request->query();
        $data = $this->service->getAll($filters);

        return $this->respond($data);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $this->validateRequest($request);
            $result = $this->service->create($validated);

            return $this->successResponse($result, 'Created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', 422);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $this->validateRequest($request);
            $result = $this->service->update($id, $validated);
    
            return $this->successResponse($result, 'Updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation failed', 422);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return $this->successResponse(null, 'Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), 400);
        }
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
