<?php
/**
 * phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */

use PHPUnit\Framework\TestCase;
use Theyak\Tau\Crypt;

final class CryptTest extends TestCase
{


    public function testSameKeyShouldDecrypt()
    {
        $encrypt = Crypt::encrypt('key', 'hello');
        $decrypt = Crypt::decrypt('key', $encrypt);
        $this->assertEquals($decrypt, 'hello');
    }


    public function testDifferentKeysShouldFail()
    {
        $encrypt = Crypt::encrypt('key', 'hello');
        $decrypt = Crypt::decrypt('key1', $encrypt);
        $this->assertEquals($decrypt, false);
    }


    public function testShoudlGetRandomKey()
    {
        $key = Crypt::getRandomKey();
        $this->assertEquals(32, strlen($key));
    }


    public function testShoudlGetLongRandomKey()
    {
        $key = Crypt::getRandomKey(48);
        $this->assertEquals(48, strlen($key));
    }
}
