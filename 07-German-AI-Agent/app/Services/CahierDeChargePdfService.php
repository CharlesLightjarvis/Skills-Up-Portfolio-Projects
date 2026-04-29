<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;

class CahierDeChargePdfService
{
    public function generate(array $specification): DomPdf
    {
        Pdf::setOption([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
        ]);

        return Pdf::loadView('pdf.cahier-de-charge', $specification)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
    }
}
