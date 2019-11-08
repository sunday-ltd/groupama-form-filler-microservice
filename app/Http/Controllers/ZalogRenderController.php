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

    /** @var  bool */
    private $removeAccents;

	/**
	 * @return bool
	 */
	public function isRemoveAccents() : bool
	{
		return $this->removeAccents;
	}

	/**
	 * @param bool $removeAccents
	 *
	 * @return ZalogRenderController
	 */
	public function setRemoveAccents(bool $removeAccents) : ZalogRenderController
	{
		$this->removeAccents = $removeAccents;

		return $this;
	}

    public function __construct() {
        define("FPDF_FONTPATH", realpath(__DIR__ . "/../../../storage/fonts"));
        try {
            $this->pdf = new Fpdi('P','mm','A4');
            $this->pdf->AddFont("OCRB", "", "FiraCode-Retina.php");


        } catch (\Exception $e) {
            http_response_code(500);
            dd($e);
        }
        $this->setUppercase = true;
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


			/*************************************************************
             * 1. Adós/zálogkötelezett nyilatkozata
			 ************************************************************/

            // Ingatlan címe IRSZ
            $this->fillCell(51,59, "1037", 4);

            // Ingatlan címe Város
            $this->fillCell(75.5,59, "Budapest", 25);

            // Ingatlan címe közterület neve
            $this->fillCell(51, 64, "Kolostor út", 14);

            // Ingatlan címe utcaszám
            $this->fillCell(139.5, 64, "30", 3);

            // Ingatlan címe emelet
            $this->fillCell(163.5, 64, "1", 2);

            // Ingatlan címe ajtó
            $this->fillCell(183.5, 64, "b", 2);

            // Ingatlan címe HELYRAJZI SZÁM KÖZTERÜLET NEVE
            $this->fillCell(51, 69, "10264/5103/511", 30);

            // Alapterület
            $this->fillCell(51, 74, "132", 4);

            // Jelzálog jogosultja
            $this->fillCell(61, 79.1, "Budapest Bank", 28);

            // Kölcsönszerződés száma
            $this->fillCell(61, 84.1, md5(uniqid()), 28);

            // Kölcsönszerződés tartalma
            $this->fillCell(61, 89.2, md5(uniqid()), 28);

            // Kölcsön összege
            $this->fillCell(61, 95, "123456789", 9);


            /*************************************************************
             * 2. Szerződő nyilatkozata biztosítási szerződés kötéséről
			 ************************************************************/

            // Biztosítás neve 1
            $this->fillCell(50.6, 122.8, "526", 3);

			// Biztosítás neve 2
			$this->fillCell(70.6, 122.8, "Társasházközösség bizt.", 26);

			// Biztosítási ajánlat/szerződés száma
			$this->fillCell(70.6, 127.9, md5(uniqid()), 26);

			// Épület biztosítási összege
			$this->fillCell(61, 138.2, "123456789", 9);

			/*************************************************************
			 * 3. Biztosított adatai
			 ************************************************************/

			// Név
			$this->fillCell(51, 157.5, "Molnár Bálint", 30);

			// Cím IRSZ
			$this->fillCell(51,162.6, "1144", 4);

			// Cím Város
			$this->fillCell(75.5,162.6, "Budapest", 25);

			// Cím közterület neve
			$this->fillCell(51, 167.7, "Ond Vezér park", 14);

			// Cím utcaszám
			$this->fillCell(139.5, 167.7, "4", 3);

			// Cím emelet
			$this->fillCell(163.5, 167.7, "2", 2);

			// Cím ajtó
			$this->fillCell(183.5, 167.7, "65", 2);

			// Szuletesi hely
			$this->fillCell(51, 172.8, "Budapest", 18);

			// Szuletesi ido YYYY
			$this->fillCell(144.5, 172.8, "1994", 4);

				// Szuletesi ido MM
			$this->fillCell(169.5, 172.8, "03", 2);

			// Szuletesi ido DD
			$this->fillCell(183.5, 172.8, "03", 2);

			// Anyja neve
			$this->fillCell(51, 177.9, "Posteiner Nóra", 30);

			/*************************************************************
			 * 4. Szerződő adatai
			 ************************************************************/

			// Név
			$this->fillCell(51, 197, "Molnár Bálint", 30);

			// Cím IRSZ
			$this->fillCell(51,202.1, "1144", 4);

			// Cím Város
			$this->fillCell(75.5,202.1, "Budapest", 25);

			// Cím közterület neve
			$this->fillCell(51, 207.2, "Ond Vezér park", 14);

			// Cím utcaszám
			$this->fillCell(139.5, 207.2, "4", 3);

			// Cím emelet
			$this->fillCell(163.5, 207.2, "2", 2);

			// Cím ajtó
			$this->fillCell(183.5, 207.2, "65", 2);

			// Szuletesi hely
			$this->fillCell(51, 212.3, "Budapest", 18);

			// Szuletesi ido YYYY
			$this->fillCell(144.5, 212.3, "1994", 4);

			// Szuletesi ido MM
			$this->fillCell(169.5, 212.3, "03", 2);

			// Szuletesi ido DD
			$this->fillCell(183.5, 212.3, "03", 2);

			// Anyja neve
			$this->fillCell(51, 217.4, "Posteiner Nóra", 30);

			$this->pdf->addPage();
            $importedPageSize = $this->pdf->getImportedPageSize($page1);
            $this->pdf->useImportedPage($page2, 0, 0, $importedPageSize["width"], $importedPageSize["height"]);

			/*************************************************************
			 * 5. Nyilatkozatok, hozzájárulások
			 ************************************************************/

			// Hitelező bank (folyósító)
			$this->fillCell(80,37.8, "Budapest Bank", 22);

			// Hitelező bank (meghatalmazott)
			$this->fillCell(90.2,42.8, "Budapest Bank", 20);

			// Hitelező bank (folyósító)
			$this->fillCell(16.4,64.7, "Budapest Bank", 28);

			// Szerződésszám
			$this->fillCell(21.4,69.7, md5(uniqid()), 19);

			/*************************************************************
			 * Lábléc
			 ************************************************************/

			// Kelt YYYY
			$this->fillCell(50.9,221.5, date("Y"), 4);

			// Kelt MM
			$this->fillCell(76,221.5, date("m"), 4);

			// Kelt DD
			$this->fillCell(91,221.5, date("d"), 4);



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
			$txt = strtoupper($txt);
        }

        $txt = (substr($txt, 0, $limit));

        $this->pdf->Text($x, $y, $txt);
    }
}
