<?php

use PHPUnit\Framework\TestCase;
use Theyak\Tau;

final class TauTest extends TestCase
{
    public function testShouldBeCli()
    {
        $this->assertEquals(Tau::isCli(), true);
    }

    public function testEolShouldBeNewLine()
    {
        $this->assertEquals(Tau::$EOL, "\n");
    }

    public function testShouldNotBeAjax() 
    {
        $this->assertEquals(Tau::isAjax(), false);
    }
}

