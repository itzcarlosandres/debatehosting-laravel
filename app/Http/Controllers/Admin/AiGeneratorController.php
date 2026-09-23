<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiHostingGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiGeneratorController extends Controller
{
    public function generateProvider(Request $request, AiHostingGenerator $generator): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'focus' => 'nullable|string|max:200',
        ]);

        try {
            $data = $generator->generate($validated['name'], $validated['focus'] ?? null);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al generar la información con IA: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateReview(Request $request, AiHostingGenerator $generator): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'focus' => 'nullable|string|max:200',
            'plan' => 'nullable|string|max:100',
        ]);

        try {
            $data = $generator->generateReview(
                $validated['name'],
                $validated['focus'] ?? null,
                $validated['plan'] ?? null
            );

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al redactar la reseña con IA: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function testGemini(Request $request, AiHostingGenerator $generator): JsonResponse
    {
        $apiKey = $request->input('api_key');
        $model = $request->input('model');

        $result = $generator->testGeminiConnection($apiKey, $model);

        return response()->json($result);
    }

    public function generateProducts(Request $request, AiHostingGenerator $generator): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        try {
            $products = $generator->generateProducts($validated['name']);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al generar los productos con IA: ' . $e->getMessage(),
            ], 500);
        }
    }
}
