<?php declare(strict_types=1);

namespace EJSHelper\Tests;

use PHPUnit\Framework\TestCase;
use Ekojs\Otp\HOTP;

class HOTPTest extends TestCase {
    private $hotp;

    protected function setUp(): void
    {
        $this->hotp = HOTP::getInstance();
        $this->hotp->createOTP([
            "secret" => "mysecret",
            "counter" => 1000
        ]);
        $this->hotp->otp->setLabel('ekojs@email.com');
        $this->hotp->otp->setIssuer("My Service HOTP");
    }

    public function testGenerateHotp(): void
    {
        $this->assertEquals("849873",$this->hotp->otp->at(1000));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->hotp->otp->verify("849873",1000));
    }

    public function testGetParameters(): void
    {
        $this->assertIsArray($this->hotp->getParams());
    }

    // public function testGetProvisioningUri(): void 
    // {
    //     $this->assertEquals("otpauth://hotp/My%20Service%20HOTP%3Aekojs%40email.com?counter=1000&issuer=My%20Service%20HOTP&secret=VZCKGWRLS7CINEYALENYPH5T442LJUAFGSNCBTBQEHMN5GSVGTJCD2B7NHCZFK5FZ3QHTQ66JYDMNUI2UBWZJAYHI62VYVHVUGTO6SQ",$this->hotp->otp->getProvisioningUri());
    // }

    public function testGenerateQr(): void
    {
        $this->assertEquals("data:image/png;base64,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",$this->hotp->generateQr()->getDataUri());
    }

    public function testCreateHotp(): void 
    {
        $otp = $this->hotp->createOTP([
            "secret" => "mysecret1234567890",
            "counter" => 1000
        ])->otp;
        $this->assertInstanceOf(\OTPHP\HOTP::class, $otp);
    }

    public function testGenerateBackupCodes(): void 
    {
        $this->assertEquals("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty",implode(" ",$this->hotp->generateBackupCodes("mysecret")));
    }

    public function testReverseMnemonic(): void 
    {
        $this->assertEquals(hash("md5","mysecret"),$this->hotp->reverseMnemonic("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty"));
    }
}
