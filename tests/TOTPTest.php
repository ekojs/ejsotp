<?php declare(strict_types=1);

namespace EJSHelper\Tests;

use PHPUnit\Framework\TestCase;
use Ekojs\Otp\TOTP;

class TOTPTest extends TestCase {
    private $totp;

    protected function setUp(): void
    {
        $this->totp = TOTP::getInstance([
            "secret" => "mysecret"
        ]);
        $this->totp->otp->setLabel('ekojs@email.com');
        $this->totp->otp->setIssuer("My Service");
    }

    public function testGenerateTotp(): void
    {
        $this->assertEquals("389403",$this->totp->otp->at(1652149726));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->totp->otp->verify("389403",1652149726));
    }

    public function testGetParameters(): void
    {
        $this->assertIsArray($this->totp->getParams());
    }

    public function testGenerateQr(): void
    {
        $this->assertEquals("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANwAAADcCAIAAACUOFjWAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAFcElEQVR4nO3dwY7jNhBF0XSQ///lyS4LIyOMzCJ1233O1rYkex6IriFZ/Pr169dfUPL30w8Ar4SSHKEkRyjJEUpyhJIcoSRHKMkRSnKEkhyhJEcoyRFKcoSSHKEkRyjJ+eeNz3x9fY0/x/+6XoD88hjN1cq3fquXr3D9BQevvM97/yhGSnKEkhyhJEcoyXmn0HkxWGFc/wF+69VbT3Xrs9eP8fLZW5da+frXjzH45msjJZSRkhyhJEcoyRFKcgYKnRfH/h7fN6Nzq5S5VazcsvJ9B8u+azsm0oyU5AglOUJJjlCSM1/o7LNSQ6zULteXiqyvO7Ya7QAjJTlCSY5QkiOU5HynQufWVMrKErLBvTKDG2tW7vu9GCnJEUpyhJIcoSRnvtB5atJi330Hl8yt1D3xDgKDjJTkCCU5QkmOUJIzUOg8tWhqZd5lsFXB4H0HrzzYXuE8IyU5QkmOUJIjlOR8Pf7f928b7L58S6Ro+OCVbEZKcoSSHKEkRyjJeafQOdZYbOVSK/Ztyll5yKf6ue2bKvsdIyU5QkmOUJIjlORsn9E5drzN4KX2zY4MXnnfN3r8tzJSkiOU5AglOUJJzvyMzr5y5Ng+mxcfsDlmsPnbgQkeIyU5QkmOUJIjlORsX7o2uJVk8CDLpzpG3/LUAamPb+gxUpIjlOQIJTlCSc5A17VIKTN4yuf1jW69ev0YK5e6deV9LF3jRxBKcoSSHKEk53R76X2bVFYMFkkrT7Vy5umgwZbY7zFSkiOU5AglOUJJzvyBoS9WqoSnZjhWHnJlR8v1jW7Z9zsfWOdmpCRHKMkRSnKEkpzP6bo2+OZje3QGGxkMft/H6x4jJTlCSY5QkiOU5GzvunbsWM9IZbOvGfO3KM6u3/yHjJTkCCU5QkmOUJIz34zg2rG/9FemFla6DwzOjlzfN1Ks2KPDjyCU5AglOUJJTnpG5zt2m1658r7TfVaeaoUZHT6EUJIjlOQIJTnb9+jsE1kGduupbtnXi2Ffdzt7dPhMQkmOUJIjlOS8s3Rt3971b1F/7Jtoub7RyrzL4FNdX2pkAaGRkhyhJEcoyRFKct4pdAa3v+zrVbxv38lKwbFvm1FkZs6MDp9JKMkRSnKEkpyBPTrHplJuOdYF4Fhx9uJYd7sDbdZeGCnJEUpyhJIcoSRnYEZn3zzEsT3z144tIVv57LeubF4YKckRSnKEkhyhJOfhZgTHNpo8NWcTKWX2/XS37vuHjJTkCCU5QkmOUJIz0Izgxb75gMEOytdvXrGvOrl+dbDLw+AxQu8xUpIjlOQIJTlCSU56j87guTK33tzs9nbrvs3H+ENGSnKEkhyhJEcoyXlnRmfFsf7Kgy2TV65867P7Dv1szn79jpGSHKEkRyjJEUpyThc6L57qyXbsEMxIcTbYA/vWle3R4UMIJTlCSY5QkvNwM4JbjnUfuNbsibBvL9StSzkwlM8klOQIJTlCSc58M4JBB/6mfsO+E0JXZmVWJngi233+Y6QkRyjJEUpyhJKcgaVrx07MvFUl7CuSBjfHXNtXug2ukdOMgB9BKMkRSnKEkpz5PTr7tr/sW1J168qD3ZePndDz1F6o9xgpyRFKcoSSHKEk5+FmBCtuzTQcm8N4qnPayq6jWw50qDNSkiOU5AglOUJJzjcudG7Z15vg1qWOTeHsq3sGT0T9HSMlOUJJjlCSI5TkzBc6zaYAg2fD7GvRNvjqLfvOWn2PkZIcoSRHKMkRSnIGCp2n9n8MHiQzWHDcuu++xWnHCqwX2kvzmYSSHKEkRyjJ+U7n6PBDGCnJEUpyhJIcoSRHKMkRSnKEkhyhJEcoyRFKcoSSHKEkRyjJEUpyhJIcoSRHKMn5F+Y83M5K0vWXAAAAAElFTkSuQmCC",$this->totp->generateQr()->getDataUri());
    }

    public function testCreateTotp(): void 
    {
        $otp = $this->totp->createOTP(["secret" => "mysecret1234567890"])->otp;
        $this->assertInstanceOf(\OTPHP\TOTP::class, $otp);
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
