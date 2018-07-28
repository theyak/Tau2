<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Theyak\Tau\Crypt;

final class CryptTest extends TestCase
{
    public function testSameKeyShouldDecrypt(): void
    {
        $encrypt = Crypt::encrypt("key", "hello");
        $decrypt = Crypt::decrypt("key", $encrypt);
        $this->assertEquals($decrypt, "hello");
    }

    public function testDifferentKeysShouldFail(): void
    {
        $encrypt = Crypt::encrypt("key", "hello");
        $decrypt = Crypt::decrypt("key1", $encrypt);
        $this->assertEquals($decrypt, false);
    }

}
