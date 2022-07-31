<?php

namespace Drupal\jppl_product;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


/**
 * Helper class for generating the QR code.
 */
class QRGeneratorService {

  /**
   * Generates the QR code data.
   *
   * @param string $link
   *   The URL link.
   *
   * @return 
   */
  public function generateQr(string $link) {
    $result = Builder::create()
      ->writer(new PngWriter())
      ->writerOptions([])
      ->data($link)
      ->encoding(new Encoding('UTF-8'))
      ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
      ->size(300)
      ->margin(10)
      ->build();

    return $result->getDataUri();
  }

}
