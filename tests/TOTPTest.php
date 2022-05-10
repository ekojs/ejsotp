<?php declare(strict_types=1);

namespace EJSHelper\Tests;

use PHPUnit\Framework\TestCase;
use Ekojs\Otp\TOTP;

class TOTPTest extends TestCase {
    private $totp;

    protected function setUp(): void
    {
        $this->totp = TOTP::getInstance([
            "secret" => "VZCKGWRLS7CINEYALENYPH5T442LJUAFGSNCBTBQEHMN5GSVGTJCD2B7NHCZFK5FZ3QHTQ66JYDMNUI2UBWZJAYHI62VYVHVUGTO6SQ"
        ]);
        $this->totp->otp->setLabel('ekojs@email.com');
        $this->totp->otp->setIssuer("My Service");
    }

    public function testGenerateTotp(): void
    {
        $this->assertEquals("710065",$this->totp->otp->at(1652149726));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->totp->otp->verify("710065",1652149726));
    }

    public function testGetParameters(): void
    {
        $this->assertIsArray($this->totp->getParams());
    }

    public function testGenerateQr(): void
    {
        $this->assertEquals("data:image/png;base64,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",$this->totp->generateQr()->getDataUri());
    }

    public function testGenerateBackupCodes(): void 
    {
        $this->assertEquals("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty",implode(" ",$this->totp->generateBackupCodes("mysecret")));
    }

    public function testReverseMnemonic(): void 
    {
        $this->assertEquals(hash("md5","mysecret"),$this->totp->reverseMnemonic("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty"));
    }
}
