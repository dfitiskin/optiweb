<?

class CBackend_Inter
{
    public $Name = null;
    public $Tables;

    public $isUrlCorrect = true;
    public $ModuleParams = null;
    public $ModuleVersion = null;
    public $WarningsDS = -1;
    public $FormValues = array();
    public $ProfileID = null;

    public $WorkMode = null;
	public $LinkUrl = '';

    function Init()
    {
        $this->Tables = array(
            'modules'        =>        'be_modules',
            'versions'       =>        'be_module_versions',
            'links'          =>        'be_module_links',
            'tree'           =>        'be_tree',
        );
        $this->Name = 'inter';

        global $User;
        $this->User = &$User;
        $this->ProfileID = $this->User->GetCurrentProfile('id');
        $this->ProfileAlias = $this->User->GetCurrentProfile('alias');
    }

    function Process($_url_params)
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
            isset($_POST['action']) && isset($_POST['mode']))
        switch ($_POST['mode'])
        {
            case 'version':
                $this->ModifyVersion($_POST,$_url_params);
            break;
        }
    }

    function ModifyVersion($_params)
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
                $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'version');

                $_fl = $Checker->VerifyValues($_add,$_ruls);
                if ($_fl)
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select(
                        $this->Tables['versions'].' v',
                        '*',
                        'v.mid = "'.$this->ModuleParams['id'].'" and (v.type = 1 or v.prid= "'.$this->ProfileID.
						'") and alias = "'.$_add['alias'].'"'
                    );
                    $_rec = $DbManager->getNextRec();
                    if ($_rec)
                    {
                        $this->FormValues = $_params['add'];
                        $Checker->addMessage($_ruls['_other']['alias_exists']);
                        $this->WarningsDS = $Checker->GetWarningDS($_ruls);
                    }
                    else
                    {
                        $_items = array(
                            'name'  =>   $_add['name'],
                            'alias' =>   $_add['alias'],
                            'prid'  =>   $this->ProfileID,
                            'mid'   =>   $this->ModuleParams['id']
                        );
                        $DbManager->InsertValues($this->Tables['versions'],$_items);
                    }
                }
                else
                {
                    $this->FormValues = $_params['add'];
                    $this->WarningsDS = $Checker->GetWarningDS($_ruls);
                }
            break;
            case 'del':
                if (isset($_params['del']) && is_array($_params['del']))
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.inter',true);
                    if ($_obj->deleteVersions($_params['del']))
                    {
                        $DbManager->DeleteValues($this->Tables['versions'],'id',$_params['del']);
                    }
                }
            break;
        }
    }

    function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
        
    	switch($_params['mode'])
        {
            case 'tab_menu':
				$_ds = & $this->Kernel->Link('dataset.abstract');
                
                if ($this->ModuleVersion)
                {
                
                    $_obj = & $this->Kernel->Link($this->ModuleParams['alias'] . '.inter', true);
        
                    if (method_exists($_obj, 'GetMenuDS'))
                    {
                        $_modes_ds = $_obj->GetMenuDS($_type_params);
                    }
                    else
                    {
                        $_modes_ds = & $this->Kernel->Link('dataset.array');
                    }
                }
                else
                {
                    $_modes_ds = & $this->Kernel->Link('dataset.array');
                    $_data = array(
                        array(
                            'name'  => 'Информация о модуле',
                            'alias' => '',
                            'mode'  => null
                        ),
                        array(
                            'name'  => 'Менеджер версий',
                            'alias' => '_manager/',
                            'mode'  => '_manager'
                        ),
                    );              
                    $_modes_ds->SetData($_data);
                    $_modes_ds->AddParam('activemode', isset($_type_params[0]) ? $_type_params[0] : null);
                }

                $_base_url = $_link_url;

                $_params_modes_ds = array (
                    'activemodes' => $_type_params,
                    'baseurl'     => $_base_url
                );
                $_modes_ds->AddParams($_params_modes_ds);
                
                $_ds->AddChildDS('tabmenu', $_modes_ds);

                $TplManager = & $this->Kernel->Link('template.manager', true);
                $_result = $TplManager->Execute($_ds, $_templs['main'], 'backend');
                return $_result;
            break;
            case '~tab_menu':
                $_ds = &$this->Kernel->Link('dataset.abstract');

                $_modes_ds = &$this->Kernel->Link('dataset.array');
                $_data = array(
                    array(
                        'name'   =>   'Администратор',
                        'alias'  =>   '',
                        'mode'   =>   isset($_type_params[0])?'_version':null
                    ),
                    array(
                        'name'   =>   'Менеджер версий',
                        'alias'  =>   '_manager/',
                        'mode'   =>   '_manager'
                    ),
                );
                $_modes_ds->setData($_data);
                $_base_url = $_link_url;

                $_params_modes_ds = array(
                    'activemode'  =>  isset($_type_params[0])?$_type_params[0]:null,
                    'baseurl'     =>  $_base_url
                );
                $_modes_ds->setParams($_params_modes_ds);

                $_ds->addChildDS('tabmenu',$_modes_ds);


                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                //return $_result;
            break;
            case 'tree':
                $_ds = $this->Kernel->Link('dataset.abstract');

                global $User;
                $_tree_ds = & $User->getModulesDS();
                $_tree_ds_params = array(
                    '_url'        =>  $_link_url,
                    '_active'     =>  $this->ModuleParams['alias'],
                    'is_sublist'  =>  1
                );
                $_tree_ds->setParams($_tree_ds_params);
                $_ds->addChildDS('tree',$_tree_ds);
                if ($this->ModuleParams['multiversion'])
                {
                    $_versions_ds = & $User->getVersionsDs($this->ModuleParams['id'], $this->ProfileID);

                    $_versions_ds_params = array(
                        '_url'          =>  $_link_url.$this->ModuleParams['alias'].'/',
                        'is_sublist'    =>  0,
                        '_active'       =>  $this->ModuleVersion?$this->ModuleVersion['alias']:null
                    );
                    $_versions_ds->setParams($_versions_ds_params);
                }
                else
                {
                    $_versions_ds = &$this->Kernel->Link('dataset.abstract');
                }

                $_tree_ds->addChildDS('sublist',$_versions_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
            case 'manager':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = $this->FormValues;
                $_ds->SetParams($_ds_params);
                $_ds->addChildDS('warnings',$this->WarningsDS);

                $_version_ds = &$this->Kernel->Link('dataset.database');
                $_version_ds->setQuery(
                            $this->Tables['versions'].' v',
                            '*',
                        'v.mid = "'.$this->ModuleParams['id'].'" and (v.type = 1 or v.prid= "'.$this->ProfileID.'")'
                );
                $_ds->addChildDS('versions',$_version_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
            case 'info':

                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                        '_url'        =>        $_link_url
                );
                $_ds->SetParams($_ds_params);
                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
            case 'module_menu':
               
			   $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.inter',true);

                if ( method_exists($_obj,'getMenuDS') )
                {
                        $_ds = &$this->Kernel->Link('dataset.abstract');

                    $_modes_ds = $_obj->GetMenuDS($_type_params);

						$_params_modes_ds = array(
        //                    'activemode'        =>  isset($_type_params[0])?$_type_params[0]:null,
        //                    'url'         =>  $_url,
                            'baseurl'       =>  $_link_url
                        );
                        $_modes_ds->addParams($_params_modes_ds);

                        $_ds->addChildDS('tabmenu',$_modes_ds);


                        $TplManager = &$this->Kernel->Link('template.manager',true);
                        $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                }
                                else
                {
                    $_result = '';
                }

                return $_result;


            break;
            case 'module':
                $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.inter',true);
				if (sizeof($_POST))
				{
					$_obj->Process($_url_params);
				}
				
	         	$linkUrl = $this->LinkUrl . $this->ModuleParams['alias'].'/';
				if ($this->ModeType == 'multi')
				{
					$linkUrl .= $this->ModuleVersion['alias'] .'/';
				}
				if (method_exists($_obj, 'setLinkUrl'))
				{
					$_obj->setLinkUrl($linkUrl);
				}
				else
				{
					$_obj->LinkUrl = $linkUrl;
				}
                return $_obj->GetContent($_url_params);
            break;
        }
    }

    function ContentUpdated()
    {
            $_ver_sql = $this->ModuleVersion?' and version = "'.$this->ModuleVersion['alias'].'"':null;

            $DbManager = &$this->Kernel->Link('database.manager');
        $_res = $DbManager->Select($this->Tables['links'],'*','prid='.$this->ProfileID.' and mid='.$this->ModuleParams['id'].' '.$_ver_sql);

        while ($_rec = $DbManager->getNextRec($_res))
        {
                $_items = array(
                    'contenttime'   =>  'now()'
                );
                $DbManager->updateValues($this->Tables['tree'],$_items,'id='.$_rec['tid']);
                }
//        Dump($_rec);
    }

    function CorrectParts($_parts)
    {
        if (isset($_parts[0]))
        {
            if (preg_match('/\w+/is',$_parts[0]))
            {
                $DbManager = &$this->Kernel->Link('database.manager',true);
                $DbManager->Select(
                    $this->Tables['modules'],
                    '*',
                    'alias = "'.$_parts[0].'"'
                );
                $this->ModuleParams = $DbManager->GetNextRec();

                
                if ($this->ProfileID) 
                {
	                if ($this->ModuleParams)
	                {
	                	if ($this->ModuleParams['multiversion'])
	                    {
	                        $_parts[0] = '_module';
	
	                        if (isset($_parts[1]))
	                        {
	                            if ($_parts[1] != '_manager')
	                            {
	
	                                $DbManager = &$this->Kernel->Link('database.manager',true);
	                                $DbManager->Select(
	                                    $this->Tables['versions'].' v',
	                                    '*',
	                                    'v.mid = "'.$this->ModuleParams['id'].'" and (v.type = 1 or v.prid= "'.$this->ProfileID.'") and alias = "'.$_parts[1].'"'
	                                );
	                                $_rec = $DbManager->getNextRec();
	                                if ($_rec)
	                                {
	                                    $this->ModuleVersion = $_rec;
	                                    $_parts[1] = '_version';
										
										$_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.inter',true);
										if (method_exists($_obj, 'setVersion'))
										{
											$_obj->setVersion($this->ModuleVersion['alias']);
										}
										/*
										else
										{
											//$_obj->Version = $this->ModuleVersion['alias'];
										}
										*/
										
	                                }
	                                else $this->isUrlCorrect = false;
	                            }
	
	                            $this->WorkMode = 'module';
	                            $this->ModeType = 'multi';
	
	                            if ($this->isUrlCorrect && isset($_parts[2]))
	                            {
	                            	$_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.inter',true);
						            $linkUrl = $this->LinkUrl . $this->ModuleParams['alias'].'/';
									if ($this->ModeType == 'multi')
									{
										$linkUrl .= $this->ModuleVersion['alias'] .'/';
									}
									if (method_exists($_obj, 'setLinkUrl'))
									{
										$_obj->setLinkUrl($linkUrl);
									}
									else
									{
										$_obj->LinkUrl = $linkUrl;
									}
	                                $this->isUrlCorrect = $_obj->isCorrectParts(array_slice($_parts,2));
	                            }
	                        }
	                    }
	                    else
	                    {
	                        $_parts[0] = '_single';
	                        $this->WorkMode = 'module';
	                        $this->ModeType ='single';
	                        if (isset($_parts[1]))
	                        {
	                            $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.inter',true);
					            $linkUrl = $this->LinkUrl . $this->ModuleParams['alias'].'/';
								if ($this->ModeType == 'multi')
								{
									$linkUrl .= $this->ModuleVersion['alias'] .'/';
								}
								if (method_exists($_obj, 'setLinkUrl'))
								{
									$_obj->setLinkUrl($linkUrl);
								}
								else
								{
									$_obj->LinkUrl = $linkUrl;
								}
	                            $this->isUrlCorrect = $_obj->isCorrectParts(array_slice($_parts,1));
	                        }
	                    }
	                }
	                else
	                {
	                    $this->isUrlCorrect = false;
	                }
                }
                else 
                { 
                	$_parts[0] = '_module'; $this->isUrlCorrect = true; 
                }
                
            }
            else
            {
                $this->isUrlCorrect = false;
            }
        }
        return $_parts;
    }

    function IsCorrectParts()
    {
        return  $this->isUrlCorrect;
    }

    function GetAccess()
    {
        return true;
    }

    function GetWorkType()
    {
        return PAGE_MODE_NORMAL;
    }

    function Control()
    {
    	if ($this->WorkMode == 'module')
        {
            $_obj = & $this->Kernel->Link($this->ModuleParams['alias'].'.inter', true);
			if (method_exists($_obj,'Control'))
			{
                $_obj->Control();
			}
        }
    }

}

?>