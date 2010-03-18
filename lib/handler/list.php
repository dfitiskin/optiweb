<?php

loadlib('Handler.Handler');

class CHandler_List extends CHandler_Handler
{
    public function run() 
    {
	    $ids         =  isset($_POST['ids'])    ? $_POST['ids']     : null;
	    $data        =  isset($_POST['data'])   ? $_POST['data']    : null;
	    $formAction  =  isset($_POST['action']) ? $_POST['action']  : null;
	    $objectName  =  isset($_POST['object']) ? $_POST['object']  : null;

	    $this->handlerList($objectName, $formAction, $ids, $data);
    }   
    
    private function handlerList($objectName, $formAction, $ids, $data)
	{
	    if (is_array($formAction))
        {
            $overThan = key($formAction);
            
            if(is_array($formAction[$overThan]))
            {
                $action = key($formAction[$overThan]);    

                switch ($overThan) 
		        {
			    	case 'ids':
			    	    if (is_array($ids))
			    	    {
			    	        $ids = array_keys($ids);
			    	    }    
			    	break;
		            case 'all':
			            $ids = array();     
			    	break;
			    	default:
			    	    if (is_numeric($overThan))
			    	    {
			    	        $ids = array($overThan);        
			    	    }
			    	break;
			    }
			    
                switch ($action) 
		        {
			    	case 'save':
			            $this->actionDataSave($objectName, $ids, $data);
			    	break;
		            case 'delete':
                        $this->actionDataDelete($objectName, $ids); 
			    	break;
		            case 'edit':
		                $this->actionDataEdit($objectName, $ids, $formAction[$overThan][$action]);
			    	break;
			    }
	    	    
			    $GLOBALS['Page']->setRedirect($this->Kernel->Url);
            }
        }
	}
	
	private function actionDataSave($objectName, $ids, $data)
	{
        if ($data && is_array($data))
 	    {
 	        foreach ($data as $id => $fields)
            {
                if (null !== $ids && (0 == count($ids) || false !== array_search($id, $ids)))
                {
                    if (is_array($fields))
                    {
                       $object = $this->storage->findOne($objectName, array('id' => $id));
                       foreach ($fields as $field => $value)
                       { 
                           $object->set($field, $value);
                       }
                       $object->save();
                    }
                }
            }
 	    }
	}
	
	private function actionDataDelete($objectName, $ids)
	{
   	    if(null !== $ids && 0 != count($ids))
   	    {
    	    foreach ($ids as $i => $id)
            {
                $object = $this->storage->findOne($objectName, array('id' => $id));
                $object->remove($object);        
            }
   	    }
	}
	
	private function actionDataEdit($objectName, $ids, $fields)
	{
	    if ($ids && is_array($fields))
	    {
	        $field = key($fields);
	        if (is_array($fields[$field]))
	        {
		        $value = key($fields[$field]);
		        $fields = array($field => $value);
		        $data = array(); 
		        foreach ($ids as $i => $id)
		        {
	                $data[$id] = $fields;
		        }
		        $this->actionDataSave($objectName, $ids, $data);
	        }
	    }
	}
}