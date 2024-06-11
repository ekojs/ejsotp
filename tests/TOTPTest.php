<?php declare(strict_types=1);

namespace EJSHelper\Tests;

use PHPUnit\Framework\TestCase;
use Ekojs\Otp\TOTP;

class TOTPTest extends TestCase {
    private $totp;

    protected function setUp(): void
    {
        $this->totp = TOTP::getInstance();
        $this->totp->createOTP([
            "secret" => "mysecret"
        ]);
        $this->totp->otp->setLabel('ekojs@email.com');
        $this->totp->otp->setIssuer("My Service");
    }

    public function testGenerateTotp(): void
    {
        $this->assertEquals("299160",$this->totp->otp->at(1652149726));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->totp->otp->verify("299160",1652149726));
    }

    public function testGetParameters(): void
    {
        $this->assertIsArray($this->totp->getParams());
    }

    public function testGenerateQr(): void
    {
        $this->assertEquals("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANwAAADcCAIAAACUOFjWAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAFc0lEQVR4nO3dwW7zNhCF0abo+79yuutC+CtY1pD64pyzjS0p8QXhCcnh1/f3919Q8vfTDwBHQkmOUJIjlOQIJTlCSY5QkiOU5AglOUJJjlCSI5TkCCU5QkmOUJIjlOT888Z7vr6+xp/jj84XIB8eY3C18vkveOlGl/5Whyuf/4KDV17nvQ/FSEmOUJIjlOQIJTnvFDoH2yqMS1/P7xQrlwqs8/deutTgr3/pQ9n2Cb7ISEmOUJIjlOQIJTkDhc7Btu/jl6qEO5NDl346OFly6TEO7kx3rfsEX2SkJEcoyRFKcoSSnPlCZ51Lpczgt/WnCo5Ltq1G28BISY5QkiOU5AglOT+p0Bmclblz30tXHiy/7tz3ZzFSkiOU5AglOUJJznyhs23SYnDZ2494jHgHgUFGSnKEkhyhJEcoyRkodH7EoqnBXTiDPz146r61T9BISY5QkiOU5AglOV+P//t+jzsLzNbt5L/jg1eyGSnJEUpyhJIcoSTn4XN01p3vcmfu5Ny2mun8vnee+ampoxcZKckRSnKEkhyhJOedGZ3BRs7n732qz/G62ZF1R5Gu+7MPXvlFRkpyhJIcoSRHKMkZWLp2p93Zus7N66qx80s1N8cMVpAbJniMlOQIJTlCSY5QkjMwo7PtLM51UwuDX/wHbVgktvrK7zFSkiOU5AglOUJJzjt7dAaPyHxqRufSY1x677mnFsWtY+kav4JQkiOU5AglOfN7dA4i+z+2tTnYdqN19x18DIUOH0IoyRFKcoSSnIFzdAYneNad47ltk8qdtXkHd/4agx/K4HtfZKQkRyjJEUpyhJKc5TM6l6w7G2aw29vgHp11K/cuvbhW9xgpyRFKcoSSHKEkZ74ZQaSkGJxZ2dbPbVuXuXXF2fmLX2SkJEcoyRFKcoSSnIH20scrPtST7eCp1mGDLRIO1nVMeOp0n/9jpCRHKMkRSnKEkpyBPTp3NGca7tQQg/2zDz9d14r70n03MFKSI5TkCCU5QknOw+fo3LFtSmPdpe7c6NxTDevs0eEzCSU5QkmOUJIzsHStuVZtcIP9Jdv2+T/V5NseHX4joSRHKMkRSnLeWbp2qSnyU1+iI2eAnr94sCp6ak/SgRkdPpNQkiOU5AglOfPtpQcnac6tW9l1fqPmYrxIEwSFDp9JKMkRSnKEkpyHl64dfPw5OpGG2XdudP7iEUZKcoSSHKEkRyjJme+6dqnuWdeF7Ny2iaVLHczuPNW2DUx37vsiIyU5QkmOUJIjlOSku649dVJO5FjPSwa7PAz+9D1GSnKEkhyhJEcoyZkvdNb1V97WuXndiTWDtvWRu/QYDgzlMwklOUJJjlCSM7907altKOvKkcFJi8HZr23nh56/eEUJZaQkRyjJEUpyhJKc+ULn3KVtNweR7ffrrrxum9FgD+wNjJTkCCU5QkmOUJLzTqEzeL7LuvVXT52js67b9LqZpPP77l+qZ6QkRyjJEUpyhJKc+QNDBw0WDevmP87d6SN36anu/IKR7T7/MVKSI5TkCCU5QknOwNK1bSdmPtV9+dy6LmTr1owNrpFzjg6/glCSI5TkCCU5y8/ROfdUt+l1C9vWNds+v9Tgix9npCRHKMkRSnKEkpzdzQjWOa8hBs8tvfMYl148ePTqoA0nAxkpyRFKcoSSHKEk5wcXOnd2xa+re9Yt9NrWi+HSY6y4kZGSHKEkRyjJEUpylp+js82dymbdMrBtJwNt6669YcuOkZIcoSRHKMkRSnK+3vhm+lTXtW1fwAeXY0V2LD1VYFm6xocQSnKEkhyhJOedQgeWMlKSI5TkCCU5QkmOUJIjlOQIJTlCSY5QkiOU5AglOUJJjlCSI5TkCCU5QkmOUJLzL2uk98s+tqosAAAAAElFTkSuQmCC",$this->totp->generateQr()->getDataUri());
    }

    public function testCreateTotp(): void 
    {
        $otp = $this->totp->createOTP(["secret" => "mysecret1234567890"])->otp;
        $this->assertInstanceOf(\OTPHP\TOTP::class, $otp);
    }

    public function testGenerateBackupCodes(): void 
    {
        $this->assertEquals("gown glue immune autumn occur era cave antenna spray cake crack nothing ball drive mother next fade certain average desert flip owner infant buddy",implode(" ",$this->totp->generateBackupCodes("mysecret")));
    }

    public function testReverseMnemonic(): void 
    {
        $this->assertEquals(hash("md5","mysecret"),$this->totp->reverseMnemonic("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty"));
    }
}
