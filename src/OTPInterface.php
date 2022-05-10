<?php declare(strict_types=1);

/**
 * OTP Interface
 * 
 * @author Eko Junaidi Salam <eko.junaidi.salam@gmail.com>
 */

namespace Ekojs\Otp;

use FurqanSiddiqui\BIP39\Wordlist;
use Endroid\QrCode\Writer\Result\ResultInterface;

interface OTPInterface
{
    public function getParams(): ?array;
    public function createOTP(?array $params=null): OTPInterface;
    public function generateQr(?string $logo=null, bool $setLabel=false, int $size=200): ResultInterface;
    public function generateBackupCodes(string $entropy, ?WordList $wordList=null): array;
    public function reverseMnemonic($words): string;
}
