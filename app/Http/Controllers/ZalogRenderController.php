<?php


	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Log;
	use Mockery\Exception;
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

		public function __construct()
		{
			define("FPDF_FONTPATH", realpath(__DIR__ . "/../../../storage/fonts"));
			try {
				$this->pdf = new Fpdi('P', 'mm', 'A4');
				$this->pdf->AddFont("fillfont", "", "TitilliumWeb-Light.php");


			} catch (\Exception $e) {
				http_response_code(500);
				dd($e);
			}
			$this->setUppercase = true;
		}

		/**
		 * @return bool
		 */
		public function isSetUppercase() : bool
		{
			return $this->setUppercase;
		}

		/**
		 * @param bool $setUppercase
		 *
		 * @return ZalogRenderController
		 */
		public function setSetUppercase(bool $setUppercase) : ZalogRenderController
		{
			$this->setUppercase = $setUppercase;

			return $this;
		}

		/**
		 * Returns and outputs the rendered PDF file
		 *
		 * @param array $data
		 */
		public function render(Request $request)
		{

			$data = json_decode($request->input('data'), true);
			/*
			$data = [
				'ados' => [
					'ingatlan' => [
						'iranyitoszam' => "1111",
						'telepules' => "ŰŐ űő",
						'utcanev' => "Zöldkő utca",
						'utcaszam' => "9",
						'emelet' => "",
						'ajto' => "A",
						'helyrajzi_szam' => "123/456/789",
						'alapterulet' => "180",
						'jelzalog_jogosult' => "Gerő Gábor",
						'kolcsonszerzodes_szama' => "85489731963",
						'kolcsonszerzodes_tartalma' => "4189498465",
						'kolcson_osszege' => "69420420"
					],
					'szerzodes' => [
						'biztositas_neve_1' => "826",
						'biztositas_neve_2' => "Társasház bizt",
						'szerzodes_szama' => "BŐŰ-912247",
						'biztositasi_osszeg' => "70250000",
					]
				],
				'biztositott' => [
					'nev' => "Molnár Bálint",
					'iranyitoszam' => "1144",
					'telepules' => "Budapest",
					'utcanev' => "Ond Vezér Park",
					'utcaszam' => "4",
					'emelet' => "2",
					'ajto' => "65",
					'szuletes' => [
						"hely" => "Budapest",
						"ev" => "1994",
						"ho" => "03",
						"nap" => "03",
					],
					'anyja_neve' => "Posteiner Nóra",
				],
				'szerzodo' => [
					'nev' => "Molnár Bálint",
					'iranyitoszam' => "1144",
					'telepules' => "Budapest",
					'utcanev' => "Ond Vezér Park",
					'utcaszam' => "4",
					'emelet' => "2",
					'ajto' => "65",
					'szuletes' => [
						"hely" => "Budapest",
						"ev" => "1994",
						"ho" => "03",
						"nap" => "03",
					],
					'anyja_neve' => "Posteiner Nóra",
				],
				'bank' => [
					'folyosito' => "Budapest Bank",
					'meghatalmazott' => "Budapest Bank",
					'adatkezelo' => "Budapest Bank",
					'szerzodesszam' => "15034895"
				],
				'kelt' => [
					'ev' => "2019",
					'ho' => '11',
					'nap' => '11'
				]
			];
			 */


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
				$this->fillCell(51, 59, $data["ados"]["ingatlan"]["iranyitoszam"], 4);

				// Ingatlan címe Város
				$this->fillCell(75.5, 59, $data["ados"]["ingatlan"]["telepules"], 25);

				// Ingatlan címe közterület neve
				$this->fillCell(51, 64, $data["ados"]["ingatlan"]["utcanev"], 14);

				// Ingatlan címe utcaszám
				$this->fillCell(139.5, 64, $data["ados"]["ingatlan"]["utcaszam"], 3);

				// Ingatlan címe emelet
				$this->fillCell(163.5, 64, $data["ados"]["ingatlan"]["emelet"], 2);

				// Ingatlan címe ajtó
				$this->fillCell(183.5, 64, $data["ados"]["ingatlan"]["ajto"], 2);

				// Ingatlan címe HELYRAJZI SZÁM KÖZTERÜLET NEVE
				$this->fillCell(51, 69, $data["ados"]["ingatlan"]["helyrajziszam"], 30);

				// Alapterület
				$this->fillCell(51, 74, $data["ados"]["ingatlan"]["alapterulet"], 4);

				// Jelzálog jogosultja
				$this->fillCell(61, 79.1, $data["ados"]["ingatlan"]["jelzalogjogosult"], 28);

				// Kölcsönszerződés száma
				$this->fillCell(61, 84.1, $data["ados"]["ingatlan"]["kolcsonszerzodesszama"], 28);

				// Kölcsönszerződés tartalma
				$this->fillCell(61, 89.2, $data["ados"]["ingatlan"]["kolcsonszerzodestartalma"], 28);

				// Kölcsön összege
				$this->fillCell(61, 95, $data["ados"]["ingatlan"]["kolcsonosszege"], 9);


				/*************************************************************
				 * 2. Szerződő nyilatkozata biztosítási szerződés kötéséről
				 ************************************************************/

				// Biztosítás neve 1
				$this->fillCell(51.3, 122.8, $data["ados"]["szerzodes"]["biztositasneve1"], 3);

				// Biztosítás neve 2
				$this->fillCell(70.6, 122.8, $data["ados"]["szerzodes"]["biztositasneve2"], 26);

				// Biztosítási ajánlat/szerződés száma
				$this->fillCell(70.6, 127.9, $data["ados"]["szerzodes"]["szerzodesszama"], 26);

				// Épület biztosítási összege
				$this->fillCell(61, 138.2, $data["ados"]["szerzodes"]["biztositasiosszeg"], 9);

				/*************************************************************
				 * 3. Biztosított adatai
				 ************************************************************/

				// Név
				$this->fillCell(51, 157.5, $data["biztositott"]["nev"], 30);

				// Cím IRSZ
				$this->fillCell(51, 162.6, $data["biztositott"]["iranyitoszam"], 4);

				// Cím Város
				$this->fillCell(75.5, 162.6, $data["biztositott"]["telepules"], 25);

				// Cím közterület neve
				$this->fillCell(51, 167.7, $data["biztositott"]["utcanev"], 14);

				// Cím utcaszám
				$this->fillCell(139.5, 167.7, $data["biztositott"]["utcaszam"], 3);

				// Cím emelet
				$this->fillCell(163.5, 167.7, $data["biztositott"]["emelet"], 2);

				// Cím ajtó
				$this->fillCell(183.5, 167.7, $data["biztositott"]["ajto"], 2);

				// Szuletesi hely
				$this->fillCell(51, 172.8, $data["biztositott"]["szuletes"]["hely"], 18);

				// Szuletesi ido YYYY
				$this->fillCell(144.5, 172.8, $data["biztositott"]["szuletes"]["ev"], 4);

				// Szuletesi ido MM
				$this->fillCell(169.5, 172.8, $data["biztositott"]["szuletes"]["ho"], 2);

				// Szuletesi ido DD
				$this->fillCell(183.5, 172.8, $data["biztositott"]["szuletes"]["nap"], 2);

				// Anyja neve
				$this->fillCell(51, 177.9, $data["biztositott"]["anyja_neve"], 30);

				/*************************************************************
				 * 4. Szerződő adatai
				 ************************************************************/

				// Név
				$this->fillCell(51, 197, $data["szerzodo"]["nev"], 30);

				// Cím IRSZ
				$this->fillCell(51, 202.1, $data["szerzodo"]["iranyitoszam"], 4);

				// Cím Város
				$this->fillCell(75.5, 202.1, $data["szerzodo"]["telepules"], 25);

				// Cím közterület neve
				$this->fillCell(51, 207.2, $data["szerzodo"]["utcanev"], 14);

				// Cím utcaszám
				$this->fillCell(139.5, 207.2, $data["szerzodo"]["utcaszam"], 3);

				// Cím emelet
				$this->fillCell(163.5, 207.2, $data["szerzodo"]["emelet"], 2);

				// Cím ajtó
				$this->fillCell(183.5, 207.2, $data["szerzodo"]["ajto"], 2);

				// Szuletesi hely
				$this->fillCell(51, 212.3, $data["szerzodo"]["szuletes"]["hely"], 18);

				// Szuletesi ido YYYY
				$this->fillCell(144.5, 212.3, $data["szerzodo"]["szuletes"]["ev"], 4);

				// Szuletesi ido MM
				$this->fillCell(169.5, 212.3, $data["szerzodo"]["szuletes"]["ho"], 2);

				// Szuletesi ido DD
				$this->fillCell(183.5, 212.3, $data["szerzodo"]["szuletes"]["nap"], 2);

				// Anyja neve
				$this->fillCell(51, 217.4, $data["szerzodo"]["anyja_neve"], 30);

				$this->pdf->addPage();
				$importedPageSize = $this->pdf->getImportedPageSize($page1);
				$this->pdf->useImportedPage($page2, 0, 0, $importedPageSize["width"], $importedPageSize["height"]);

				/*************************************************************
				 * 5. Nyilatkozatok, hozzájárulások
				 ************************************************************/

				// Hitelező bank (folyósító)
				$this->fillCell(80, 37.8, $data["bank"]["folyosito"], 22);

				// Hitelező bank (meghatalmazott)
				$this->fillCell(90.2, 42.8, $data["bank"]["meghatalmazott"], 20);

				// Hitelező bank (folyósító)
				$this->fillCell(16.4, 64.7, $data["bank"]["adatkezelo"], 28);

				// Szerződésszám
				$this->fillCell(21.4, 69.7, $data["bank"]["szerzodesszam"], 19);

				/*************************************************************
				 * Lábléc
				 ************************************************************/

				// Kelt YYYY
				$this->fillCell(50.9, 221.5, $data["kelt"]["ev"], 4);

				// Kelt MM
				$this->fillCell(76, 221.5, $data["kelt"]["ho"], 4);

				// Kelt DD
				$this->fillCell(91, 221.5, $data["kelt"]["nap"], 4);


				$this->pdf->Output();

			} catch (\Exception $e) {
				throw new \InvalidArgumentException("Could not generate PDF file: " . $e);
			}
		}

		/**
		 * @param float  $x
		 * @param float  $y
		 * @param string $text
		 * @param int    $limit
		 */
		private function fillCell(float $x, float $y, $text, int $limit = 30)
		{

			$this->pdf->SetFont('fillfont', '', 14);

//        $txt= str_replace(["ő", "ű", "Ő", "Ű"], ["ö", "ü", "Ö", "Ü"], $text);


//        $txt = mb_convert_encoding($txt, "cp1252", "UTF-8");

			try {
			$txt = iconv('UTF-8', 'ISO-8859-16//TRANSLIT', $text);

			}
			catch (Exception $exception) {
				Log::warning("Unable to convert input: [$text] " . $exception->getMessage());
				$txt = $text;
			}

			if ($this->setUppercase) {
				$txt = strtoupper($txt);
			}

			$txt = (substr($txt, 0, $limit));

			$this->pdf->Text($x, $y, $txt);
		}
	}
