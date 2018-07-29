<?php

namespace theyak;

class Tau
{

    public static $EOL = PHP_SAPI === 'cli' ? "\n" : "<br>\n";


    /**
     * Determine if script is running via ajax.
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }


    /**
     * Determines of script is running in CLI mode.
     *
     * @return bool
     */
    public static function isCli(): bool
    {
        return php_sapi_name() == 'cli';
    }


    /**
     * A quick and dirty method to display the contents of a variable.
     * Includes file and line numbers. If you'd like something more complete,
     * and don't mind adding 200k+ to your code, you can use far better libraries such as
     * [VarDumper](https://symfony.com/doc/current/components/var_dumper.html),
     * [Kint](https://kint-php.github.io/kint/), or [Tracy](https://tracy.nette.org/).
     *
     * @param mixed $data Data to display
     * @param int $line (optional) line number called from
     * @param string $file (optional) file called from
     */
    public static function dump($data, $line = 0, string $file = ''): void
    {
        if (!$file && function_exists('debug_backtrace')) {
            $dbg = debug_backtrace();
            $file = $dbg[0]['file'];
            if (!$line) {
                $line = $dbg[0]['line'];
            }
        }

        $title = [];
        if ($file) {
            $title[] = 'File: ' . $file;
        }
        if ($line) {
            $title[] = 'Line: ' . $line;
        }

        if (static::isCli() || static::isAjax()) {
            echo implode(' - ', $title) . static::$EOL;
        } else {
            echo '<pre style="text-algin:left;background-color: #fffff;color: #000000;';
            echo 'border: 1px; border-style: outset; padding: 5px;">';
            echo '<strong>' . implode(' - ', $title) . '</strong><br>';
        }

        if (is_bool($data)) {
            echo $data ? 'true' : 'false';
        } elseif (is_string($data)) {
            echo $data . ' (Length: ' . strlen($data) . ')';
        } elseif (!$data) {
            if (is_numeric($data)) {
                echo '0 (number)';
            } elseif ($data === null) {
                echo 'null';
            } else {
                echo 'empty';
            }
        } elseif (is_array($data) || is_object($data)) {
            if (is_resource($data)) {
                echo 'Resource';
            } elseif (static::isCli() || static::isAjax()) {
                print_r($data);
            } else {
                echo htmlspecialchars(print_r($data, true));
            }
        } else {
            if (static::isCli() || static::isAjax()) {
                echo $data;
            } else {
                echo str_replace("\t", '&nbsp; &nbsp; ', htmlspecialchars($data));
            }
        }

        echo (static::isCli() || static::isAjax()) ? PHP_EOL : '</pre>';
    }
}
