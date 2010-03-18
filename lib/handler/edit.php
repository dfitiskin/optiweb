<?php

loadlib('Handler.Handler');

class CHandler_Edit extends CHandler_Handler
{
    public function run() 
    {
	    $data        =  isset($_POST['data'])             ?  $_POST['data']             : null;
	    $objectName  =  isset($_POST['object'])           ?  $_POST['object']           : null;
	    $files       =  isset($_FILES)                    ?  $_FILES                    : null;
	    $deleteFiles =  isset($_POST['deletefile'])      ?  $_POST['deletefile']        : null;
	    $checks      =  isset($this->checks[$objectName]) ?  $this->checks[$objectName] : null;
        if ($deleteFiles)
        {
	        $this->deleteFiles($deleteFiles, $checks, $objectName);
        }
	    $this->handlerEdit($objectName, $data, $files, $checks);
    }
    
    private function deleteFiles($deleteFiles, $checks, $objectName)
    {
        $object = $this->storage->findOne($objectName, array('id' => $this->node->get('id')));
        foreach ($checks as $param => $checks)
	    {
	        if (isset($deleteFiles[$param]) && $object->get($param))
	        {
	            $file = sprintf(
	                '%s..%s',
	                GEN_DATA_PATH,
	                $object->get($param)
                );   
                unlink($file);
                $object->set($param, null);
                $object->save();
	        }
	    }
    }
    
    private function handlerEdit($objectName, $data, $files, $checks) 
    {
        $check = true;
        $form = $this->Kernel->link('object.object');
        $form->setup($data);
        if ($files)
        {
            $checkFile = $this->checksFiles($files, $checks, $form);
        }
        $object = $this->editData(
            $checks,
            $form,
            $objectName 
        );
        if ($object)
        {
            $check = true;
            if ($files && $checkFile)
            {
                $check = $this->addFiles($files, $checks, $objectName, $object);
            }
            
	        if ($check)
	        {
	            // жуть какая то, но работает, надо переделать)
	            $url = '';
	            $nodes = array();
	            foreach ($this->matchMode as $node => $data)
	            {
	                $nodes[] = $node;    
	            }
	            $parentNode = isset($nodes[count($nodes)-2]) ? $nodes[count($nodes)-2] : key($this->passage);
	            list($parentobjectName, $parentPart, $parentFilters) = $this->linkPassage[$parentNode]['data'];
	            $filters = array();
	            foreach ($parentFilters as $filter => $i)
	            {
	                $filters[] = $filter;
	            }
	            preg_match_all('/\([^\)]+\)/', $parentPart, $match);
	            $purl = '';
	            foreach($match[0] as $i => $patern)
	            {
	                $paternUrl = $parentobjectName .'_'.$filters[$i];
	                if($object->get($paternUrl))
	                {
	                    $purl = str_ireplace($patern, $object->get($paternUrl), $parentPart) .'/';
	                }     
	            }
	            
	            if ($purl)
	            {
		            $url .= count($this->matchMode) > 1 ? '../' : ''; 
	                $url .= $purl;
	            }
	            $node = $nodes[count($nodes)-1];
	            list($aObjectName, $aPart, $aFilters) = $this->linkPassage[$node]['data'];
	            $filters = array();
	            foreach ($aFilters as $filter => $i)
	            {
	                $filters[] = $filter;
	            }
	            preg_match_all('/\([^\)]+\)/', $aPart, $match);
	            $aurl = '';
	            foreach($match[0] as $i => $patern)
	            {
	                $aUrl = $filters[$i];
	                if($object->get($aUrl))
	                {
	                    $aurl = str_ireplace($patern, $object->get($aUrl), $aPart) .'/';
	                }     
	            }
                $GLOBALS['Page']->setRedirect($this->Kernel->Url . '../' . $url . $aurl);
	        }
        }
    }
    
    private function addFiles($files, $checks, $objectName, $object)
    {
	    $result = true;
        foreach ($checks as $param => $checks)
	    {
	        if (isset($files[$param]))
	        {
	            $extExp = explode(".", $files[$param]['name']);
	            $ext = strtolower(array_pop($extExp));
	            
	            $path = sprintf(
	                '%s%s/%s/%d/',
	                GEN_DATA_PATH,
	                $this->Name,
	                $objectName,
	                $object->get('id')
                );

                $fileManager = $this->Kernel->link('Services.Filemanager');
                $fileManager->createFolder($path);
                
	            $fileName = $path.sprintf(
	                '%d.%s',
	                $object->get('id'),
	                $ext
                );

                $paramFileName = sprintf(
	                '/%s/%s/%s/%d/%4$d.%s',
                    'data',
                    $this->Name,
                    $objectName,
	                $object->get('id'),
	                $ext
                );
                
	            if (move_uploaded_file($files[$param]['tmp_name'], $fileName))
	            {
	                if (method_exists($object, 'setImageFile'))
			        {
		    		    $object->setImageFile($fileName);
				    }
				    else
				    {
    	                $object->set($param, $paramFileName);
				    }
	            }
	            else
	            {
	                $result = false;
	                break;    
	            }
	        }
	    }
        $object->save();
	    return $result;
    }
    
    function checksFiles($files, $checks, $form)
    {
	    $this->parent->set('form', $form);
        $result = true;
        foreach ($checks as $param => $checks)
	    {
	        if(isset($files[$param]))
	        {
		        if(is_uploaded_file($files[$param]['tmp_name']))
		        {
		            if (false !== array_search('image', $checks))
			        {
			            if ($files[$param]['type'] != 'image/jpeg' && 
			                $files[$param]['type'] != 'image/pjpeg' &&
			                $files[$param]['type'] != 'image/gif'  && 
			                $files[$param]['type'] != 'image/png')
		                {
		                    $this->parent->set('form.'. $param .'_errors', 1);
		                    $this->parent->set('form.'. $param .'_image_error', 1);
		        		    $result = false;    
		                }
			        }
			        if (false !== array_search('file', $checks))
			        {
			            if (false !== stristr($files[$param]['name'], '.php'))
		                {
		                    $this->parent->set('form.'. $param .'_errors', 1);
		                    $this->parent->set('form.'. $param .'_file_error', 1);
		        		    $result = false;    
		                }
			        }
		        }
		        else
		        {
		            $result = false;
		        }
	        }
	    } 
	    return $result;   
    }
    
    private function editData($checks, $form, $objectName)
	{
	    $object = null;
	    $check = $this->checks($checks, $form, $objectName);
	    if ($check)
	    {
	        $object = $this->storage->findOne($objectName, array('id' => $this->node->get('id')));
		    foreach ($checks as $param => $check)
		    {
		        if(false === array_search('image', $check) && false === array_search('file', $check))
                {
                    $object->set($param, $form->get($param));
                }
		    }
		    $object->save();
	    }
	    return $object;
	}

    function checks($checks, $form, $objectName)
    {
	    $this->parent->set('form', $form);
        $result = true;
        foreach ($checks as $param => $checks)
	    {
	        if (false !== array_search('empty', $checks))
	        {
	            if (! $form->get($param))
                {
                    $this->parent->set('form.'. $param .'_errors', 1);
                    $this->parent->set('form.'. $param .'_empty_error', 1);
        		    $result = false;    
                }
	        }
	        if (false !== array_search('unique', $checks))
	        {
	            $object = $this->storage->findOne(
                    $objectName, 
                    array(
                        $param => $form->get($param),
                        $this->Name.'_id' => $this->parent->get($this->Name.'.id')
                    )
                );
                $id = $this->node->get('id');
                if ($object && $id)
                {
	                $currentObject = $this->storage->findOne(
	                    $objectName, 
	                    array('id' => $id)
	                );
	                if ($object->get($param) == $currentObject->get($param))
	                {
	                    $object = null;        
	                }
                }
                if ($object)
                {
                    $this->parent->set('form.'. $param .'_errors', 1);
                    $this->parent->set('form.'. $param .'_unique_error', 1);
        		    $result = false;    
                }
	        }
	        if (false !== array_search('numeric', $checks))
	        {
	            if (null == $form->get($param) || ! is_numeric($form->get($param)) || ! $form->get($param))
                {
                    $form->set($param, 0);
                }
	        }
	    } 
	    return $result;   
    }
}