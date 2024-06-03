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
        $this->assertEquals("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANwAAADcCAIAAACUOFjWAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAFgElEQVR4nO3dwXIjNwwFwCiV///lzS2HqZhliAD5LHdfLc2MtK9YwpIEX3/+/PkLkvx9+wHgSSiJI5TEEUriCCVxhJI4QkkcoSSOUBJHKIkjlMQRSuIIJXGEkjhCSRyhJM4/b7zn9Xq1P8f/Ki1AfjzV473rv84pfVelZ2688pz3vmcjJXGEkjhCSRyhJM47hc5DY9Gw/gG+U6ysX1y68vohH+8tXar08dcav5ySlhLKSEkcoSSOUBJHKInTUOg83Po9fquUKRUrJTuTUjtF4dy/4DcZKYkjlMQRSuIIJXH6C505jT/Ad1aFHSs4So6tRjvASEkcoSSOUBJHKInzkwqdnRmOxrqnVBU1bqzZue/PYqQkjlASRyiJI5TE6S90QiYtGuuAxr4GO3VPeAeBRkZK4gglcYSSOEJJnIZC59aiqbkeZQ+N8z2laadj901b9makJI5QEkcoiSOUxHld/+/7t93qrxxSNHzwSjYjJXGEkjhCSRyhJM47hc6xxmKNl5o7kmfuMdZPtePW1NE3GSmJI5TEEUriCCVx+md0GqcWGvubzbWXLgn5ctb3vf5dGSmJI5TEEUriCCVxxruuzU2lzB0Yun7vzkPOncFz7Oyfub1Q/zFSEkcoiSOUxBFK4jTM6NxaylXS2AR64qf9Gzdq/Opu/aN8xUhJHKEkjlASRyiJ0zCjMzdbsHPfnWN1Si/embLauVTpynMsXeNXEEriCCVxhJI4l2d01i8u3ah0qbkrz7UMWDs2sVR6DIUOH0IoiSOUxBFK4vR3XTtWrDT2RiuZa5HQaOep1g40YTNSEkcoiSOUxBFK4vTP6Mzt8DhW2Rzbo9PY7uxYS7qdx/gmIyVxhJI4QkkcoSTOO3t0jv0efzg2HbJz39IHPDY5VHpx4wc0o8OHEEriCCVxhJI440vXjk20HHuMtbm1ajuXOnYWjkKHzySUxBFK4gglcfoLnYeQA2wa77t+8fpGO5+o9JAhO4cUOnwIoSSOUBJHKInTUOhkdgFYv/fhVmu4tblu07da0n2TkZI4QkkcoSSOUBLnnT06x9oNlF68czRO42E/x/b5HztzqHQpS9f4TEJJHKEkjlASp+HA0Ea31lAdW+g11/1srvosMaPDZxJK4gglcYSSOA1d1x6ONfhau7WxpnSjtbkNTI03mmCkJI5QEkcoiSOUxGmY0ZnrBvYwtzeoVAfMLSHbee+t9m4TM0lGSuIIJXGEkjhCSZyGA0Nr97tUNNw6BDOklJnrUFe67zcZKYkjlMQRSuIIJXFOL11rXOh17Nd6yVx1sv5rYx/rxv1M7zFSEkcoiSOUxBFK4oy3l848qvIndpsu3TfzMb7JSEkcoSSOUBJHKInT33Utc0XZscpmbacJQunFjZdav3jiuzJSEkcoiSOUxBFK4vQXOnNn0qzNnaPTWNnMfd6dvmrrF59vXG2kJI5QEkcoiSOUxOk/MDTkQJeHnf0uc4ffrDVeeWf26/xeKCMlcYSSOEJJHKEkTn8zgkZz53gea6RWeshjp93MLQJsYaQkjlASRyiJI5TE6T9HZ8fctv/MzTFrc3NjjWvkNCPgVxBK4gglcYSSOJf36Nw63mb9GOsbNXZfvjWxFM5ISRyhJI5QEkcoidNf6NzSuHfkVgezxuNtjvWT1nWNX0EoiSOUxBFK4vzgQqf0E3uuR9n6vremcObqnsYTUb9ipCSOUBJHKIkjlMQZP0fnmGMnhM5NDjX+tWRnkmZiPaGRkjhCSRyhJI5QEqeh0AnZ/zG30KtxJdv6r7ee+dbZo18xUhJHKIkjlMQRSuK8Mo+94TczUhJHKIkjlMQRSuIIJXGEkjhCSRyhJI5QEkcoiSOUxBFK4gglcYSSOEJJHKEkjlAS519R6OjOiu9+2QAAAABJRU5ErkJggg==",$this->totp->generateQr()->getDataUri());
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
