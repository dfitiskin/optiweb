<?
class CBackend_Tree
{
    public $Profile = null;
    public $FManager = null;
    public $DbManager = null;
    public $User = null;
    public $Name = null;

    public $Tables = null;
    public $TreeDir = null;

    public $Config = null;
    public $BlankXMLNode = null;

    public $OpenedModes = array();
    public $NodeParams = null;
    public $NodeError = null;
    public $Nodes = null;
    public $LastCreatedNode = array();
    
    private $modes = array();
    private $modesAlias = array();
    private $moduleParams = null; 
    
    private $blockName = array();

    function Init()
    {
        $this->blockName = array(
		    'content', 
		    'content_after',
			'content_after2',
			'content_after3',
			'content_after4'
		);
        
	    $this->Tables =  array(
                'tree'                =>        'be_tree',
            'modules'        =>        'be_modules',
            'links'                =>        'be_module_links',
        );

        global $BackendManager;
        $this->Manager = &$BackendManager;

            global $User;
            $this->User = &$User;
        $this->ProfileAlias = $this->User->GetCurrentProfile('alias');
        $this->ProfileID = $this->User->GetCurrentProfile('id');
                $this->FManager = &$this->Kernel->Link('services.filemanager');
        $this->DbManager = &$this->Kernel->Link('database.manager',true);
        $this->Name = 'tree';
        $this->TreeDir = PROFILES_DIR . $User->GetCurrentProfile('alias') . '/' . TREE_DIR . '/';
        $this->BlocksDir = PROFILES_DIR . $User->GetCurrentProfile('alias') . '/' . BLOCKS_DIR . '/';
        $this->BlankXMLNode = '<?xml version="1.0" encoding="windows-1251"?><node><ruleset></ruleset><childs><ruleset></ruleset></childs></node>';
        $this->OpenedModes = $User->getParam('struct_nodes');

        if (!$this->OpenedModes) $this->OpenedModes = array();
                if (isset($_COOKIE['c']))
        {
                $_closed = explode(',',$_COOKIE['c']);
            for ($i=0;$i<sizeof($_closed);$i++)
                    unset($this->OpenedModes[$_closed[$i]]);
        }

                if (isset($_COOKIE['o']))
        {
                $_opened = explode(',',$_COOKIE['o']);
            for ($i=0;$i<sizeof($_opened);$i++)
                    $this->OpenedModes[$_opened[$i]] = 1;
        }

        $User->setParam('struct_nodes',$this->OpenedModes);
    }

    function SetCurrentProfile($_profile)
    {
                $this->Profile = $_profile;
                $this->TreeDir = PROFILES_DIR . $this->GetCurrentProfile('alias') . '/' . TREE_DIR . '/';
    }

    function GetCurrentProfile($_id='id')
    {
                if ($this->Profile != null)
        {
                return $this->Profile[$_id];
        }
        else
        {
            return $this->User->GetCurrentProfile($_id);
        }
    }

    function GetNodeFolder($_parent)
    {
            if (!$_parent) return '';
            $this->DbManager->Select($this->Tables['tree'],'*','id='.$_parent);
        $_rec = $this->DbManager->GetNextRec();
        return $_rec&&($_rec['pid']!='0')?$this->GetNodeFolder($_rec['pid']).$_rec['alias'].'/':'';
    }

    function setNode($_params,$_pid = 0)
    {
        static $_profile = null;
        if (!$_profile) $_profile = $this->User->GetCurrentProfile();

        if (!sizeof($_params))
        {
            $this->DbManager->Select($this->Tables['tree'],'*','pid = 0 and prid ='.$_profile);
            $_rec = $this->DbManager->GetNextRec();
            $this->NodeParams = $_rec;
        }
        if ($_pid)
        {
            $_alias = array_shift($_params);
                $this->DbManager->Select($this->Tables['tree'],'*','pid = '.$_pid.' and alias = "'.$_alias.'" and prid ='.$_profile);
            }
            else
            {
                $this->DbManager->Select($this->Tables['tree'],'*','pid = '.$_pid.' and prid ='.$_profile);
        }
        $_rec = $this->DbManager->GetNextRec();
        if (!$_rec)
        {
                $this->NodeError = array(
                    'params'    =>  $_params,
                    'level'                =>        sizeof($_params)+1
                );
                return false;
        }
        $this->Nodes[] = $_rec;
        if (sizeof($_params))
        {
                        return $this->setNode($_params,$_rec['id']);
        }
        else
        {
            $this->NodeParams = $_rec;
        }
        return true;
    }

    function isNodeSetted()
    {
                return ($this->NodeParams)?true:false;
    }

    function getNodeParam($_name)
    {
                return $this->NodeParams[$_name];
    }

    function updStructInfo()
    {
            $_arr = array(
                'update'        =>        time(),
        );
            $this->Manager->setInfoValue('struct',$_arr);
    }

    function updNodeParams($_params,$_id)
    {
            $_params['updatetime'] = 'now()';
                $this->DbManager->UpdateValues($this->Tables['tree'],$_params,'id='.$_id);
        $this->updStructInfo();
    }

    function DeleteNode($_id)
    {

            $this->DbManager->Delete($this->Tables['links'],'tid='.$_id);
                $this->DbManager->Select($this->Tables['tree'],'pid,sort','id='.$_id);
                $_rec = $this->DbManager->GetNextRec();
        if ($this->DbManager->GetRecsCount($this->Tables['tree'],'id='.$_rec['pid'])==1)
        {

                        $_path = $this->TreeDir . $this->GetNodeFolder($_id);
                $this->FManager->DeleteFolder($_path);
            $_path = $this->BlocksDir . $this->GetNodeFolder($_id);
            $this->FManager->DeleteFolder($_path);
                        $this->DbManager->Update($this->Tables['tree'],'updatetime = now(), sort = sort-1','pid='.$_rec['pid'].' and sort>'.$_rec['sort']);
                $this->DeleteSubTree($_id);

        }
        $this->DbManager->Select($this->Tables['tree'],'count(*) as count','pid='.$_rec['pid']);
        $_count = $this->DbManager->GetNextRec();
        if (!$_count['count'])
        {
                $_items = array('terminal' => 1);
                $this->DbManager->UpdateValues($this->Tables['tree'],$_items,'id='.$_rec['pid']);
        }
        $this->updStructInfo();
    }

    function DeleteSubTree($_id)
    {
                $_res = $this->DbManager->Select($this->Tables['tree'],'id','pid='.$_id);
        while($_rec = $this->DbManager->GetNextRec($_res))
                        $this->DeleteSubTree($_rec['id']);
        $this->DbManager->Delete($this->Tables['tree'],'id='.$_id);
    }
    
    
	function modesToXml($modes)
	{
	    if ('modes' != key($modes))
	    {
	        $result = '';
	        foreach($modes as $modeName => $mode)
	        {
		        $this->modesAlias[$modeName] = $mode;
	            $result .= '<node alias="'. $modeName .'" name="'.$this->moduleParams->nodeName[$modeName].'"';
			    if (isset($mode['subtree']) && $mode['subtree'])
			    {
			        $result .= '>'. $this->modesToXml($mode['subtree']) . '</node>';
			    }
			    else
			    {
			        $result .= ' />';
			    }
	        }
	    }
	    else
	    {
	        $result = '<?xml version="1.0" encoding="windows-1251"?><link';
	        if (isset($modes['subtree']))
	        {
	            $result .= '>'. $this->modesToXml($modes['subtree']) .'</link>'; 
	        }
	        else
	        {
	            $result .= ' />';
	        }
	         
	    }
		return $result;
	}

	function getxmlNode($module, $modes, $main = false)
	{
	    $block = '';
	    if (isset($modes['modes'][0]))
        {
            foreach ($modes['modes'] as $i => $mode)
            {
                $block .= ''	            
	            . '<block name="'.$this->blockName[$i].'" descr="'. $this->modes[$mode['name']] .'" source="d" object="'. $module['alias'] .'">'
				. 	'<param name="mode" value="'. $mode['name'] .'" />'
				. 	'<template name="main" file="'. $mode['name'] .'_main.tpl"/>'
	            . '</block>';
            }
        }
        $xmlNode = '' 
		. '<?xml version="1.0" encoding="windows-1251"?>'
		. '<node>'
		. 	'<ruleset>'
	    .       $block
		. 	'</ruleset>'
		. 	'<childs>'
		. 		'<ruleset>';
		if ($main)
		{
		    $xmlNode .= '<mainobject name="'. $module['alias'] .'.viewer" />';
        }
        $xmlNode .= ''
		. 		'</ruleset>'
		. 	'</childs>'
		. '</node>';
		return $xmlNode;      
	}
	
	function setTplFile($module, $modes)
	{
	    if (isset($modes['modes'][0]))
        {
		    foreach ($modes['modes'] as $i => $mode)
            {
	            $fileTpl = PROFILES_DIR . '/' . TEMPLS_DIR . '/' . $module['alias'] . '/' . $mode['name'] . '_main.tpl';
			    if (! file_exists($fileTpl))
		        {
		            file_put_contents($fileTpl, '');    
		        }
            }
        }
	}
	
    function linkModule($params)
    {
        $result = 0;
        
        $dbManager = & $this->Kernel->link('Database.Manager', true);
        $module = $dbManager->getNextRec(
            $dbManager->select(
                $this->Tables['modules'], 
				'*', 
                sprintf(
					'id=%d', 
                    $params['module']
                )
            )
        );
        if (! $module)
        {
            $result = 1;
        }
        else
        {
            $fileManager = $this->Kernel->link('Services.Filemanager');
            $fileManager->createFolder(MODULES_DIR . $module['alias'].'/link/');
        	$filePath = MODULES_DIR . $module['alias'] . '/link/tree.xml';
            $fl = false;
            
            
            if (! file_exists($filePath))
            {
                $this->moduleParams = $this->Kernel->Link($module['alias'] .'.Params', true);
                if (count($this->moduleParams->Modes))
                {
                    foreach ($this->moduleParams->Modes as $i => $mode)
                    {
                        $this->modes[$mode['name']] = $mode['desc'];
                    }
                }
                $xmlFile = $this->modesToXml($this->moduleParams->ModesTree);
                file_put_contents($filePath, $xmlFile);			        
            } 
                      
            $xmlTool = $this->Kernel->Link('System.XmlTools', true);
            $doc = $xmlTool->openDomDoc($filePath);
            $link = $xmlTool->getChildNodeByTagName($doc, 'link');

            $rootPath = MODULES_DIR. $module['alias'] . '/link/nodetree/';
            
            if (! file_exists($rootPath . NODE_FILE))
            {
                $block = '';
		        $fileManager->createFolder($rootPath);
		        $xmlNode = $this->getxmlNode($module, $this->moduleParams->ModesTree, true);
		        $this->setTplFile($module, $this->moduleParams->ModesTree);
		        file_put_contents($rootPath . NODE_FILE, $xmlNode);
            }
            
            $xml = $this->Kernel->readFile($rootPath . NODE_FILE);
            
            $id = $this->getNodeParam('id');

            $params['content'] = 1;
            $params['type'] = 1;
            $nodeParams =  array(
				'xml'    =>    $xml,
            );
	            
	            $fl = $this->createNode($params, $nodeParams, $id);
            
            if (! $fl)
            {
                $result = 2;    
            }
            else
            {
				$rootId = $this->LastCreatedNode['id'];
				$items = array(
			        'prid'    =>    $this->ProfileID,
			        'tid'     =>    $rootId,
			        'mid'     =>    $params['module']
				);
				
				$dbManager->insertValues(
				    $this->Tables['links'],
				    $items
                );
                
                $fl = $this->recurseCreateNodes(
                    $link, 
                    $module, 
                    $rootPath, 
                    $this->LastCreatedNode['id']
                );

                if (! $fl)
                {
                    $this->deleteSubTree($rootId);
                    $result = 3;
                }
            }
        }
        return $result;
    }
    
    function recurseCreateNodes($node, $paramsModule, $path, $pid)
    {
        $result = true;
        
        $subNodes = $node->childNodes;
        $xmlTool = & $this->Kernel->link('System.XmlTools', true);
        
        for ($i = 0; $subNodes && $i < $subNodes->length; $i++)
        {
            $curNode = $subNodes->item($i);
            
            $alias = $xmlTool->getNodeAttribute($curNode, 'alias');
                
            $paramsName = $xmlTool->getNodeAttribute($curNode, 'name');
            $paramsFullname = $xmlTool->getNodeAttribute($curNode, 'fullname');
            $paramsFullname =  $paramsFullname ? $paramsFullname : $paramsName;
            
            $params = array(
                'alias'       =>        $alias,
                'name'        =>        $paramsName,
                'fullname'    =>        $paramsFullname,
                'module'      =>        $paramsModule['id'],
                'content'     =>        1,
                'type'        =>        1,
                'menu'        =>        ''
            );

            if (! file_exists($path . $alias . '/' . NODE_FILE))
            {
                $fileManager = $this->Kernel->link('Services.Filemanager');
		        $fileManager->createFolder($path . $alias);
		        $xmlNode = $this->getxmlNode($paramsModule, $this->modesAlias[$alias]);
		        $this->setTplFile($paramsModule, $this->modesAlias[$alias]);
                file_put_contents($path . $alias . '/' . NODE_FILE, $xmlNode);
            }
            
            $xml = $this->Kernel->readFile($path . $alias . '/' . NODE_FILE);
            if (! $xml)
            {
                $result = false; 
            }
            else
            {
	            $nodeParams = array(
					'xml' => $xml
	            );
	            $fl = $this->createNode($params, $nodeParams, $pid);
	            if (! $fl)
	            {
	                $result = false;
	            }
	            else
	            {
	                $fl = $this->recurseCreateNodes(
                        $curNode, 
                        $paramsModule, 
                        $path . $alias . '/', 
                        $this->LastCreatedNode['id']
                    );
                    if (! $fl)
                    {
                        $result = false;
                    }
	            }
            }
        }
        return $result;
    }

    function CreateNode($_node , $_params = null, $_parent = '0')
    {
        $this->DbManager->Select($this->Tables['tree'],'id','alias = "'.$_node['alias'].'" and pid='.$_parent.' and prid = "'.$this->GetCurrentProfile().'"');
        $_id = $this->DbManager->GetNextRec();

        if ($_id) return false;

                $this->DbManager->Select($this->Tables['tree'],'max(sort) as max','pid='.$_parent);
        $_rec = $this->DbManager->GetNextRec();

        if (!$_rec) $_rec['max'] = 0;
        $this->DbManager->Select($this->Tables['tree'],'level','id='.$_parent);
        $_level = $this->DbManager->GetNextRec();
        $_level = $_level['level'];

        if (!isset($_node['type'])) $_node['type'] = 0;

        $_items = array(
                'alias'                =>         $_node['alias'],
            'prid'                =>  $this->GetCurrentProfile(),
            'pid'                =>        $_parent,
            'name'                =>  $_node['name'],
            'fullname'        =>  $_node['fullname'],
            'sort'                =>        $_rec['max']+1,
            'terminal'        =>        1,
            'type'                =>        $_node['type'],
            'level'                =>        $_level+1,
            'updatetime'=>         'now()',
            'content'        =>        isset($_node['content'])?$_node['content']:0,
            'menu'                =>  isset($_node['menu'])?$_node['menu']:'',
            'link'                =>        isset($_node['module'])?$_node['module']:null
        );

                $this->DbManager->InsertValues($this->Tables['tree'],$_items);
        $_id = $this->DbManager->GetLastId();
        $this->LastCreatedNode = $_items;
        $this->LastCreatedNode['id'] = $_id;
        if ($_parent)
        {
                $_items = array('terminal' => 0);
                $this->DbManager->UpdateValues($this->Tables['tree'],$_items,'id='.$_parent);
        }
        if ($_node['type'] != 2)
        {
                $_path = $this->TreeDir . $this->GetNodeFolder($_id);
                $_path .= NODE_FILE;

                $_data = $_params['xml']?$_params['xml']:$this->BlankXMLNode;
                $this->FManager->WriteFile($_path,$_data);
        }
        $this->updStructInfo();
        return true;
    }

    function UpNode($_id)
    {
            $this->DbManager->Select($this->Tables['tree'],'id,pid,sort','id='.$_id);
        $_rec = $this->DbManager->GetNextRec();
        if ($_rec['sort']>1)
        {
                $_pre_sort = $_rec['sort']-1;
                        $this->DbManager->Update($this->Tables['tree'],'updatetime = now(), sort='.$_rec['sort'],'pid='.$_rec['pid'].' and sort ='.$_pre_sort);
            $this->DbManager->Update($this->Tables['tree'],'updatetime = now(), sort='.$_pre_sort,'id='.$_id);
            $this->updStructInfo();
        }
    }

    function DownNode($_id)
    {
            $this->DbManager->Select($this->Tables['tree'],'id,pid,sort','id='.$_id);
        $_rec = $this->DbManager->GetNextRec();
        $this->DbManager->Select($this->Tables['tree'],'max(sort) as max','pid='.$_rec['pid']);
        $_rec2 = $this->DbManager->GetNextRec();
        if ($_rec['sort']<$_rec2['max'])
        {
                $_post_sort = $_rec['sort']+1;
                        $this->DbManager->Update($this->Tables['tree'],'updatetime = now(), sort='.$_rec['sort'],'pid='.$_rec['pid'].' and sort ='.$_post_sort);
            $this->DbManager->Update($this->Tables['tree'],'updatetime = now(), sort='.$_post_sort,'id='.$_id);
            $this->updStructInfo();
        }
    }

    function &GetRootDS()
    {
            $_ds_main = $this->Kernel->Link('dataset.abstract');

        $_ds = $this->Kernel->LinkClass('CBackend_Tree_Dataset');
                $_ds->SetTreeTable($this->Tables['tree']);
                $_ds->SetProfile($this->GetCurrentProfile());
        $_ds->SetOpenedModes($this->OpenedModes);
        $_ds->SetRoot();
        $_ds_main->AddChildDS('root',$_ds);
        return  $_ds_main;
    }
}

global $Kernel;
$Kernel->LoadLib('database','dataset');

class CBackend_Tree_Dataset extends cdataset_database
{
    public $Profile = null;
    public $Table = null;
        public $Root = '0';
    public $OpenedModes = null;

    function CBackend_Tree_Dataset()
    {
                $this->ParentId = 0;
    }


    function SetTreeTable($_table)
    {
            $this->Table = $_table;
    }

    function SetRoot()
    {
                $this->Root = 1;
    }

    function SetProfile($_id)
    {
            $this->Profile = $_id;
    }

    function Refresh()
    {
            $_pid = ($this->Root)?0:$this->Parent->Items['id'];
            $this->SetQuery($this->Table,'*','prid='.$this->Profile.' and pid='.$_pid,'order by sort');
        parent::Refresh();
    }

    function SetOpenedModes(&$_arr)
    {
                $this->OpenedModes = &$_arr;
    }

    function GetChildDS($_name)
    {
        $_ds = parent::GetChildDS($_name);
        if ($_name == 'sublist' && $_ds == null)
        {
                if ($this->Items)
            {
                    $_ds = $this->Kernel->LinkClass('CBackend_Tree_Dataset');
                    $_ds->SetTreeTable($this->Table);
                    $_ds->SetProfile($this->Profile);
                $_ds->SetOpenedModes($this->OpenedModes);
                $this->AddChildDS('sublist',$_ds);
            }
            else
            {
                                $_ds = $this->Kernel->Link('dataset.abstract');
            }
        }

            return $_ds;
    }

    function GetParam($_name)
    {
            $_result = null;
        if ($_name == '_opened' && $this->OpenedModes != null)
        {
                $_result = isset($this->OpenedModes[$this->Items['id']]) ? $this->OpenedModes[$this->Items['id']] : false;
        }
        elseif ($_name == '_url')
        {
            if (!isset($this->Items['_url']))
            {
                $this->Items['_url'] = $this->Parent->GetParam('_url').$this->Items['alias'].'/';
            }
                $_result = $this->Root ? '' : $this->Items['_url'];
        }
        else
        {
                $_result = parent::GetParam($_name);
        }
        return $_result;
    }

    function Next()
    {
            if ($this->Current<$this->RecsCount)
        {
            $this->Items = $this->Manager->GetNextRec($this->Resource);
                        $this->Current++;
            $this->Out = 1;
            return true;
        }

        return false;
    }
 }
?>
