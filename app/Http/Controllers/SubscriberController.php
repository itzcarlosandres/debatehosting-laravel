<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Subscriber::updateOrCreate(
            ['email' => strtolower(trim($request->email))]
        );

        return response()->json([
            'success' => true,
            'message' => '¡Suscripción confirmada! Te enviaremos el boletín semanal de auditorías.',
        ]);
    }
}
