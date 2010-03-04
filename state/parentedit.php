<?php

loadlib('State.Edit');

class CState_ParentEdit extends CState_Edit
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
        
        $parentObject = $ds->get($this->parentObjectNameList);
        foreach($this->objectFilters as $filter => $value)
        {
            if (! is_array($value))
            {
                $parentObject->set('no_'.$filter, $value);
            }
        }
        $ds->set($this->parentObjectNameList, $parentObject);
    }
}