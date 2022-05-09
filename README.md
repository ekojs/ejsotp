OTP Library
=============

TOTP dan HOTP Library with backup codes, compatible with google authenticator. Compatible with PHP >= 7.3.

[![Latest Stable Version](http://poser.pugx.org/ekojs/otp/v)](https://packagist.org/packages/ekojs/otp) [![Total Downloads](http://poser.pugx.org/ekojs/otp/downloads)](https://packagist.org/packages/ekojs/otp) [![Latest Unstable Version](http://poser.pugx.org/ekojs/otp/v/unstable)](https://packagist.org/packages/ekojs/otp) [![License](http://poser.pugx.org/ekojs/otp/license)](https://packagist.org/packages/ekojs/otp) [![PHP Version Require](http://poser.pugx.org/ekojs/otp/require/php)](https://packagist.org/packages/ekojs/otp)

## Install

For PHP version **`>= 7.3`**:

```
composer require ekojs/otp
```

## How to use
### Generate TOTP (Time-Based One-Time Password) based on [RFC 6238](https://datatracker.ietf.org/doc/html/rfc6238)
```php
<?php
require_once "vendor/autoload.php";

use Ekojs\Otp\TOTP;
use Ekojs\Otp\HOTP;

$ejsotp = TOTP::getInstance();
$ejsotp->otp->setLabel("ekojs@email.com");
$ejsotp->otp->setIssuer("My Service");

echo "The TOTP secret is: {$ejsotp->otp->getSecret()}".PHP_EOL;
echo "The current TOTP is: {$ejsotp->otp->now()}".PHP_EOL;
echo "The Uri is: {$ejsotp->otp->getProvisioningUri()}".PHP_EOL;
```

### Verify TOTP
```php
$ejsotp = TOTP::getInstance(["secret" => "VE7RDW7LC45QHKVZI6SPHDQK254TKO7CPG6KHPQ4RYN4MGBBA6EAAHVYHRVAGO5LPF6XNDPAOLE3KYQHBBHPB62VFVNZURWRZUDER4A"]);
$ejsotp->otp->setLabel('ekojs@email.com');
$ejsotp->otp->setIssuer("My Service");

echo 'Current TOTP: ' . $ejsotp->otp->now() . PHP_EOL;
var_dump($ejsotp->otp->verify("988942"));
```

### Generate HOTP (HMAC-Based One-Time Password) based on [RFC 4226](http://tools.ietf.org/html/rfc4226)
```php
$ejshotp = HOTP::getInstance(["counter" => 1000]);
$ejshotp->otp->setLabel("ekojs@email.com");
$ejshotp->otp->setIssuer("My Service HOTP");

echo "The HOTP secret is: {$ejshotp->otp->getSecret()}".PHP_EOL;
echo "The current HOTP is: {$ejshotp->otp->at($ejshotp->otp->getCounter())}".PHP_EOL;
echo "The Uri is: {$ejshotp->otp->getProvisioningUri()}".PHP_EOL;
```

### Verify HOTP
```php
$ejshotp = HOTP::getInstance([
    "secret" => "HZHL2VE2RWMT2KHDQCYCLPXJRJC7T63SZFNDTLEEEJISHLQS5Y6CRDTW4D7D3GA35VMSA32NAGLXEEFDSRT63E332JQOCTDAVK4HZHI",
    "counter" => 1000
]);
$ejshotp->otp->setLabel("ekojs@email.com");
$ejshotp->otp->setIssuer("My Service HOTP");

echo 'Current OTP: ' . $ejshotp->otp->at(1001) . PHP_EOL;
var_dump($ejshotp->otp->verify("598162",1001));
```

### Generate Backup Codes (Mnemonic) based on [BIP 39](https://github.com/bitcoin/bips/blob/master/bip-0039.mediawiki)
```php
echo "Hash secret : ". hash("md5","mysecret").PHP_EOL; // 06c219e5bc8378f3a8a3f83b4b7e4649
echo "Backup codes: ". implode(" ",$ejsotp->generateBackupCodes("mysecret")).PHP_EOL;
```

### Reverse Mnemonic / Backup Codes
```php
echo "Reverse Mnemonic: ".$ejsotp->reverseMnemonic("almost awkward just jungle daring keep penalty lecture deputy fossil muscle nasty").PHP_EOL; // 06c219e5bc8378f3a8a3f83b4b7e4649
```

### Generate QrCode compatible with [Google Authenticator](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2)
```php
$result = $ejsotp->generateQr();
echo "<img src='".$result->getDataUri()."' />";
```

### Verify Using Google Authenticator
Scan this Qr using Google Authenticator

![Imgur](https://i.imgur.com/FexUnMJ.png)

Create test.php, and execute it from terminal
```php
<?php
require_once "vendor/autoload.php";

use Ekojs\Otp\TOTP;

$ejsotp = TOTP::getInstance(["secret" => "VZCKGWRLS7CINEYALENYPH5T442LJUAFGSNCBTBQEHMN5GSVGTJCD2B7NHCZFK5FZ3QHTQ66JYDMNUI2UBWZJAYHI62VYVHVUGTO6SQ"]);
$ejsotp->otp->setLabel('ekojs@email.com');
$ejsotp->otp->setIssuer("My Service");

var_dump($ejsotp->otp->verify("input code from your Google Authenticator")); // if true the code is valid
echo 'Current OTP: ' . $ejsotp->otp->now() . PHP_EOL;
```