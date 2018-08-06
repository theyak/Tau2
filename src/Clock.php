<?php
/**
 * Clock module for what may become Tau2 or something else entirely.
 * This is brand new in Tau. A more complete library can be found
 * at https://carbon.nesbot.com/.
 *
 * Disclaimer: No code here is taken from Carbon or any other library.
 * Any similarities are purely coincedence.
 *
 * @Author          theyak
 * @Copyright       2018
 * @Project Page    https://github.com/theyak/tau2
 * @docs            None!
 *
 * 2018-08-04 Created
 *
 * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
 */

namespace Theyak\Tau;

class Clock extends \DateTime
{


    /**
     * Constructor
     *
     * @param  string|int|\DateTime $time
     * @param  string|\DateTimeZone $timezone
     */
    public function __construct($time = 'now', $timezone = null)
    {
        if (is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        if (!$time) {
            $time = 'now';
        } elseif (is_int($time)) {
            $time = date('Y-m-d H:i:s', $time);
        } elseif ($time instanceof \DateTime) {
            $timezone = $time->getTimezone();
            $time = $time->format('Y-m-d H:i:s.u');
        }

        parent::__construct($time);

        // Although the parent constructor is supposed to take
        // a timezone, we found it didn't always work so we
        // set it here. Very strange.
        if ($timezone) {
            $this->setTimezone($timezone);
        }
    }


    /**
     * Sets time zone.
     *
     * @param string|\DateTimeZone $timezone
     */
    public function setTimezone($timezone)
    {
        if (is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }
        parent::setTimezone($timezone);

        return $this;
    }


    /**
     * Create a copy. Useful when chaining actions such as $t->copy()->modify("+1 day")->format();
     */
    public function copy()
    {
        return clone $this;
    }


    /**
     * Clone of \DateTime's format method with a default parameter of ISO 8601 time
     *
     * @param  string $format See http://php.net/manual/en/function.date.php
     * @return string
     */
    public function format($format = 'c')
    {
        return parent::format($format);
    }


    /**
     * Convert instance to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }


    /**
     * Get various properties of time
     *
     * @param  string $property
     */
    public function __get($property)
    {
        switch (strtolower($property)) {
            case 'day':
                return (int) $this->format('d');
            case 'month':
                return (int) $this->format('m');
            case 'year':
                return (int) $this->format('Y');
            case 'hour':
            case 'hours':
                return (int) $this->format('H');
            case 'minute':
            case 'minutes':
                return (int) $this->format('i');
            case 'second':
            case 'seconds':
                return (int) $this->format('s');
        }
        return null;
    }


    /**
     * Static function to turn any valid time into a formatted string
     *
     * @param  mixed $time
     * @param  string $format See http://php.net/manual/en/function.date.php
     * @return string
     */
    public static function toString($time, $format = 'c')
    {
        $dt = new Clock($time);
        return $dt->format($format);
    }
}
