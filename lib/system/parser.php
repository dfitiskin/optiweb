<?php

//------------------------------------------------------------------------------
// Module : System
// Class  : CMS Parser
// Ver    : 0.2 beta
// Date   : 25.09.2004
// Desc   : Парсер
//------------------------------------------------------------------------------

class CSystem_Parser
{
    public $Map = array();
    public $ShuffleMap = array();
    public $ThisMap = array();
    public $ChildsMap = array();

    public $TypeParams = array();
    public $SourceParams = array();
    public $CacheAllow = true;

    public $CurrentLevel = null;

    public $Profile = null;
    public $XmlTools = null;

//------------------------------------------------------------------------------
// Инициализация парсера
//------------------------------------------------------------------------------
    function Init()
    {
        $this->XmlTools = &$this->Kernel->Link('system.xmltools');
    }

    function Execute($_profile,$_params,$_cache = false)
    {
        $this->SourceParams = $_params;
        $this->Profile = $_profile;

        $_data = null;
        if ($_cache)
        {
            $_data = $this->getCache();
        }

        if ($_data)
        {
            $_result = true;
            $this->Map = &$_data[0];
            $this->TypeParams = &$_data[1];
        }
        else
        {
            $this->Map = array(
                'blocks'            => array(),
                'template'          =>  null,
                'controlobjects'    =>  null,
                'mainobject'        =>  null
            );

            $this->ChildsMap = $this->ThisMap = $this->ShuffleMap = $this->Map;

            $this->CurrentLevel = 0;

            $this->CurrentUrl = "/";
            $_result = $this->BuildMap($this->LoadNode(''),$_params,'');
            if ($_result && $_cache)
            {
                $this->setCache();
            }
        }

        return $_result;
    }

    function getCache()
    {
        $Cacher = &$this->Kernel->Link('system.cacher',true);
        $Cacher->setProfile($this->Profile);
        $_data = $Cacher->getTreeCache($this->SourceParams);
        return $_data;
    }

    function setCache()
    {
        $_map = $this->Map;
        $_type_params = $this->TypeParams;
        $_data = array(
            $_map,
            $_type_params
        );
        $Cacher = &$this->Kernel->Link('system.cacher',true);
        $Cacher->setProfile($this->Profile);
        $Cacher->setTreeCache($_type_params,$_data);
    }

    function &GetMap()
    {
        return $this->Map;
    }

    function &GetThisMap()
    {
        return $this->ThisMap;
    }

    function &GetChildsMap()
    {
        return $this->ChildsMap;
    }

    function &GetParentMap()
    {
        return $this->ParentMap;
    }


    function &GetShuffleMap()
    {
        return $this->ShuffleMap;
    }


    function & LoadNode($_dir)
    {
        $_node = null;
        $_path = PROFILES_DIR.$this->Profile.'/'.TREE_DIR.'/'.$_dir.NODE_FILE;
        $xml = $this->Kernel->ReadFile($_path);
        if ($xml)
        {
            $_node = $this->XmlTools->getDomDoc($xml);
        }
        return $_node;
    }

    function BuildMap(& $_node, & $_params, $_dir)
    {
		static $_not_corrected = true;
        if (!$_node) return false;

        $_rulesets = $_node->getElementsByTagName('ruleset');

        $_this_ruleset = $_rulesets->item(0)?$_rulesets->item(0):null;
        $_childs_ruleset = $_rulesets->item(1)?$_rulesets->item(1):null;

		if (!sizeof($_params))
        {
            $this->ParentMap = $this->Map;
            if ($_childs_ruleset) $this->ShuffleMap = &$this->applyRuleset($_childs_ruleset,1);
            if ($_childs_ruleset) $this->ChildsMap = &$this->applyRuleset($_childs_ruleset,2,true);
            if ($_this_ruleset) $this->ThisMap = &$this->applyRuleset($_this_ruleset,1);
        }
        else
        {
            if ($_childs_ruleset) $this->applyRuleset($_childs_ruleset,3);
        }

        $this->CurrentLevel++;
        if (sizeof($_params))
        {
            $_new_dir = $_dir . $_params[0] . '/';
            $_next_node = $this->LoadNode($_new_dir);
            if (!$_next_node)
            if (isset($this->Map['mainobject']))
            {
				$MainObject = &$this->Kernel->Link($this->Map['mainobject']['name'],true);
				
				$ver = isset($this->Map['mainobject']['version'])?$this->Map['mainobject']['version']:null;
				if (method_exists($MainObject, 'setVersion'))
				{
					$MainObject->setVersion($ver);
				}
				else
				{				
					$MainObject->Version = $ver;
				}
                
				$moduleParams = array();
				if (isset($MainObject->Params))
				{
					$moduleParams = $MainObject->Params;                
				}
				$moduleParams = array_merge($moduleParams, $this->Map['mainobject']['params']);
				
				if (method_exists($MainObject, 'setModuleParams'))
				{
					$MainObject->setModuleParams($moduleParams);
				}
				else
				{
					$MainObject->Params = $moduleParams;
				}
                
				
				$this->CacheAllow = false;
                if ($_not_corrected)
                {
                	$_in_params = array_slice($this->SourceParams,$this->Map['mainobject']['level']);
                	$_link_params = array_slice($this->SourceParams,0,$this->Map['mainobject']['level']);
					$linkUrl = $this->Kernel->BaseUrl . implode('/', $_link_params) . '/';
					if (method_exists($MainObject, 'setLinkUrl'))
					{
						$MainObject->setLinkUrl($linkUrl);
					}
					else
					{
						$MainObject->LinkUrl = $linkUrl;
					}

					$_out_params = $MainObject->CorrectParts($_in_params);
                    if (($_offset = sizeof($_out_params)-sizeof($_params))>=0)
                    {
                        $_params = array_slice($_out_params,$_offset);
                    }

                    $_not_corrected = false;

                    $_new_dir = $_dir . $_params[0] . '/';
                    $_next_node = $this->LoadNode($_new_dir);
                }
                if (!$_next_node)
                {
                    if ($MainObject->isCorrectParts())
                    {
                        $this->TypeParams = array_merge($this->TypeParams,$_params);
                        return true;
                    }
                    return null;
                }
            }
            else return false;


            array_push($this->TypeParams, $_params[0]);
            array_shift($_params);
            return $this->BuildMap($_next_node, $_params, $_new_dir);
        }

        return true;
    }

    function &applyRuleset(& $_xmlRuleset, $_scope_mask = 3,$_local = false)
    {
        $_map = array(
            'blocks'        => array()
        );

        $nodes = & $_xmlRuleset->childNodes;

        for ($i = 0; $i < $nodes->length; $i++)
        {
            $node = $nodes->item($i);

            //Dump($node);
            if ($node->nodeType != XML_ELEMENT_NODE) continue;
            switch ($node->nodeName)
            {
                case 'mainobject':

                    $_arr = $this->applyMainObject($node);

                    if (!$_local)
                    {
                        $this->Map['mainobject'] = $_arr;
                    }
                    $_map['mainobject'] = $_arr;
                break;
                case 'controlobject':
                    $_control = array(
                        'name'  =>        $node->getAttribute('name'),
                        'level' =>        $this->CurrentLevel
                    );
                    if (!$_local)
                    {
                        $this->Map['controlobjects'][] = $_control;
                    }
                    $_map['controlobjects'][] = $_control;
                break;
                case 'template':
                   $_arr = array(
                        'name'  => $node->getAttribute('name'),
                        'file'  => $node->getAttribute('file'),
                        'level' => $this->CurrentLevel
                    );

                    if (!$_arr['name']) $_templ = 'template';
                    else
                    {
                        $_templ = 'templ_'.$_arr['name'];
                    }

                    if (!$_local)
                    {
                        $this->Map[$_templ] = $_arr;
                    }
                    $_map[$_templ] = $_arr;
                break;
                case 'block':
                    $_scope = $node->getAttribute('scope')?$node->getAttribute('scope'):1;
                    if ($_scope_mask & $_scope)
                    {
                        $_new = $this->applyBlock($node,$_local);
                        $_map['blocks'] = array_merge($_map['blocks'],$_new['blocks']);
                    }
                break;
            }
        }
        return $_map;
    }

    function applyMainObject(&$_node)
    {
        $_nodes = $_node->childNodes;

        $_params = array();
        for ($i = 0; $i < $_nodes->length; $i++)
        {
            if ($_nodes->item($i)->nodeType != XML_ELEMENT_NODE) continue;
            if ($_nodes->item($i)->nodeName == 'param')
            {
                $_params[$_nodes->item($i)->getAttribute('name')] = $this->XmlTools->DecodeStr($_nodes->item($i)->getAttribute('value'));
            }
        }
        $_arr = array(
            'name'    =>   $_node->getAttribute('name'),
            'version' =>   $_node->getAttribute('version'),
            'level'   =>   $this->CurrentLevel,
            'params'  =>   $_params
        );
        return $_arr;
    }

    function & applyBlock(& $_block,$_local)
    {
        $_type = $_block->getAttribute('source');
        $_map = array();
        switch($_type)
        {
            case 's':
                $_map = $this->applyStaticBlock($_block,$_local);
            break;
            case 'd':
                $_map = $this->applyDynamicBlock($_block,$_local);
            break;
            case 't':
                $_map = $this->applyTemplateBlock($_block,$_local);
            break;
        }
        return $_map;
    }

    function & applyStaticBlock(&$_block,$_local)
    {
        $_name = $_block->getAttribute('name');
        $_content = $this->XmlTools->DecodeStr($_block->nodeValue);
        if (!$_content) $_content = null;
        $_operation = $_block->getAttribute('operation');

        if (isset($this->Blocks[$_name]))
        {
            $_old_content = $this->Blocks[$_name];
        }

        switch($_operation)
        {
        }

        $_descr = $this->XmlTools->DecodeStr($_block->getAttribute('descr'));
        $_link = $_block->getAttribute('link');
        $_scope = $_block->getAttribute('scope');
        $_scope = $_scope?$_scope:0;

        $_map['blocks'][''.$_name.''] = array(
            'name'      => $_name,
            'source'    => 's',
            'content'   => $_content,
            'level'     => $this->CurrentLevel,
            'descr'     => $_descr?$_descr:'',
            'scope'     => $_scope,
            'link'      => $_link
        );
        if (!$_local)
        {
            $this->Map['blocks'] = array_merge($this->Map['blocks'],$_map['blocks']);
        }
        return $_map;
    }

    function &applyDynamicBlock(&$_block,$_local)
    {
        $_name = $_block->getAttribute('name');

        $_cache = $_block->getAttribute('cache')?$_block->getAttribute('cache'):"n";
        $_descr = $this->XmlTools->DecodeStr($_block->getAttribute('descr'));
        $_scope = $_block->getAttribute('scope');
        $_scope = $_scope?$_scope:0;

        $_value = array(
            'name'      => $_name,
            'source'    => 'd',
            'object'    => $_block->getAttribute('object'),
            'cache'     => $_cache,
            'level'     => $this->CurrentLevel,
            'params'    => array(),
            'template'  => array(),
            'descr'     => $_descr?$_descr:'',
            'scope'     => $_scope,
            'content'   => null
        );



        $_nodes = & $_block->childNodes;

        for ($i = 0; $i < $_nodes->length; $i++)
        {
            $_node = $_nodes->item($i);
            if ($_node->nodeType != XML_ELEMENT_NODE) continue;

            switch($_node->nodeName)
            {
                case 'template':

                    $_templ_name = $_node->getAttribute('name') ? $_node->getAttribute('name') : 'default';
                    $_value['template'][$_templ_name] = array(
                        'file'  => $_node->getAttribute('file')
                    );
                break;
                case 'param':
                    $_value['params'][$_node->getAttribute('name')] = $_node->getAttribute('value');
                break;
            }
        }
        $_map['blocks'][''.$_name.''] = & $_value;
        if (!$_local)
        {
            $this->Map['blocks'] = array_merge($this->Map['blocks'],$_map['blocks']);
        }
        return $_map;
    }

    function &applyTemplateBlock(&$_block,$_local)
    {
        $_name = $_block->getAttribute('name');
        $_cache = $_block->getAttribute('cache')?$_block->getAttribute('cache'):"n";
        $_descr = $this->XmlTools->DecodeStr($_block->getAttribute('descr'));
        $_scope = $_block->getAttribute('scope');
        $_scope = $_scope?$_scope:0;

        $_value = array(
            'name'     => $_name,
            'source'   => 't',
            'cache'    => $_cache,
            'level'    => $this->CurrentLevel,
            'slots'    => array(),
            'template' => array(),
            'descr'    => $_descr?$_descr:'',
            'scope'    => $_scope,
            'content'  => null
        );
        $_nodes = & $_block->childNodes;
        for ($i = 0; $i < $_nodes->length; $i++)
        {
            $_node = $_nodes->item($i);
            if ($_node->nodeType != XML_ELEMENT_NODE) continue;

            switch($_node->nodeName)
            {
                case 'template':
                    $_templ_name = $_node->getAttribute('name') ? $_node->getAttribute('name') : 'default';
                    $_value['template'][$_templ_name] = array(
                        'file'  => $_node->getAttribute('file')
                    );
                break;
                case 'slot':
                    $_value['slots'][] = array(
                        'name'   =>        $_node->getAttribute('name'),
                        'descr'  =>        $this->XmlTools->DecodeStr($_node->getAttribute('descr')),
                        'type'   =>        $_node->getAttribute('type'),
                        'value'  =>        $this->XmlTools->DecodeStr($_node->getAttribute('value'))
                    );
                break;
            }
        }

        $_map['blocks'][''.$_name.''] = & $_value;
        if (!$_local)
        {
            $this->Map['blocks'] = array_merge($this->Map['blocks'],$_map['blocks']);
        }

        return $_map;
    }
}

?>