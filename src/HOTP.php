<?php declare(strict_types=1);

/**
 * TOTP dan HOTP Library with backup codes, compatible with google authenticator
 * 
 * @author Eko Junaidi Salam <eko.junaidi.salam@gmail.com>
 */

namespace Ekojs\Otp;

use OTPHP\HOTP as LabHOTP;
use OTPHP\OTP as LabOTP;
use \FurqanSiddiqui\BIP39\BIP39;
use \FurqanSiddiqui\BIP39\WordList;
use ParagonIE\ConstantTime\Base32;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\Result\ResultInterface;

class HOTP implements OTPInterface {
    public static $instance;
    public $otp;
    protected $writer;
    protected $bip39;
    public $parameters = [];

    public function __construct() {
        $this->writer = new PngWriter();
        self::$instance = $this;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getParams(): ?array {
        return $this->parameters ?? null;
    }

    public function createOTP(?array $params=null): OTPInterface {
        $this->parameters = $params;
        $this->otp = LabHOTP::create($params["secret"] ?? Base32::encodeUpper(random_bytes(64)), $params["counter"] ?? 0, $params["digest"] ?? 'sha1', $params["digits"] ?? 6);
        return $this;
    }

    public function generateQr(?string $logo=null, bool $setLabel=false, int $size=200): ResultInterface {
        $qlogo = null;
        $qlabel = null;

        $qrCode = new QrCode(
            data: $this->otp->getProvisioningUri(),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: $size,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        if(!empty($logo)) {
            $qlogo = new Logo(
                path: $logo,
                resizeToWidth: 50,
                punchoutBackground: true
            );
        }

        if(!empty($this->otp->getLabel()) && $setLabel) {
            $qlabel = new Label(
                text: $this->otp->getLabel(),
                textColor: new Color(0, 0, 255)
            );
        }

        return $this->writer->write($qrCode, $qlogo, $qlabel);
    }

    public function generateBackupCodes(string $entropy, ?WordList $wordList=null): array {
        $wordList = $wordList ?? WordList::English();
        return BIP39::Entropy(hash("sha256",$entropy))->words;
    }

    public function reverseMnemonic($words): string {
        return BIP39::Words($words)->entropy;
    }
}
 
