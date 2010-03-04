<?php

loadlib('State.Add');

class CState_ParentAdd extends CState_Add
{
    public function dataset($ds, $storage)
    {
        parent::dataset($ds, $storage);
   	    $parent = $this->Kernel->Link('State.Parent');
        $parent->dataset(
            $ds, 
            $storage, 
            $this->form,
            $this->objectName, 
            $this->parentObjectName,
            $this->parentObjectFilters,
            $this->parentObjectNameList
        );
    }
}