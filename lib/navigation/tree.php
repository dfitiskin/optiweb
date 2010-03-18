<?

class CNavigation_Tree
{

    var $Config = null;

    var $Name = null;
    var $Urls = null;
    var $Scopes = null;
        /*
    var $Templs = array(
                        'main'        =>        'tree.tpl'
    );
            */

        var $Tables = null;
    var $DbManager = null;
    var $ActiveParams = null;
    var $ActiveIDS = null;


    function Init()
    {
            $this->Tables = array(
                    'tree'                        =>        'be_tree',
                    'modules'           =>        'be_modules',
                    'module_links'  =>        'be_module_links'
            );
            $this->Tables['tree'] = 'be_tree';
        $this->DbManager = &$this->Kernel->Link('database.manager',true);
    }


    function getActiveNode($_params,$_pid = null)
    {
        $_alias = array_shift($_params);
        if ($_pid === null) $_exp = 'level = 1';
        else $_exp = 'pid = '.$_pid;

            $this->DbManager->Select($this->Tables['tree'],'*',$_exp.' and alias = "'.$_alias.'" and prid='.$this->Kernel->ProfileID);
                $_rec = $this->DbManager->getNextRec();
        if (!$_rec) return false;
        else
        {
                $this->ActiveIDS[] = $_rec['id'];
                if (sizeof($_params)) return $this->getActiveNode($_params,$_rec['id']);
            else return $_rec;
        }
    }

    function getActiveParams($_url_params)
    {
        if ($this->ActiveParams == null)
                $this->ActiveParams = $this->getActiveNode($_url_params);
        return $this->ActiveParams;
    }

    function Execute($_params, $_templs,$_type_params, $_url_params,$_link_url,$_level)
    {
		switch($_params['mode'])
        {
            case 'tree':

                if (isset($this->Kernel->Info['struct']['update']))
                {
                    $_tree_update = $this->Kernel->Info['struct']['update'];
                }
                else
                {
                    $_tree_update = date('y-m-D h:i:s',0);
                }

                $Manager = $this->Kernel->Link('template.manager',true);
                $Menu = $this->Kernel->Link('navigation.menu',true);
                $_hierarhy = $Menu->getFullHierarchy($this->Kernel->Params);

                $_activeids = array();
                for ($i=0;$i<sizeof($_hierarhy);$i++)
                {
                    if (isset($_hierarhy[$i]['id']))
                    $_activeids[] = $_hierarhy[$i]['id'];
                }

                $_root_params = $_hierarhy[$_params['level']-1];

                $_main_params = array(
                    'active_ids'        =>  $_activeids,
                    'alias'             =>  !(isset($_params['base_url']) && $_params['base_url']) && $_params['level'] > 1 ? $_hierarhy[$_params['level']-1]['alias']:null,
                    'url'               =>  isset($_params['base_url']) && $_params['base_url'] ? $_link_url : '/',
                );

                if (isset($_params['navtype']) && !empty($_params['navtype']))
                {
                    $_main_params['menu'][] = $_params['navtype'];
                }

                if (isset($_params['navtype2']) && !empty($_params['navtype2']))
                {
                    $_main_params['menu'][] = $_params['navtype2'];
                }

                $_main_ds = $this->Kernel->Link('dataset.abstract');
                $_main_ds->setParams($_main_params);

                $_ds = $this->Kernel->LinkClass('CMenu_Tree_DataSet');
                $_main_ds->AddChildDS('root',$_ds);

                $_ds->SetRoot($_root_params['id']);
                $_ds->setTables($this->Tables);

                $result = $Manager->Execute($_main_ds, $_templs['main'],$this->Name);
                return $result;
            break;
        }
    }
}

global $Kernel;
$Kernel->LoadLib('database','dataset');

class CMenu_Tree_DataSet extends CDataset_Database
{
    var $PrevItems = array();
    var $NextItems = array();

    var $RootID;
    var $TreeTable;

    function ViewNext()
    {
        $_result = false;
        if ($this->NextItems)
        {
            $_result = true;
        }
        elseif ($this->Current < $this->RecsCount)
        {
            if (sizeof($this->Data))
            {
                $this->NextItems = array_shift($this->Data);
            }
            else
            {
                $this->Params['dstype'] = 'db';
                $this->NextItems = $this->Manager->GetNextRec($this->Resource);
            }
            $_result = true;
        }
        return $_result;
    }

    function Refresh()
    {
        $this->Manager = &$this->Kernel->Link('database.manager',true);
        $_menu_exp = ' AND menu<>""';
        if (isset($this->Parent->Params['menu']))
        {
            $_menu_exp = ' AND menu IN ("'.implode('", "', $this->Parent->Params['menu']).'")';
        }
        $this->Resource = $this->Manager->Select(
            $this->Tables['tree'],
            '*',
            sprintf('pid=%d%s', $this->RootID, $_menu_exp),
            'ORDER BY sort'
        );
        $this->Current = 0;
        $this->RecsCount = $this->Manager->GetNumRows();
    }

    function setRoot($_id)
    {
        $this->RootID = $_id;
        if (!isset($this->Parent->RootID))
        {
            $_alias = $this->Parent->GetParam('alias');
            $this->Params['parent_url'] = $this->Parent->GetParam('url').(($_alias)?$this->Parent->GetParam('alias').'/':'');
        }
        else
        {
            $this->Params['parent_url'] = $this->Parent->GetParam('url');
        }
    }

    function setTables($_tables)
    {
        $this->Tables = $_tables;
    }


    function &GetChildDS($_name)
    {
        $_ds = &parent::GetChildDS($_name);

        if ($_name == 'sublist')
        {

            $_ds = '-1';
            if ($this->Items['link'])
            {
                $DbManager = &$this->Kernel->Link('database.manager',true);
                $DbManager->Select($this->Tables['modules'],'*','id='.$this->Items['link']);
                $_module = $DbManager->getNextRec();

                $Object = &$this->Kernel->Link($_module['alias'].'.viewer',true);
                if (method_exists($Object,'getTreeDS'))
                {
                    $DbManager->Select($this->Tables['module_links'],'*','tid='.$this->Items['id']);
                    $_version = $DbManager->getNextRec();
                    $_version = $_version?$_version['version']:null;
                    $_ds = & $Object->getTreeDS($_version, $this->GetParam('url'));
                    $_ds->Parent = &$this;
                }


            }
            else
            {

                $_ds = &$this->Kernel->LinkClass('CMenu_Tree_DataSet');
                $_ds->Parent = &$this;
                $_ds->setTables($this->Tables);
                $_ds->setRoot($this->Items['id']);

            }
        }

        return $_ds;
    }

    function GetParam($_name)
    {
        
		switch ($_name)
        {
            case 'is_next_active':
                if ($this->ViewNext())
                {
                    /*
                    $_a = $this->getParam('active_ids');
                    $_curr_item = array_pop($_a);

                    return $this->NextItems['id'] === $_curr_item;
                    */
                    return in_array($this->NextItems['id'], $this->getParam('active_ids'));
                }
            break;
            case 'is_prev_active':
                if ($this->PrevItems)
                {
                    /*
                    $_a = $this->getParam('active_ids');
                    $_curr_item = array_pop($_a);
                    return $this->PrevItems['id'] === $_curr_item;
                    */
                    return in_array($this->PrevItems['id'], $this->getParam('active_ids'));
                }
            break;
            case 'is_active' :
                if ($this->Params['is_active']===null)
                {
                    $this->Params['is_active'] = in_array($this->getParam('id'),$this->getParam('active_ids'));
                }
                return $this->Params['is_active'];
            break;

            case 'is_cur_active':
                $_a = $this->getParam('active_ids');
                $_curr_item = array_pop($_a);
                return $this->getParam('id') === $_curr_item;
            break;

            case 'url' :
                return $this->Params['parent_url'].$this->Items['alias'].'/';
            break;

            case 'childs_count':
                $_menu_exp = ' AND menu<>""';
                if (isset($this->Parent->Params['menu']))
                {
                    $_menu_exp = ' AND menu IN ("'.implode('", "', $this->Parent->Params['menu']).'")';
                }
            
                $_count = $this->Manager->GetRecsCount(
                    $this->Tables['tree'],
                    'pid="'.$this->Items['id'].'"' . $_menu_exp
                );
                return $_count;
            break;

            case 'child_url' :
                if (!$this->Params['fchild_alias'])
                {
                    $this->GetParam('next_content');
                }
                return $this->Params['parent_url'].$this->Items['alias'].'/'.$this->Params['fchild_alias'].'/';

            break;

            case 'next_content':
                $this->Manager->Select($this->Tables['tree'],'content,alias','menu <> "" and pid = '.$this->Items['id'],'order by sort limit 1');
                $_rec = $this->Manager->GetNextRec();
                $this->Params['fchild_alias'] = $_rec[1];
                return $_rec[0];
            break;
        }
        return parent::GetParam($_name);
    }

    function Next()
    {
        $this->Params['is_active'] = null;
        $this->Params['fchild_alias'] = null;

        $this->PrevItems = $this->Items;

        if ($this->Current < $this->RecsCount)
        {
            if ($this->NextItems)
            {
                $this->Items = $this->NextItems;
                $this->NextItems = array();
                $this->Current++;
            }
            elseif (sizeof($this->Data))
            {
                $this->Items = array_shift($this->Data);
                $this->Current++;
            }
            else
            {
                $this->Params['dstype'] = 'db';
                $this->Items = $this->Manager->GetNextRec($this->Resource);
                $this->Current++;
            }
            return true;
        }
        return false;
    }

}

class CNavigation_FastTraverseDS extends CMenu_Tree_DataSet
{
    function Refresh()
    {
        $this->Manager = &$this->Kernel->Link('database.manager',true);
                $this->Resource = $this->Manager->Select($this->TreeTable,'*','menu <> "" and terminal <> 1 and type <> 2 and pid = '.$this->RootID,'order by fullname');
                $this->Current = 0;
        $this->RecsCount = $this->Manager->GetNumRows();
    }
}

?>
