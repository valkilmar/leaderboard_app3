<?php

namespace AppVal;

class Template {
    
    private $path;
    private $params;
    
    public function __construct($path, $params = [])
    {
        $path = strtolower($path);
        if (stripos($path, '.php') === false) {
            $path .= '.php';
        }
        
        if (stripos($path, 'views' . DIRECTORY_SEPARATOR) === false) {
            $path = 'views' . DIRECTORY_SEPARATOR . $path;
        }
        
        $this->path = $path;
        $this->params = $params;
    }
    
    
    public function render($returnResult = false, $params = null)
    {
        if ($params !== null) {
            extract($params);
        } else {
            extract($this->params);
        }
        
        ob_start();
        
        require $this->path;
        
        $result = ob_get_clean();
        
        if ($returnResult) {
            return $result;
        }
        
        echo $result;
    }
}