<?php
/**
 * phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */

use PHPUnit\Framework\TestCase;
use Theyak\Tau\Clock;

final class ClockTest extends TestCase
{


    public function testShouldCreateFromIsoString()
    {
        $t = new Clock('2018-08-31T23:39:11-04:00');

        $tz = $t->getTimeZone();
        $this->assertEquals($t->format('Y-m-d H:i:s'), '2018-08-31 23:39:11');
        $this->assertInstanceOf(\DateTimeZone::class, $tz);
        $this->assertEquals($tz->getName(), '-04:00');
    }


    public function testShouldCreateFromSqlString()
    {
        $t = new Clock('2018-08-31 23:39:11');

        $tz = $t->getTimeZone();
        $this->assertEquals($t->format('Y-m-d H:i:s'), '2018-08-31 23:39:11');
        $this->assertInstanceOf(\DateTimeZone::class, $tz);
        $this->assertEquals($tz->getName(), date_default_timezone_get());
    }


    public function testShouldCreateFromHuman()
    {
        $t = new Clock('August 31, 2018 11:39:11 pm');

        $tz = $t->getTimeZone();
        $this->assertEquals($t->format('Y-m-d H:i:s'), '2018-08-31 23:39:11');
        $this->assertInstanceOf(\DateTimeZone::class, $tz);
        $this->assertEquals($tz->getName(), date_default_timezone_get());
    }


    public function testShouldCreateFromModifiers()
    {
        $modifiers = [
            'yesterday', 'today', 'tomorrow', 'now', '+1 day', '-1 day',
            '+1 month', '-1 month', '+1 year', '-1 year', '+6 months', null,
        ];


        foreach ($modifiers as $modifier) {
            $dt = new \DateTime($modifier);
            $clock = new Clock($modifier);
            $this->assertEquals($dt->format('c'), $clock->format('c'));
        }
    }


    public function testShouldCopy()
    {
        $clock = new Clock();
        $copy = $clock->copy();

        $clock->modify('+1 day');
        $this->assertNotEquals($clock->format('c'), $copy->format('c'));
    }


    public function testShouldSetTimezone()
    {
        $clock = new clock('2017-01-01t12:00:00+00:00');
        $clock->settimezone('us/pacific');

        $this->assertequals('2017-01-01T04:00:00-08:00', $clock->format('c'));
    }


    public function testShouldSetTimezoneInConstructor()
    {
        $clock = new clock('2017-01-01T12:00:00+00:00', 'US/Pacific');
        $this->assertequals('2017-01-01T04:00:00-08:00', $clock->format());
    }


    public function testTimestamp()
    {
        $dt = new DateTime('2017-01-01');
        $timestamp = $dt->getTimestamp();
        $clock = new Clock($timestamp);
        $this->assertEquals('2017-01-01T00:00:00+00:00', $clock->format());
    }


    public function testShouldSetDateTimezone()
    {
        $clock = new clock('2017-01-01t12:00:00+00:00');
        $clock->settimezone(new \DateTimezone('US/pacific'));

        $this->assertequals($clock->format(), '2017-01-01T04:00:00-08:00');
    }


    public function testShouldGetProperties()
    {
        $clock = new Clock('2017-01-02T12:20:30+00:00');
        $this->assertEquals($clock->year, 2017);
        $this->assertEquals($clock->month, 1);
        $this->assertEquals($clock->day, 2);
        $this->assertEquals($clock->hour, 12);
        $this->assertEquals($clock->minute, 20);
        $this->assertEquals($clock->second, 30);
        $this->assertEquals($clock->hours, 12);
        $this->assertEquals($clock->minutes, 20);
        $this->assertEquals($clock->seconds, 30);
        $this->assertNull($clock->other);
    }


    public function testShouldChain()
    {
        $clock = new Clock('2017-01-01T12:00:00+00:00');
        $update = $clock->copy()->setTimezone('US/Pacific')->format();

        $this->assertEquals($clock->format(), '2017-01-01T12:00:00+00:00');
        $this->assertEquals($update, '2017-01-01T04:00:00-08:00');
    }


    public function testShouldConvertToIsoString()
    {
        $clock = new Clock('2017-01-01T12:00:00+00:00');
        $this->assertEquals('2017-01-01T12:00:00+00:00', (string)$clock);
    }


    public function testShouldFormatStatically()
    {
        $clock = new Clock('2017-01-01T12:00:00+00:00');
        $format = Clock::toString($clock, 'Y-m-d H:i:s');
        $this->assertEquals($format, '2017-01-01 12:00:00');
    }
}
