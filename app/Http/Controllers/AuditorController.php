<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditorController extends Controller
{
    public function index()
    {
        return view('pages.auditor');
    }

    public function inspect(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:255',
        ]);

        $rawDomain = trim($request->domain);
        $clean = preg_replace('#^https?://#i', '', $rawDomain);
        $clean = explode('/', $clean)[0];
        $clean = strtolower(trim($clean));

        if (empty($clean) || !str_contains($clean, '.')) {
            return response()->json(['error' => 'Por favor introduce un dominio válido (ej: miweb.com).'], 422);
        }

        $ip = gethostbyname($clean);
        $hasDns = ($ip !== $clean);

        // Medición de latencia básica TTFB
        $startTime = microtime(true);
        $ch = curl_init("https://" . $clean);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_NOBODY => true,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'DebateHosting Auditor/2.0',
        ]);
        curl_exec($ch);
        $info = curl_getinfo($ch);
        $httpCode = $info['http_code'] ?? 0;
        $ttfb = round(($info['starttransfer_time'] ?? (microtime(true) - $startTime)) * 1000);
        $contentType = $info['content_type'] ?? 'Desconocido';
        $serverHeader = $info['header_size'] > 0 ? 'Detectado' : 'Oculto';
        curl_close($ch);

        return response()->json([
            'domain' => $clean,
            'ip' => $hasDns ? $ip : 'No resuelta',
            'http_code' => $httpCode > 0 ? $httpCode : 'Sin respuesta',
            'ttfb' => $ttfb > 0 ? $ttfb . ' ms' : 'N/A',
            'ssl' => $httpCode > 0,
            'server' => $serverHeader,
            'status' => $httpCode >= 200 && $httpCode < 400 ? 'online' : 'unreachable',
        ]);
    }
}
