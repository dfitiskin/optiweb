<?php

class CSystem_Config
{
    public $Profile;                                        // Профиль
    public $Updaters = array();                             // ?
    public $BlankConfig = null;                             // ?

    function Init()
    {
        $this->BlankConfig = '<?xml version="1.0" encoding="windows-1251"?><config></config>';
        $this->BlankWarnings = '<?xml version="1.0" encoding="windows-1251"?><config><_other type="a"/></config>';
    }

    function setData($_modulename,$_data,$_name = 'main')
    {
        $_profile = $this->getProfile();
        $_path = PROFILES_DIR.$_profile.'/'.DATA_DIR.'/'.$_modulename.'/'.$_name.CACHE_EXT;
        $FManager = &$this->Kernel->Link('services.filemanager');
        $_data = serialize($_data);
        $FManager->WriteFile($_path,$_data);
    }


    function getData($_modulename,$_name = 'main')
    {
        $_profile = $this->getProfile();
        $_path = PROFILES_DIR.$_profile.'/'.DATA_DIR.'/'.$_modulename.'/'.$_name.CACHE_EXT;
        $_data = $this->Kernel->ReadFile($_path);
        if ($_data != null )$_data = unserialize($_data);
        return $_data;
    }

    function &GetConfig($_module,$_file='main',$_type = 'c')
    {
        $_cfg_params = array(
            'scope'  =>   'p',
            'file'   =>   $_file,
            'module' =>   $_module,
            'type'   =>   $_type
        );

        return $this->GetConfigEx($_cfg_params);
    }

    function &GetWarnings($_module,$_file='main')
    {
        return $this->GetConfig($_module,$_file,'w');
    }

    function GetAdminConfig($_module,$_lib,$_file='main',$_type='c')
    {
        $_cfg_params = array(
            'scope'   =>    'g',
            'file'    =>    $_file,
            'module'  =>    $_module,
            'type'    =>    $_type,
            'lib'     =>    $_lib
        );

        return $this->GetConfigEx($_cfg_params);
    }

    function GetAdminWarnings($_module,$_lib,$_file='main')
    {
        return $this->GetAdminConfig($_module,$_lib,$_file,'w');
    }


    function &GetConfigEx($_cfg_params)
    {
        $_params = $this->GetConfigParams($_cfg_params);
        if ($_cfg_params['scope'] == 'p')
        {
            $this->Kernel->findFile(null,PROFILES_DIR);
            $_path = $this->Kernel->findFile($_params['xml_file'],CONFIG_DIR.'/'.$_cfg_params['module'],PROFILES_DIR,array(),$this->getProfile());
            $_dir = dirname($_path);
            $_dir = preg_replace('/\\/\.\\/?/','/',$_dir);
            $_params['path'] = $_dir;
        }
        if (file_exists($_params['path'].$_params['cache_file'])&& filemtime($_params['path'].$_params['cache_file'])>
            filemtime($_params['path'].$_params['xml_file']))
        {
            $_ser = $this->Kernel->ReadFile($_params['path'].$_params['cache_file']);
            $_arr = unserialize($_ser);
            return $_arr;
        }
        else
        {
            $CUpdater = & $this->GetConfigUpdater($_params['path'].$_params['xml_file'],$_cfg_params['type']);
            $CUpdater->SetActiveNode(array());
            $_struct = $CUpdater->GetConfigStruct();
            $_ser = serialize($_struct);
            $this->Kernel->WriteFile($_params['path'].$_params['cache_file'],$_ser);
            return $_struct;
        }
    }

    function getProfile()
    {
        if (PROFILE == '_backend')
        {
            global $User;
            $_profile = $User->GetCurrentProfile('alias');
        }
        else
        {
            $_profile = PROFILE;
        }
        return $_profile;
    }

    function GetConfigParams($_params)
    {
        switch ($_params['scope'])
        {
            case 'g':
                $_base_path = PROFILES_DIR.CONFIG_DIR.'/'.$_params['module'].'/'.$_params['lib'].'/';
            break;
            case 'p':
                $_profile = $this->getProfile();
                $_base_path = PROFILES_DIR.$_profile.'/'.CONFIG_DIR.'/'.$_params['module'].'/';
            break;
        }

        if ($_params['type'] == 'c') $_type = 'c';
        else $_type = 'w';

        $_xml_file = $_type.'_'.$_params['file'].CONFIG_XML_EXT;
        $_cch_file = $_type.'_'.$_params['file'].CONFIG_CCH_EXT;

        $_out_params = array(
            'path'        =>   $_base_path,
            'xml_file'    =>   $_xml_file,
            'cache_file'  =>   $_cch_file
        );

        return $_out_params;
    }

    function &GetProtectConfig($_params)
    {
        $_params['scope'] = 'g';
        $_out_params = $this->GetConfigParams($_params);
        return $this->GetConfigUpdater($_out_params['path'].$_out_params['xml_file'],$_params['type']);
    }

    function &GetProfileConfig($_module,$_file='main')
    {
        $_params = array(
            'scope'    =>   'p',
            'file'     =>   $_file,
            'module'   =>   $_module,
            'type'     =>   'c'
        );

        $_out_params = $this->GetConfigParams($_params);
        return $this->GetConfigUpdater($_out_params['path'].$_out_params['xml_file'],$_params['type']);
    }

    function &GetProfileWarning($_module,$_file='main')
    {
        $_params = array(
            'scope'   =>   'p',
            'file'    =>   $_file,
            'module'  =>   $_module,
            'type'    =>  'w'
        );
        $_out_params = $this->GetConfigParams($_params);
        return $this->GetConfigUpdater($_out_params['path'].$_out_params['xml_file'],$_params['type']);
    }

    function &GetConfigUpdater($_path,$_type)
    {
        $_xmlfile = $this->Kernel->ReadFile($_path);
        if ($_xmlfile == null)
        {
            if ($_type == 'c') $_xmlfile = $this->BlankConfig;
            else $_xmlfile = $this->BlankWarnings;

            $FManager = &$this->Kernel->Link('services.filemanager');
            $FManager->WriteFile($_path,$_xmlfile);
        }

        $_object = new CSystem_Config_Updater();
        $_object->Kernel = &$this->Kernel;
        $_object->Init();

        $_object->SetXML($_xmlfile);
        $_object->Prepare();
        $_object->SetPath($_path);
        return $_object;
    }

}

class CSystem_Config_Updater
{
    public $XML = null;
    public $XMLTools = null;
    public $Doc = null;
    public $Root = null;
    public $Node = null;
    public $Type = null;
    public $ChildNo = null;
    public $Child = null;

    function Init()
    {
        $this->XMLTools = $this->Kernel->Link('system.xmltools');
    }

    function SetPath($_path)
    {
        $this->Path = $_path;
    }

    function SaveConfig()
    {
        $this->Doc->dump_file($this->Path);
    }

    function SetXML($_xml)
    {
        $this->XML = $_xml;
    }

    function Prepare()
    {
        $this->Doc = & $this->XMLTools->getDomDoc($this->XML);
        $this->Root = & $this->Doc->documentElement;
    }

    function UpdateState()
    {
    }

    function SetActiveNode($_parts = array())
    {
        $_node = $this->Root;
        $_type = 'a';
        while (sizeof($_parts))
        {
            if ($_type == 'd')
            {
                $_level = array_shift($_parts);
                $_name = array_shift($_parts);

                $_head = &$this->XMLTools->getChildNodeByTagName($_node,'header');
                $_head = &$this->XMLTools->getChildNodeByTagName($_head,$_name);

                $_type = $_head->get_attribute('type')?$_head->get_attribute('type'):'a';
                $_item = $_node->first_child();

                $_id = 0;
                while (($_id < $_level) && ($_item = $this->XMLTools->GetNextElement($_item,'row')))
                {
                    $_id++;
                }
                $_node = $this->XMLTools->getChildNodeByTagName($_item,$_name);
            }
            else
            {
                $_name = array_shift($_parts);
                $_node = $this->XMLTools->getChildNodeByTagName($_node,$_name);
                $_type = $_node->get_attribute('type')?$_node->get_attribute('type'):'a';
            }
        }

        $this->Node = & $_node;
        $this->Type  = $_type;
        $this->Child = null;

        return true;
    }

    function GetChildParams()
    {
        if ($this->Child == null)
        $this->Child = $this->Node->first_child();
        else $this->Child = $this->Child->next_sibling();

        if ($this->Child)
        {
            $_params = array();
            $_params['name'] = $this->Child->tagname;
            $_params['descr'] = $this->Child->get_attribute('descr')?$this->XMLTools->DecodeStr($this->Child->get_attribute('descr')):'';
            $_params['type'] = $this->Child->get_attribute('type')?$this->Child->get_attribute('type'):'s';
            $_params['value'] = ($_params['type'] == 's')?$this->XMLTools->DecodeStr($this->Child->get_attribute('value')):null;
            return $_params;
        }
        return false;
    }

    function AddNode($_params)
    {
        $_node = &$this->Doc->create_element($_params['name']);
        $_node->set_attribute('type',$this->XMLTools->EncodeStr($_params['type']));
        $_node->set_attribute('descr',$this->XMLTools->EncodeStr($_params['descr']));
        if ($_params['type'] == 'd')
        {
            $_header = &$this->Doc->create_element('header');
            $_node->append_child($_header);
        }
        $this->Node->append_child($_node);
    }

    function isNodeExists($_name)
    {
        $_node = &$this->XMLTools->getChildNodeByTagName($this->Node,$_name);
        return $_node?true:false;
    }


    function DelNode($_name)
    {
        $_node = &$this->XMLTools->getChildNodeByTagName($this->Node,$_name);
        $_node->unlink_node();
    }

    function UpdateNode($_name,$_params)
    {
        $_node = &$this->XMLTools->getChildNodeByTagName($this->Node,$_name);
        $_node->set_attribute('value',$this->XMLTools->EncodeStr($_params['value']));
    }

    function GetNextRow()
    {
        if ($this->Child == null)
        $this->Child = $this->Node->first_child();
        $this->Child = $this->XMLTools->GetNextElement($this->Child,'row');
        $_item = null;
        if ($this->Child)
        {
            $Item = $this->Child->first_child();
            if ($Item)
            do
            {
                $_item[$Item->tagname] = array(
                    'name'    =>  $Item->tagname,
                    'value'   =>  $this->XMLTools->DecodeStr($Item->get_attribute('value')),
                    'type'    =>  $this->Header[$Item->tagname]['type']
                );
            }
            while($Item = $Item->next_sibling());
            return $_item;
        }
        return null;
    }

    function GetChildsCount()
    {
        $_childs = $this->Node->child_nodes();
        $_count = 0;
        while (sizeof($_childs))
        {
            $_child = array_shift($_childs);
            if ($_child->tagname=='row' ) $_count++;
        }
        return $_count;
    }

    function GetHeader($_node=null)
    {
        $fl = false;
        if ($_node == null)
        {
            $_node = $this->Node; 
            $fl = true;
        }
        $Head = &$this->XMLTools->getChildNodeByTagName($_node,'header');
        $Item = $Head->first_child();
        $_header = array();
        if ($Item)
        do{
            $_header[$Item->tagname] = array(
                'name'        =>        $Item->tagname,
                'descr'        =>        $this->XMLTools->DecodeStr($Item->get_attribute('descr')),
                'type'        =>        $this->XMLTools->DecodeStr($Item->get_attribute('type'))
            );
        }
        while($Item = $Item->next_sibling());

        if ($fl)
        {
            $this->Header = $_header;
        }
        return $_header;
    }

    function isDataColExists($_name)
    {
        $Head = &$this->XMLTools->getChildNodeByTagName($this->Node,'header');
        $_node = $this->XMLTools->getChildNodeByTagName($Head,$_name);
        return $_node?true:false;
    }

    function DataAddCol($_params)
    {
        $Head = &$this->XMLTools->getChildNodeByTagName($this->Node,'header');
        $_node = $this->Doc->create_element($_params['name']);
        $_node->set_attribute('type',$_params['type']);
        $_node->set_attribute('descr',$this->XMLTools->EncodeStr($_params['descr']));
        $Head->append_child($_node);

        $Item = $this->Node->first_child();
        $_node = $this->Doc->create_element($_params['name']);
        while ($Item = $this->XMLTools->GetNextElement($Item,'row'))
        {
            if ($_params['type'] == 'd')
            {
                $_header = &$this->Doc->create_element('header');
                $_node->append_child($_header);
            }
            $Item->append_child($_node);
        }
    }

    function DataAddRow($_params)
    {
        $Row = $this->Doc->create_element('row');
        $_header = $this->GetHeader();
        foreach($_header as $k=>$v)
        {
            $_node = $this->Doc->create_element($k);
            if ($v['type'] == 's')
            {
                $_node->set_attribute('value',$this->XMLTools->EncodeStr($_params[$k]));
            }
            if ($v['type'] == 'd')
            {
                $_header = &$this->Doc->create_element('header');
                $_node->append_child($_header);
            }

            $Row->append_child($_node);
        }
        $this->Node->append_child($Row);
    }

    function DataDeleteCol($_name)
    {
        $Head = &$this->XMLTools->getChildNodeByTagName($this->Node,'header');
        $_node = $this->XMLTools->getChildNodeByTagName($Head,$_name);
        $_node->unlink_node();
        $Item = $this->Node->first_child();
        while ($Item = $this->XMLTools->GetNextElement($Item,'row'))
        {
            $_node = $this->XMLTools->getChildNodeByTagName($Item,$_name);
            $_node->unlink_node();
        }
    }

    function DataDeleteRow($_num)
    {
        $Item = $this->Node->first_child();
        $_id = 1;
        while ($Item = $this->XMLTools->GetNextElement($Item,'row'))
        {
            if ($_num == $_id)
            {
                $Item->unlink_node();
                break;
            }
            $_id++;
        }
    }

    function DataUpdate($_upd)
    {
        $_header = $this->GetHeader();
        $Item = $this->Node->first_child();
        $_num = 1;
        while ($Item = $this->XMLTools->GetNextElement($Item,'row'))
        {
            if (isset($_upd[$_num]))
            {
                $_node = $Item->first_child();
                if ($_node)
                do
                {
                    $_node->set_attribute('value',$this->XMLTools->EncodeStr($_upd[$_num][$_node->tagname]));
                }
                while ($_node = $_node->next_sibling());
            }
            $_num++;
        }
    }

    function GetConfigStruct()
    {
        switch($this->Type)
        {
            case 'a':
                return $this->GetArrayStruct($this->Node);
            break;
            case 'd':
                return $this->GetDataStruct($this->Node);
            break;
        }
    }

    function GetArrayStruct($_node)
    {
        $_childs = $_node->childNodes;
        $_arr = array();
        for ($i = 0; $i < $_childs->length; $i++)
        {
            $_node = $_childs->item($i);
            $_type = $_node->getAttribute('type');
            switch ($_type)
            {
                case 'a':
                    $_arr[$_node->nodeName] = $this->GetArrayStruct($_node);
                break;
                case 'd':
                    $_arr[$_node->nodeName] = $this->GetDataStruct($_node);
                break;
                case 's':
                    $_arr[$_node->nodeName] = $this->XMLTools->DecodeStr($_node->getAttribute('value'));
                break;
            }
        }
        return $_arr;
    }

    function GetDataStruct($_node)
    {
        $_header = $this->GetHeader($_node);
        $_data = array();
        $_item = $_node->first_child();
        while ($_item = $this->XMLTools->GetNextElement($_item,'row'))
        {
            $_fields = $_item->child_nodes();
            $_arr = array();
            for($i=0;$i<sizeof($_fields);$i++)
            {
                $_type = $_header[$_fields[$i]->tagname]['type'];
                switch ($_type)
                {
                    case 'a':
                        $_arr[$_fields[$i]->tagname] = $this->GetArrayStruct($_fields[$i]);
                    break;
                    case 'd':
                        $_arr[$_fields[$i]->tagname] = $this->GetDataStruct($_fields[$i]);
                    break;
                    case 's':
                        $_arr[$_fields[$i]->tagname] = $this->XMLTools->DecodeStr($_fields[$i]->get_attribute('value'));
                    break;
                }
            }
            $_data[] = $_arr;
        }
        return $_data;
    }
}

?>