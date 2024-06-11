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
        $this->assertEquals("983261",$this->hotp->otp->at(1000));
    }

    public function testVerifyTotp(): void
    {
        $this->assertTrue($this->hotp->otp->verify("983261",1000));
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
        $this->assertEquals("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANwAAADcCAIAAACUOFjWAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAF7UlEQVR4nO3dwY7dNgwF0E7R///l6WYWTlEBEkjK9yXnLB88toNcCIRkUV/f399/QZK/334B+C+hJI5QEkcoiSOUxBFK4gglcYSSOEJJHKEkjlASRyiJI5TEEUriCCVxhJI4QkkcoSSOUBJHKIkjlMQRSuIIJXGEkjhCSRyhJI5QEuefiZt+fX2133On59Hzuc/ru37fee7K6XN37nPqrf+XU0ZK4gglcYSSOCM15dNEDXRaG63e4fT31XNP68LVPbvq4NW77fy+Y6I2fTJSEkcoiSOUxBmvKZ+65uEqc5Y716x+X13TVeOurumqrVem50dPGSmJI5TEEUriXK0pu+zMyVVqxNMatKJSz028TwIjJXGEkjhCSZyPrCkra81PO/N/qxp05z5Plfr19J6raz6FkZI4QkkcoSTO1ZpyYm6vsq69826VfTyr9fG36sudv01gpCSOUBJHKIkzXlNO7+dYPauyr2Vin/jEPvSJOdoERkriCCVxhJI4IzXl9LzXRD+gU6f16NNp3Vl5n53npjFSEkcoiSOUxInrT9k1D5dgos/l08Sa+1s1+pORkjhCSRyhJE7E95Q7NdDq+p1nVWq1rve52T9ydZ+uWn+akZI4QkkcoSTO13StMLF/uav/zun7VOYap9+nax4xYX+PkZI4QkkcoSRO3DxlpfY6vefps7rmO3eueev30/efYKQkjlASRyiJMzJP+dZ52afv0LWvZaJv+UQvpB0J+3iMlMQRSuIIJXGu1pQTteD0OY2Va57eqk13npVwntCTkZI4QkkcoSTOeE3ZpevbwYl9Kl39I6f/9q1vTE8ZKYkjlMQRSuKM79H55WED5wdWvgWcWGuumN57tGNi/vWUkZI4QkkcoSTO1XN0TufhpvvvVM7R6VI54+epa294wrnhRkriCCVxhJI4V3uen9aRE2dwT/QDqvztzj1P+3p2eWu+1khJHKEkjlAS5+rZjBP9gLrqyMr73DxH8ea5ODfPznkyUhJHKIkjlMS52p/yqTI/t7qma6/J6Xnc099lTtSyyWviRkriCCVxhJI4V/d93+yhndDD6NREv8yuGtF53/zRhJI4Qkmc19a+K/tpJuqknWdN905fXf+J9XeFkZI4QkkcoSTO1X3f09d39Xc8vf/pHprKvqKdujnt+9FTRkriCCVxhJI44zVlpb7pqo0q85Snv69qu9P6cmXiDMaucyy7GCmJI5TEEUrivLZHZ8fNPd0rE99fVnpMVt5hh/6U8D+EkjhCSZzx/pQ3v0HsquFW9zz924qJsxAn9rNPMFISRyiJI5TEuTpPeVqXTJw3uLMGvXqHibX4ie9Nu840v7ne/WSkJI5QEkcoifNbnfed9m3izrN2dP0bb/YqqjBSEkcoiSOUxPmY/pTTtVHlPqe6vqes3H/nb619ww+hJI5QEufqvu/pGu50/fqtvugTe6t3asG3egOdMlISRyiJI5TEGZ+nrPQnv9nn/NTEGnHXXOD0nvRpRkriCCVxhJI4Eb2EptdYJ2qsHW+dD/5U2dPzVq1ppCSOUBJHKIkTse/7tJf4qa6e513r3af/rrR5xGlGSuIIJXGEkjhX93136dpHUpk7TK7zuno22aMDP4SSOEJJnPGe510mzuPuOpunq/9lxfT3nTcZKYkjlMQRSuJEn/fdtf+maz6yq2ZdXVOpjyf21N/sA/9kpCSOUBJHKInz2veUKxN9JW+er316/cQ6+2qutMLaN380oSSOUBInYt93xelc4NPpvObE+vXEOvvO9Tf7hp4yUhJHKIkjlMT5+JpypXJOz+o+XXu0K/vfd541sS9ndc0EIyVxhJI4QkmcqzXldC3y1veX0/dcmV5b9z0l/BBK4gglca6e911R+aZw555Ple81J3qSr5576uazKoyUxBFK4gglcT6yPyW/NyMlcYSSOEJJHKEkjlASRyiJI5TEEUriCCVxhJI4QkkcoSSOUBJHKIkjlMQRSuIIJXGEkjhCSRyhJI5QEkcoiSOUxBFK4gglcYSSOEJJnH8BIivA8eK+vGcAAAAASUVORK5CYII=",$this->hotp->generateQr()->getDataUri());
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
        $this->assertEquals("gown glue immune autumn occur era cave antenna spray cake crack nothing ball drive mother next fade certain average desert flip owner infant buddy",implode(" ",$this->hotp->generateBackupCodes("mysecret")));
    }

    public function testReverseMnemonic(): void 
    {
        $this->assertEquals(hash("md5","mysecret"),$this->hotp->reverseMnemonic("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty"));
    }
}
