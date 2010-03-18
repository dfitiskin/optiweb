<?php
loadlib('object.object');

class CSystem_Front extends CObject_Object
{
    var $Name = null;
	private $isUrlCorrect = false;
	
	function getAccess()
    {
        return true;
    }
	
	function getName()
	{
		return $this->Name;
	}
	
	function setName()
	{
	}
	
	function getUrl()
	{
		return $this->Kernel->Url;
	}
	
	function setUrl()
	{
	}
	

    function execute($params, $templs, $typeParts, $parts, $linkUrl, $level)
    {
        return $this->getBlockContent($params, $templs, $typeParts, $parts, $linkUrl, $level);
    }

    function getBlockContent($params = array(), $templs = array(), $typeParts = array(), $parts = array(), $linkUrl= null, $level = 0)
    {
        $result = null;
        $mode = isset($params['mode']) ? $params['mode'] : 'index';
        $method = 'get' . $mode . 'content';
        if (method_exists($this, $method))
        {
            $result = $this->$method($params, $templs);
        }
        return $result;
    }
	
	function process()
	{
		if (isset($_POST['object'],$_POST['mode'], $_POST['action'], $_POST['send']) && $_POST['object'] == $this->Name && is_array($_POST['send']))
		{
			$this->processForm($_POST['mode'], $_POST['action'], $_POST['send']);
		}
	}
	
	function processForm($mode, $action, $params)
    {
        $method = 'handle' . $mode . $action;		

        if (method_exists($this, $method))
        {
			$form = $this->Kernel->link('object.object');
			$form->setup($params);
            $this->$method($form, $params);
        }		
    }

	function setModuleParams($params)
	{
		$paramsObject = $this->Kernel->link('object.object');
		$paramsObject->setup($params);
		$this->set('config', $paramsObject);		
	}	
}