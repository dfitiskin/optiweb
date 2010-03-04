<?

class CBackend_TreeNode
{
	public $Profile = null;
    public $xmlPath = null;
    public $htmlPath = null;

    public $Doc = null;
    public $Node = null;
    public $Ruleset = null;
    public $Block = null;
    public $BlockParams = null;
    public $BlockTempls = null;

    function Init()
    {
    	$this->XmlTools = &$this->Kernel->Link('system.xmltools');
    }


   	//--------------------------------------------------------------------------
    // Установка профиля
    //--------------------------------------------------------------------------
    function setProfile($_profile)
    {
    	$this->Profile = $_profile;
    }

   	//--------------------------------------------------------------------------
    // Установка пути
    //--------------------------------------------------------------------------
	function setPath($_params)
    {
	    $_dir = implode('/',$_params);
	    if ($_dir) $_dir .= '/';
	    $this->xmlPath = PROFILES_DIR.$this->Profile.'/'.TREE_DIR.'/'.$_dir.NODE_FILE;
        $this->htmlPath = PROFILES_DIR.$this->Profile.'/'.BLOCKS_DIR.'/'.$_dir;
    }

   	//--------------------------------------------------------------------------
    // Установка узла
    //--------------------------------------------------------------------------
    function setNode()
    {
	    $_xml = $this->Kernel->ReadFile($this->xmlPath);
        if ($_xml)
        {
		    $this->Doc = new DOMDocument('1.0', 'Windows-1251');
            $this->Doc->loadXml($_xml, XML_OPTION_SKIP_WHITE);
    		$this->Node = $this->Doc->documentElement;
            return true;
        }
        return false;
    }

    function setNodeTemplate($_scope,$_template_name,$_name=null)
    {
        $_rulesets = $this->Node->getElementsByTagname('ruleset');
        $_ruleset = $_scope > 0 ? $_rulesets->item(1) : $_rulesets->item(0);
		$_templs = $_ruleset->childNodes;
        $_template = $this->XmlTools->getElementNodeByAttribute($_templs,'template','name',$_name);
        if (!$_template)
        {
		   	$_template = $this->Doc->createElement('template');
        	$_template = $_ruleset->appendChild($_template);
        }
        $_template->setAttribute('file',$_template_name);
        if ($_name) $_template->setAttribute('name',$_name);
    }

    function deleteNodeTemplate($_scope,$_name=null)
    {
        $_rulesets = $this->Node->getElementsByTagname('ruleset');
        $_ruleset = $_scope > 0 ? $_rulesets->item(1) : $_rulesets->item(0);
        $_templs = $_ruleset->childNodes;
        $_template = $this->XmlTools->getElementNodeByAttribute($_templs,'template','name',$_name);

        if ($_template)
        {
            do
            {
            	if ($_template->getAttribute('name') == $_name)
                {
    			   	$_template->parentNode->removeChild($_template);
                    break;
                }
            }
            while ($_template = $this->XmlTools->getNextElement($_template,'template'));
        }
    }
    function updMainObjectParam($_name,$_value)
    {
        $_mainobjects = $this->Node->getElementsByTagname('mainobject');
        $_mainobject = $_mainobjects->item(0);
        if ($_mainobject)
        {
        	$_param = $this->XmlTools->getChildNodeByTagName($_mainobject,'param');
	        while ($_param && ($_param->getAttribute('name') != $_name))
            {
	            $_param = &$this->XmlTools->GetNextElement($_param,'param');
            }
            
	        if ($_param)
            {
                $_param->setAttribute('value',$this->XmlTools->EncodeStr($_value));
            }
			else
            {
	            $_block_param_null = $this->Doc->createElement('param');
	            $_block_param = $_mainobject->appendChild($_block_param_null);
	            $_block_param->setAttribute('name',$_name);
	            $_block_param->setAttribute('value',$this->XmlTools->EncodeStr($_value));
            }
        }
	}

    function setMainObjectVersion($_version)
    {
        $_mainobjects = $this->Node->getElementsByTagname('mainobject');
        $_mainobject = $_mainobjects->item(0);

        if ($_mainobject)
        {
			$_mainobject->setAttribute('version',$_version);
        }
    }


   	//--------------------------------------------------------------------------
    // Поиск блока
    //--------------------------------------------------------------------------
	function findBlock($_name,$_scope)
    {
        $_rulesets = $this->Node->getElementsByTagname('ruleset');
        $this->Ruleset = $_scope > 0 ? $_rulesets->item(1) : $_rulesets->item(0);
        $_blocks = $this->Ruleset->getElementsByTagname('block');
        $this->Block = null;
        
        for($i = 0; $i < $_blocks->length; $i++)
        {
            $_block = $_blocks->item($i);
            if (($_block->getAttribute('name')) == $_name)
            {
                $this->Block = $_block;
                break;
            }
        }
    }

    //--------------------------------------------------------------------------
    // Установка блока
    //--------------------------------------------------------------------------
	function setBlock(&$_block)
    {
		$this->Block = &$_block;
		$this->Block = $this->Ruleset->appendChild($this->Block);
    }

    //--------------------------------------------------------------------------
    // Возвращает клон блока
    //--------------------------------------------------------------------------
	function &getBlock()
    {
    	$_result = & $this->Block->cloneNode(true);
		return $_result;
    }

   	//--------------------------------------------------------------------------
    // Удаление блока
    //--------------------------------------------------------------------------
    function deleteBlock()
    {
		if ($this->Block !== null)
    	{
            $_block_path = $this->getBlockPath();
            if (file_exists($_block_path))
            {
            	unlink($_block_path);
            }
        	$this->Block->parentNode->removeChild($this->Block);
            $this->Block = null;
        }
    }

   	//--------------------------------------------------------------------------
    // Создание нового блока
    //--------------------------------------------------------------------------
    function newBlock()
    {
    	$this->Block = $this->Doc->createElement('block');
        $this->Block = $this->Ruleset->appendChild($this->Block);
    }

   	//--------------------------------------------------------------------------
    // Создание аттрибутов
    //--------------------------------------------------------------------------
    function setBlockAttribute($_name,$_value)
    {
    	$this->Block->setAttribute($_name,$this->XmlTools->EncodeStr($_value));
    }

    function setBlockName($_value)
    {
    	$this->SetBlockAttribute('name',$_value);
    }

    function setBlockDescr($_value)
    {
    	$this->SetBlockAttribute('descr',$_value);
    }

    function setBlockSource($_value)
    {
    	$this->SetBlockAttribute('source',$_value);
    }

    function setBlockObject($_value)
    {
    	$this->SetBlockAttribute('object',$_value);
    }

    function setBlockScope($_value)
    {
    	if ($_value == '0')
        {
    		$this->Block->removeAttribute('scope');
        }
		else
        {
	        $this->SetBlockAttribute('scope',$_value);
        }
    }

    //--------------------------------------------------------------------------
    // Значения аттрибутов
    //--------------------------------------------------------------------------
    function getBlockAttribute($_name)
    {
    	$_value = $this->Block->getAttribute($_name);
    	$_result = $this->XmlTools->DecodeStr($_value);
        return $_result;
    }

    function &getBlockName()
    {
    	$_result = $this->getBlockAttribute('name');
    	return $_result;
    }

    function &getBlockSource()
    {
    	$_result = &$this->getBlockAttribute('source');
    	return $_result;
    }

    function getBlockScope()
    {
    	$_result = $this->getBlockAttribute('scope');
    	return $_result;
    }

    function &getBlockObject()
    {
    	$_result = $this->getBlockAttribute('object');
    	return $_result;
    }

   	//--------------------------------------------------------------------------
    // Добавление параметра блока
    //--------------------------------------------------------------------------
    function setBlockParam($_name, $_value)
    {
        $_block_param_null = $this->Doc->createElement('param');
        $_block_param = $this->Block->appendChild($_block_param_null);
        $_block_param->setAttribute('name', $_name);
        $_block_param->setAttribute('value', $this->XmlTools->EncodeStr($_value));
    }

   	//--------------------------------------------------------------------------
    // Добавление шаблона блока
    //--------------------------------------------------------------------------
    function setBlockTemplate($_name,$_file)
    {
        $_block_param_null = $this->Doc->createElement('template');
        $_block_param = $this->Block->appendChild($_block_param_null);
        $_block_param->setAttribute('name',$_name);
        $_block_param->setAttribute('file',$_file);
        //$_block_param->setAttribute('type',$_type);
    }

   	//--------------------------------------------------------------------------
    // Добавление слота для блока
    //--------------------------------------------------------------------------
    function setBlockSlot($_name,$_descr,$_type,$_value)
    {
        $_block_param_null = $this->Doc->createElement('slot');
        $_block_param = $this->Block->appendChild($_block_param_null);
        $_block_param->setAttribute('name',$_name);
        $_block_param->setAttribute('type',$_type);
        $_block_param->setAttribute('value',$this->XmlTools->EncodeStr($_value));
        $_block_param->setAttribute('descr',$this->XmlTools->EncodeStr($_descr));
    }


   	//--------------------------------------------------------------------------
    // Извлечене параметра блока
    //--------------------------------------------------------------------------
    function getBlockParam($_name)
    {
		$_param = $this->XmlTools->getChildNodeByTagName($this->Block,'param');
		while ($_param && ($_param->getAttribute('name') !== $_name))
        {
	        $_param = &$this->XmlTools->GetNextElement($_param,'param');
        }
        
		if ($_param)
        {
            return $_param->getAttribute('value');
        }
        else
        {
            return null;
        }
    }

    function getBlockSlot($_name)
    {
		$_slot = $this->XmlTools->getChildNodeByTagName($this->Block,'slot');
		
        while ($_slot && ($_slot->getAttribute('name') !== $_name))
        {
	        $_slot = &$this->XmlTools->GetNextElement($_slot,'slot');
        }
        
		if ($_slot)
        {
        	$_arr = array(
            	'name'	=>	$_name,
                'type'	=> 	$_slot->getAttribute('type'),
                'descr'	=>	$this->XmlTools->DecodeStr($_slot->getAttribute('descr')),
				'value'	=>	$this->XmlTools->DecodeStr($_slot->getAttribute('value'))
            );
			return $_arr;
    	}
        else
        {
            return null;
        }
    }


    function getBlockTemplate($_name)
    {
		$_templ = $this->XmlTools->getChildNodeByTagName($this->Block,'template');
		while ($_templ && ($_templ->getAttribute('name') != $_name)){};

    	return ($_templ) ? $_templ->getAttribute('file') : null;
    }

    function updBlockParam($_name,$_value)
    {
        $_param = $this->XmlTools->getChildNodeByTagName($this->Block, 'param');
		while ($_param && ($_param->getAttribute('name') != $_name))
        {
	        $_param = &$this->XmlTools->GetNextElement($_param, 'param');
        }
        
		if ($_param)
        {
            $_param->setAttribute('value',$this->XmlTools->EncodeStr($_value));

            while ($_param = &$this->XmlTools->GetNextElement($_param, 'param'))
            {
                if ($_param->getAttribute('name') == $_name)
                {
                    $_param->parentNode->removeChild($_param);
                }
            }
        }
        else
        {
            return false;
        }
        return true;
    }

    function updBlockTemplate($_name,$_file)
    {
		$_templ = $this->XmlTools->getChildNodeByTagName($this->Block,'template');
		while ($_templ && ($_templ->getAttribute('name') != $_name))
        {
	        $_templ = &$this->XmlTools->GetNextElement($_templ,'template');
        }
		
        if ($_templ)
        {
            $_templ->setAttribute('file',$_file);
        }
        else
        {
            return false;
        }

        $_old = null;
		while ($_templ)
		{
			$_templ = &$this->XmlTools->GetNextElement($_templ,'template');
			if ($_templ && $_templ->getAttribute('name') == $_name)
			{
	            if ($_old)
                {
                    $_old->parentNode->removeChild($_old);                	
                }
	            $_old = $_templ;
			}
	    }
	    
        if ($_old)
        {
            $_old->parentNode->removeChild($_old);        	
        }
        return true;
    }

    function updBlockSlot($_name,$_descr,$_type,$_value)
    {
		$_slot = $this->XmlTools->getChildNodeByTagName($this->Block,'slot');
		while ($_slot && ($_slot->getAttribute('name') != $_name))
	        $_slot = &$this->XmlTools->GetNextElement($_slot,'slot');
		if ($_slot)
        {
	        $_slot->setAttribute('type',$_type);
    	    $_slot->setAttribute('value',$this->XmlTools->EncodeStr($_value));
        	$_slot->setAttribute('descr',$this->XmlTools->EncodeStr($_descr));
            return true;
        }
        return false;
    }

    function deleteBlockSlot($_name)
    {
		$_slot = $this->XmlTools->getChildNodeByTagName($this->Block,'slot');
		while ($_slot && ($_slot->getAttribute('name') != $_name))
	    {
            $_slot = &$this->XmlTools->GetNextElement($_slot,'slot');
	    }    

		if ($_slot)
        {
	        $_slot->parentNode->removeChild();
        }
    }

    function deleteBlockSlots()
    {
		$_childs = $this->Block->childNodes;
        for ($i=0; $i < $_childs->length; $i++)
        {
        	$_node = $_childs->item($i);
            if ($_node->nodeName == 'slot')
            {
                $_node->parentNode->removeChild($_node);
            }
        }
    }

    function deleteBlockParams()
    {
		$_childs = $this->Block->childNodes;
        for ($i=0; $i < $_childs->length; $i++)
        {
        	$_node = $_childs->item($i);
            $_node->parentNode->removeChild($_node);
        }
    }


    function isBlockExists()
    {
    	return $this->Block ? true : false;
    }


    function getBlockPath()
    {
    	$_name = $this->getBlockName();
        $_scope = $this->getBlockScope();
        if($_scope)
        {
            $_name = 'child_'.$_name;
        }
        $_block_path = $this->htmlPath.$_name.BLOCK_EXT;
		return $_block_path;
    }

    function getBlockStaticContent()
    {
    	$_block_path = $this->getBlockPath();
		$_data = $this->Kernel->ReadFile($_block_path);
    	return $_data;
    }

    function setBlockStaticContent(&$_data)
    {
		$_block_path = $this->getBlockPath();
        $FManager = $this->Kernel->Link('services.filemanager');
        $FManager->WriteFile($_block_path,$_data);
    }

    function Save()
    {
    	$this->Doc->save($this->xmlPath);
    }

    function View()
    {
        echo htmlentities($this->Doc->saveXML());
    }
}

?>