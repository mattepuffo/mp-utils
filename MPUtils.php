<?php

/**
 * Metodi di utilità
 *
 * @author Matteo Ferrone
 * @since 2025-10-05
 * @version 3.0.0
 */
class MPUtils {

  public function sanitizeName($name): array|string {
    $cerca = array("à", "è", "é", "ì", "ò", "ù", "'", "?", " ", "__", "&", "%", "#", "(", ")", "/", "+", "°");
    $sostituisci = array("a", "e", "e", "i", "o", "u", "-", "-", "-", "-", "e", "-per-cento-", "-", "", "", "-", "_", "_");
    $doppioMeno = array("---");
    $sostDoppioMeno = array("-");
    $newString = str_replace($cerca, $sostituisci, trim(strtolower($name)));
    return str_replace($doppioMeno, $sostDoppioMeno, trim(strtolower($newString)));
  }

  public function unSanitizeName($name): array|string {
    $cerca = array("-", "_");
    $sostituisci = array(' ', ' ');
    return str_replace($cerca, $sostituisci, trim($name));
  }

  public function levaParolacce($testo): array|string {
    $cerca = array("stronz", "merd", "cacca", "porc", "vaffanculo", "cul", "cazz", "figa", "fottere", "scopare",
        "idiot", "scem", "cretin", "deficent", "deficient", "imbecill", "pisci", "pisciare", "smerdare", "fottiti",
        "fottut", "trombare", "porko", "cornut", "troi", "puttan", "zoccol", "fregn", "suca", "minchi");
    return str_replace($cerca, "***", $testo);
  }

  public function troncaTesto($testo, $caratteri = 300) {
    if (strlen($testo) <= $caratteri) {
      return $testo;
    }
    $nuovoTesto = substr($testo, 0, $caratteri);
    $condizione1 = preg_match("/^([^<]|<[^a]|<a.*>.*<\/a>)*$/", $nuovoTesto);
    if ($condizione1 == 0) {
      $caratteri = strrpos($nuovoTesto, "<a");
      $nuovoTesto = substr($testo, 0, $caratteri); // Taglia prima del link
    }
    return $nuovoTesto;
  }

  public function scriviTesto($file, $message) {
    $f = fopen($file, 'a+');
    fwrite($f, $message);
    fclose($f);
  }

  public function chkEmail($email) {
    $email = trim($email);
    if (!$email) {
      return false;
    }
    $numAt = count(explode('@', $email)) - 1;
    if ($numAt != 1) {
      return false;
    }
    if (strpos($email, ';') || strpos($email, ',') || strpos($email, ' ')) {
      return false;
    }
    if (!preg_match('/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email)) {
      return false;
    }
    return true;
  }

  public function truncateNumberFormat($number, $decimals = 2, $decimalPoint = ',', $thousandPoint = '.') {
    if (($number * pow(10, $decimals + 1) % 10) == 5) {
      $number -= pow(10, -($decimals + 1));
    }
    return number_format($number, $decimals, $decimalPoint, $thousandPoint);
  }

  public function listaAnni($annoStart, $annoEnd) {
    $arrAnni = array();
    for ($i = $annoStart; $i <= $annoEnd; $i++) {
      $arrAnni[] = $i;
    }
    rsort($arrAnni);
    return $arrAnni;
  }

  public function parseXml($file) {
    $fileContents = file_get_contents($file);
    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $simpleXml = simplexml_load_string($fileContents);
    $json = json_encode($simpleXml);
    return $json;
  }

  public function sortObject($objA, $objB) {
    return strcmp($objA->__toString(), $objB->__toString());
  }

  public function ln2br($text) {
    return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
  }

  public function getProportionValue($a, $b, $c) {
    return (float)((float)($b * $c) / $a);
  }

  public function htmlToText($string) {
    $search = array(
        "'<script[^>]*?>.*?</script>'si",
        "'<[\/\!]*?[^<>]*?>'si",
        "'([\r\n])[\s]+'",
        "'&(quot|#34);'i",
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&(reg|#174);'i",
        "'&#8482;'i",
        "'&#149;'i",
        "'&#151;'i",
        "'&#(\d+);'e"
    );
    $replace = array(
        " ",
        " ",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
        "&iexcl;",
        "&cent;",
        "&pound;",
        "&copy;",
        "&reg;",
        "<sup><small>TM</small></sup>",
        "&bull;",
        "-",
        "uchr(\\1)"
    );
    $text = preg_replace($search, $replace, $string);
    return $text;
  }

  public function isUTF8($text) {
    $res = mb_detect_encoding($text);
    return $res == "UTF-8" || $res == "ASCII";
  }

  public function closeUnclosedTags($unclosedString): string {
    preg_match_all("/<([^\/]\w*)>/", $closedString = $unclosedString, $tags);
    for ($i = count($tags[1]) - 1; $i >= 0; $i--) {
      $tag = $tags[1][$i];
      if (substr_count($closedString, "</$tag>") < substr_count($closedString, "<$tag>")) {
        $closedString .= "</$tag>";
      }
    }
    return $closedString;
  }

  public function vatGetValue($amount, $vatPercent = 22): float|int {
    return ($amount * $vatPercent) / 100;
  }

  public function windowClose($reloadOpener = false): void {
    if (!$reloadOpener) {
      echo "<script  type=\"text/javascript\" >\nwindow.self.close()\n</script>\n";
    } else {
      echo "<script type=\"text/javascript\" >\nwindow.opener.location.reload(true);window.self.close()\n</script>\n";
    }
  }

  public function arrayToXml($array, &$xml): void {
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        if (!is_numeric($key)) {
          $subnode = $xml->addChild("$key");
          $this->arrayToXml($value, $subnode);
        } else {
          $subnode = $xml->addChild("item$key");
          $this->arrayToXml($value, $subnode);
        }
      } else {
        $xml->addChild("$key", htmlspecialchars("$value"));
      }
    }
  }

  public function toXmlWithPrefix($node, $array, $prefix = ''): void {
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        $this->toXmlWithPrefix($node->addChild(is_numeric($key) ? 'item' : $key), $value);
      } else {
        $node->addChild($key, $value);
      }
    }
  }

  public function arrayToCsv($array, $csvFile): void {
    $f = fopen($csvFile, 'w');
    foreach ($array as $row) {
      fputcsv($f, $row, ';');
    }
    fclose($f);
  }

  function downloadCsv($array, $filename = "export.csv", $delimiter = ";"): void {
    $f = fopen('php://memory', 'w');
    foreach ($array as $line) {
      fputcsv($f, $line, $delimiter);
    }
    fseek($f, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    fpassthru($f);
  }

  public function generateColor(): string {
    mt_srand((double)microtime() * 1000000);
    $colorCode = '';
    while (strlen($colorCode) < 6) {
      $colorCode .= sprintf("%02X", mt_rand(0, 255));
    }
    return '#' . $colorCode;
  }

  public function daysInYear($year): int {
    $days = 0;
    for ($month = 1; $month <= 12; $month++) {
      $days = $days + cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
    return $days;
  }

  public function getMonthName($monthNumber): string {
    $arrayMonths = array(
        1 => 'Gennaio',
        2 => 'Febbraio',
        3 => 'Marzo',
        4 => 'Aprile',
        5 => 'Maggio',
        6 => 'Giugno',
        7 => 'Luglio',
        8 => 'Agosto',
        9 => 'Settembre',
        10 => 'Ottobre',
        11 => 'Novembre',
        12 => 'Dicembre'
    );
    return $arrayMonths[$monthNumber];
  }

  public function getMonthNumber($monthName): int {
    $arrayMonths = array(
        'Gennaio' => 1,
        'Febbraio' => 2,
        'Marzo' => 3,
        'Aprile' => 4,
        'Maggio' => 5,
        'Giugno' => 6,
        'Luglio' => 7,
        'Agosto' => 8,
        'Settembre' => 9,
        'Ottobre' => 10,
        'Novembre' => 11,
        'Dicembre' => 12
    );
    return $arrayMonths[$monthName];
  }

  public function diffDateInDays($date1, $date2): bool|\DateInterval {
    $d1 = new \DateTime($date1);
    $d2 = new \DateTime($date2);
    $diff = $d1->diff($d2);
    return $diff->format('%r%a');
  }

  function getDaysInMonth($year, $month, $day = 'Monday'): array {

    $strDate = 'first ' . $day . ' of ' . $year . '-' . $month;

    $startDay = new \DateTime($strDate);

    $days = array();

    while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
      $days[] = clone($startDay);
      $startDay->modify('+ 7 days');
    }

    return $days;
  }

  function duplicaConWatermark($imgToCopy, $dir, $nomeFile, $text, $color = 'black', $fontSize = 20, $imageFormat = 'png'): void {
    $image = new Imagick($imgToCopy);
    $draw = new ImagickDraw();
    $draw->setFontSize($fontSize);
    $draw->setFillColor($color);
    $draw->setGravity(Imagick::GRAVITY_CENTER);
    $image->annotateImage($draw, 100, 12, 0, $text);
    $image->setImageFormat($imageFormat);
    $image->writeImage($dir . $nomeFile . '.' . $imageFormat);
  }

  public function checkDigit($valore): mixed {
    $codiceControllo = 0;
    $calcolaValore = 0;
    $ultimaCifra = 0;
    $multiploSuperiore = 0;

    $codice = str_replace('-', '', $valore);

    $indice = 1;
    foreach (str_split(strrev($codice)) as $n) {
      if ($indice % 2 == 0) {
        $calcolaValore += $n;
      } else {
        $calcolaValore += ($n * 3);
      }
      $indice++;
    }

    $ultimaCifra = substr($calcolaValore, -1);

    switch ($ultimaCifra) {
      case 0:
        $multiploSuperiore = $calcolaValore;
        break;
      case 1:
        $multiploSuperiore = $calcolaValore + 9;
        break;
      case 2:
        $multiploSuperiore = $calcolaValore + 8;
        break;
      case 3:
        $multiploSuperiore = $calcolaValore + 7;
        break;
      case 4:
        $multiploSuperiore = $calcolaValore + 6;
        break;
      case 5:
        $multiploSuperiore = $calcolaValore + 5;
        break;
      case 6:
        $multiploSuperiore = $calcolaValore + 4;
        break;
      case 7:
        $multiploSuperiore = $calcolaValore + 3;
        break;
      case 8:
        $multiploSuperiore = $calcolaValore + 2;
        break;
      case 9:
        $multiploSuperiore = $calcolaValore + 1;
        break;
    }

    $codiceControllo = $multiploSuperiore - $calcolaValore;

    return $codiceControllo;
  }

  public function formatDateUs($dd, $separator = '-'): string {
    $dnExplWn = explode($separator, $dd);
    return $dnExplWn[2] . '-' . $dnExplWn[1] . '-' . $dnExplWn[0];
  }

  public function isValidDate($date, $format = 'Y-m-d'): bool {
    $dateTime = \DateTime::createFromFormat($format, $date);
    return $dateTime && $dateTime->format($format) === $date;
  }

  public function creaTimeFile($file, $isFine) {
    $timeFile = fopen($file, "a");
    $txt = date('Y-m-d H:i:s') . "\r\n";
    if ($isFine) {
      $txt .= "-----\r\n";
    }
    fwrite($timeFile, $txt);
    fclose($timeFile);
  }

  /**
   * @param $string
   * @return array|string|string[]|null
   */
  public function capitalizeWords($string): array|string|null {
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");

    $string = preg_replace_callback(
        '/\bMc(\p{L})/u',
        function ($matches) {
          return 'Mc' . mb_strtoupper($matches[1], 'UTF-8');
        },
        $string
    );
    return $string;
  }

  /**
   * Trasformazione in numero romano
   *
   * @param $number
   * @return string
   */
  public function intToRoman($number): string {
    $map = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];
    $return = '';
    while ($number > 0) {
      foreach ($map as $roman => $int) {
        if ($number >= $int) {
          $number -= $int;
          $return .= $roman;
          break;
        }
      }
    }
    return $return;
  }

  /**
   * Lista dei giorni in italiano
   *
   * @return string[]
   */
  public function listDays(): array {
    return [
        'lun' => 'Lunedì',
        'mar' => 'Martedì',
        'mer' => 'Mercoledì',
        'gio' => 'Giovedì',
        'ven' => 'Venerdì',
        'sab' => 'Sabato',
        'dom' => 'Domenica',
    ];
  }

  /**
   * Funziona per calcolo tasse
   *
   * @param $price
   * @param $iva
   * @param $vatIncluded
   * @param $discount
   * @return array
   */
  public function calucateTax($price, $iva, $vatIncluded, $discount = 0): array {
    $imponibile = (float)$price;
    $priceCalc = (float)$price;

    if ($discount > 0) {
      $discount = str_replace(',', '.', $discount);
      $priceCalc = (float)$price;
    }

    if ($vatIncluded == 't') {
      $imponibile = $priceCalc / (1 + ($iva / 100));
      $priceTot = $price - $discount;
      $taxValue = (($priceTot * $iva / 100));
    } else {
      $taxValue = (($priceCalc * $iva / 100));
      $priceTot = $priceCalc + $taxValue;
    }

    return [
        'tasse_valore' => round($taxValue, 2),
        'price' => round($imponibile, 2),
        'price_totale' => round($priceTot, 2)
    ];
  }

  /**
   * Funzione per conversione da centesimi per Stripe
   *
   * @param $euroImporto
   * @return int
   */
  public function euroToCents($euroImporto): int {
    return (int)round(floatval($euroImporto) * 100);
  }

  /**
   * Converte i secondi in formato hh:mm:ss
   *
   * @param mixed $secs
   * @return string
   */
  public static function sec2hms(mixed $secs): string {
    $secs = round($secs);
    $secs = abs($secs);

    $hours = floor($secs / 3600) . ':';
    if ($hours == '0:') $hours = '00:';

    $minutes = substr('00' . floor((int)($secs / 60) % 60), -2) . ':';
    if ($minutes == '') $minutes = '00:';

    $seconds = substr('00' . $secs % 60, -2);

    return $hours . $minutes . $seconds;
  }

  /**
   * Funzione per rendere più leggibile gli errori SQL
   *
   * @param $err
   * @return string
   */
  public function translateSqlError($err): string {
    return match (true) {
      str_contains($err, 'duplicate key value violates unique constraint') && str_contains($err, 'u_email_unique') => "L'indirizzo email inserito è già registrato.",
      str_contains($err, 'duplicate key value violates unique constraint') => "Hai inserito un valore già esistente per un campo che deve essere unico.",
      str_contains($err, 'null value in column') => (
      preg_match('/null value in column "([^"]+)"/', $err, $matches)
          ? "Il campo '" . $matches[1] . "' è obbligatorio."
          : "Un campo obbligatorio non è stato compilato."
      ),
      default => $err,
    };
  }

  /**
   * Funzione per upload generico
   *
   * @param $dir
   * @param string $uploadName
   * @param array $allowedExtensions
   * @return string
   */
  public function genericUpload(
      $dir,
      string $uploadName = 'upload',
      array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']
  ): string {
    $uploadDir = SERVER_BASE_URL_LOAD_RESOURCES . "/" . $dir;

    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    if (!isset($_FILES[$uploadName]) || $_FILES[$uploadName]['error'] !== UPLOAD_ERR_OK) {
      $errorCode = $_FILES[$uploadName]['error'] ?? null;

      $errorMessage = match ($errorCode) {
        UPLOAD_ERR_INI_SIZE => 'Il file supera la dimensione massima definita in php.ini (upload_max_filesize).',
        UPLOAD_ERR_FORM_SIZE => 'Il file supera la dimensione massima definita nel form HTML (MAX_FILE_SIZE).',
        UPLOAD_ERR_PARTIAL => 'Il file è stato caricato solo parzialmente.',
        UPLOAD_ERR_NO_FILE => 'Nessun file è stato caricato.',
        UPLOAD_ERR_NO_TMP_DIR => 'Manca la cartella temporanea.',
        UPLOAD_ERR_CANT_WRITE => 'Impossibile scrivere il file sul disco.',
        UPLOAD_ERR_EXTENSION => 'Caricamento interrotto da un\'estensione PHP.',
        default => 'Errore sconosciuto durante il caricamento del file.',
      };

      return json_encode([
          'status' => 0,
          'error' => $errorMessage,
          'code' => $errorCode,
      ]);
    }

    $fileTmpPath = $_FILES[$uploadName]['tmp_name'];
    $originalName = $_FILES[$uploadName]['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
      return json_encode([
          'status' => 0,
          'error' => 'Tipo di file non supportato.'
      ]);
    }

    $newFileName = uniqid('', true) . '.' . $extension;
    $destPath = $uploadDir . '/' . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
      return json_encode([
          'status' => 0,
          'error' => 'Impossibile salvare il file.'
      ]);
    }

    return json_encode([
        'status' => 1,
        'file' => "https://" . $_SERVER['HTTP_HOST'] . '/' . $uploadDir . '/' . $newFileName,
        'url' => "https://" . $_SERVER['HTTP_HOST'] . '/' . $uploadDir . '/' . $newFileName,
        'file_name' => $newFileName,
    ]);
  }

  /**
   * Funzione per unire $utente_settings (array di sessione)
   * con $settingsKeysAnagrafiche (campi form)
   *
   * @param $array1
   * @param $array2
   * @param array $arrayExclude
   * @return array
   */
  public function campiForm($array1, $array2, array $arrayExclude = []): array {
    $result = [];
    foreach ($array1 as $campo) {
      $nomeCampo = $campo['campo'];

      if (!empty($arrayExclude) && in_array($nomeCampo, $arrayExclude)) {
        continue;
      }

      $valoreUtente = $array2[$nomeCampo] ?? null;
      $record = $campo;
      $record['valore'] = $valoreUtente;

      if ($nomeCampo === 'categoria_protetta') {
        $record['tipo_categoria_protetta'] = $array2['tipo_categoria_protetta'] ?? null;
      }

      $result[$nomeCampo] = $record;
    }

    return $result;
  }

  /**
   * Conversione array di oggetti in array associativo
   *
   * @param array $arr
   * @param string $key
   * @return array
   */
  public function toAssocArray(array $arr, string $key = 'id'): array {
    $arrReturn = [];
    foreach ($arr as $obj) {
      $arr = get_object_vars($obj);
      $arrReturn[$arr[$key]] = $arr;
    }

    return $arrReturn;
  }

  /**
   * Funzione per calcolare le ore partendo da due date
   *
   * @param string $dataInizio
   * @param string $dataFine
   * @return float
   */
  function calcolaOre(string $dataInizio, string $dataFine): float {
    $format = 'd-m-Y H:i';

    $inizio = DateTime::createFromFormat($format, $dataInizio);
    $fine = DateTime::createFromFormat($format, $dataFine);

    if (!$inizio || !$fine) {
      return 0;
    }

    $diff = $fine->getTimestamp() - $inizio->getTimestamp();

    if ($diff <= 0) {
      return 0;
    }

    return $diff / 3600;
  }

  /**
   * Converte le ore in secondi
   * Accetta anche ore con decimali
   *
   * @param float $ore
   * @return int
   */
  function oreInSecondi(float $ore): int {
    $oreIntere = floor($ore);
    $minuti = ($ore - $oreIntere) * 100;

    $secondi = ($oreIntere * 3600) + ($minuti * 60);

    return (int)$secondi;
  }

  /**
   * @param string $hhmmss
   * @param int $decimali
   * @return float
   */
  public function hhmmssInOreDecimali(string $hhmmss, int $decimali = 3): float {
    [$ore, $minuti, $secondi] = explode(':', $hhmmss);
    $oreDecimali = $ore + ($minuti / 60) + ($secondi / 3600);
    return round($oreDecimali, $decimali);
  }

  /**
   * @param float $oreDecimali
   * @return string
   */
  public function oreDecimaliInHHMMSS(float $oreDecimali): string {
    $ore = floor($oreDecimali);
    $minutiDecimali = ($oreDecimali - $ore) * 60;
    $minuti = floor($minutiDecimali);
    $secondi = round(($minutiDecimali - $minuti) * 60);

    if ($secondi === 60) {
      $secondi = 0;
      $minuti++;
    }
    if ($minuti === 60) {
      $minuti = 0;
      $ore++;
    }

    return sprintf('%02d:%02d:%02d', $ore, $minuti, $secondi);
  }
}
