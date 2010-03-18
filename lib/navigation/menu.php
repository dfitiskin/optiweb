<?

class CNavigation_Menu
{
    public $Tables = null;

    function Init()
    {
        $this->Tables['tree'] = 'be_tree';
    }

    function getRootNode($_name)
    {
        static $_params = null;
        if (!$_params && isset($this->Kernel->Params[0]))
        {
            $_alias = $this->Kernel->Params[0];
            $DbManager = &$this->Kernel->Link('database.manager',true);
            $DbManager->Select($this->Tables['tree'],'*','prid='.$this->Kernel->ProfileID.' and level=2 and alias="'.$_alias.'"');
            $_params = $DbManager->getNextRec();
        }
        return isset($_params[$_name])?$_params[$_name]:null;
    }

    function getSecNode($_name)
    {
        static $_params = null;
        if (!$_params && isset($this->Kernel->Params[1]))
        {
            $_alias = $this->Kernel->Params[0];
            $_pid = $this->getRootNode('id');
            $DbManager = &$this->Kernel->Link('database.manager',true);
            $DbManager->Select($this->Tables['tree'],'*','prid='.$this->Kernel->ProfileID.' and pid="'.$_pid.'" and alias="'.$this->Kernel->Params[1].'"');
            $_params = $DbManager->getNextRec();
        }
        return isset($_params[$_name])?$_params[$_name]:null;
    }

    function getHierarchy($_url_params)
    {
        static $_data = array();
        if (sizeof($_data)) return $_data;

        $DbManager =  &$this->Kernel->Link('database.manager',true);
        $DbManager->Select(
            $this->Tables['tree'],
            '*',
            'prid='.$this->Kernel->ProfileID.' and level=1'
        );
        $_rec = $DbManager->getNextRec();

        $_data[] = array(
            'url'       =>  $this->Kernel->BaseUrl,
            'name'      =>  $_rec['name'],
			'fullname'  =>  $_rec['fullname'],
            'alias'     =>  $_rec['alias'],
            'content'   =>  1,
            'id'        =>  $_rec['id'],
        );


        $_id = $this->getRootNode('id');

        if ($_id)
        {
            $_name = $this->getRootNode('name');
			$_fullname = $this->getRootNode('fullname');
            $_alias = $this->getRootNode('alias');
            $_content = $this->getRootNode('content');
            $_terminal = $this->getRootNode('terminal');
            $_sort = $this->getRootNode('sort');
            $_menu = $this->getRootNode('menu');
            $_id = $this->getRootNode('id');
            $_pid = $this->getRootNode('pid');
            $_link = $this->getRootNode('link');
            $_type = $this->getRootNode('type');

            $_url = $this->Kernel->BaseUrl.$_alias.'/';

            if ($_content)
            {
                $_data[] = array(
                    'url'       =>  $_url,
                    'name'      =>  $_name,
					'fullname'  =>  $_fullname,
                    'alias'     =>  $_alias,
                    'terminal'  =>  $_terminal,
                    'sort'      =>  $_sort,
                    'content'   =>  1,
                    'id'        =>  $_id,
                    'menu'      =>  $_menu,
                    'pid'       =>  $_pid,
                    'link'      =>  $_link,
                    'type'      =>  $_type,
                );
            }
            else
            {
                $DbManager->Select(
                    $this->Tables['tree'],
                    '*',
                    'pid='.$_id.' and content = 1 ',
                    'order by sort'
                );
                $_rec = $DbManager->getNextRec();

                $_data[] = array(
                    'url'       =>  $_url.$_rec['alias'].'/',
                    'name'      =>  $_name,
					'fullname'  =>  $_fullname,
                    'alias'     =>  $_alias,
                    'terminal'  =>  $_rec['terminal'],
                    'type'      =>  $_rec['type'],
                    'sort'      =>  $_rec['sort'],
                    'menu'      =>  $_rec['menu'],
                    'pid'       =>  $_rec['pid'],
                    'link'      =>  $_rec['link'],
                    'content'   =>  1,
                    'id'        =>  $_id,
                );

            }
            array_shift($_url_params);

            if (sizeof($_url_params))
            {
                $DbManager = &$this->Kernel->Link('database.manager',true);
            }
            $_rec = true;

            while(($_alias = array_shift($_url_params)) && $_rec)
            {
                $DbManager->Select(
                    $this->Tables['tree'],
                    '*',
                    'prid='.$this->Kernel->ProfileID.' and pid='.$_id.' and alias="'.$_alias.'"'
                );
                $_rec = $DbManager->getNextRec();
                if ($_rec)
                {
                    $_id = $_rec['id'];
                    $_url .= $_alias.'/';
                    $_data[] = array(
                        'url'       =>  $_url,
                        'terminal'  =>  $_rec['terminal'],
						'name'      =>  $_rec['name'],
                        'fullname'  =>  $_rec['fullname'],
                        'sort'      =>  $_rec['sort'],
                        'content'   =>  $_rec['content'],
                        'menu'      =>  $_rec['menu'],
                        'id'        =>  $_rec['id'],
                        'pid'       =>  $_rec['pid'],
                        'link'      =>  $_rec['link'],
                        'alias'     =>  $_alias,
                        'type'      =>  $_rec['type'],
                    );
                }
                else
                {
                    break;
                }
            }
        }
        return $_data;
    }

    function getFullHierarchy($_url_params)
    {
        static $_add_data = null;
        $_data = $this->getHierarchy($_url_params);

        global $Page;
        if ($Page->MainObject)
        {
            $Object = &$this->Kernel->Link($Page->MainObject['name'],true);
            if (method_exists($Object,'gethierarchy'))
            {
                if ($_add_data === null)
                {
                    $_add_data = $Object->getHierarchy();
                }
                if ($_add_data)
                {
                    $_data = array_merge($_data,$_add_data);
                }
            }
        }
        return $_data;
    }

    function Execute(&$_params, &$_templs,$_type_params,$_url_params,$_link_url,$_level)
    {
		switch ($_params['mode'])
        {
            case 'hierarchy':
                if (isset($this->Kernel->Info['struct']['update']))
                {
                    $_tree_update = $this->Kernel->Info['struct']['update'];
                }
                else
                {
                    $_tree_update = date('y-m-D h:i:s',0);
                }

                $Manager = $this->Kernel->Link('template.manager',true);
                $_cache_params = $_params;
                $_cache_params['url'] = $this->Kernel->Url;

                $Manager->setCacheParams($this->Name,$_templs['main'],$_tree_update,$_cache_params,'fullhierarchy');

                if (0 &&  $_cache = &$Manager->getCache())
                {
                    return $_cache;
                }
                else
                {
                    $_ds = &$this->Kernel->Link('dataset.abstract');
                    $_hierar_ds = &$this->Kernel->Link('dataset.array');
                    $_data = $this->getFullHierarchy($this->Kernel->Params);
                    if (isset($_params['order']) && $_params['order'] < 0)
                    {
                        $_data = array_reverse($_data);
                    }
                    $_hierar_ds->setData($_data);
                    $_ds->AddChildDS('hierarchy',$_hierar_ds);

                    $result = $Manager->Execute($_ds, $_templs['main'],$this->Name);
                }
                return $result;
            break;
            case 'header':
                $_hierarchy = $this->getFullHierarchy($this->Kernel->Params);

                if ($_params['level'] == -1)
                {
                    return $_hierarchy[sizeof($_hierarchy)-1]['fullname'];
                }

                return isset($_hierarchy[$_params['level']]['fullname'])?$_hierarchy[$_params['level']]['fullname']:null;
            break;
            case 'alias':
                case 'alias':
                    if ($_params['level'] == -1)
                    {
                        if ($this->getSecNode('alias'))
                        {
                            return $this->getSecNode('alias');
                        }
                        return $this->getRootNode('alias');
                    }
                    else
                    {
                        return $this->getRootNode('alias');
                    }
                break;
            break;
            case 'menu':
                if (!$_templs['main']['file'])
                {
                    return null;
                }

                if (isset($this->Kernel->Info['struct']['update']))
                {
                    $_tree_update = $this->Kernel->Info['struct']['update'];
                }
                else
                {
                    $_tree_update = date('y-m-D h:i:s',0);
                }

                $Manager = $this->Kernel->Link('template.manager',true);
                $_cache_params = $_params;

                $_cache_params['url'] = implode('_',$_url_params);
                $_cache_params['current_url'] = ($_params['level'] == sizeof($_url_params))?1:0;

                $Manager->setCacheParams($this->Name,$_templs['main'],$_tree_update,$_cache_params,'menu');

                if (false && $_cache = &$Manager->getCache())
                {
                    return $_cache;
                }
                else
                {
                    $_main_ds = $this->Kernel->Link('dataset.abstract');
                    $_ds = $this->Kernel->LinkClass('CMenu_Linear_DataSet');
                    $_ds->setTable($this->Tables['tree']);

                    $_main_ds->AddChildDS('menu',$_ds);

                    $_hierarchy = $this->getFullHierarchy($this->Kernel->Params);

                    if ($_params['level'] == -1)
                    {
                        $_params['level'] = sizeof($_url_params) + 2 + $_level;
                        if (!isset($_hierarchy[$_params['level']-2]['id'])) return '';
                    }


                    if (isset($_hierarchy[$_params['level']-2]['type']) && $_hierarchy[$_params['level']-2]['type'] == 1)
                    {
                        $DbManager = &$this->Kernel->Link('database.manager',true);
                        $DbManager->Select(
                           'be_modules',
                            '*',
                            'id='.$_hierarchy[$_params['level']-2]['link']
                        );
                        $_module = $DbManager->getNextRec();

                        $Object = &$this->Kernel->Link($_module['alias'].'.viewer',true);
                        if (method_exists($Object,'getTreeDS'))
                        {
                            $DbManager->Select(
                                'be_module_links',
                                '*',
                                'tid='.$_hierarchy[$_params['level']-2]['id']
                            );
                            $_version = $DbManager->getNextRec();
                            $_version = $_version?$_version['version']:null;
                            $_ds = & $Object->getTreeDS($_version, $_hierarchy[$_params['level']-2]['url']);
                        }
                        $_result = 'din menu';
                    }
                    elseif (isset($_hierarchy[$_params['level']-2]['id']))
                    {
                        $_id = $_hierarchy[$_params['level']-2]['id'];

                        $_menu_exp = '"'.$_params['navtype'].'"';
                        if (isset($_params['navtype1']) && $_params['navtype1'])
                        {
                            $_menu_exp .= ',"'.$_params['navtype1'].'"';
                        }

                        if (isset($_params['navtype2']) && $_params['navtype2'])
                        {
                            $_menu_exp .= ',"'.$_params['navtype2'].'"';
                        }

                        $_menu_exp = ' menu in ('.$_menu_exp.') ';


                        $_ds->SetQuery(
                            $this->Tables['tree'],
                            '*',
                            'prid='.$this->Kernel->ProfileID.' and pid = '.$_id.' and level="'.$_params['level'].'" and '.$_menu_exp,
                            'order by sort'
                        );

                        $_url = implode('/',array_slice($this->Kernel->Params,0,$_params['level']-2));
                        if ($_url) $_url .= '/';
                        $_url = '/'.$_url;

                        $_ds_params = array(
                            'active'      =>  isset($_hierarchy[$_params['level']-1]['alias'])?$_hierarchy[$_params['level']-1]['alias']:null,
                            'url'         =>  $_url,
                            'current_url' =>  $this->Kernel->Url,
                            'view_level'  =>  $_params['level']-$_level,
                            'curr_level'  =>  sizeof($_url_params),
                        );
                        $_ds->SetParams($_ds_params);
                        $_result = $Manager->Execute($_main_ds, $_templs['main'],$this->Name);
                    }
                    else
                    {
                        $_result = 'no content';
                    }
                }
                return $_result;
            break;
            case 'info':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_hierarchy = $this->getFullHierarchy($this->Kernel->Params);
                $_index = sizeof($_url_params)+$_level;
                $_menu_exitst = isset($_hierarchy[$_index]['id']) && !$_hierarchy[$_index]['terminal'] && ($_hierarchy[$_index]['type'] <> 1);
                if ($_menu_exitst)
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select($this->Tables['tree'],'*','pid='.$_hierarchy[$_index]['id'].' and content = 1 and menu ="'.$_hierarchy[$_index]['menu'].'"','order by sort');
                    $_rec = $DbManager->getNextRec();

                    if (!$_rec)
                    {
                        $_menu_exitst = false;
                    }
                }

                $_level = isset($_params['level'])&&($_params['level']>1)?$_params['level']:1;
                $_count = $_level + sizeof($_url_params) - 1;

                $_ds_params = $_GET;
                $_ds_params['menu_exists'] = $_menu_exitst;
                $_ds_params['curr_level']  = sizeof($_url_params);
                $_ds_params['root_alias']  = isset($_url_params[0])?$_url_params[0]:null;

                $_ds_params['head_active'] = $_level<(sizeof($_hierarchy)-1);
                $_ds_params['head_name']   = $_hierarchy[$_level]['fullname'];
                $_ds_params['head_url']    = $_hierarchy[$_level]['url'];
                $_ds_params['level']       = $_level;
                $_ds_params['curr_level']  = sizeof($_url_params)+$_level;
                $_ds_params['notterm']     = isset($_hierarchy[$_count]['id']) && !$_hierarchy[$_count]['terminal'];
                $_ds_params['link_url']    = $_link_url;


                $_ds->setParams($_ds_params);

                $_hierar_ds = &$this->Kernel->Link('dataset.array');
                $_hierar_ds->setData($_hierarchy);
                $_ds->AddChildDS('hierarchy',$_hierar_ds);


                $Manager = $this->Kernel->Link('template.manager',true);
                $result = &$Manager->Execute($_ds, $_templs['main'],$this->Name);
                return $result;
            break;
            case 'nextnode':
                $_ds = &$this->Kernel->Link('dataset.abstract');

               $_hierarchy = $this->getFullHierarchy($this->Kernel->Params);
               $_level = sizeof($this->Kernel->Params);
               $_node = $_hierarchy[$_level];
                if (!isset($_node['object']))
                {
                    $DbManager = &$this->Kernel->Link('mysql.manager',true);
                    $DbManager->Select($this->Tables['tree'],'*','pid= '.$_node['pid'].' and sort>'.$_node['sort'].' and menu="'.$_node['menu'].'"');
                    $_rec = $DbManager->getNextRec();

                    if ($_rec)
                    {
                        $_prev_node = $_hierarchy[$_level-1];
                        $_ds_params = array(
                            'islast'        =>  0,
                            'curr_level'    =>  sizeof($_url_params),
                            'url'          =>  $_prev_node['url'].$_rec['alias'].'/'
                        );
                    }
                    else
                    {
                        $_ds_params = array(
                            'islast'    =>  1
                        );
                    }

                    $_ds->setParams($_ds_params);

                    $Manager = $this->Kernel->Link('template.manager',true);
                    $result = &$Manager->Execute($_ds, $_templs['main'],$this->Name);
                    return $result;
                }
            break;
            case 'pageheader':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_hierarchy = $this->getFullHierarchy($this->Kernel->Params);


                $_level = isset($_params['level'])?$_params['level']:1;
                $_count = $_level + sizeof($_url_params) - 1;

                $_ds_params = array(
                    'head_active' => $_level<sizeof($_hierarchy)-1,
                    'head_name'   => $_hierarchy[$_level]['fullname'],
                    'head_url'    => $_hierarchy[$_level]['url'],
                    'level'       => $_level,
                    'curr_level'  => sizeof($_url_params)+$_level,
                    'notterm'     => isset($_hierarchy[$_count]['id']) && !$_hierarchy[$_count]['terminal'],
                    'link_url'    => $_link_url
                );

                $_ds->setParams($_ds_params);

                $_hierar_ds = &$this->Kernel->Link('dataset.array');
                $_hierar_ds->setData($_hierarchy);
                $_ds->AddChildDS('hierarchy',$_hierar_ds);



                $Manager = $this->Kernel->Link('template.manager',true);
                $result = &$Manager->Execute($_ds, $_templs['main'],$this->Name);
                return $result;
            break;
        }
    }
}

global $Kernel;
$Kernel->LoadLib('database','dataset');

class CMenu_Linear_DataSet extends CDataset_Database
{
    public $Table = null;

    function setTable($_table)
    {
        $this->Table = $_table;
    }

    function GetParam($_name)
    {
        switch ($_name)
        {
            case 'isactive' :
                $_url = $this->getParam('url');
                if (($this->Kernel->Url == $_url) || (strpos($this->Kernel->Url,$_url) === 0))
                {
                    return true;
                }
            break;
            case 'url' :
                if ($this->Items['type'] == 2)
                {
                    return $this->Items['url'];
                }

                if (!$this->Items['content'])
                {
                    $DbManager =  &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select($this->Table,'*','pid='.$this->Items['id'].' and content=1 and menu <> ""','order by sort');
                    $_rec = $DbManager->getNextRec();
                    if ($_rec)
                    {
                        return $this->Params['url'].$this->Items['alias'].'/'.$_rec['alias'].'/';
                    }
                }
                return $this->Params['url'].$this->Items['alias'].'/';

                break;
        }
        return parent::GetParam($_name);
    }

    function Next()
    {
        if ($this->Current<$this->RecsCount)
        {
            $this->Items = $this->Manager->GetNextRec($this->Resource);
            if ($this->Items['alias'])
            {
                $this->Current++;
            }
            return true;
        }
        return false;
    }
}

?>