<?php
/**
 * View module for what may become Tau2 or something else entirely.
 *
 * @Author          theyak
 * @Copyright       2018
 * @Project Page    https://github.com/theyak/tau2
 * @docs            None!
 *
 * 2018-09-14 Created
 */

namespace Theyak\Tau;

class View
{
    protected $paths = [];
    protected $extensions = ['phtml'];
    protected $data = [];
    protected $debug = false;
    protected $defaultTemplate = null;
    protected $options = [];
    protected $blockStack = [];

    /**
     * Constructor
     *
     * @param  array $options
     *   $options = [
     *     'paths' => (array) Paths to search for template files
     *     'defaultTemplate' => (string|function) A default template to display if not found
     *     'extension' => (string|array) Extension(s) for view template files
     *     'debug' => (boolean) Turn debug mode on or off.
     *   ]
     */
    public function __construct($options = [])
    {
        $this->options = $options;

        if ($folders = $this->getOpt('folders')) {
            $this->setPaths($this->toArray($folders));
        } else if ($paths = $this->getOpt('paths')) {
            $this->setPaths($this->toArray($paths));
        }

        if ($defaultTemplate = $this->getOpt('defaultTemplate')) {
            if (is_callable($defaultTemplate)) {
                $this->defaultTemplate = $options['defaultTemplate'];
            } else {
                $this->defaultTemplate = (string) $defaultTemplate;
            }
        }

        $this->extensions = $this->toArray($this->getOpt('extension', ['phtml']));
        $this->debug = $this->getOpt('debug', false);

        $this->addBlock('minimize', 'static::minimize');
    }

    /**
     * Add a path to search while looking for view file
     *
     * @param  string $path
     * @param  string $id
     */
    public function addPath(string $path, string $id = null): void
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }

        $id = $this->generatePathId($path, $id);
        $this->paths[$id] = $path;
    }

    /**
     * Set paths to search for view files
     *
     * @param  array $paths List of paths to search
     */
    public function setPaths(array $paths = []): void
    {
        $this->paths = [];
        if (is_array($paths)) {
            foreach ($paths AS $id => $key) {
                $this->addPath($key, $id);
            }
        } else if (is_string($paths)) {
            $this->addPath($paths);
        }
    }

    public function addBlock($name, $callable)
    {
        if (is_callable($callable)) {
            $this->blocks[$name] = $callable;
        } else {
            $this->blocks[$name] = function() {
                if ($this->debug) {
                    echo 'Block: ' . $name;
                }
            };
        }
    }

    public function block($name, $data = []) {
        if (isset($this->blocks[$name]) && is_callable($this->blocks[$name])) {
            $this->blockStack[] = [$this->blocks[$name], $data];
            ob_start();
        }
    }

    public function endBlock()
    {
        if (ob_get_level()) {
            $text = ob_get_clean();
            if (count($this->blockStack)) {
                $block = array_pop($this->blockStack);
                echo call_user_func($block[0], $text, $block[1]);
            }
        }
    }

    /**
     * Look for file within paths
     *
     * @param  string|function $file
     */
    protected function finder($file)
    {
        if (!$file) {
            return null;
        }

        if (is_callable($file)) {
            $file = $file();
        }

        if (is_array($this->extensions)) {
            $extensions = $this->extensions;
        } else {
            $extensions = ['phtml'];
        }

        // Check if path id is specified
        if (strpos($file, '::') > 0) {
            list($id, $file) = explode('::', $file, 2);
            if (isset($this->paths[$id])) {
                $paths = [$this->paths[$id]];
            }
        } else {
            $paths = $this->paths;
        }

        // Check paths for file
        foreach ($extensions AS $ext) {
            if (is_array($paths) && count($paths) > 0) {
                foreach ($this->paths AS $path) {
                    $search = $path . $file . '.' . $ext;
                    if (is_file($search)) {
                        return $search;
                    }
                }
            }
        }

        // Check current folder
        foreach ($this->extensions AS $ext) {
            $search = './' . $file . '.' . $ext;
            if (is_file($search)) {
                return $search;
            }
        }

        return null;
    }


    /**
     * Look for file within paths
     *
     * @param  string $file
     */
    public function find(string $file)
    {
        $template = $this->finder($file);
        if (!$template) {
            $template = $this->finder($this->defaultTemplate);
            if (!$template) {
                if ($this->debug) {
                    echo "Template not found: $file";
                }
                return null;
            }
        }

        return $template;
    }

    public function embed(string $file, array $data = []): void
    {
        $this->render($file, $data);
    }

    public function render(string $file, array $data = []): void
    {
        $file = $this->find($file);
        if (!$file) {
            return;
        }

        extract($this->data, EXTR_REFS);
        extract($data, EXTR_REFS);

        include $file;

        while (ob_get_status()) {
            ob_end_flush();
        }
    }

    /**
     * Generate a id for the path so that it can be directly accessed
     * via the find function, instead of traversing all paths.
     *
     * @param  string $path
     * @param  string $id
     */
    protected function generatePathId(string $path, string $id): string
    {
        if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $id)) {
            return $id;
        }

        if (strpos($path, DIRECTORY_SEPARATOR) > 0) {
            $parts = explode(DIRECTORY_SEPARATOR, $path);
            $end = array_pop($parts);
            return array_pop($parts) . '.' . $end;
        }

        if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $path)) {
            return $path;
        }

        return 'id' . md5($path);
    }


    /**
     * Convert a value to an array
     *
     * @param  mixed $value
     */
    protected function toArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_object($value)) {
            return json_decode(json_encode($value), true);
        }

        if (is_scalar($value)) {
            return [$value];
        }

        return [];
    }

    /**
     * Get a value from the options object/array
     */
    private function getOpt(string $key, $dflt = null) {
        if (is_object($this->options)) {
            return property_exists($this->options, $key) ? $this->options->{$key} : $dflt;
        }
        if (is_array($this->options)) {
            return isset($this->options[$key]) ? $this->options[$key] : $dflt;
        }

        return $dflt;
    }


    public static function minimize($text)
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_map('trim', $lines);
        return implode("", $lines);
    }
}
