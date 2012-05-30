My common classes
=================

Changes
-------

### v0.13 ###
Added the option to set an Enum value by its index.

### v0.12 ###
Changed Percentage class

### v0.11 ###
Significant changes on Enum.
Using abstract method <i>default</i> insted of attribute <i>_default</i>

Autoloader
-----------

The autoloader to use with classes, feel free to implement yours

    <?php
    
    namespace Shina;
    
    class Autoloader
    {
    
        static public function register()
        {
            spl_autoload_register(array('\Shina\Autoloader', 'callback'));
        }
    
        static public function callback($class)
        {
            if (substr($class, 0, 5) == 'Shina')
            {
                $arr_path = explode('\\', $class);
                $filename = array_pop($arr_path);
                array_shift($arr_path);
                $path = dirname(__FILE__) . '/' . strtolower(join('/', $arr_path)) . '/' . $filename . '.php';
                include($path);
            }
        }
    
    }