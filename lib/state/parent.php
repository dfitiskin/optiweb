<?php

class CState_Parent
{
    public function dataset($ds, 
                            $storage, 
                            $form, 
                            $objectName, 
                            $parentObjectName, 
                            $parentObjectFilters, 
                            $parentObjectNameList
    )
    {
        $filters = array();
        foreach($parentObjectFilters as $filter => $value)
        { 
            $filters[$filter] = 0;
        } 
        $parentObject = $storage->find(
   	        $parentObjectName,
   	        $filters,
   	        'id'
        );
        
        foreach($parentObjectFilters as $filter => $value)
        {
            if (! is_array($value))
            {
                if ($form && $form->get($filter))
                {
                    $parentObject->set($filter, $form->get($filter));    
                }
                else
                {
                    $parentObject->set($filter, $value);
                }
            }
        }
        $parentObject->set($objectName, $ds->get('object'));
   	    $ds->set($parentObjectNameList, $parentObject);
    }
}