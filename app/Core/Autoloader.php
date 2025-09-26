<?php

/**
 * PSR-4 Autoloader
 * Tự động load classes theo PSR-4 standard
 */
class Autoloader
{
    protected $prefixes = [];
    
    /**
     * Register autoloader
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }
    
    /**
     * Add namespace prefix
     */
    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
        // Normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';
        
        // Normalize base directory with trailing separator
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';
        
        // Initialize the namespace prefix array
        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }
        
        // Retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }
    
    /**
     * Load class file for a given class name
     */
    public function loadClass($class)
    {
        // The current namespace prefix
        $prefix = $class;
        
        // Work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {
            
            // Retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);
            
            // The rest is the relative class name
            $relativeClass = substr($class, $pos + 1);
            
            // Try to load a mapped file for the prefix and relative class
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                return $mappedFile;
            }
            
            // Remove the trailing namespace separator for the next iteration
            $prefix = rtrim($prefix, '\\');
        }
        
        // Never found a mapped file
        return false;
    }
    
    /**
     * Load mapped file for namespace prefix and relative class
     */
    protected function loadMappedFile($prefix, $relativeClass)
    {
        // Are there any base directories for this namespace prefix?
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }
        
        // Look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $baseDir) {
            
            // Replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $baseDir
                  . str_replace('\\', '/', $relativeClass)
                  . '.php';
            
            // If the mapped file exists, require it
            if ($this->requireFile($file)) {
                return $file;
            }
        }
        
        // Never found it
        return false;
    }
    
    /**
     * If a file exists, require it from the file system
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}

// Initialize autoloader
$loader = new Autoloader();
$loader->register();

// Register namespaces
$loader->addNamespace('App', BASE_PATH . '/app');
$loader->addNamespace('Database', BASE_PATH . '/database');
$loader->addNamespace('Auth', BASE_PATH . '/activities/Auth');
$loader->addNamespace('Admin', BASE_PATH . '/activities/Admin');