<?

class CBackend_Profiles
{

	public $Tables = array();
    public $Ruls = array();
    public $WarningDS = -1;
    public $Params = array();

    public $WorkMode = null;
    public $ProfileProperty = null;

	function Init()
    {
    	$this->Tables = array(
        	'profiles'			=>	'be_profiles',
			'profiles_access'	=>	'be_profiles_access',
            'tree'				=>	'be_tree',
            'module_links'		=>	'be_module_links',
            'module_versions'	=>	'be_module_versions',
            'navigation'		=>	'be_navigation',

        );

        $this->Ruls = array(
			'add'	=> array(
            	'name'	=>	'nn;len|30',
                'alias'	=>	'nn;match|[\w\d]+;len|30'
            )
        );

   		$this->Name = 'system';
        $this->WorkMode  = 'list';
    }


    function Process($_url_params)
    {
		if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
        	isset($_POST['action']) && isset($_POST['mode']))
	    switch ($_POST['mode'])
        {
			case 'manage':
            	$this->ModifyProfile($_POST,$_url_params);
            break;
			case 'property':
            	$this->ModifyProperty($_POST,$_url_params);
            break;
        }
    }

    function ModifyProperty($_params)
    {
        switch ($_params['action'])
        {
            case 'add':
            	if ($_params['add']['hostname'] && $_params['add']['rootdir'])
                {
                	$_items = $_params['add'];
                    $_items['pid'] = $this->ProfileProperty['id'];
                    $DbManager = &$this->Kernel->Link('database.manager',true);
	            	$DbManager->InsertValues($this->Tables['profiles_access'],$_items);
    			}
            break;
            case 'del':
            	if (isset($_params['kill']) && sizeof($_params['kill']))
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
	            	$DbManager->DeleteValues($this->Tables['profiles_access'],'id',$_params['kill']);
    			}
            break;

		}
    }

    function ModifyProfile($_params)
    {
        switch ($_params['action'])
        {
            case 'del':
                if (isset($_params['kill']) && is_array($_params['kill']))
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $FManager = &$this->Kernel->Link('services.filemanager');
                    $Tree = &$this->Kernel->Link('backend.tree');
                    foreach ($_params['kill'] as $i => $profileId)
                    {
                    	$DbManager->Select($this->Tables['profiles'],'*','id='.$profileId);
                    	$rec = $DbManager->GetNextRec();
                    	if ($rec)
                    	{
                    		if (PROFILES_DIR != (PROFILES_DIR . $rec['alias']))
                    		{
                    			$FManager->DeleteFolder(PROFILES_DIR.$rec['alias']);
		                        $r = $DbManager->Select($this->Tables['tree'],'*','pid=0 and prid='.$rec['id']);
		                        $rec = $DbManager->getNextRec($r);
		                        if ($rec)
		                        {
		                        	$Tree->DeleteSubTree($rec['id']);
		                        }
                    		}
                    		
                    	}
                    }
                    /*
                     * 
                    for ($i=0;$i<sizeof($_POST['kill']);$i++)
                    {
                        $DbManager->Select($this->Tables['profiles'],'*','id='.$_POST['kill'][$i]);
                        $_rec = $DbManager->GetNextRec();
                        if ($_rec)
                        {
	                        $FManager->DeleteFolder(PROFILES_DIR.$_rec['alias']);
	                        $DbManager->Select($this->Tables['tree'],'*','pid=0 and prid='.$_rec['id']);
	                        $_rec = $DbManager->GetNextRec();
	                        $Tree->DeleteSubTree($_rec['id']);
                        }
                    }
					*/
                    
                    $DbManager->DeleteValues($this->Tables['profiles'], 'id', $_params['kill']);
                    $DbManager->DeleteValues($this->Tables['profiles_access'], 'pid', $_params['kill']);
                    $DbManager->DeleteValues($this->Tables['module_links'], 'prid', $_params['kill']);
                    $DbManager->DeleteValues($this->Tables['module_versions'], 'prid', $_params['kill']);
                    $DbManager->DeleteValues($this->Tables['navigation'], 'prid', $_params['kill']);
                }
            break;
            case 'add':
                $_items = $_params['add'];
                $this->Params = $_items;
                $Checker = &$this->Kernel->Link('services.checker',false);
                $_fl = $Checker->CheckValues($_items,$this->Ruls['add']);

                if ($_fl)
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $_count = $DbManager->GetRecsCount($this->Tables['profiles'],'alias="'.$_items['alias'].'"');

                    if (!$_count)
                    {
                    	$DbManager->InsertValues($this->Tables['profiles'],$_items);
                        $_items['id'] = $DbManager->GetLastId();
                        $_source = $_params['source'];
                        $this->CreateProfile($_items,$_source);
                    }
                    else
                    {
                        $_messes = $this->Kernel->LoadConfig('profiles','warnings');
                        $this->WarningDS = &$this->Kernel->Link('dataset.array');
                        $this->WarningDS->AddData($_messes['alias_exist']);
                    }
                }
                else
                {
                    $_messes = $this->Kernel->LoadConfig('profiles','warnings');
                    $this->WarningDS = &$Checker->GetWarningDS($_messes);
                }

            break;
        }
    }

    function CreateProfile($_profile,$_source)
    {
		$_source = explode('_',$_source);

    	switch($_source[0])
    	{
        	case 'new':
	            $Tree = &$this->Kernel->Link('backend.tree');
	            $_node = array(
	                'alias'     =>  'root',
	                'fullname'  =>  'главная',
	                'name'      =>  'главная'
	            );
	            $Tree->SetCurrentProfile($_profile);
	            $Tree->CreateNode($_node);
        	break;
        	case 'copy':
        		$DbManager = &$this->Kernel->Link('database.manager');
        		$FManager = &$this->Kernel->Link('services.filemanager');

                $DbManager->Select($this->Tables['profiles'],'*','id='.$_source[1]);
        		$_src_profile = $DbManager->getNextRec();
        		// клонирование дерева
                $r = $DbManager->Select($this->Tables['tree'],'*','pid=0 and prid='.$_src_profile['id']);
                $_src_root_node = $DbManager->getNextRec($r);

                // клонирование версий
                $this->cloneVersions($_src_profile['id'],$_profile['id']);

                // клонирование навигации
                $this->cloneTable($this->Tables['navigation'],$_src_profile['id'],$_profile['id']);


            	$this->TreeClone($_src_root_node,0,$_profile);
                $FManager->Copy(PROFILES_DIR.$_src_profile['alias'].'/'.TREE_DIR.'/',PROFILES_DIR.$_profile['alias'].'/'.TREE_DIR.'/');
                $FManager->Copy(PROFILES_DIR.$_src_profile['alias'].'/'.TEMPLS_DIR.'/',PROFILES_DIR.$_profile['alias'].'/'.TEMPLS_DIR.'/');
                $FManager->Copy(PROFILES_DIR.$_src_profile['alias'].'/'.BLOCKS_DIR.'/',PROFILES_DIR.$_profile['alias'].'/'.BLOCKS_DIR.'/');
                $FManager->Copy(PROFILES_DIR.$_src_profile['alias'].'/'.DATA_DIR.'/',PROFILES_DIR.$_profile['alias'].'/'.DATA_DIR.'/');

        	break;
    	}
    }

	function TreeClone($_src_node,$_pid,$_dest)
	{
        $DbManager = &$this->Kernel->Link('database.manager');

        // клонирование узла
		$_node = $_src_node;
		unset($_node['id']);
		$_node['pid'] = $_pid;
		$_node['prid'] = $_dest['id'];
        $DbManager->InsertValues($this->Tables['tree'],$_node);
        $_node['id'] = $DbManager->getLastID();

        // клонирование связей
        $r = $DbManager->Select($this->Tables['module_links'],'*','tid='.$_src_node['id']);
        $_link = $DbManager->getNextRec($r);
        if ($_link)
        {
	        unset($_link['id']);
	        $_link['tid'] = $_node['id'];
	        $_link['prid'] = $_dest['id'];
	        $DbManager->InsertValues($this->Tables['module_links'],$_link);
        }

        // клонирование узлов
        $r = $DbManager->Select($this->Tables['tree'],'*','pid='.$_src_node['id']);
        while($_src_node = $DbManager->getNextRec($r))
        {
			$this->TreeClone($_src_node,$_node['id'],$_dest);
        }

	}


    function cloneVersions($_src,$_dest)
	{
    	$DbManager = &$this->Kernel->Link('database.manager');
        $_res = $DbManager->Select($this->Tables['module_versions'],'*','type = 0 and prid='.$_src);
        while($_rec = $DbManager->getNextRec($_res))
        {
			unset($_rec['id']);
			$_rec['prid'] = $_dest;
			$DbManager->InsertValues($this->Tables['module_versions'],$_rec);
        }
	}

	function cloneTable($_table,$_src,$_dest)
	{
    	$DbManager = &$this->Kernel->Link('database.manager');
        $_res = $DbManager->Select($_table,'*','prid='.$_src);
        while($_rec = $DbManager->getNextRec($_res))
        {
			unset($_rec['id']);
			$_rec['prid'] = $_dest;
			$DbManager->InsertValues($_table,$_rec);
        }

	}

    function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
    	switch ($_params['mode'])
        {
			case 'list':

                //if ($this->WarningDS == null) $this->WarningDS = &$this->Kernel->Link('dataset.abstract');

            	$_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = $this->Params;
				$_ds_params['object'] = $this->Name;
                $_ds->SetParams($_ds_params);

                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_ds_profiles = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('profiles',$_ds_profiles);

                $_ds_profiles->SetQuery(
                    $this->Tables['profiles'],
                    '*',
                    null,
                    'ORDER BY name'
                );
                
                $_profile_params = array(
                	'_url'	=>	$_link_url
                );
                $_ds_profiles->setParams($_profile_params);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['list'],$this->Name);
            	return $_result;
            break;
			case 'property':

            	$_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = $this->ProfileProperty;
				$_ds_params['object'] = $this->Name;
                $_ds->SetParams($_ds_params);

                $_ds->addChildDS('warnings',$this->WarningDS);

                $_ds_access = &$this->Kernel->Link('dataset.database');
                $_ds_access->setQuery($this->Tables['profiles_access'],'*','pid="'.$this->ProfileProperty['id'].'"');
                $_ds->AddChildDS('access',$_ds_access);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['property'],$this->Name);
            	return $_result;
            break;
        }
    }

    function CorrectParts($_params)
    {
        if (preg_match('/^\w+$/',$_params[0]))
        {

        	$DbManager = &$this->Kernel->Link('database.manager',true);
            $DbManager->Select($this->Tables['profiles'],'*','alias = "'.$_params[0].'"');
			$_rec = $DbManager->getNextRec();
            if ($_rec)
            {
            	$this->ProfileProperty = $_rec;
	            $this->WorkMode = 'property';
	            $_params[0] = '_profile';
            }
        }
        return $_params;
    }

    function isCorrectParts()
    {
        return true;
    }

    function GetAccess()
    {
    	return true;
    }

}

?>