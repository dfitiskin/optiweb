<?php

loadlib('object.list');

class CObject_StorageResult extends CObject_List 
{
    var $storage = null;
    var $type = null;
    
    function setStorage($st)
    {
        $this->storage = $st;
    }
    
    function setResult($type, $res)
    {
        while ($rec = $this->storage->getNextRec($res))
        {
            $item = $this->storage->getLoaded($type, $rec['id']);
            
            if (!$item)
            {
                $item = $this->storage->create($type);
                $item->setup($rec);
                $this->storage->setLoaded($item);
            }
            $this->add($item);
        }
    }   
}