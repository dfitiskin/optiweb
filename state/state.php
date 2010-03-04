<?php

class CState_State extends CObject_Object
{
    private $variables = array();
    
    public function getDataset($ds)
    {
        $this->dataset($ds, $this->storage);
    }
    
    public function __set($name, $value)
    {
        return $this->variables[$name] = $value;    
    }

    public function __get($name)
    {
        return $this->variables[$name];
    }
    
}