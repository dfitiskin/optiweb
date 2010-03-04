<?

define('VERSIONS',1);
define('VERSIONS_PLUS_ALL',2);

class CBackend_ModParams
{
	var $Modes = array();
    var $ModesList  = array();
    var $Params = array();
    var $Templates = array();
	var $links = array();
	var $nameLinks = array();
	var $nodeName = array();

	var $linkNode = array();  
    var $currentLink = array();
    var $blockData = array();
	var $parentNode = array();
	var $rootNode = array();

    function getModesDescr()
    {
    	$_modes = array();
        for ($i=0;$i<sizeof($this->Modes);$i++)
        	$_modes[$this->Modes[$i]['name']] = $this->Modes[$i]['desc'];
        return $_modes;
    }

    function GetModes($_install = 'b')
    {
    	$_modes = array();
       	foreach($this->Modes as $k=>$v)
        if (isset($v['type']) && $v['type'] == $_install)
        {
			$_modes[] = $v;
        }
		return $_modes;
    }

    function  GetParams($_mode)
    {
		return isset($this->Params[$_mode])?$this->Params[$_mode]:array();
    }

    function  GetTemplates($_mode)
    {
		return isset($this->Templates[$_mode])?$this->Templates[$_mode]:array();
    }

    function isMode($_name)
    {
    	return isset($this->ModesList[$_name])?true:false;
    }

    function GetModeDescr($_name)
    {
		return $this->isMode($_name)?$this->Modes[$this->ModesList[$_name]]['desc']:null;
    }

    // Для создания динамического раздела

    function getModuleParams()
    {
		return isset($this->ModuleParams)?$this->ModuleParams:array();
    }

    function getTreeModes($_params)
    {
    	$_modes = &$this->ModesTree;
    	while (sizeof($_params))
        {
        	$_param = array_shift($_params);
        	$_modes = &$_modes['subtree'][$_param];
        }
		return $_modes['modes'];
    }

    function getTables()
    {
    	return (isset($this->Tables) && ($this->Tables != null))?$this->Tables:null;
    }
    
    function addLocalBlock($_mode, $_descr, $_no_tpl = false)
    {
        $this->Modes[] = array(
            'name'        =>        $_mode,
            'desc'        =>        $_descr,
            'type'        =>        'l'
        );

        $this->ModesList[$_mode] = count($this->Modes) - 1;
        $this->Params[$_mode] = array();
        $this->Templates[$_mode] = array();

        if (!$_no_tpl)
        {
                $this->addTemplate($_mode, 'main', $_descr);
        }
    }
    
    function addGlobalBlock($_mode, $_descr, $_no_tpl = false)
    {
        $this->Modes[] = array(
            'name'        =>        $_mode,
            'desc'        =>        $_descr . ' (внешний блок)',
            'type'        =>        'b'
        );

        $this->ModesList[$_mode] = count($this->Modes) - 1;
        $this->Params[$_mode] = array();
        $this->Templates[$_mode] = array();

        if (!$_no_tpl)
        {
            $this->addTemplate($_mode, 'main', $_descr);
        }
        
        $this->addVersionParam($_mode);
        $this->addParam($_mode, '_link_url', 'Путь к разделу', 'st', '/');
        
    }    

    function addTemplate($_mode, $_tpl, $_descr, $_file_name = null)
    {
        $this->Templates[$_mode][] = array(
            'name'  =>  $_tpl,
            'desc'  =>  $_descr,
            'file'  =>  $_file_name ? $_file_name : $_mode.'_'.$_tpl.'.tpl'
        );
    }

    
    function linkBlock($mode, $path, $scope = 0)
    {
        if (preg_match('/\(\/(\w+)\)/', $path, $match))
        {
            $this->linkBlock($mode, str_replace($match[0], '/' . $match[1], $path), $scope);
            $this->linkBlock($mode, str_replace($match[0], '', $path), $scope);
        }
        else
        {
            $this->addLinkBlock($mode, $path, $scope);
        }
    }
    
    function addLinkBlock($_mode, $_path, $_scope = 0)
    {
        $_parts = explode('/', $_path);
        array_shift($_parts);
        array_pop($_parts);

        if (!isset($this->ModesTree))
        {
            $this->ModesTree = array(
                'modes'     => array(),
                'subtree'   => array(),
            );
        }

        $_current_node = & $this->ModesTree;

        foreach ($_parts as $i => $_node_name)
        {
            if (!isset($_current_node['subtree'][$_node_name]))
            {
                $_current_node['subtree'][$_node_name] = array(
                    'modes'     => array(),
                    'subtree'   => array(),
                );
            }
            $_current_node = & $_current_node['subtree'][$_node_name];
        }

        $_current_node['modes'][] = array(
            'name'    =>  $_mode,
            'scope'   =>  $_scope,
            'block'   =>  null,
        );
    }    
    
    function addParam($_mode, $_name, $_descr, $_type = 'st', $_def_value = null, $_values = null)
    {
        $this->Params[$_mode][] = array(
            'name'  =>  $_name,
            'type'  =>  $_type,
            'value' =>  $_def_value,
            'desc'  =>  $_descr,
            'descr' =>  $_descr,
            'values' => $_values,
        );
    }
    
    function addVersionParam($_mode)
    {
        $this->Params[$_mode][] = array(
            'name'  =>  'version',
            'type'  =>  'version',
            'list'  =>  VERSIONS,
            'value' =>  null,
            'desc'  =>  'Версия',
            'descr' =>  'Версия',
        );
    } 

    function addModuleParam($_name, $_descr, $_type = 'st', $_def_value = null, $_values = null)
    {
        $this->ModuleParams[] = array(
            'name' => $_name,
            'svalue' => $_def_value,
            'type' => $_type,
            'value' => $_def_value,
            'desc' => $_descr,
            'descr' => $_descr,
            'values' => $_values,
        );        
    }
    

    
    /********************************************************/
    
    var $nodePattern = array();
    var $nodeFilters = array();
    
	function setNode($node, $name, $pattern = array(), $filters = array())
    {
	    $this->nodeName[$node] = $name;
	    $this->nodePattern[$node] = $pattern;
	    $this->nodeFilters[$node] = $filters;
    }
}
?>
