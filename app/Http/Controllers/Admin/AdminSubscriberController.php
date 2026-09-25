<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminSubscriberController extends Controller
{
    /**
     * Listado y búsqueda de suscriptores al boletín
     */
    public function index(Request $request): View
    {
        $query = Subscriber::query();

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where('email', 'like', "%{$search}%");
        }

        $subscribers = $query->latest('created_at')->paginate(20)->withQueryString();

        $totalSubscribers = Subscriber::count();
        $newThisMonth = Subscriber::where('created_at', '>=', now()->startOfMonth())->count();
        $newThisWeek = Subscriber::where('created_at', '>=', now()->startOfWeek())->count();

        return view('admin.subscribers.index', compact(
            'subscribers',
            'totalSubscribers',
            'newThisMonth',
            'newThisWeek'
        ));
    }

    /**
     * Añadir suscriptor manualmente desde el panel de control
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:subscribers,email',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor introduce un correo electrónico válido.',
            'email.unique' => 'Este correo ya se encuentra registrado en la lista de suscriptores.',
        ]);

        Subscriber::create([
            'email' => strtolower(trim($request->email)),
        ]);

        return redirect()->route('admin.subscribers.index')->with('success', 'Suscriptor añadido exitosamente a la lista.');
    }

    /**
     * Eliminar suscriptor
     */
    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->route('admin.subscribers.index')->with('success', 'El suscriptor ha sido eliminado correctamente.');
    }

    /**
     * Exportar lista completa de suscriptores a CSV
     */
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'debatehosting_suscriptores_'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM para UTF-8 en Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($handle, ['ID', 'Correo Electrónico', 'Fecha de Registro', 'Hora']);

            Subscriber::latest('created_at')->chunk(200, function ($subscribers) use ($handle) {
                foreach ($subscribers as $s) {
                    fputcsv($handle, [
                        $s->id,
                        $s->email,
                        $s->created_at ? $s->created_at->format('Y-m-d') : '',
                        $s->created_at ? $s->created_at->format('H:i:s') : '',
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
