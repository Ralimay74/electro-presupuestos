<?php

namespace App\Mail;

use App\Models\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class BudgetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Budget $budget;
    public string $clientEmail;
    public string $clientName;
    public ?string $customMessage;

    public function __construct(Budget $budget, string $clientEmail, string $clientName, ?string $customMessage = null)
    {
        $this->budget = $budget;
        $this->clientEmail = $clientEmail;
        $this->clientName = $clientName;
        $this->customMessage = $customMessage ?? 'Adjunto encontrará el presupuesto solicitado. Quedamos a su disposición para cualquier consulta.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Presupuesto ' . $this->budget->number . ' - ' . config('app.name'),
            from: config('mail.from.address'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.budget',
        );
    }

    public function attachments(): array
    {
        // Generar PDF
        $pdf = $this->generatePdf();
        
        // Guardar PDF temporalmente
        $filename = 'presupuesto-' . $this->budget->number . '.pdf';
        $path = storage_path('app/public/temp/' . $filename);
        
        // Crear directorio si no existe
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // Guardar PDF
        file_put_contents($path, $pdf->output());
        
        return [
            Attachment::fromPath($path)
                ->as($filename)
                ->withMime('application/pdf'),
        ];
    }

    private function generatePdf()
    {
        // Cargar relaciones
        $this->budget->load(['client', 'lines.material']);
        
        // Calcular totales
        $subtotal = $this->budget->lines->sum('subtotal');
        $ivaAmount = $subtotal * ($this->budget->iva_percent / 100);
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
            'budget' => $this->budget,
            'company' => $company,
            'subtotal' => $subtotal,
            'ivaAmount' => $ivaAmount,
            'total' => $total,
        ];
        
        return Pdf::loadView('pdfs.budget', $data)
                  ->setPaper('a4')
                  ->setOption('isRemoteEnabled', true)
                  ->setOption('defaultFont', 'DejaVu Sans');
    }
}