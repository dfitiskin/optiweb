<?php

loadlib('object.object');

class CSystem_Inter extends CObject_Object
{
	public  $Name = null;
	public  $config = null;
    public  $moduleParams = null;	
    public  $storage = null;	
	public  $parts = array();
	public  $matchMode = array();
	public  $links = array();
	public  $nameLinks = array();
	public  $hierarchy = array();
    public  $checks = array();
    public  $nodes = array();
    public  $isNode = array();
    public  $nodeName = array();
    public  $modes = array();
    public  $passage = array();
    public  $currentNode = array();  
    public  $node = null; 
	public  $linkPassage = array();
    private $isUrlCorrect = false;
    private $menuItem = array();
    private $linkCirclePassage = array();
    private $activeMode = null;
    public $nodeHierarchy = array();
    
    
	function init()
	{
	    $this->config = $this->Kernel->link($this->Name . '.Config', true);
	    $this->storage = $this->Kernel->link($this->Name.'.Storage');
	    $this->checks = $this->config->checks;
	    $this->nodes  = $this->Kernel->link('object.object');
	    $this->node   = $this->Kernel->link('object.object');

	    $profile = $this->Kernel->Link('object.object');
	    $profile->set('id', $GLOBALS['BackendInter']->ProfileID);
	    $this->set('profile', $profile);
	    
	    $version = $this->Kernel->Link('object.object');
	    $versionData = $GLOBALS['BackendInter']->ModuleVersion;
	    $version->set('id', $versionData ? $versionData['id'] : 0);
	    $this->set($this->Name, $version);
	    
	    if(! $this->get($this->Name.'.id'))
	    {
	        $this->inter();
	    }
	}

	public function setVersion($versionAlias)
	{
	    $version = $this->storage->findOne($this->Name, array('alias' => $versionAlias, 'prid' => $this->get('profile.id')));
	    $this->set($this->Name, $version);
	    foreach($this->storage->_config->Impl as $objectName => $impl)
	    {
	        if ($objectName != $this->Name)
	        {
	            $this->storage->_config->Filters[$objectName][$this->Name.'_id'] = $this->get($this->Name.'.id');
	        }
	    }
	    $this->inter();
	}

	public function deleteVersions($ids)
    {
        foreach ($ids as $i => $id)
        {
            if ($version = $this->storage->find($this->Name, $id))
            {
                $version->remove($version);
            }
        }
        return true;
    }
    
    public function getName()
	{
		return $this->Name;
	}
	
	public function getUrl()
	{
		return $this->Kernel->Url;
	}
	
    public function getMenuDs()
    {
        $menuDs = $this->Kernel->Link('Dataset.Array');
        $menuDs->setData($this->menuItem);
        $menuDs->addParam('activemode', $this->activeMode);
        return $menuDs;
    }
	
    public function isCorrectParts($parts)
	{
	    $this->setCorrectPassage();
		$result = $this->isParts($parts, $this->passage[key($this->passage)]['subtree']);
		
		foreach ($this->currentNode as $parts => $node)
		{
		    if (isset($this->modes[$node]))
		    {
		        $this->setActiveMode($node);        
		    }
		}
	    return $result;
	}
    
    public function getContent($params = array())
    {
        $param = 0 < count($params) ? $params[count($params)-1] : 0;
        $currentNode = $param ? $this->currentNode[$param] : key($this->passage);
        $methodNode = 'getNode'.$currentNode;
        $templateFile = array('file' => '_inter/'.$currentNode.'.tpl');
        $templateContentFile = array('file' => '_inter/content.tpl');
        
        $ds = $this->Kernel->link('object.object');
        if(method_exists($this, $methodNode))
        {
            $this->$methodNode($ds, $this->nodes);
        }
        
        
        $template2 = $this->Kernel->link('template2.manager', true);
        $content = $template2->execute($ds, $templateFile, $this->Name);
        
        $ds = $this->Kernel->Link('dataset.abstract');
        $template = $this->Kernel->link('template.manager', true);
        $ds->addParam('content', $content);
        $hierarchy = array(
            array(
                'url'      => $this->LinkUrl,
				'fullname' => $this->nodeName[key($this->passage)]
            )
        ); 
        $hierarchy = array_merge($hierarchy, $this->getHierarchy());
        $hierarchyDs = $this->Kernel->Link('dataset.array');
        $hierarchyDs->setData($hierarchy);
        $ds->addChildDS('hierarchy', $hierarchyDs);
        $result = $template->execute($ds, $templateContentFile, $this->Name);
        return $result;
    }
    
	public function process()
	{
	    if (isset($_POST['module'], $_POST['object'], $_POST['handler']) && $this->Name == $_POST['module'])
	    {
	        $handlerMethod = 'handler'.$_POST['object'].$_POST['handler'];
	        if (method_exists($this, $handlerMethod))
	        {
    		    $this->$handlerMethod();
		    }    
	    }
	}
	
	protected function setNodeHierarchy($nodeName, $hierarchy, $parentFilters = null)
    {
        $this->nodeHierarchy[$nodeName] = array($hierarchy, $parentFilters);   
    }
	
	protected function setNode($node, $name)
    {
	    $this->nodeName[$node] = $name;
    }
    
    protected function setMenu($mode)
    {
        $url =  $this->LinkUrl.$this->get($this->Name.'.alias').'/'.$mode.'/';
        if (! $this->activeMode)
        {
           $this->activeMode = $mode;
           $url = $this->LinkUrl.$this->get($this->Name.'.alias').'/'; 
        } 
        $this->modes[$mode] = true;
        $this->addMenu($this->nodeName[$mode], $url ,$mode);
    }
    
    protected function setNodePart($node, $objectName, $patern, $filters = array('id'=> 1))
    {
        $this->linkPassage[$node]['data'] = array($objectName, $patern, $filters);
    }
    
    protected function stateList($ds, $objectName, $objectNameList, $objectFilters = null, $objectOrderBy = null)
    {
        $state = $this->Kernel->link('State.List');
        $state->storage = $this->storage; 
        $state->moduleName = $this->Name;
        $state->this = $this;
        $state->objectName = $objectName;
        $state->objectFilters = $objectFilters;
        $state->objectNameList = $objectNameList;
        $state->objectOrderBy = $objectOrderBy;
        $state->getDataset($ds);
    }
    
    protected function stateParentAdd($ds, $objectName, $parentObjectName, $parentObjectFilters)
    {
        $state = $this->Kernel->link('State.ParentAdd');
        $state->storage = $this->storage; 
        $state->moduleName = $this->Name;
        $state->this = $this;
        $state->form = $this->get('form');
        $state->objectName = $objectName;
        $state->parentObjectName = $parentObjectName;
        $state->parentObjectFilters = $parentObjectFilters; 
        $state->parentObjectNameList = $parentObjectName.'list';
        $state->getDataset($ds);
    }
    
    protected function stateParentEdit($ds, $objectName, $objectFilters, $parentObjectName, $parentObjectFilters)
    {

        $state = $this->Kernel->link('State.ParentEdit');
        $state->storage = $this->storage; 
        $state->moduleName = $this->Name;
        $state->this = $this;
        $state->form = $this->get('form');
        $state->objectName = $objectName;
        $state->objectFilters = $objectFilters;
        $state->parentObjectName = $parentObjectName;
        $state->parentObjectFilters = $parentObjectFilters; 
        $state->parentObjectNameList = $parentObjectName.'list';
        $state->getDataset($ds);
    }
    
    protected function stateAdd($ds, $objectName, $params)
    {
        $state = $this->Kernel->link('State.Add');
        $state->storage = $this->storage; 
        $state->moduleName = $this->Name;
        $state->this = $this;
        $state->objectName = $objectName;
        
        if(null !== $this->get('form'))
        {
            $state->form = $this->get('form');
        }
        else
        {
	        $state->form = $this->Kernel->link('object.object');
        }
        if(is_array($params) && count($params))
        { 
            foreach($params as $param => $value)
	        { 
	            $state->form->set($param, $value);
	        }
        }
        $state->getDataset($ds);
    }
    
    protected function stateEdit($ds, $objectName, $objectFilters)
    {
        $state = $this->Kernel->link('State.Edit');
        $state->storage = $this->storage; 
        $state->moduleName = $this->Name;
        $state->this = $this;
        $state->form = $this->get('form');
        $state->objectName = $objectName;
        $state->objectFilters = $objectFilters;
        $state->getDataset($ds);
    }
    
    
    
    
    private function setActiveMode($activeMode)
    {
        $this->activeMode = $activeMode;
    }
    
    private function addMenu($name, $url, $mode)
    {
        $this->menuItem[] = array(
	        'name'      =>  $name,
	        'alias'     =>  $url,
	        'mode'      =>  $mode
        );
    }
    
	// Взрыв мозга
	protected function setPassage($from, $to = null)
    {
        if (null == $to)
        {
            $this->passage[$from] = array();        
        }
        else
        {
            if (! isset($this->linkPassage[$from]))
            {
                $this->linkPassage[$from] = & $this->passage[$from];
            }
            $passage = & $this->linkPassage[$from];
            $passage['data'] = array();
            if($to != $from)
            {
                $passage['subtree'][$to] = array();
                $this->linkPassage[$to] = & $passage['subtree'][$to];
            }
            if($to == $from)
            {
                $this->linkCirclePassage[$to] = & $this->linkPassage[$from];     
            }
            
        }            
    }
    
    private function setCorrectPassage()
    {
	    foreach($this->linkCirclePassage as $circlePassage => $data)
	    {
	        $this->linkPassage[$circlePassage]['subtree'][$circlePassage] = $data; 
	    }
    }
    
    private function isParts($parts, $tree)
    {
        $result = false;
        $part = array_shift($parts);
        foreach ($tree as $node => $data)
        {
            list($objectName, $patern, $filters) = $data['data'];
	        if (preg_match('/^'.$patern.'$/', $part, $match))
	        {
                if ($parts && isset($data['subtree']) && $data['subtree'] )
		        {
		            if ($this->addNodePart($node, $objectName, $filters, $match))
                    {
                        $this->currentNode[$part] = $node;
                        $this->matchMode[$node] = $match;
                        $result = $this->isParts($parts, $data['subtree']);
                    }
		        } 
		        else
		        {
                    if ($this->addNodePart($node, $objectName, $filters, $match))
                    {
                        $this->currentNode[$part] = $node;
                        $this->matchMode[$node] = $match;
                        $result = true;
                    }
		        }
		        break;
	        }
        }
        return $result;
    }
    
    private function addNodePart($nodeName, $objectName, $filters, $match)
    {
        $result = true;
        if(1 < count($match))
        {
            foreach($filters as $filter => $i)
            {
                if (isset($match[$i]))
                {
                    $filters[$filter] = $match[$i];
                }
            }
            $node = $this->storage->findOne($objectName, $filters);
            $result = false;
            if ($node)
            {
                $result = true;
                $node->set('nodeName', $nodeName);
                $this->nodes->set($nodeName, $node);
                $this->node = $node;
            }
        }
        return $result;
    }
    
	function setModuleParams($params)
	{
		$paramsObject = $this->Kernel->link('object.object');
		$paramsObject->setup($params);
		$this->set('config', $paramsObject);		
	}

    function getHierarchy()
    {
        $url = array(substr($this->LinkUrl, 0, -1));
        $matchs = $this->matchMode;
        foreach($matchs as $mode => $match)
        {
	        if (isset($this->nodeHierarchy[$mode]))
	        {
	            list($name, $parentFilters) = $this->nodeHierarchy[$mode];
		        
	            if($parentFilters)
	            {
	                $object = $this->nodes->get($mode);
	                $hierarchy = $this->getHierarchyParent($mode, $match, $url, $object, $name, $parentFilters);
			        $this->hierarchy = array_merge($this->hierarchy, array_reverse($hierarchy));        
	            }
	            else
	            {
		            $url[] = $match[0];
		            if ($this->nodes->get($mode))
		            {
		                $name = $this->getHierarchyNameMode($this->nodes->get($mode), $name);
		            }
		
		            $this->hierarchy[] = array(
			        	'fullname' => $name,
			          	'url' => implode('/', $url) . '/'
			        );
	            }
	        }
	        else
	        {
	            echo 'No hierarchy mode: "'.$mode.'"';
	        }
        }
        return $this->hierarchy;
    }
    
    function getHierarchyParent($mode, $match, & $url, $object, $name, $parentFilters)
	{
	    $hierarchy = array();
	    list($objectName, $part, $filters) = $this->linkPassage[$mode]['data'];
        
        $purl = $match[0];
        $filtersKeys = array_keys($filters);
        for ($i = 1; $i < count($match); $i++)
        {
            $purlI = explode($match[$i], $purl);
            $purl = $purlI[0] . $object->get($filtersKeys[$i-1]). (isset($purlI[1]) ? $purlI[1] : '');    
        }
        $url[] = $purl;
	    
        $hierarchy[] = array(
			'fullname' => $this->getHierarchyNameMode($object, $name),
      		'url' => implode('/', $url) . '/'
        );
        
	    $check = true; 	    
	    foreach($parentFilters as $parentFilter => $i)
	    {
	        if($object->get($parentFilter))
	        {
	            $objectFilters = array(
	                array_search($i, $filters) => $object->get($parentFilter)    
	            );         
	        }
	        else
	        {
	            $check = false;
	        }
	    }
	    if ($check)
        {
    	    array_pop($url);
            $object = $this->storage->findOne($objectName, $objectFilters);
            $hierarchy = array_merge($hierarchy, $this->getHierarchyParent($mode, $match, $url, $object, $name, $parentFilters));
        }
	    
        return $hierarchy;
	}
    
	function getHierarchyNameMode($object, $hierarchyName)
	{
        $result = null;
	    if ($object)
        {
	        $result = preg_replace(
				'/{(\w+)}/e', 
	            '$object->get("\\1")', 
	            $hierarchyName
	        );        
        }
	    return $result;
	}
	
	protected function handlerList()
    {
        $handler = $this->Kernel->link('Handler.List');
        $handler->setParent($this);
        $handler->run();
    }
    
    protected function handlerAdd()
    {
        $handler = $this->Kernel->link('Handler.Add');
        $handler->setParent($this);
        $handler->run();
    }
    
    protected function handlerEdit()
    {
        $handler = $this->Kernel->link('Handler.Edit');
        $handler->setParent($this);
        $handler->run();
    }
    
}