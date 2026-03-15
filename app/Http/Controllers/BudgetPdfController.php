<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BudgetPdfController extends Controller
{
    public function generate(Budget $budget)
    {
        // Cargar relaciones necesarias
        $budget->load(['client', 'lines.material']);
        
        // Datos de tu empresa (personalízalos)
        $company = [
            'name' => 'RYM Soluciones Integrales',
            'nif' => 'B-8041731b',
            'address' => 'Calle Ejemplo 123, 41020, Sevilla',
            'phone' => '664301542',
            'email' => 'raymar000@gmail.com',
            'web' => 'www.tuempresa.com',
        ];
        
        // Calcular subtotal
        $subtotal = $budget->lines->sum('subtotal');
        $ivaAmount = $subtotal * ($budget->iva_percent / 100);
        $total = $subtotal + $ivaAmount;
        
        $data = [
            'budget' => $budget,
            'company' => $company,
            'subtotal' => $subtotal,
            'ivaAmount' => $ivaAmount,
            'total' => $total,
        ];
        
        // Generar PDF
        $pdf = Pdf::loadView('pdfs.budget', $data)
                  ->setPaper('a4')
                  ->setOption('isRemoteEnabled', true)
                  ->setOption('defaultFont', 'DejaVu Sans'); // Para caracteres UTF-8
        
        // Nombre del archivo
        $filename = 'presupuesto-' . $budget->number . '.pdf';
        
        return $pdf->download($filename);
    }
}