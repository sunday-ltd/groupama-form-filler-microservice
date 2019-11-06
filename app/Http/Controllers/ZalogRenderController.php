<?php


namespace App\Http\Controllers;

use setasign\Fpdi\PdfReader;
use setasign\Fpdi\Fpdi;

class ZalogRenderController extends Controller
{
    /** @var Fpdi */
    private $pdf;

    /** @var bool */
    private $setUppercase;

    public function __construct()
    {
        define("FPDF_FONTPATH", realpath(__DIR__ . "/../../../storage/fonts"));
        try {
            $this->pdf = new Fpdi('P','mm','A4');
            $this->pdf->AddFont("OCRB", "", "OCRBMedium.php");


        } catch (\Exception $e) {
            http_response_code(500);
            dd($e);
        }

        $this->setUppercase = false;

    }

    /**
     * @return bool
     */
    public function isSetUppercase(): bool
    {
        return $this->setUppercase;
    }

    /**
     * @param bool $setUppercase
     * @return ZalogRenderController
     */
    public function setSetUppercase(bool $setUppercase): ZalogRenderController
    {
        $this->setUppercase = $setUppercase;
        return $this;
    }

    public function render()
    {
        try {

            $filename = realpath(__DIR__ . "/../../../storage/zalogkotelezetti_nyilatkozat.pdf");
            $pageCount = $this->pdf->setSourceFile($filename);
            $page1 = $this->pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);
            $page2 = $this->pdf->importPage(2, PdfReader\PageBoundaries::MEDIA_BOX);

            $this->pdf->addPage();
            $importedPageSize = $this->pdf->getImportedPageSize($page1);
            $this->pdf->useImportedPage($page1, 0, 0, $importedPageSize["width"], $importedPageSize["height"]);


            /*
             * 1. Adós/zálogkötelezett nyilatkozata
             */

            // Ingatlan címe IRSZ
            $this->fillCell(50.5,59, "1037", 4);

            // Ingatlan címe Város
            $this->fillCell(75.5,59, "Budapest III. kerület", 25);

            // Ingatlan címe közterület neve
            $this->fillCell(50.5, 64, "Kolostor út", 14);

            // Ingatlan címe utcaszám
            $this->fillCell(139.5, 64, "30", 3);

            // Ingatlan címe emelet
            $this->fillCell(163.5, 64, "1", 2);

            // Ingatlan címe ajtó
            $this->fillCell(183.5, 64, "b", 1);

            // Ingatlan címe HELYRAJZI SZÁM KÖZTERÜLET NEVE
            $this->fillCell(50.5, 69, "012345678901234567890123456789", 30);

            // Alapterület
            $this->fillCell(50.5, 74, "132", 4);

            // Jelzálog jogosultja
            $this->fillCell(60.5, 79.1, "Budapest Bank", 28);

            // Kölcsönszerződés száma
            $this->fillCell(60.5, 84.1, md5(uniqid()), 28);

            // Kölcsönszerződés tartalma
            $this->fillCell(60.5, 89.2, md5(uniqid()), 28);

            // Kölcsön összege
            $this->fillCell(60.5, 95, 123456789, 9);


            /*
             * 2. Szerződő nyilatkozata biztosítási szerződés kötéséről
             */

            // Biztosítás neve 1
            $this->fillCell(50.6, 122.8, "526", 3);

            // Biztosítás neve 2
            $this->fillCell(70.6, 122.8, "Társasházközösség bizt.", 26);



            $this->pdf->addPage();
            $importedPageSize = $this->pdf->getImportedPageSize($page1);
            $this->pdf->useImportedPage($page2, 0, 0, $importedPageSize["width"], $importedPageSize["height"]);


            $this->pdf->Output();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * @param float $x
     * @param float $y
     * @param string $text
     * @param int $limit
     */
    private function fillCell(float $x, float $y, string $text, int $limit = 30) {

        $this->pdf->SetFont('OCRB','',14);

        $txt= str_replace(["ő", "ű", "Ő", "Ű"], ["ö", "ü", "Ö", "Ü"], $text);
        $txt = mb_convert_encoding($txt, "cp1252", "UTF-8");

        if ($this->setUppercase) {
            if (function_exists("mb_strtoupper")) {
                $txt = mb_strtoupper($txt);
            }
            else {
                $txt = strtoupper($txt);
            }
        }
        $txt = (substr($txt, 0, $limit));

        $this->pdf->Text($x, $y, $txt);

    }
}
