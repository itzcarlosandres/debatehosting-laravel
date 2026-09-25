<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Por favor introduce tu dirección de correo electrónico.',
            'email.email' => 'Por favor ingresa un correo electrónico válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email'),
            ], 422);
        }

        $email = strtolower(trim($request->email));

        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            return response()->json([
                'success' => true,
                'already_subscribed' => true,
                'message' => '¡Ya formas parte de la comunidad! Tu correo ya estaba registrado.',
            ]);
        }

        Subscriber::create([
            'email' => $email,
        ]);

        return response()->json([
            'success' => true,
            'already_subscribed' => false,
            'message' => '¡Suscripción confirmada! Bienvenido a El Debate.',
        ]);
    }
}
