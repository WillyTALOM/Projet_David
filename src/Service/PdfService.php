<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;



class PdfService
{
    private $dompdf;

    public function __construct()
    {

        $this->domPdf = new Dompdf();
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Garamond');

        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html)
    {
        // $this->domPdf = new DomPdf();

        $this->domPdf->loadHtml($html);

        $this->domPdf->render();

        $this->domPdf->stream("details.pdf", [
            'Attachement' => false
        ]);
    }

    public function generateBinaryPdf($html)
    {
        $this->domPdf = new Dompdf();
        $this->domPdf->loadHtml($html);

        $this->domPdf->render();

        $this->domPdf->output();
    }
}
