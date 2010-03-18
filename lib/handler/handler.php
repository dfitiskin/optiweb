<?php

class CHandler_Handler
{
    protected $parent = null; 
    
    public function setParent($parent)
    {
        $this->parent = $parent;
        foreach ($parent as $propertyName => $propertyValue) {
            $this->$propertyName = $propertyValue;
        }  
    }
    
    public function run() 
    {
         
    }  
}