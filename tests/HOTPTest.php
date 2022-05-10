<?php declare(strict_types=1);

namespace EJSHelper\Tests;

use PHPUnit\Framework\TestCase;
use Ekojs\Otp\HOTP;

class HOTPTest extends TestCase {
    private $hotp;

    protected function setUp(): void
    {
        $this->hotp = HOTP::getInstance([
            "secret" => "mysecret",
            "counter" => 1000
        ]);
        $this->hotp->otp->setLabel('ekojs@email.com');
        $this->hotp->otp->setIssuer("My Service HOTP");
    }

    public function testGenerateHotp(): void
    {
        $this->assertEquals("723619",$this->hotp->otp->at(1000));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->hotp->otp->verify("723619",1000));
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
