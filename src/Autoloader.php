<?php

namespace App;

class Autoloader
{
    protected array $namespaceMap = [];

    /**
     * Register the autoloader.
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Add a namespace and its corresponding directory to the map.
     *
     * @param string $namespace The namespace prefix.
     * @param string $directory The base directory for this namespace.
     */
    public function addNamespace(string $namespace, string $directory)
    {
        $this->namespaceMap[$namespace] = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Load a class file based on its namespace and class name.
     *
     * @param string $class The fully-qualified class name.
     */
    public function loadClass(string $class)
    {
        foreach ($this->namespaceMap as $namespace => $directory) {
            // Check if the class belongs to this namespace
            if (str_starts_with($class, $namespace)) {
                // Remove the namespace prefix and convert to file path
                $relativeClass = str_replace($namespace, '', $class);
                $relativeClass = ltrim($relativeClass, '\\');
                $file = $directory . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

                // Include the file if it exists
                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        }
    }
}
