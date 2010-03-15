<?php

class CBackend_Blocks
{
    public $User;
    public $Name;
    public $Tables;

    public $ProfileAlias;
    public $FormValues;
    public $WarningsDS = '-1';



    function Init()
    {
        $this->Tables = array(
            'types'     =>  'be_navigation',
            'modules'   =>  'be_modules',
            'versions'  =>  'be_module_versions',
            'links'     =>  'be_module_links',
        );
        $this->FormValues = array();
        global $User;
        $this->User = &$User;
        $this->ProfileAlias = $this->User->GetCurrentProfile('alias');
        $this->ProfileID = $this->User->GetCurrentProfile('id');
        $this->Name = 'struct';
        $this->Tree = &$this->Kernel->Link('backend.tree',true);
    }

    function Process($_url_params)
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
           isset($_POST['action']) && isset($_POST['mode']))
        {
            $_flush_cache = true;
            switch ($_POST['mode'])
            {
                case 'types':
                    $this->ModifyTypes($_POST,$_url_params);
                break;

                case 'page_edit':
                    $this->ModifyPage($_POST,$_url_params);
                break;
                case 'module':
                    $this->ModifyModule($_POST,$_url_params);
                break;
                case 'node_manage':
                    $this->ModifyNodeParams($_POST,$_url_params);
                    $_flush_cache = false;
                break;
                case 'blocks_manage':
                    $this->ModifyBlocksList($_POST,$_url_params);
                break;
                case 'block_edit':
                    $this->ModifyBlock($_POST,$_url_params);
                    array_shift($_url_params);
                    array_shift($_url_params);
                break;
                case 'block_edit_dinamic':
                    $this->ModifyDinamicBlock($_POST,$_url_params);
                    array_shift($_url_params);
                    array_shift($_url_params);
                break;
                case 'block_edit_template':
                    $this->ModifyTemplateBlock($_POST,$_url_params);
                    array_shift($_url_params);
                    array_shift($_url_params);
                break;
            }
            if ($_flush_cache)
            {
                    $Cacher = &$this->Kernel->Link('system.cacher');
                $Cacher->setProfile($this->ProfileAlias);
                $Cacher->clearTreeCache($_url_params);
            }
        }
    }

    function getLinkRoot($_url_params)
    {
        $Page = &$this->Kernel->Link('system.page');
        $Page->SetProfile($this->ProfileAlias);
        $Page->SetParams($_url_params);
        $Page->Parse();
        return array_slice( $_url_params,0,$Page->MainObject['level']);
    }


    function ModifyTypes($_params,$_url_params)
    {

     switch($_params['action'])
        {
                    case 'add':
                $_add = $_params['add'];

                $Fitler = &$this->Kernel->Link('services.filter',true);
                $_ruls = array(
                    'alias'     =>  'sts;dnc',
                    'name'      =>  'sts',
                );
                $Fitler->FiltValues($_add,$_ruls);

                $Checker = &$this->Kernel->Link('services.checker',true);
                $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'add_types');
                $_fl = $Checker->VerifyValues($_add,$_ruls);

                if ($_fl)
                {

                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select($this->Tables['types'],'id',
                            'prid="'.$this->ProfileID.'" and alias="'.$_add['alias'].'"'
                    );

                    $_rec = $DbManager->getNextRec();

                    if ($_rec)
                    {

                        $this->FormValues = $_add;
                        $Checker->addMessage($_ruls['_other']['alias_exists']);
                        $this->WarningsDS = $Checker->GetWarningDS($_ruls);

                    }
                    else
                    {
                            $_items = array(
                                'alias'    =>  $_add['alias'],
                                'name'      =>  $_add['name'],
                                'prid'     =>  $this->ProfileID,
                            );
                            $DbManager->InsertValues($this->Tables['types'],$_items);
                    }

                }else{
                    $this->FormValues = $_add;
                    $this->WarningsDS = $Checker->GetWarningDS($_ruls);
                }
                break;
            case 'del':
                    if (isset($_params['del']))
                {
                        $_del = $_params['del'];
                        $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->DeleteValues($this->Tables['types'],'id',$_del);
                       }
            break;
        }

    }

    //--------------------------------------------------------------------------
    //  Обновление параметров модуля
    //--------------------------------------------------------------------------
    function ModifyModule($_params,$_url_params)
    {
        switch ($_params['action'])
        {
            case 'upd':

                $pathModule = $this->updNodeParams($_params['nodeparams'],$_params['id']);

//              $_link_params = array_slice($_url_params,0,$_map['mainobject']['level']);

                $TreeNode = &$this->Kernel->Link('backend.treenode');
                $TreeNode->setProfile($this->ProfileAlias);

                $TreeNode->setPath($this->getLinkRoot($_url_params));
                $TreeNode->setNode();
                if (isset($_params['version']))
                        $TreeNode->setMainObjectVersion($_params['version']);
                if (isset($_params['module']) && sizeof($_params['module']))
                {
                    foreach ($_params['module'] as $k=>$v)
                    {
                        $TreeNode->updMainObjectParam($k,$v);
                    }
                }
                $TreeNode->Save();



                $Parser = &$this->Kernel->Link('system.parser');
                $Parser->Execute($this->ProfileAlias,$_url_params);
                $_map = $Parser->GetMap();
        //                Dump($_map);

                $_alias = explode('.',$_map['mainobject']['name']);
                $_alias = $_alias[0];

                $TreeNode2 = &$this->Kernel->Link('backend.treenode');
                $TreeNode2->setProfile($this->ProfileAlias);
                $TreeNode2->setPath($_url_params);
                $TreeNode2->setNode();

                $TreeNode->setPath($_url_params);
                $TreeNode->setNode();

                $_object = &$this->Kernel->Link($_alias.'.params');

                $_link_params = array_slice($_url_params,$_map['mainobject']['level']);
                $_tree_params = $_object->getTreeModes($_link_params);

                foreach($_map['blocks'] as $k=>$v)
                {
                    if ($v['source'] == 'd')
                    {
                        for ($i=0;$i<sizeof($_tree_params);$i++)
                            if (!$_tree_params[$i]['block'] && $_tree_params[$i]['scope'] == $v['scope'] &&
                                $_tree_params[$i]['name'] == $v['params']['mode'] )
                            {
                                $_tree_params[$i]['block'] = $v['name'];
                                $_tree_params[$i]['templs'] = $v['template'];
                                $_tree_params[$i]['params'] = $v['params'];
                                break;
                            }
                    }
                }

                if (isset($_params['modes']))
                {
                    $_modes = $_params['modes'];
                    for($i=0;$i<sizeof($_tree_params);$i++)
                    {
                        //Dump($_tree_params[$i]);
                        $_mode = $_modes[$_tree_params[$i]['name']];
                        if (!$_tree_params[$i]['block'] && $_mode['block'])
                        {
                            $TreeNode->findBlock($_mode['block'],$_tree_params[$i]['scope']);
                            if (!$TreeNode->isBlockExists())
                            {
                                $_tree_params[$i]['block'] = $_mode['block'];
                                $TreeNode->newBlock();
                                $TreeNode->setBlockScope($_tree_params[$i]['scope']);
                                $TreeNode->setBlockName($_mode['block']);
                                $TreeNode->setBlockSource('d');
                                $TreeNode->setBlockObject($_alias.'.viewer');
                                $_mode['templs'] = $_object->GetTemplates($_tree_params[$i]['name']);
                                $_mode['params'] = $_object->getParams($_tree_params[$i]['name']);
                                for ($j=0;$j<sizeof($_mode['templs']);$j++)
                                    $TreeNode->setBlockTemplate($_mode['templs'][$j]['name'],$_mode['templs'][$j]['file']);
                                for ($j=0;$j<sizeof($_mode['params']);$j++)
                                    $TreeNode->setBlockParam($_mode['params'][$j]['name'],$_mode['params'][$j]['value']);
                                $TreeNode->setBlockParam('mode',$_tree_params[$i]['name']);
                            }
                        }
                        else
                        {
                            $TreeNode->findBlock($_tree_params[$i]['block'],$_tree_params[$i]['scope']);

                            if ($TreeNode->isBlockExists())
                            {
                                if ($_mode['block'] == '')
                                {
                                    $TreeNode->deleteBlock();
                                }
                                else
                                {
                                    if ($_mode['block'] != $_tree_params[$i]['block'])
                                    {
                                        $TreeNode2->findBlock($_mode['block'],$_tree_params[$i]['scope']);
                                        if (!$TreeNode2->isBlockExists())
                                            $TreeNode->setBlockName($_mode['block']);
                                    }
                                    //$TreeNode->updBlockParam();
                                    if (isset($_mode['templs']))
                                        foreach ($_mode['templs'] as $k=>$v)
                                            $TreeNode->updBlockTemplate($k,$v);

                                    if (isset($_mode['params']))
                                    foreach ($_mode['params'] as $k=>$v)
                                    {
                                            if (!$TreeNode->updBlockParam($k,$v))
                                                $TreeNode->setBlockParam($k,$v);
                                    }
                                }


                            }
                        }

                    }
                    $TreeNode->save();
                }

                $DbManager = &$this->Kernel->Link('database.manager');
                $DbManager->Select($this->Tables['links'],'*','tid='.$_params['id']);
                $_rec = $DbManager->getNextRec();

                $_items = array(
                    'version'   =>  isset($_params['version'])?$_params['version']:'',
                    'prid'      =>  $this->ProfileID
                );

                if (!$_rec)
                {
                    $DbManager->Select($this->Tables['modules'],'*','alias="'.$_alias.'"');
                    $_rec = $DbManager->getNextRec();
                    $_items['tid'] = $_params['id'];
                    $_items['mid'] = $_rec['id'];
                    $DbManager->InsertValues($this->Tables['links'],$_items);
                }
                else
                {
                    $DbManager->UpdateValues($this->Tables['links'],$_items,'tid='.$_params['id']);
                }
	    	$GLOBALS['Page']->setRedirect($this->Kernel->Url);
            break;
        }
   }

    function updNodeParams($_params,$_id)
    {
    	if (! isset($_params['menu'])) 
    	{
    		$_params['menu'] = 0;	
    	}
        
    	$Tree = &$this->Kernel->Link('backend.tree',true);

    	$pathModule = PROFILES_DIR.$this->ProfileAlias.'/_tree/'.$Tree->getNodeFolder($_id);
    	
    	if (isset($_params['alias']) && $_params['alias'])
    	{
    		$path = preg_split('(/)', $pathModule, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    		$oldAlias = array_pop($path);
    		if ($oldAlias != $_params['alias'])
    		{
	    		$path[] = $_params['alias'];
	    		$newPathModule = '/'.implode('/', $path).'/';
	    		rename($pathModule, $newPathModule);
	    		$pathModule = $newPathModule;
    		}	
    	}
    	$Tree->updNodeParams($_params,$_id);
    	
    	if ($Tree->NodeParams['id'] == $_id)
        {
            foreach($_params as $k=>$v)
            {
				$Tree->NodeParams[$k] = $v;
            }
        }
        for ($i=0;$i<sizeof($Tree->Nodes);$i++)
        {
            if ($Tree->Nodes[$i]['id'] == $_id)
            {
                foreach($_params as $k=>$v)
                {
                    $Tree->Nodes[$i][$k] = $v;
                }
                break;
            }
        }
        
		if (isset($_params['alias']) && $_params['alias'])
    	{
    		$path = preg_split('(/)', $this->Kernel->Url, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    		$oldAlias = array_pop($path);
    		if ($oldAlias != $_params['alias'])
    		{
    			$path[] = $_params['alias'];
		    	header('Location: '.'/'.implode('/', $path).'/');
		    	exit;
    		}
    	}
    }

    function ModifyNodeParams($_params,$_url_params)
    {
            switch ($_params['action'])
            {
            case 'upd':
                    $this->updNodeParams($_params['upd'],$_params['id']);
            break;
                }
    }

    function ModifyBlocksList($_params,$_url_params)
    {
        switch ($_params['action'])
        {
            case 'upd_templ':
                $_templs = $_params['template'];
                $TreeNode = &$this->Kernel->Link('backend.treenode');
                $TreeNode->setProfile($this->ProfileAlias);
                $TreeNode->setPath($_url_params);
                $TreeNode->setNode();
                if (isset($_templs['this']))
                {
                    if ($_templs['this'])
                    {
                        $TreeNode->setNodeTemplate(0,$_templs['this']);
                    }
                    else
                    {
                        $TreeNode->deleteNodeTemplate(0);
                    }
                }
                if (isset($_templs['shuffle']))
                {
                    if ($_templs['shuffle'])
                    {
                        $TreeNode->setNodeTemplate(1,$_templs['shuffle']);
                    }
                    else
                    {
                        $TreeNode->deleteNodeTemplate(1);
                    }
                }
                $TreeNode->Save();
            break;
            case 'upd_spectempl':
                    $_templs = $_params['template'];
                $TreeNode = &$this->Kernel->Link('backend.treenode');
                $TreeNode->setProfile($this->ProfileAlias);
                $TreeNode->setPath($_url_params);
                $TreeNode->setNode();
                if (isset($_templs['4print']))
                {
                        if ($_templs['4print']) $TreeNode->setNodeTemplate(1,$_templs['4print'],'forprint');
                    else $TreeNode->deleteNodeTemplate(1,'forprint');
                }

                if (isset($_templs['404']))
                {
                        if ($_templs['404']) $TreeNode->setNodeTemplate(1,$_templs['404'],'404');
                    else $TreeNode->deleteNodeTemplate(1,'404');
                }

                if (isset($_templs['403']))
                {
                        if ($_templs['403']) $TreeNode->setNodeTemplate(1,$_templs['403'],'403');
                    else $TreeNode->deleteNodeTemplate(1,'403');
                }

                if (isset($_templs['error']))
                {
                        if ($_templs['error']) $TreeNode->setNodeTemplate(1,$_templs['error'],'error');
                    else $TreeNode->deleteNodeTemplate(1,'error');
                }


                $TreeNode->Save();
            break;
            case 'del':
                $TreeNode = &$this->Kernel->Link('backend.treenode');
                $TreeNode->setProfile($this->ProfileAlias);
                $TreeNode->setPath($_url_params);
                $TreeNode->setNode();
                    $TreeNode->findBlock($_params['block'],$_params['scope']);
                    if ($TreeNode->isBlockExists())
                        $TreeNode->deleteBlock();
                $TreeNode->Save();

                    if ($_params['block'] == 'content' && !$_params['scope'])
                    {
                        $Tree = $this->Kernel->Link('backend.tree',true);
                        if(!$Tree->isNodeSetted())
                            $Tree->setNode($_url_params);
                        $_id = $Tree->getNodeParam('id');
                        $_params = array(
                            'content'           =>  0,
//                        'contenttime'   =>        'now()'
                        );
                        $Tree->updNodeParams($_params,$_id);
                    }

            break;
            case 'add':
                 $_path = null;

                if ($_params['add']['create'])
                {
                        if ($_params['add']['create'] == 'new')
                        {
                            $_add = $_params['add'];
                            $Fitler = &$this->Kernel->Link('services.filter',true);
                            $_ruls = array(
                                'name'   =>  'sts;dnc;trim',
                                'descr'  =>  'sts'
                            );
                            $Fitler->FiltValues($_add,$_ruls);

                            $Checker = &$this->Kernel->Link('services.checker',true);
                            $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'block_manage');

                            $_fl = $Checker->VerifyValues($_add,$_ruls);

                            if ($_fl)
                            {
                                $toTreeNode = &$this->Kernel->Link('backend.treenode');
                                $toTreeNode->setProfile($this->ProfileAlias);
                                $toTreeNode->setPath($_url_params);
                                $toTreeNode->setNode();
                                $_to_block  =   $_add['name'];
                                $_to_scope  =   $_add['scope'];
                                $toTreeNode->findBlock($_to_block,$_to_scope);

                                if (!$toTreeNode->isBlockExists())
                                {
                                    $toTreeNode->newBlock();
                                    $toTreeNode->setBlockScope($_to_scope);
                                    $toTreeNode->setBlockName($_to_block);
                                    $toTreeNode->setBlockDescr($_params['add']['descr']);
                                    if ($_add['src']=='s'){

                                        $toTreeNode->setBlockSource('s');

                                    }elseif ($_add['src']=='t'){

                                    $toTreeNode->setBlockSource('t');
                                        $Templates = &$this->Kernel->Link('backend.templates');
                                        $_files = $Templates->GetAvailTemplateFiles('_blocks',$this->ProfileAlias);
                                        $_file = '';
                                        if (sizeof($_files))
                                            $_file = $_files[0]['filename'];

                                        $toTreeNode->setBlockTemplate('main',$_file);

                                                        $TPLManager = &$this->Kernel->Link('template.manager');
                                                        $_slots = $TPLManager->getSlotsList($_file,'_blocks',$this->ProfileAlias);
                                        $_slots_data = array_flip($_slots);
                                        foreach ($_slots_data as $k=>$v)
                                                $toTreeNode->setBlockSlot($k,$k,'s','');

                                    }elseif ($_add['src']=='d'){

                                        $_object_name = $_params['add']['module'];

                                        $toTreeNode->setBlockSource('d');
                                        $toTreeNode->setBlockObject($_object_name);

                                        $_arr = explode('.',$_object_name);
                                        $_object = &$this->Kernel->Link($_arr[0].'.params');
                                        $_modes = $_object->GetModes();
                                        if (sizeof($_modes))
                                        {
                                            $_mode = $_modes[0];
                                            $toTreeNode->setBlockParam('mode',$_mode['name']);

                                            $_curr_params = $_object->GetParams($_mode['name']);

                                            for($i=0;$i<sizeof($_curr_params);$i++)
                                                $toTreeNode->setBlockParam($_curr_params[$i]['name'],$_curr_params[$i]['value']);

                                            $_curr_templates = $_object->GetTemplates($_mode['name']);
                                            for($i=0;$i<sizeof($_curr_templates);$i++)
                                            {
                                                if (isset($_curr_templates[$i]['file']))
                                                    $toTreeNode->setBlockTemplate($_curr_templates[$i]['name'],$_curr_templates[$i]['file']);
                                            }
                                        }
                                    }
                                    $toTreeNode->Save();

                                    if ($_to_block == 'content' && !$_to_scope)
                                    {
                                        $Tree = $this->Kernel->Link('backend.tree',true);
                                        if(!$Tree->isNodeSetted())
                                            $Tree->setNode($_url_params);
                                        $_id = $Tree->getNodeParam('id');
                                        $_params = array(
                                            'content'           =>  1,
                                        'contenttime'   =>        'now()'
                                        );
                                        $Tree->updNodeParams($_params,$_id);
                                    }

                                }
                        }else{
                                $this->FormValues = $_add;
                                $this->WarningsDS = $Checker->GetWarningDS($_ruls);
                        }

                        }elseif ($_params['add']['create'] == 'based' && $_params['add']['from']){

                            list($_block,$_setted,$_scope,$_level) = explode('|',$_params['add']['from']);
                        if ($_setted) $_from_url_params = $_url_params;
                        else $_from_url_params = array_slice($_url_params,0,$_level);

                            $_add = $_params['add'];
//                        Dump($_add);
                            $Fitler = &$this->Kernel->Link('services.filter',true);
                            $_ruls = array(
                                'name'   =>  'sts;dnc',
                                'descr'  =>  'sts'
                            );
                            $Fitler->FiltValues($_add,$_ruls);

                            $Checker = $this->Kernel->Link('services.checker',true);
                            $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'block_manage_copy');
                            $_fl = $Checker->VerifyValues($_add,$_ruls);
                            if ($_fl)
                            {
                                $fromTreeNode = $this->Kernel->Link('backend.treenode');
                                $fromTreeNode->setProfile($this->ProfileAlias);
                                $fromTreeNode->setPath($_from_url_params);
                                $fromTreeNode->setNode();
                                $fromTreeNode->findBlock($_block,$_scope);
                                if ($fromTreeNode->isBlockExists())
                                {
                                    $toTreeNode = $this->Kernel->Link('backend.treenode');
                                    $toTreeNode->setProfile($this->ProfileAlias);
                                    $toTreeNode->setPath($_url_params);
                                    $toTreeNode->setNode();
                                    $toTreeNode->findBlock($_params['add']['name'],$_params['add']['scope']);
                                    if ($toTreeNode->isBlockExists())
                                        $toTreeNode->deleteBlock();

                                    $toTreeNode->setBlock($fromTreeNode->getBlock());

                                    $_to_block = $_block;
                                    if ($_params['add']['name'])
                                    {
                                        $_to_block = $_params['add']['name'];
                                        $toTreeNode->setBlockName($_to_block);
                                    }

                                    $_to_scope = $_params['add']['scope'];
                                    $toTreeNode->setBlockScope($_to_scope);

                                    if ($_params['add']['descr'])
                                        $toTreeNode->setBlockDescr($_params['add']['descr']);

                                    $_source = $toTreeNode->getBlockSource();
                                    if ($_source == 's')
                                    {
                                        $toTreeNode->setBlockStaticContent($fromTreeNode->getBlockStaticContent());
                                    }
                                    $toTreeNode->Save();

                                    if ($_to_block == 'content' && !$_to_scope)
                                    {
                                        $Tree = $this->Kernel->Link('backend.tree',true);
                                        if(!$Tree->isNodeSetted())
                                            $Tree->setNode($_url_params);
                                        $_id = $Tree->getNodeParam('id');
                                        $_params = array(
                                            'content'           =>  1,
                                        'contenttime'   =>        'now()'
                                        );
                                        $Tree->updNodeParams($_params,$_id);
                                    }
                                    }

                        }else{
                            $this->FormValues = $_add;
                            $this->WarningsDS = $Checker->GetWarningDS($_ruls);
                        }
                    }
                }
            break;
                }
    }

    function AddStaticBlock($_params,$_url_params)
    {
        $TreeNode = &$this->Kernel->Link('backend.treenode');
        $TreeNode->setProfile($this->ProfileAlias);
        $TreeNode->setPath($_url_params);
        $TreeNode->setNode();
        $TreeNode->findBlock($_params['block'],$_params['scope']);
        if (!$TreeNode->isBlockExists())
        {
                        $TreeNode->newBlock();
            $TreeNode->setBlockName($_params['block']);
            $TreeNode->setBlockScope($_params['scope']);
        }

        $TreeNode->setBlockSource('s');
        $TreeNode->setBlockScope($_params['scope']);
        if (isset($_params['descr']))
                $TreeNode->setBlockDescr($_params['descr']);

        $Fitler = &$this->Kernel->Link('services.filter',true);

        $_ruls = array(
            'data'    =>  'sts;delbaseurl',
        );
        $_data = array(
            'data'  =>  $_params['content']
        );
        $Fitler->FiltValues($_data,$_ruls);
        $_data = $_data['data'];

        $TreeNode->setBlockStaticContent($_data);
        $TreeNode->Save();

        if ($_params['block'] == 'content' && !$_params['scope'])
        {
                $Tree = $this->Kernel->Link('backend.tree',true);
                $_id = $Tree->getNodeParam('id');
                $_params = array(
                    'content'           =>  1,
                'contenttime'   =>        'now()'
                );
                $Tree->updNodeParams($_params,$_id);
        }


    }

    function ModifyBlock($_params,$_url_params)
    {
//        Dump($_params);
            switch ($_params['action'])
            {
            case 'switch_mode':
                    $_state = $this->User->getParam('html_editor');
                $_state = $_state?0:1;
                $this->User->setParam('html_editor',$_state);
            break;
            case 'add':
                                foreach($_params['add'] as $k=>$v)
                {
                                        $_block_params = array(
                                'block'                =>        $k,
                            'scope'                =>        0,
                            'content'   =>        $v
                               );
                                        $this->AddStaticBlock($_block_params,$_url_params);
                }
                        break;
            case 'upd':
                array_shift($_url_params);
                array_shift($_url_params);
                $this->AddStaticBlock($_params,$_url_params);
            break;
        }
        }


    function addBlockThis($blockName, $blockContent, $urlParams)
    {
         $block = array(
            'block'     =>  $blockName,
            'content'   =>  $blockContent,
            'scope'     =>  0
        );
        $this->AddStaticBlock($block, $urlParams);
    }
    
    function deleteBlockThis($blockName, $urlParams)
    {
        $TreeNode = &$this->Kernel->Link('backend.treenode', true);
        $TreeNode->setProfile($this->ProfileAlias);
        $TreeNode->setPath($urlParams);
        $TreeNode->setNode();
        $TreeNode->findBlock($blockName, 0);
        if ($TreeNode->isBlockExists())
        {
            $TreeNode->deleteBlock();
        }
        $TreeNode->Save();
    }
    

    function ModifyPage($_params,$_url_params)
    {
        switch ($_params['action'])
        {
            case 'static':
            
                 if (isset($_params['title']))
                 {
                    if ($_params['title'])
                    {                 
                        $this->addBlockThis('title', $_params['title'], $_url_params);
                    }
                    else
                    {
                        $this->deleteBlockThis('title', $_url_params);
                    }
                }
                
                 if (isset($_params['keywords']))
                 {
                    if ($_params['keywords'])
                    {
                        $this->addBlockThis('keywords', $_params['keywords'], $_url_params);                 
                    }
                    else
                    {
                         $this->deleteBlockThis('keywords', $_url_params);
                    }
                }
                
                
                 if (isset($_params['descript']))
                 {
                    if ($_params['descript'])
                    {                 
                       $this->addBlockThis('descript', $_params['descript'], $_url_params);
                    }
                    else
                    {
                         $this->deleteBlockThis('descript', $_url_params);
                    }
                }
                
                $_block = array(
                    'block'     =>  'content',
                    'content'   =>  $_params['content'],
                    'scope'     =>  0
                );
                $this->AddStaticBlock($_block,$_url_params);
            break;
            case 'dinamic':
                 if (isset($_params['title']))
                 {
                    if ($_params['title'])
                    {                 
                        $this->addBlockThis('title', $_params['title'], $_url_params);
                    }
                    else
                    {
                        $this->deleteBlockThis('title', $_url_params);
                    }
                }
                
                 if (isset($_params['keywords']))
                 {
                    if ($_params['keywords'])
                    {
                        $this->addBlockThis('keywords', $_params['keywords'], $_url_params);                 
                    }
                    else
                    {
                         $this->deleteBlockThis('keywords', $_url_params);
                    }
                }
                
                
                 if (isset($_params['descript']))
                 {
                    if ($_params['descript'])
                    {                 
                       $this->addBlockThis('descript', $_params['descript'], $_url_params);
                    }
                    else
                    {
                         $this->deleteBlockThis('descript', $_url_params);
                    }
                }

                $_content_params = $_params;
                $_content_params['descr'] = '';
                $_content_params['action'] = 'upd';
                $_content_params['block'] = 'content';
                $_content_params['scope'] = '0';
                $_content_params['action'] = 'upd';
                array_unshift($_url_params,$_content_params['block'],$_content_params['block']);
                $this->ModifyDinamicBlock($_content_params,$_url_params);
            break;
        }
    }

    function ModifyDinamicBlock($_params,$_url_params)
    {
        switch ($_params['action'])
        {
            case 'upd':

                array_shift($_url_params);
                array_shift($_url_params);


                $TreeNode = &$this->Kernel->Link('backend.treenode');
                $TreeNode->setProfile($this->ProfileAlias);
                $TreeNode->setPath($_url_params);
                $TreeNode->setNode();
                $TreeNode->findBlock($_params['block'],$_params['scope']);
                if ($TreeNode->isBlockExists())
                {
                    $TreeNode->setBlockDescr($_params['descr']);
                    $_mode = $TreeNode->getBlockParam('mode');
                    if ($_mode != $_params['act_mode'])
                    {
                        $_mode = $_params['act_mode'];
                        $_object_name = $TreeNode->getBlockObject();
                        $_arr = explode('.',$_object_name);
                        $_object = &$this->Kernel->Link($_arr[0].'.params');

                        $TreeNode->deleteBlockParams();
                        $TreeNode->setBlockParam('mode',$_mode);

                        $_curr_params = $_object->GetParams($_mode);
                        for($i=0;$i<sizeof($_curr_params);$i++)
                            $TreeNode->setBlockParam($_curr_params[$i]['name'],$_curr_params[$i]['value']);

                        $_curr_templates = $_object->GetTemplates($_mode);

                        for($i=0;$i<sizeof($_curr_templates);$i++)
                        {
                            if (isset($_curr_templates[$i]['file']))
                                $TreeNode->setBlockTemplate($_curr_templates[$i]['name'],$_curr_templates[$i]['file']);
                        }
                    }
                    else
                    {

                        if (isset($_params['params']))
                            foreach($_params['params'] as $k=>$v)
                        if (!$TreeNode->updBlockParam($k,$v))
                            $TreeNode->setBlockParam($k,$v);

                        if (isset($_params['templs']))
                            foreach($_params['templs'] as $k=>$v)
                        {
                            if (!$TreeNode->updBlockTemplate($k,$v))
                               $TreeNode->setBlockTemplate($k,$v);
                        }

                    }
                    $TreeNode->Save();
                    if ($_params['block'] == 'content' && !$_params['scope'])
                    {
                        $Tree = $this->Kernel->Link('backend.tree',true);
                        $_id = $Tree->getNodeParam('id');
                        $_params = array(
                            'content'       =>  1,
                            'contenttime'   =>  'now()'
                        );
                        $Tree->updNodeParams($_params,$_id);
                    }

                }
            break;
        }
    }

    function ModifyTemplateBlock($_params,$_url_params)
    {

            array_shift($_url_params);
            array_shift($_url_params);

            $TreeNode = &$this->Kernel->Link('backend.treenode');
            $TreeNode->setProfile($this->ProfileAlias);
            $TreeNode->setPath($_url_params);
            $TreeNode->setNode();
            $TreeNode->findBlock($_params['block'],$_params['scope']);

        if ($TreeNode->isBlockExists())
            switch ($_params['action'])
            {
                case 'upd':
                $TreeNode->setBlockDescr($_params['descr']);
                    $_upd = isset($_params['upd'])?$_params['upd']:array();
                                $_templ = $TreeNode->getBlockTemplate('main');
                if ($_templ != $_params['templ']) $TreeNode->updBlockTemplate('main',$_params['templ']);
                $TPLManager = &$this->Kernel->Link('template.manager');
                $_slots = $TPLManager->getSlotsList($_params['templ'],'_blocks',$this->ProfileAlias);
                $_slots_data = array_flip($_slots);
                foreach ($_slots_data as $k=>$v)
                {
                        if (isset($_upd[$k]))
                                                $_slots_data[$k] = $_upd[$k];
                    else
                            $_slots_data[$k] = array(
                                'descr'        => $k,
                                'type'        => "_stat",
                                'value'        => '',
                            'descr'        => ''
                        );
                }
                                  $TreeNode->deleteBlockSlots();

                if (sizeof($_slots_data))
                    foreach($_slots_data as $k=>$v)
                {

                    if (!$v['type'])
                            $v['type'] = "_stat";
                    if (!isset($v['value'])) $v['value'] = '';
                    $_type = ($v['type']=='_stat')?'s':'d';
                    $_value = ($_type=='s')?$v['value']:$v['type'];

                           $TreeNode->setBlockSlot($k,$v['descr'],$_type,$_value);
                }
                $TreeNode->Save();

            break;
        }
    }



    function GetNodeUrl($_params,$_level)
    {
            $_url = implode('/',array_slice($_params,0,$_level));
        if ($_url) $_url .= '/';
        return $_url;
    }

    function &GetBlockData($_name,$_params)
    {
        $_arr = array(
            'name'  =>  $_name,
            'src'   =>  $_params['source'],
            'descr'   =>  $_params['descr'],
            'level'   =>  $_params['level'],
            'scope'   =>  isset($_params['scope'])?$_params['scope']:0,
        );

        if ($_params['source'] == 'd')
        {
            $_mode = isset($_params['params']['mode'])?$_params['params']['mode']:null;
            $_tmp = explode('.',$_params['object']);
            $_object = &$this->Kernel->Link($_tmp[0].'.params');
            $_arr['mode_descr'] = $_object->GetModeDescr($_mode);
            $DbManager = &$this->Kernel->Link('database.manager',true);
            $DbManager->Select($this->Tables['modules'],'name','alias = "'.$_tmp[0].'"');
            $_module = $DbManager->GetNextRec();
            $_arr['module_descr'] = $_module['name'];
        }
          /*
        if ($_params['source'] == 't')
        {

                }    */

        return $_arr;
    }

    //--------------------------------------------------------------------------
    // Сортировка блоков при отображении в блоках
    //--------------------------------------------------------------------------
    function sortBlocks(&$_data)
    {
        usort($_data,"user_blocks_sort");
    }

    //--------------------------------------------------------------------------
    // Обработка параметров для динамических блоков
    //--------------------------------------------------------------------------
    function proccessDynamicParams($_params,$_block,$_defparams)
    {
        for($i=0; $i<sizeof($_params); $i++)
        {
            if (isset($_params[$i]['type']) && ($_params[$i]['type'] <> 'st'))
            {
                switch($_params[$i]['type'])
                {
                    case 'version':

                        $_params[$i]['type'] = 'list';
                        $DbManager = &$this->Kernel->Link('database.manager',true);
                        $DbManager->Select(
                           $this->Tables['modules'].' m,'.$this->Tables['versions'].' v',
                           'v.alias as value, v.name as name',
                           'm.id = v.mid and m.alias="'.$_defparams['object'].'" and v.prid="'.$this->ProfileID.'"'
                        );

                        $_data = array();
                        if ($_params[$i]['list'] = VERSIONS_PLUS_ALL)
                        {
                            $_data[] = array(
                                'name'  =>  'Все',
                                'value' =>  'all'
                            );
                        }
                        while ($_rec = $DbManager->getNextRec())
                        {
                           $_data[] = $_rec;
                        }

                        $_params[$i]['valuesset'] = array(
                           'items' => $_data
                        );
                    break;
                    case 'navtype':
                        $_params[$i]['type'] = 'list';

                        $DbManager = &$this->Kernel->Link('database.manager',true);
                        $DbManager->Select($this->Tables['types'],'alias as value, name as name','prid = "'.$this->ProfileID.'"');

                        $_data = array();

                        while ($_rec = $DbManager->getNextRec())
                        {
                           $_data[] = $_rec;
                        }

                        $_params[$i]['valuesset'] = array(
                           'items' => $_data
                        );
                    break;

                    case 'navtype_null':
                        $_params[$i]['type'] = 'list';

                        $DbManager = &$this->Kernel->Link('database.manager',true);
                        $DbManager->Select($this->Tables['types'],'alias as value, name as name','prid = "'.$this->ProfileID.'"');

                        $_data = array();

                        $_data[] = array(
                            'name'  =>  'Не задана',
                            'value' =>  ''
                        );

                        while ($_rec = $DbManager->getNextRec())
                        {
                           $_data[] = $_rec;
                        }

                        $_params[$i]['valuesset'] = array(
                           'items' => $_data
                        );
                    break;

                    case 'sel':
                        $_params[$i]['type'] = 'list';
                        $_params[$i]['valuesset'] = array(
                           'items' =>        $_params[$i]['values']
                        );
                    break;
                }

                if (isset($_block['params'][$_params[$i]['name']]))
                {
                    $_params[$i]['svalue'] = $_block['params'][$_params[$i]['name']];
                }

            }
            else
            {
                $_params[$i]['type'] = 'string';
                if (isset($_block['params'][$_params[$i]['name']]))
                {
                    $_params[$i]['value'] = $_block['params'][$_params[$i]['name']];
                }

            }

        }
        return $_params;
    }


    //--------------------------------------------------------------------------
    // Отобраджение контента
    //--------------------------------------------------------------------------
    function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
    	switch ($_params['mode'])
        {
            //------------------------------------------------------------------
            // Редактирование типов навигации
            //------------------------------------------------------------------
            case 'navigation':

                $_ds = &$this->Kernel->Link('dataset.abstract');

                $_ds_params = $this->FormValues;
                $_ds_params['_object'] = $this->Name;
                $_ds_params['_url'] = $this->Kernel->Url;
                $_ds->SetParams($_ds_params);

                $_ds->addChildDS('warnings',$this->WarningsDS);

                $_types_ds = &$this->Kernel->Link('dataset.database');
                $_types_ds->SetQuery($this->Tables['types'],'*','prid = "'.$this->ProfileID.'"');
                $_ds->addChildDS('types',$_types_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;

            break;

            //------------------------------------------------------------------
            // Редактирование статической страницы
            //------------------------------------------------------------------
            case 'page':

                $_page_params = $_url_params;

                $Page = $this->Kernel->Link('system.page');
                $Page->SetProfile($this->ProfileAlias);
                $Page->SetParams($_page_params);
                $Page->Parse();
                $Page->ChangeMap('this');
                $_title_params = $Page->GetBlockParams('title');
                $_keywords_params = $Page->GetBlockParams('keywords');
                $_descript_params = $Page->GetBlockParams('descript');
                $_content_params = $Page->GetBlockParams('content');


                $_page_name = $this->Tree->getNodeParam('name');

                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_items = array(
                    'name'      =>  $_page_name,
                    'title'     =>  $_title_params['content'],
                    'keywords'     =>  $_keywords_params['content'],
                    'descript'     =>  $_descript_params['content'],
                    'content'   =>  $_content_params['content'],
                );
                
                switch($_content_params['source'])
                {
                    //----------------------------------------------------------
                    // Статическая страница с динамическим блоком
                    //----------------------------------------------------------

                    case 'd':
                        $_block_params = $_content_params;

                        $_mode = isset($_block_params['params']['mode'])?$_block_params['params']['mode']:null;
                        $_viewer = explode('.',$_block_params['object']);

                        $_object = &$this->Kernel->Link($_viewer[0].'.params');

                        $_main_object = explode('.',$Page->MainObject['name']);
                        if ($_viewer[0] == $_main_object[0]) $_link = 'l';
                        else $_link = 'b';

                        $_modes = $_object->GetModes($_link);
                        $_mode_ds = &$this->Kernel->Link('dataset.array');
                        $_mode_ds->setData($_modes);
                        $_mode_ds_param = array(
                            'mode'  =>  $_mode
                        );
                        $_mode_ds->setParams($_mode_ds_param);
                        $_ds->AddChildDS('modes',$_mode_ds);


                        $_params = $_object->GetParams($_mode);
                        $_params_ds = &$this->Kernel->Link('dataset.array');

                        $_defparams = array(
                            'object'    =>  $_viewer[0]
                        );
                        $_params = $this->proccessDynamicParams($_params,$_block_params,$_defparams);

                        $_params_ds->setData($_params);
                        $_ds->AddChildDS('params',$_params_ds);


                        $_tpls = $_object->GetTemplates($_mode);

                        $_templs_ds = &$this->Kernel->Link('dataset.array');

                        $Templates = &$this->Kernel->Link('backend.templates');
                        for($i=0;$i<sizeof($_tpls);$i++)
                        {
                            $_tpls[$i]['activefile'] = $_block_params['template'][$_tpls[$i]['name']]['file'];
                            $_tpls[$i]['library'] = $Templates->getTemplateLib($_block_params['template'][$_tpls[$i]['name']]['file'],$_viewer[0],$this->ProfileAlias);
                        }

                        $_templs_ds->setData($_tpls);

                        $_templs_ds_params = array(
                            'object'    =>  $_viewer[0],
                            'profile'   =>  $this->ProfileAlias
                        );
                        $_templs_ds->setParams($_templs_ds_params);

                        $_avtpls = $Templates->GetAvailTemplateFiles($_viewer[0],$this->ProfileAlias,$_mode);
                        $_avtempls_ds = &$this->Kernel->Link('dataset.array');
                        $_avtempls_ds->setData($_avtpls);
                        $_templs_ds->AddChildDS('avtempls',$_avtempls_ds);

                        $_ds->AddChildDS('templs',$_templs_ds);

                        $_templ = $_templs['dinamic'];

                    break;

                    //----------------------------------------------------------
                    // Статическая страница с шиблонным блоком
                    //----------------------------------------------------------

                    case 't':
                        $_templ = $_templs['static'];
                    break;

                    //----------------------------------------------------------
                    // Статическая страница с статическим блоком
                    //----------------------------------------------------------
                    default:

                        $_templ = $_templs['static'];
                        $_state = $this->User->getParam('html_editor');
                        $_items['editor_state'] = $_state;

                        $HTMLEditor = &$this->Kernel->Link('htmleditor.main');
                        $_editor_params = array(
                            'htmldoc'       =>  $_items['content'],
                            'editor_name'   =>  'content',
                            'state'         =>  $_state,
                        );
                        $_items['editor'] = $HTMLEditor->Execute($_editor_params);

                    break;
                }

                $_ds->SetParams($_items);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templ,$this->Name);
                return $_result;

            break;

            //------------------------------------------------------------------
            // Менеджер разделов
            //------------------------------------------------------------------
            case 'main':

                $Tree = &$this->Kernel->Link('backend.tree',true);

                if ($Tree->NodeParams['type'] != 1)
                //--------------------------------------------------------------
                // Статический раздел
                //--------------------------------------------------------------
                {

                    $_profile = $this->ProfileAlias;
                    $Page = $this->Kernel->Link('system.page');
                    $Page->SetProfile($_profile);
                    $Page->SetParams($_url_params);
                    $Page->Parse();
                    $Page->ChangeMap('this');

                    $_ds = &$this->Kernel->Link('dataset.abstract');

                    if (!$Tree->isNodeSetted())
                        $Tree->setNode($_url_params);

                    $_ds_params = array();
                    $_ds_params['id'] = $Tree->getNodeParam('id');
                    $_ds_params['menu'] = $Tree->getNodeParam('menu');
                    $_ds_params['name'] = $Tree->getNodeParam('name');
                    $_ds_params['fullname'] = $Tree->getNodeParam('fullname');
                    $_ds_params['url'] = $Tree->getNodeParam('url');

                    $_url = implode('/',$_url_params);
                    if ($_url) $_url .= '/';
                    $_ds_params['_url'] = $_url;

                    $_ds->SetParams($_ds_params);

                    $_navtypes_ds = &$this->Kernel->Link('dataset.database');
                    $_navtypes_ds->SetQuery($this->Tables['types'],'*','prid = "'.$this->ProfileID.'"');
                    $_ds->addChildDS('navtypes',$_navtypes_ds);

                    if ($Tree->NodeParams['type'])
                    {
                        $_templ = $_templs['link'];
                    }
                    else
                    {
                        $_templ = $_templs['main'];
                    }
                }
                else
                //--------------------------------------------------------------
                // Динамичекский раздел
                //--------------------------------------------------------------
                {

                    $_ds = &$this->Kernel->Link('dataset.abstract');
                    $_profile = $this->User->GetCurrentProfile('alias');
                    $_profile_id = $this->User->GetCurrentProfile('id');


                    $Parser = &$this->Kernel->Link('system.parser');
                    $Parser->Execute($_profile,$_url_params);
                    $_map = $Parser->GetMap();

                    $_alias = explode('.',$_map['mainobject']['name']);

                    $_alias = $_alias[0];

                    // набор данных версий модуля
                    $_version_ds = &$this->Kernel->Link('dataset.database');
                    $_version_ds->setQuery(
                        $this->Tables['modules'].' m,'.$this->Tables['versions'].' v',
                        'v.*',
                        'm.id = v.mid and m.alias="'.$_alias.'" and v.prid = '.$_profile_id
                    );

                    $_version_ds_params = array(
                        '_active'   => $_map['mainobject']['version']
                    );
                    $_version_ds->setParams($_version_ds_params);

                    $_ds->addChildDS('versions',$_version_ds);


                    $Tree = &$this->Kernel->Link('backend.tree',true);
                    if (isset($Tree->Nodes[$_map['mainobject']['level']+1]))
                    {
                        $_node = $Tree->Nodes[$_map['mainobject']['level']+1];
                    }
                    else
                    {
                        $_node = $Tree->Nodes[$_map['mainobject']['level']];
                    }
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select($this->Tables['modules'],'*','alias="'.$_alias.'"');
                    $_module = $DbManager->getNextRec();
                    $_ds_params = array();
                    $_ds_params['id'] = $_node['id'];
                    $_ds_params['level'] = $_node['level'];
                    $_ds_params['alias'] = $_node['alias'];
                    $_ds_params['menu'] = $_node['menu'];
                    $_ds_params['name'] = $_node['name'];
                    $_ds_params['fullname'] = $_node['fullname'];
                    $_ds_params['object'] = $_alias;
                    $_ds_params['module_name'] = $_module['name'];

                    $_url = implode('/',$_url_params);
                    if ($_url) $_url .= '/';
                    $_ds_params['_url'] = $_url;

                    $_ds->SetParams($_ds_params);

                    $_navtypes_ds = &$this->Kernel->Link('dataset.database');
                    $_navtypes_ds->SetQuery($this->Tables['types'],'*','prid = "'.$_profile_id.'"');
                    $_ds->addChildDS('navtypes',$_navtypes_ds);


                    $_link_params = array_slice($_url_params,$_map['mainobject']['level']);
                    $_object = &$this->Kernel->Link($_alias.'.params');

                    $_module_params = $_object->getModuleParams();

                    $_tree_params = $_object->getTreeModes($_link_params);

                    foreach($_map['blocks'] as $k=>$v)
                    {
                        if ($v['source'] == 'd')
                        {
                            for ($i=0;$i<sizeof($_tree_params);$i++)
                                if (!$_tree_params[$i]['block'] && $_tree_params[$i]['scope'] == $v['scope'] &&
                                    $_tree_params[$i]['name'] == $v['params']['mode'] )
                                {
                                    $_tree_params[$i]['block'] = $v['name'];
                                    $_tree_params[$i]['templs'] = $v['template'];
                                    $_tree_params[$i]['params'] = $v['params'];
                                    break;
                                }
                        }
                    }

                    $_defparams = array(
                        'object'    =>  $_alias
                    );

                    $Templates = &$this->Kernel->Link('backend.templates');
                    for ($i=0;$i<sizeof($_tree_params);$i++)
                    {
                        $_tree_params[$i]['mode_name'] = $_tree_params[$i]['name'];
                        $_tree_params[$i]['descr'] = $_object->GetModeDescr($_tree_params[$i]['name']);

                        if ($_tree_params[$i]['block'])
                        {
                            $_mode_params = $_object->getParams($_tree_params[$i]['name']);

                            $_mode_params = $this->proccessDynamicParams($_mode_params,$_tree_params[$i],$_defparams);

                            $_tree_params[$i]['params'] = null;
                            $_tree_params[$i]['params']['items'] = $_mode_params;

                            $_mode_templs = $_object->GetTemplates($_tree_params[$i]['name']);
                            $_avtpls = $Templates->GetAvailTemplateFiles($_alias,$_profile,$_tree_params[$i]['name']);

                            for($j=0;$j<sizeof($_mode_templs);$j++)
                            {

                                $_file = isset($_tree_params[$i]['templs'][$_mode_templs[$j]['name']]['file'])?$_tree_params[$i]['templs'][$_mode_templs[$j]['name']]['file']:$_mode_templs[$j]['file'];
                                $_mode_templs[$j]['activefile'] = $_file;
                                $_mode_templs[$j]['library'] = $Templates->getTemplateLib($_file,$_alias,$_profile);
                                $_mode_templs[$j]['avtempls']['items'] = $_avtpls;
                            }

                            $_tree_params[$i]['templs'] = null;
                            $_tree_params[$i]['templs']['items'] = $_mode_templs;
                        }
                        else
                        {
                                $_tree_params[$i]['params'] = -1;
                                $_tree_params[$i]['templs'] = -1;
                        }
                    }

                    // набор данных: режимы модуля
                    $_mode_ds = &$this->Kernel->Link('dataset.array');
                    $_mode_ds_tree = array(
                        'items' =>  $_tree_params
                    );
                    $_mode_ds->setTree($_mode_ds_tree);
                    $_ds->addChildDS('modes',$_mode_ds);

                    for ($i=0;$i<sizeof($_module_params);$i++)
                    {
                        if ($_module_params[$i]['type'] == 'sel')
                            $_module_params[$i]['values']['items'] = $_module_params[$i]['values'];

                        $_module_params[$i]['svalue'] = isset($_map['mainobject']['params'][$_module_params[$i]['name']])?$_map['mainobject']['params'][$_module_params[$i]['name']]:$_module_params[$i]['svalue'];
                    }

                    // набор данных: параметры модуля
                    $_module_ds = &$this->Kernel->Link('dataset.array');
                    $_module_tree = array(
                            'items' =>  $_module_params
                    );
                    $_module_ds->setTree($_module_tree);
                    $_ds->addChildDS('moduleparams',$_module_ds);

                    $_templ = $_templs['module'];
                }

                $TplManager = &$this->Kernel->Link('template.manager',true);
    
                $_result = $TplManager->Execute($_ds,$_templ,$this->Name);
                return $_result;

            break;
            case 'blocks':
                 $_profile = $this->User->GetCurrentProfile('alias');

                // Инициализация парсера
                $Parser = &$this->Kernel->Link('system.parser');
                $Parser->Execute($_profile,$_url_params);
                // Карты
                $_this_map = $Parser->GetThisMap();
                $_childs_map = $Parser->GetChildsMap();
                $_shuffle_map = $Parser->GetShuffleMap();
                $_parent_map = $Parser->GetParentMap();

                $_url = implode('/',$_url_params);
                                if ($_url) $_url .= '/';

                $_name = '';
                if (isset($_this_map['blocks']['name']) && $_this_map['blocks']['name']['source']=='s')
                {
                        $_path = PROFILES_DIR.$_profile.'/'.BLOCKS_DIR.'/'.$_url.'name'.BLOCK_EXT;
                        $_name = $this->Kernel->ReadFile($_path);
                }


                $Templates = &$this->Kernel->Link('backend.templates');
                $_files = $Templates->GetAvailTemplateFiles('_main',$_profile);

                $_p_templ = null;
                $_p_templ_descr = null;
                $_p_templ4print = null;
                $_p_templ4print_descr = null;

                for($i=0;$i<sizeof($_files);$i++)
                {
                    if (isset($_parent_map['template']) && $_files[$i]['filename'] == $_parent_map['template']['file'])
                    {
                        $_p_templ = $_files[$i]['filename'];
                        $_p_templ_descr = $_files[$i]['descript'];
                    }
                    if (isset($_parent_map['templ_forprint']) && $_files[$i]['filename'] == $_parent_map['templ_forprint']['file'])
                    {
                        $_p_templ4print = $_files[$i]['filename'];
                        $_p_templ4print_descr = $_files[$i]['descript'];
                    }
                    if (isset($_parent_map['templ_forprint']) && $_files[$i]['filename'] == $_parent_map['templ_forprint']['file'])
                    {
                        $_p_templ4print = $_files[$i]['filename'];
                        $_p_templ4print_descr = $_files[$i]['descript'];
                    }
                }

                       // Общий DS
                $_ds = &$this->Kernel->Link('dataset.abstract');

                $_ds_params = array(
                    'url'                   =>        $_url,
                    'name'                  =>        $_name,
                    'p_templ_descr'         =>        $_p_templ_descr,
                    'p_templ'               =>        $_p_templ,
                    'p_templ4print_descr'   =>        $_p_templ4print_descr,
                    'p_templ4print'         =>        $_p_templ4print,
                    'object'                =>        '_main',
                    'profile'               =>        $_profile,
                    'block_name'            =>        isset($this->FormValues['name'])?$this->FormValues['name']:'',
                    'hide_blocks'           =>        $this->User->getParam('hide_blocks')?1:0,
                    'sect_tblocks'          =>        $this->User->getParam('sect_tblocks')?1:0,
                    'sect_sblocks'          =>        $this->User->getParam('sect_sblocks')?1:0,
                    'sect_cblocks'          =>        $this->User->getParam('sect_cblocks')?1:0,
                    'sect_mtempl'           =>        $this->User->getParam('sect_mtempl')?1:0,
                    'sect_stempl'           =>        $this->User->getParam('sect_stempl')?1:0,
                    'sect_addblock'         =>        $this->User->getParam('sect_addblock')?1:0,
//                                        'create'                                =>        isset($this->FormValues['create'])?$this->FormValues['create']:'',
//                    'block_descr'                        =>        isset($this->FormValues['descr'])?$this->FormValues['descr']:'',
                );

                $_ds_params = array_merge($this->FormValues,$_ds_params);

                $_ds->SetParams($_ds_params);

                // DS Шаблонов
                                $_this_templ_ds = &$this->Kernel->Link('dataset.array');
                $_this_templ_ds->SetData($_files);
                $_shuffle_templ_ds = clone $_this_templ_ds;
                $_templ4print_ds = clone $_this_templ_ds;
                $_templ404_ds = clone $_this_templ_ds;
                $_templ403_ds = clone $_this_templ_ds;
                $_templError_ds = clone $_this_templ_ds;

                // DS главных шаблонов

                $_mtemplds = &$this->Kernel->link('dataset.abstract');
                $_mtemplds->RecsCount = 1;

                $_mtemplds_params = array(
                    'visible'                        =>        $this->User->getParam('sect_mtempl')?1:0,
                    'section_name'                =>        'sect_mtempl'
                );
                $_mtemplds->setParams($_mtemplds_params);

                $_this_templ_params_ds = array(
                    'active'    =>  isset($_this_map['template'])?$_this_map['template']['file']:null,
                    'library'   =>  isset($_this_map['template'])?$Templates->getTemplateLib($_this_map['template']['file'],'_main',$_profile):null,
                    'name'      =>  'this'
                );
                $_this_templ_ds->setParams($_this_templ_params_ds);
                $_mtemplds->AddChildDS('this_templs',$_this_templ_ds);

                $_shuffle_templ_params_ds = array(
                    'active'    => isset($_shuffle_map['template'])?$_shuffle_map['template']['file']:null,
                    'library'   => isset($_shuffle_map['template'])?$Templates->getTemplateLib($_shuffle_map['template']['file'],'_main',$_profile):null,
                    'name'      =>  'shuffle'
                );
                $_shuffle_templ_ds->setParams($_shuffle_templ_params_ds);
                $_mtemplds->AddChildDS('shuffle_templs',$_shuffle_templ_ds);
                $_ds->AddChildDS('main_template',$_mtemplds);

                //  DS специальных шаблонов
                $_stemplds = &$this->Kernel->link('dataset.abstract');
                $_stemplds->RecsCount = 1;

                $_stemplds_params = array(
                    'visible'                        =>        $this->User->getParam('sect_stempl')?1:0,
                    'section_name'                =>        'sect_stempl'
                );
                $_stemplds->setParams($_stemplds_params);

                $_4print_templ_params_ds = array(
                    'active'    =>  isset($_shuffle_map['templ_forprint'])?$_shuffle_map['templ_forprint']['file']:null,
                    'library'   =>  isset($_shuffle_map['templ_forprint'])?$Templates->getTemplateLib($_shuffle_map['templ_forprint']['file'],'_main',$_profile):null,
                    'name'      =>  '4print'
                );
                $_templ4print_ds->setParams($_4print_templ_params_ds);
                $_stemplds->AddChildDS('templs4print',$_templ4print_ds);

                $_404_templ_params_ds = array(
                    'active'    => isset($_shuffle_map['templ_404'])?$_shuffle_map['templ_404']['file']:null,
                    'library'   => isset($_shuffle_map['templ_404'])?$Templates->getTemplateLib($_shuffle_map['templ_404']['file'],'_main',$_profile):null,
                    'name'      =>  '404'
                );
                $_templ404_ds->setParams($_404_templ_params_ds);
                $_stemplds->AddChildDS('template404',$_templ404_ds);

                $_403_templ_params_ds = array(
                    'active'        => isset($_shuffle_map['templ_403'])?$_shuffle_map['templ_403']['file']:null,
                    'library'        => isset($_shuffle_map['templ_403'])?$Templates->getTemplateLib($_shuffle_map['templ_403']['file'],'_main',$_profile):null,
                    'name'      =>  '403'
                );
                $_templ403_ds->setParams($_403_templ_params_ds);
                $_stemplds->AddChildDS('template403',$_templ403_ds);

                $_error_templ_params_ds = array(
                    'active'    => isset($_shuffle_map['templ_error'])?$_shuffle_map['templ_error']['file']:null,
                    'library'   => isset($_shuffle_map['templ_error'])?$Templates->getTemplateLib($_shuffle_map['templ_error']['file'],'_main',$_profile):null,
                    'name'      =>  'error'
                );
                $_templError_ds->setParams($_error_templ_params_ds);
                $_stemplds->AddChildDS('templateerror',$_templError_ds);


                $_ds->AddChildDS('spec_template',$_stemplds);



                // DS для раздела
                $_this_ds = &$this->Kernel->Link('dataset.array');
                $_this_ds_param = array(
                        'part_name'                        =>        'для раздела',
                        'part_full_name'        =>        'Только для раздела',
                    'active'                        =>        isset($this->FormValues['from'])?$this->FormValues['from']:'',
                    'visible'                        =>        $this->User->getParam('sect_tblocks')?1:0,
                    'section_name'                =>        'sect_tblocks'
                );
                $_this_ds->SetParams($_this_ds_param);
                $_data = array();
                                foreach($_this_map['blocks'] as $k=>$v)
                {
                        $_arr = $this->GetBlockData($k,$v);
                    $_arr['parent'] = 0;
                    if (isset($_shuffle_map['blocks'][$k]))
                            $_arr['parent'] = 1;
                    else if (isset($_parent_map['blocks'][$k]))
                    {
                                                $_arr['parent'] = 2;
                        $_arr['parent_url'] = $this->GetNodeUrl($_url_params,$_parent_map['blocks'][$k]['level']);
                    }
                        $_arr['setted'] = 1;
                    $_data[] = $_arr;
                }
                $this->sortBlocks($_data);
                $_this_ds->setData($_data);
                $_ds->AddChildDS('this_blocks',$_this_ds);

                // DS для подразделов
                $_ds_childs = &$this->Kernel->Link('dataset.array');
                $_childs_ds_param = array(
                        'part_name'                        =>        'для подразделов',
                        'part_full_name'        =>        'Только для подразделов',
                    'active'                        =>        isset($this->FormValues['from'])?$this->FormValues['from']:'',
                    'visible'                        =>        $this->User->getParam('sect_cblocks')?1:0,
                    'section_name'                =>        'sect_cblocks'
                );
                $_ds_childs->SetParams($_childs_ds_param);

                $_data = array();
                foreach($_childs_map['blocks'] as $k=>$v)
                {
                    $_arr = $this->GetBlockData($k,$v);
                    $_arr['parent'] = 0;
                    if (isset($_shuffle_map['blocks'][$k]))
                            $_arr['parent'] = 1;
                    else if (isset($_parent_map['blocks'][$k]))
                    {
                                                $_arr['parent'] = 2;
                        $_arr['parent_url'] = $this->GetNodeUrl($_url_params,$_parent_map['blocks'][$k]['level']);
                    }
                        $_arr['setted'] = 1;
                    $_data[] = $_arr;
                }

                    $this->sortBlocks($_data);
                $_ds_childs->setData($_data);
                $_ds->AddChildDS('childs_blocks',$_ds_childs);

                // смешанный DS
                //$_ds_shuffle = &$this->Kernel->Link('dataset.array');
                $_ds_shuffle = &$this->Kernel->LinkClass('CBlocks_DS');
                $_data = array();
                foreach($_shuffle_map['blocks'] as $k=>$v)
                {
                        $_arr = $this->GetBlockData($k,$v);
                    $_arr['parent'] = 0;
                    if (isset($_parent_map['blocks'][$k]))
                    {
                                                $_arr['parent'] = 2;
                        $_arr['parent_url'] = $this->GetNodeUrl($_url_params,$_parent_map['blocks'][$k]['level']);
                    }
                        $_arr['setted'] = 1;
//                        $_ds_shuffle->AddData($_arr);
                    $_data[] = $_arr;
                }

                $_ds_shuffle_param = array(
                        'part_name'                        =>        'для всех',
                        'part_full_name'        =>        'Для всех',
                    'active'                        =>        isset($this->FormValues['from'])?$this->FormValues['from']:'',
                    'no_setted_blocks'        =>        sizeof($_data)?0:1,
                    'visible'                        =>        $this->User->getParam('sect_sblocks')?1:0,
                    'section_name'                =>        'sect_sblocks'
                );

                $_ds_shuffle->SetParams($_ds_shuffle_param);

                if ($_parent_map)
                foreach($_parent_map['blocks'] as $k=>$v)
                if (!isset($_shuffle_map['blocks'][$k]))
                {
                    $_arr = $this->GetBlockData($k,$v);
                    $_arr['parent_url'] = $this->GetNodeUrl($_url_params,$_parent_map['blocks'][$k]['level']);
                    $_arr['parent'] = 2;
                    $_arr['setted'] = 0;
                        $_data[] = $_arr;
                }

                $this->sortBlocks($_data);
                $_ds_shuffle->setData($_data);
                $_ds->AddChildDS('shuffle_blocks',$_ds_shuffle);

                    // DS для добавления блока

                $_addblockds = &$this->Kernel->link('dataset.abstract');
                $_addblockds->RecsCount = 1;

                $_addblockds_params = array(
                    'visible'                        =>        $this->User->getParam('sect_addblock')?1:0,
                    'section_name'                =>        'sect_addblock'
                );
                $_addblockds->setParams($_addblockds_params);

                $_addblockds->addChildDS('warnings',$this->WarningsDS);
                $_addblockds->AddChildDS('this_blocks',$_this_ds);
                $_addblockds->AddChildDS('childs_blocks',$_ds_childs);
                $_addblockds->AddChildDS('shuffle_blocks',$_ds_shuffle);

                $_block_scope_data = array(
                        array(
                                'alias'        =>        0,
                        'name'        =>        'Для раздела',
                    ),
                        array(
                                'alias'        =>        1,
                        'name'        =>        'Для всех',
                    ),
                    array(
                                'alias'        =>        2,
                        'name'        =>        'Для подразделов',
                    )
                );
                $_block_scope_params = array(
                        'active'        =>        isset($this->FormValues['scope'])?$this->FormValues['scope']:''
                );
                $_block_scope_ds = &$this->Kernel->Link('dataset.array');
                $_block_scope_ds->setData($_block_scope_data);
                $_block_scope_ds->setParams($_block_scope_params);
                $_addblockds->addChildDS('block_scope',$_block_scope_ds);

                                // DS Модулей
                $_ds_modules = &$this->Kernel->Link('dataset.database');
                                $_ds_modules_params = array(
                    'active'        =>        isset($this->FormValues['module'])?$this->FormValues['module']:null
                );
                $_ds_modules->setParams($_ds_modules_params);
                $_ds_modules->SetQuery($this->Tables['modules'],'*','blocklink = 1','order by name');

                $_addblockds->AddChildDS('modules',$_ds_modules);

                $_ds->addChildDS('addblock',$_addblockds);

                                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'view':
                $_profile = $this->User->GetCurrentProfile('alias');
                    $_page_params = $_url_params;
                $_block = array_shift($_page_params);
                $_scope = array_shift($_page_params);

                    $Page = $this->Kernel->Link('system.page');
                                $Page->SetProfile($_profile);
                $Page->SetParams($_page_params);
                $Page->Parse();
                if ($_scope == 0) $Page->ChangeMap('this');
                else if ($_scope == 1) $Page->ChangeMap('shuffle');
                         else if ($_scope == 2) $Page->ChangeMap('childs');
                $_block_params = $Page->GetBlockParams($_block);

                $_main_ds = &$this->Kernel->Link('dataset.abstract');
                                $_items        = array(
                    'content'        =>        $_block_params['content'],
                    'name'                =>        $_block,
                    'descr'                =>        $_block_params['descr']
                );
                $_main_ds->SetParams($_items);

                                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_main_ds,$_templs['main'],$this->Name);

                return $_result;
                        break;
            case 'edit':
                $_profile = $this->User->GetCurrentProfile('alias');
                $_profile_id = $this->User->GetCurrentProfile('id');

                $_page_params = $_url_params;
                $_block = array_shift($_page_params);
                $_scope = array_shift($_page_params);

                $Page = $this->Kernel->Link('system.page');
                $Page->SetProfile($_profile);
                $Page->SetParams($_page_params);
                $Page->Parse();

                if ($_scope == 0) $Page->ChangeMap('this');
                else if ($_scope == 1) $Page->ChangeMap('shuffle');
                else if ($_scope == 2) $Page->ChangeMap('childs');

                $_block_params = $Page->GetBlockParams($_block);

                $_main_ds = &$this->Kernel->Link('dataset.abstract');
                $_items = array(
                    'name'      =>  $_block,
                    'descr'     =>  $_block_params['descr'],
                    'content'   =>  $_block_params['content'],
                    'scope'     =>  $_block_params['scope'],
                );

                switch($_block_params['source'])
                {
                    case 'd':

                        $_mode = isset($_block_params['params']['mode'])?$_block_params['params']['mode']:null;
                        $_viewer = explode('.',$_block_params['object']);

                        $_object = &$this->Kernel->Link($_viewer[0].'.params');

                        $_main_object = explode('.',$Page->MainObject['name']);
                        if ($_viewer[0] == $_main_object[0]) $_link = 'l';
                        else $_link = 'b';

                        $_modes = $_object->GetModes($_link);
                        //Dump($_modes);
                        usort($_modes,'user_modes_sort');

                        $_mode_ds = &$this->Kernel->Link('dataset.array');
                        $_mode_ds->setData($_modes);
                        $_mode_ds_param = array(
                            'mode'  => $_mode
                        );
                        $_mode_ds->setParams($_mode_ds_param);
                        $_main_ds->AddChildDS('modes',$_mode_ds);


                        $_params = $_object->GetParams($_mode);
                        $_params_ds = &$this->Kernel->Link('dataset.array');

                        $_defparams = array(
                            'object' =>  $_viewer[0]
                        );
                        $_params = $this->proccessDynamicParams($_params,$_block_params,$_defparams);                       
                        

                        $_params_ds->setData($_params);
                        $_main_ds->AddChildDS('params',$_params_ds);


                        $_tpls = $_object->GetTemplates($_mode);

                        $_templs_ds = &$this->Kernel->Link('dataset.array');

                        $Templates = &$this->Kernel->Link('backend.templates');

                        for($i=0;$i<sizeof($_tpls);$i++)
                        {
                            if (isset($_block_params['template'][$_tpls[$i]['name']]['file']))
                            {
                                $_tpls[$i]['activefile'] = $_block_params['template'][$_tpls[$i]['name']]['file'];
                                $_tpls[$i]['library'] = $Templates->getTemplateLib($_block_params['template'][$_tpls[$i]['name']]['file'],$_viewer[0],$_profile);
                            }
                        }

                        $_templs_ds->setData($_tpls);

                        $_templs_ds_params = array(
                            'object'    =>  $_viewer[0],
                            'profile'   =>  $_profile
                        );
                        $_templs_ds->setParams($_templs_ds_params);

                        $_avtpls = $Templates->GetAvailTemplateFiles($_viewer[0],$_profile,$_mode);
                        $_avtempls_ds = &$this->Kernel->Link('dataset.array');
                        usort($_avtpls,'user_templ_sort');
                        $_avtempls_ds->setData($_avtpls);
                        $_templs_ds->AddChildDS('avtempls',$_avtempls_ds);

                        $_main_ds->AddChildDS('templs',$_templs_ds);

                        $_templ = $_templs['dinamic'];
                    break;
                    case 't':

                                $Templates = &$this->Kernel->Link('backend.templates');

                                $_avtpls = $Templates->GetAvailTemplateFiles('_blocks',$_profile);

                        $_avtempls_ds = &$this->Kernel->Link('dataset.array');
                        $_avtempls_ds->setData($_avtpls);
                        $_avtempls_ds_params = array(
                                'templ'        =>        $_block_params['template']['main']['file']
                        );
                        $_avtempls_ds->setParams($_avtempls_ds_params);
                        $_main_ds->AddChildDS('avtempls',$_avtempls_ds);

                            $_template = $_template_lib = null;
                            if (isset($_block_params['template']['main']['file']))
                            {
                                $_template = $_block_params['template']['main']['file'];
                                $_template_lib = $Templates->getTemplateLib($_template,'_blocks',$_profile);
                            }

                        $_items['template'] = $_template;
                        $_items['template_lib'] = $_template_lib;
                        $_items['object'] = '_blocks';
                        $_items['profile'] = $_profile;

                        $_blocks_ds = &$this->Kernel->Link('dataset.array');

                        $Page->ChangeMap('parent');
                        $_parent_blocks = $Page->Map['blocks'];

                        $Page->ChangeMap('shuffle');
                        $_shuffle_blocks = $Page->Map['blocks'];

                        switch($_block_params['scope'])
                        {
                                case '0':
                                                                $Page->ChangeMap('this');
                                                                $_blocks = $Page->Map['blocks'];
                                unset($_blocks[$_block_params['name']]);
                            break;
                                case '1':
                                    $_blocks = array();
                                unset($_shuffle_blocks[$_block_params['name']]);
                            break;
                                case '2':
                                                                $Page->ChangeMap('childs');
                                                                $_blocks = $Page->Map['blocks'];
                                unset($_blocks[$_block_params['name']]);
                            break;
                        }

                        $_blocks = array_merge($_shuffle_blocks,$_blocks);
                        $_blocks = array_merge($_parent_blocks,$_blocks);
                        $_blocks = array_values($_blocks);

                        $_blocks_ds->SetData($_blocks);
                        $_main_ds->AddChildDS('blocks',$_blocks_ds);

                        $_slots_ds = &$this->Kernel->Link('dataset.array');
                        $_slots = isset($_block_params['slots'])?$_block_params['slots']:array();
                        $_slots_ds->setData($_slots);
                        $_main_ds->AddChildDS('slots',$_slots_ds);
                        $_slots_ds->AddChildDS('blocks',$_blocks_ds);

                        $_templ = $_templs['template'];
                    break;
                        default:

                                                $_templ = $_templs['static'];
                        $_state = $this->User->getParam('html_editor');
                        $_items['editor_state'] = $_state;

                            $HTMLEditor = &$this->Kernel->Link('htmleditor.main');
                            $_editor_params = array(
                                'htmldoc'                 =>  $_items['content'],
                            'editor_name'        =>        'content',
                            'state'                        =>        $_state,
                            );
                        $_items['editor'] = $HTMLEditor->Execute($_editor_params);

                    break;

                }
                    $_main_ds->SetParams($_items);


                                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_main_ds,$_templ,$this->Name);
                return $_result;
            break;
        }
    }
}

function user_blocks_sort($a_block,$b_block)
{
    $_types = array('s'=>1,'t'=>2,'d'=>3);
    if ($a_block['src']==$b_block['src'])
    {
        if ($a_block['name']==$b_block['name']) return 0;
        else return ($a_block['name']>$b_block['name'])?1:-1;
    }
    else return ($_types[$a_block['src']]>$_types[$b_block['src']])?1:-1;
}

function user_modes_sort($_a,$_b)
{
    return ($_a['desc']>$_b['desc'])?1:-1;
}

function user_templ_sort($_a,$_b)
{
    return ($_a['descript']>$_b['descript'])?1:-1;
}





global $Kernel;
$Kernel->LoadLib('array','dataset');

class CBlocks_DS extends CDataset_Array
{

    function Refresh()
    {
            $this->Params['was_setted'] = 0;
        parent::Refresh();
    }

        function Next()
    {

            if (isset($this->Items['setted']))
            if (!$this->Items['setted'])
                        $this->Params['was_parent'] = 1;
        else
        {
                $this->Params['was_setted'] = 1;
                $this->Params['was_parent'] = 0;
        }

        return parent::Next();
    }
}

