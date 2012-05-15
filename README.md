# My common classes #

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