<?php

loadlib('object.object');

class CObject_StorageObject extends CObject_Object
{
    protected $storage = null;
    private $type = 'object';

    function setType($value)
    {
        $this->type = $value;
    }

    function getType()
    {
        return $this->type;
    }

    function setStorage(& $storage)
    {
        $this->storage = & $storage;
    }

    function remove()
    {
        $this->storage->remove($this);
    }

    function save()
    {
        $this->storage->save($this);
    }

    function onRemove()
    {

    }

    function onSave()
    {

    }

    function onLoad()
    {

    }
}
