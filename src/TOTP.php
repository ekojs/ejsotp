<?php declare(strict_types=1);

/**
 * TOTP dan HOTP Library with backup codes, compatible with google authenticator
 * 
 * @author Eko Junaidi Salam <eko.junaidi.salam@gmail.com>
 */

namespace Ekojs\Otp;

use OTPHP\TOTP as LabTOTP;
use \FurqanSiddiqui\BIP39\BIP39;
use \FurqanSiddiqui\BIP39\Wordlist;
use ParagonIE\ConstantTime\Base32;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\Result\ResultInterface;

class TOTP implements OTPInterface {
    public static $instance;
    private $mode;
    public $otp;
    protected $writer;
    protected $bip39;
    public $parameters = [];

    public function __construct() {
        $this->writer = new PngWriter();
        $this->bip39 = new BIP39();
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
        $this->otp = LabTOTP::create(!empty($params["secret"]) && is_string($params["secret"]) ? Base32::encodeUpper($params["secret"]) : null, $params["period"] ?? 30, $params["digest"] ?? 'sha1', $params["digits"] ?? 6, $params["epoch"] ?? 0);
        return $this;
    }

    public function generateQr(?string $logo=null, bool $setLabel=false, int $size=200): ResultInterface {
        $label = null;
        $qrCode = QrCode::create($this->otp->getProvisioningUri());
        $qrCode->setSize($size);

        if(!empty($logo)) $logo = Logo::create($logo)->setResizeToWidth(50);
        if(!empty($this->otp->getLabel()) && $setLabel) $label = Label::create($this->otp->getLabel())->setTextColor(new Color(0, 0, 255));

        return $this->writer->write($qrCode, $logo, $label);
    }

    public function generateBackupCodes(string $entropy, ?WordList $wordList=null): array {
        $wordList = $wordList ?? WordList::English();
        return $this->bip39->wordlist($wordList)->useEntropy(hash("md5",$entropy))->mnemonic()->words;
    }

    public function reverseMnemonic($words): string {
        return $this->bip39::Words($words)->entropy;
    }
}
 