<?php

loadlib('State.State');

class CState_List extends CState_State
{
    public function dataset($ds, $storage)
    {
        $ds->set('controller', $this->this);
        $object = $storage->find($this->objectName, $this->objectFilters, $this->objectOrderBy);
        $object->set('formmode', $this->objectName);
        $object->set('formaction', 'list');
        $ds->set($this->objectNameList, $object);
        
    }
}