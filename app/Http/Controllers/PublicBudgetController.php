<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicBudgetController extends Controller
{
    /**
     * Mostrar presupuesto público
     */
    public function show($token)
    {
        // Buscar presupuesto por token
        $budget = Budget::where('public_token', $token)
            ->where('is_public', true)
            ->where(function ($query) {
                $query->whereNull('public_token_expires_at')
                      ->orWhere('public_token_expires_at', '>', now());
            })
            ->firstOrFail();

        // Cargar relaciones
        $budget->load(['client', 'lines.material']);

        // Calcular totales
        $subtotal = $budget->lines->sum('subtotal');
        $ivaAmount = $subtotal * ($budget->iva_percent / 100);
        $total = $subtotal + $ivaAmount;

        return view('public.budget', compact('budget', 'subtotal', 'ivaAmount', 'total'));
    }

    /**
     * Aceptar presupuesto
     */
    public function accept($token, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'comments' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $budget = Budget::where('public_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        // Actualizar estado a aprobado
        $budget->update(['status' => 'approved']);

        // Aquí podrías enviar un email notificando la aceptación
        // \Illuminate\Support\Facades\Mail::to('tu@email.com')
        //     ->send(new \App\Mail\BudgetAccepted($budget, $request->all()));

        return view('public.accepted', compact('budget'));
    }

    /**
     * Rechazar presupuesto
     */
    public function reject($token, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $budget = Budget::where('public_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        // Actualizar estado a rechazado
        $budget->update(['status' => 'rejected']);

        return view('public.rejected', compact('budget'));
    }

    /**
     * Descargar PDF público
     */
    public function downloadPdf($token)
    {
        $budget = Budget::where('public_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        // Cargar relaciones
        $budget->load(['client', 'lines.material']);

        // Calcular totales
        $subtotal = $budget->lines->sum('subtotal');
        $ivaAmount = $subtotal * ($budget->iva_percent / 100);
        $total = $subtotal + $ivaAmount;

        // Datos de empresa
        $company = [
            'name' => 'RYM Soluciones Integrales',
            'nif' => 'B-8041731b',
            'address' => 'Calle Ejemplo 123, 41020, Sevilla',
            'phone' => '664301542',
            'email' => 'raymar000@gmail.com',
            'web' => 'www.tuempresa.com',
        ];

        $data = [
            'budget' => $budget,
            'company' => $company,
            'subtotal' => $subtotal,
            'ivaAmount' => $ivaAmount,
            'total' => $total,
        ];

        $pdf = Pdf::loadView('pdfs.budget', $data)
                  ->setPaper('a4')
                  ->setOption('isRemoteEnabled', true)
                  ->setOption('defaultFont', 'DejaVu Sans');

        $filename = 'presupuesto-' . $budget->number . '.pdf';
        
        return $pdf->download($filename);
    }
}