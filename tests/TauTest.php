<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Theyak\Tau;

final class TauTest extends TestCase
{
    public function testShouldBeCli(): void
    {
        $this->assertEquals(Tau::isCli(), true);
    }

    public function testEolShouldBeNewLine(): void
    {
        $this->assertEquals(Tau::$EOL, "\n");
    }

    public function testShouldNotBeAjax(): void 
    {
        $this->assertEquals(Tau::isAjax(), false);
    }
}

