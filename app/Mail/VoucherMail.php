<?php

namespace App\Mail;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\PaymentControl;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VoucherMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Patient        $patient,
        public readonly PaymentControl $paymentControl,
        public readonly Payment        $payment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Pago #'.str_pad($this->payment->id, 6, '0', STR_PAD_LEFT).
                     ' — $'.number_format($this->payment->amount, 2).' · Dentalux',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.voucher');
    }

    public function attachments(): array
    {
        try {
            $this->paymentControl->load('payments');

            $date   = $this->payment->payment_date;
            $payNum = str_pad($this->payment->id, 6, '0', STR_PAD_LEFT);

            // ── Rutas en storage ──
            $folder         = 'vouchers/'.$date->format('Y').'/'.$date->format('m').'/'.$date->format('d');
            $pathStandard   = $folder.'/PAY-'.$payNum.'_comprobante.pdf';
            $pathTicket     = $folder.'/PAY-'.$payNum.'_ticket.pdf';

            // ── PDF Estándar A4 ──
            // Si ya existe en storage lo reutiliza, si no lo genera
            if (Storage::disk('local')->exists($pathStandard)) {
                $contentStandard = Storage::disk('local')->get($pathStandard);
                Log::info("VoucherMail: Reutilizando PDF existente {$pathStandard}");
            } else {
                $contentStandard = Pdf::loadView('payments.voucher-pdf', [
                    'patient'         => $this->patient,
                    'paymentControl'  => $this->paymentControl,
                    'payment'         => $this->payment,
                    'includSignature' => false,
                ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => false,
                    'defaultFont'          => 'DejaVu Sans',
                    'enable_php'           => false,
                    'enable_javascript'    => false,
                ])
                ->output();

                if (strlen($contentStandard) > 500) {
                    Storage::disk('local')->put($pathStandard, $contentStandard);
                    Log::info("VoucherMail: PDF estándar generado y guardado en {$pathStandard}");
                }
            }

            // ── Ticket térmico ──
            // Solo se guarda, no se adjunta al correo
            if (!Storage::disk('local')->exists($pathTicket)) {
                $contentTicket = Pdf::loadView('payments.voucher-ticket', [
                    'patient'         => $this->patient,
                    'paymentControl'  => $this->paymentControl,
                    'payment'         => $this->payment,
                ])
                ->setPaper([0, 0, 226.77, 700], 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => false,
                    'defaultFont'          => 'DejaVu Sans',
                    'enable_php'           => false,
                    'enable_javascript'    => false,
                ])
                ->output();

                if (strlen($contentTicket) > 500) {
                    Storage::disk('local')->put($pathTicket, $contentTicket);
                    Log::info("VoucherMail: Ticket generado y guardado en {$pathTicket}");
                }
            } else {
                Log::info("VoucherMail: Ticket ya existe, se omite generación");
            }

            // ── Adjuntar al correo solo el PDF estándar ──
            if (strlen($contentStandard) < 500) {
                Log::error("VoucherMail: PDF estándar vacío o inválido");
                return [];
            }

            return [
                Attachment::fromData(
                    fn() => $contentStandard,
                    'PAY-'.$payNum.'_comprobante.pdf'
                )->withMime('application/pdf'),
            ];

        } catch (\Exception $e) {
            Log::error('VoucherMail error: '.$e->getMessage().' | '.$e->getFile().':'.$e->getLine());
            return [];
        }
    }
}