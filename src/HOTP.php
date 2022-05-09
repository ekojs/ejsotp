<?php declare(strict_types=1);

/**
 * TOTP dan HOTP Library with backup codes, compatible with google authenticator
 * 
 * @author Eko Junaidi Salam <eko.junaidi.salam@gmail.com>
 */

namespace Ekojs\Otp;

use OTPHP\HOTP as LabHOTP;
use \FurqanSiddiqui\BIP39\BIP39;
use \FurqanSiddiqui\BIP39\Wordlist;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;

class HOTP {
    public static $instance;
    public $otp;
    protected $writer;
    protected $bip39;
    public $parameters = [];

    public function __construct(?array $params=null) {
        $this->parameters = $params;
        $this->otp = LabHOTP::create($params["secret"] ?? null, $params["counter"] ?? 0, $params["digest"] ?? 'sha1', $params["digits"] ?? 6);
        $this->writer = new PngWriter();
        $this->bip39 = new BIP39();
        self::$instance = $this;
    }

    public static function getInstance(?array $params=null) {
        if (self::$instance === null) {
            self::$instance = new self($params);
        }
        return self::$instance;
    }

    public function getParams(): array {
        return $this->parameters;
    }

    public function generateQr(?string $logo=null, bool $setLabel=false, int $size=200) {
        $label = null;
        $qrCode = QrCode::create($this->otp->getProvisioningUri());
        $qrCode->setSize($size);

        if(!empty($logo)) $logo = Logo::create($logo)->setResizeToWidth(50);
        if(!empty($this->otp->getLabel()) && $setLabel) $label = Label::create($this->otp->getLabel())->setTextColor(new Color(0, 0, 255));

        return $this->writer->write($qrCode, $logo, $label);
    }

    public function generateBackupCodes(string $entropy, ?WordList $wordList=null) {
        $wordList = $wordList ?? WordList::English();
        return $this->bip39->wordlist($wordList)->useEntropy(hash("md5",$entropy))->mnemonic();
    }

    public function reverseMnemonic($words) {
        return $this->bip39::Words($words);
    }
}
 