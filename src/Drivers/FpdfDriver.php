<?php

namespace PdfMaker\Drivers;

use PdfMaker\Contracts\PdfMaker;
use PdfMaker\Certificates\Data;
use Fpdf\Fpdf;

class FpdfDriver implements PdfMaker
{
    public function make(array $crtObjects): array
    {
        $config = $this->getConfig();
        $response = [];

        foreach ($crtObjects as $cert) {
            $fpdf = new Fpdf();
                  
            $fpdf->AddPage('L');
            $fpdf->SetLineWidth(1);
            $fpdf->Image($cert->template,0,0,295);

            // Company intro
            $fpdf->SetFont($config->companyIntro->fontName, '', $config->companyIntro->fontSize); 
            $fpdf->SetXY($config->companyIntro->x, $config->companyIntro->y); 
            $fpdf->MultiCell(265, 50, mb_convert_encoding($cert->companyIntro, 'ISO-8859-1', 'UTF-8'), '', 'L', 0);

            // Person name
            $fpdf->SetFont($config->personName->fontName, '', $config->personName->fontSize); 
            $fpdf->SetXY($config->personName->x, $config->personName->y); 
            $fpdf->MultiCell(265, 10, mb_convert_encoding($cert->data->personName, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Certification text
            $fpdf->SetFont($config->certificationText->fontName, '', $config->certificationText->fontSize);
            $fpdf->SetXY($config->certificationText->x, $config->certificationText->y); 
            $fpdf->MultiCell(260, 7, mb_convert_encoding($cert->certificationText, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Certification Date
            $fpdf->SetFont($config->certificationDate->fontName, '', $config->certificationDate->fontSize);
            $fpdf->SetXY($config->certificationDate->x, $config->certificationDate->y); 
            $fpdf->MultiCell(265, 30, mb_convert_encoding($cert->data->strDate, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Person signature name
            $fpdf->SetFont($config->signatureName->fontName, '', $config->signatureName->fontSize);
            $fpdf->SetXY($config->signatureName->x, $config->signatureName->y); 
            $fpdf->MultiCell(265, 30, mb_convert_encoding($cert->data->personName, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Person signature document (CPF)
            $fpdf->SetFont($config->signatureDocument->fontName, '', $config->signatureDocument->fontSize);
            $fpdf->SetXY($config->signatureDocument->x, $config->signatureDocument->y); 
            $fpdf->MultiCell(265, 30, 'CPF: ' . mb_convert_encoding($cert->data->document, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            $pdfdoc = $fpdf->Output('', 'S');

            $name = str_replace(" ", '-', $cert->data->personName);

            $nameFormatted = "storage/generated/{$name}-{$cert->name}.pdf";
            $fpdf->Output($nameFormatted,'F');

            $response[$cert->name] = 'Criado com sucesso';
        }

        return $response;
    }

    private function getConfig(): \stdClass
    {
        $configFile = __DIR__ . '/../../pdfConfig.json';
        $config = json_decode(file_get_contents($configFile));

        return $config->certificates;
    }
}
