<?php

class CImage_Terminal
{
    private $mode = null;
    
    public function checkPassword($pass)
    {
        $this->mode = $pass;
        return true;
    }
    
    public function execute($params)
    {
        $toFile = sprintf(
            '%s%s/cache/%s.jpg',
            GEN_DATA_PATH,
            $this->Name,
            md5($this->mode . implode('/', $params))
        );
        
        if (!is_file($toFile))
        {
            $width = array_shift($params);
            $height = array_shift($params);
            $file = sprintf(
                '%s%s',
                ROOT_DIR,
                implode('/', $params)
            );
            
            $images = $this->Kernel->link('services.imagelib');
            
            switch ($this->mode)
            {
                case 'cut':
                    $images->edgeResizeImage(
                        $file, 
                        $width, 
                        $height, 
                        $toFile
                    );
                break;
                default:
                    $images->resizeImage(
                        $file, 
                        $width, 
                        $height, 
                        $toFile
                    );
                break;
            }
        }
        
        
        echo $this->Kernel->readFile($toFile);
    }
}