<?php declare(strict_types=1);

namespace EJSHelper\Tests;

use PHPUnit\Framework\TestCase;
use Ekojs\Otp\HOTP;

class HOTPTest extends TestCase {
    private $hotp;

    protected function setUp(): void
    {
        $this->hotp = HOTP::getInstance([
            "secret" => "VZCKGWRLS7CINEYALENYPH5T442LJUAFGSNCBTBQEHMN5GSVGTJCD2B7NHCZFK5FZ3QHTQ66JYDMNUI2UBWZJAYHI62VYVHVUGTO6SQ",
            "counter" => 1000
        ]);
        $this->hotp->otp->setLabel('ekojs@email.com');
        $this->hotp->otp->setIssuer("My Service HOTP");
    }

    public function testGenerateHotp(): void
    {
        $this->assertEquals("301943",$this->hotp->otp->at(1000));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->hotp->otp->verify("301943",1000));
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

    public function testGenerateBackupCodes(): void 
    {
        $this->assertEquals("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty",implode(" ",$this->hotp->generateBackupCodes("mysecret")));
    }

    public function testReverseMnemonic(): void 
    {
        $this->assertEquals(hash("md5","mysecret"),$this->hotp->reverseMnemonic("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty"));
    }
}
