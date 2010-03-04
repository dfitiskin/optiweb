<?
class CBackend_Struct
{

	public $User = null;
    public $Name = '';
    public $WorkMode = '';
    public $FormValues = array();
    public $WarningsDS = '-1';
    public $LinkParams =  array();
    public $UrlParams = array();
    public $TreeParams = array();
    public $Tables = array();



	function Init()
    {
	    global $User;
		$this->User = &$User;
        $this->ProfileAlias = $this->User->GetCurrentProfile('alias');
        $this->ProfileID = $this->User->GetCurrentProfile('id');

        $this->Name = 'struct';
    	$this->Tree = &$this->Kernel->Link('backend.tree',true);
        $this->ModePrefixes = array(
        	'manager'		=>	'',
        	'detail'		=>	'_blocks/',
            'page'			=>  '_page/',
            'navigation'	=>  '_navigation/'
        );
		$this->Tables = array(
        	'types'		=>	'be_navigation',
        	'modules'	=>	'be_modules',
//            'versions'	=>	'be_module_versions',
        );
    }

    function Process($_url_params)
    {
    	if (sizeof($_url_params) && strpos($_url_params[0],'_')===0)
        	array_shift($_url_params);

		if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
        	isset($_POST['action']) && isset($_POST['mode']))
	    switch ($_POST['mode'])
        {
			case 'tree':
            	$this->ModifyTree($_POST,$_url_params);
            break;
        }
    }

    function ModifyTree($_params,$_url_params)
    {
        if ($_params['action'] == 'upd')
        {
        	if (isset($_params['del']))$_params['action'] = 'del';
        	if (isset($_params['ins_x']))$_params['action'] = 'add';
			if (isset($_params['up']))$_params['action'] = 'up';
			if (isset($_params['down']))$_params['action'] = 'down';
        }
    	switch ($_params['action'])
    	{
            case 'del':
                if (isset($_params['del']) && is_array($_params['del']))
                {
                	$_del = key($_params['del']);
                    $this->Tree->DeleteNode($_del);
                    $this->Control();
                }
            break;
            case 'add':
	            if (isset($_params['add']) && is_array($_params['add']))
                {
                	$_add = $_params['add'];

                    $Fitler = &$this->Kernel->Link('services.filter',true);
                    $_ruls = array(
                    	'alias'		=>	'sts;dnc',
                        'name'		=>	'sts',
                        'fullname'	=>	'sts'
                    );
                    $Fitler->FiltValues($_add,$_ruls);

	                $Checker = &$this->Kernel->Link('services.checker',true);
	                $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'tree');
                    $_fl = $Checker->VerifyValues($_add,$_ruls);
                    if ($_fl)
                    {

                        $_type = $this->Tree->getNodeParam('type');
						
                       	if ($_type != 2)
                       	switch($_add['type'])
                        {
                        	case 2:
							$_add['content'] = '1';
	                        case 0:
	                            $_id = $this->Tree->getNodeParam('id');
	                            $_fl2 = $this->Tree->CreateNode($_add,null,$_id);
	                            if (!$_fl2 && $_id)
	                            {
	                                $this->FormValues = $_params['add'];
	                                $Checker->addMessage($_ruls['_other']['alias_exists']);
	                                $this->WarningsDS = $Checker->GetWarningDS($_ruls);
	                            }
                            break;
                            case 1:
	                            $_error_code = $this->Tree->LinkModule($_add);
	                            if ($_error_code)
	                            {
	                                $this->FormValues = $_params['add'];
	                                if ($_error_code == 1)
	                                    $Checker->addMessage($_ruls['_other']['module_absent']);
	                                elseif($_error_code == 2)
	                                    $Checker->addMessage($_ruls['_other']['alias_exists']);
	                                elseif($_error_code == 3)
	                                    $Checker->addMessage($_ruls['_other']['module_error']);

	                                $this->WarningsDS = $Checker->GetWarningDS($_ruls);
	                            }
                            break;
                        }
//                        Dump($_add);

                    }else{
	                    $this->FormValues = $_params['add'];
	                    $this->WarningsDS = $Checker->GetWarningDS($_ruls);
                    }

            	}
            break;
            case 'up':
                if (isset($_params['up']) && is_array($_params['up']))
                {
                	foreach($_params['up'] as $k=>$v)
                    {
	                    $this->Tree->UpNode($k);
                    }
                }
            break;
            case 'down':
                if (isset($_params['down']) && is_array($_params['down']))
                {
                	foreach($_params['down'] as $k=>$v)
                    {
	                    $this->Tree->DownNode($k);
                    }
                }
            break;
      }
    }


	function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
        switch ($_params['mode'])
        {
        	case 'tab_menu' :
                $_ds = &$this->Kernel->Link('dataset.abstract');

                $_modes_ds = &$this->Kernel->Link('dataset.array');
                $_data = array(
                	array(
                    	'name'		=>	'Параметры',
                    	'alias'		=>	'',
                        'mode'    	=>	'manager'
                    ),
                );
                $_type = $this->Tree->getNodeParam('type');

                $_data[] = array(
                    'name'      =>  $_type == 1 ? 'Ключевые слова' : 'Содержание',
                    'alias'     =>  '_page/',
                    'mode'      =>  'page'
                );

                if ($_type != 2)
                {
                    $_data[] = array(
                    	'name'		=>	'Блоки и шаблоны',
                    	'alias'		=>	'_blocks/',
                        'mode'    	=>	'detail'
                    );
                }

                $_modes_ds->setData($_data);

                $_base_url = implode('/',$this->LinkParams);
                if ($_base_url) $_base_url .= '/';
				$_base_url = $this->Kernel->BaseUrl.$_base_url;

                $_url = implode('/',$this->TreeParams);
                if ($_url) $_url .= '/';

                $_params_modes_ds = array(
                	'activemode'	=>	$this->WorkMode,
                    'url'			=> 	$_url,
                    'baseurl'		=>	$_base_url
                );
                $_modes_ds->setParams($_params_modes_ds);

                $_ds->addChildDS('tabmenu',$_modes_ds);


                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
        	case 'tree':
				$_ds = &$this->Tree->GetRootDS();

                if (sizeof($_url_params) && $_url_params[0][0] == '_')
	    			$_url_params = array_slice($_url_params,1);

                if(!$this->Tree->isNodeSetted())
	                $this->Tree->setNode($_url_params);
                $_id = $this->Tree->getNodeParam('id');

                $_base_url = implode('/',$this->LinkParams);
                if ($_base_url) $_base_url .= '/';
				$_base_url = $this->Kernel->BaseUrl.$_base_url;


                $_ds_params = $this->FormValues;
                $_ds_params['active_id'] = $_id;
                $_ds_params['mode_alias'] = $this->ModePrefixes[$this->WorkMode];
                $_ds_params['baseurl'] = $_base_url;
                $_ds->SetParams($_ds_params);
                $_ds->addChildDS('warnings',$this->WarningsDS);

                $_ds_modules = &$this->Kernel->Link('dataset.database');
				$_ds_modules_params = array(
                    'active'	=>	isset($this->FormValues['src'])?$this->FormValues['src']:''
                );
                $_ds_modules->setParams($_ds_modules_params);
                $_ds_modules->SetQuery($this->Tables['modules'],'*','nodelink = 1','order by name');
                $_ds->addChildDS('modules',$_ds_modules);

                $_navtypes_ds = &$this->Kernel->Link('dataset.database');
                $_navtypes_ds->SetQuery($this->Tables['types'],'*','prid = "'.$this->ProfileID.'"');
                $_ds->addChildDS('navtypes',$_navtypes_ds);


                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'struct');
                return $_result;
            break;
            case 'pagelink':
                $_ds = &$this->Tree->GetRootDS();
                
                if (empty($this->TreeParams))
                {
                    $pageurl = '/';
                }
                else
                {
                    $pageurl = sprintf(
                        '/%s/',
                        implode('/', $this->TreeParams)
                    );
                }
                $_ds_params['pageurl'] = $pageurl;

                $_ds->SetParams($_ds_params);
            
                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'struct');
                return $_result;
            break;
        }
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
        $this->WorkMode = 'manager';
        $this->TreeParams = $this->UrlParams;
        if (sizeof($this->UrlParams))
    	switch($this->UrlParams[0])
        {
        	case '_edit' :
            	$this->TreeParams = array_slice($this->TreeParams,3);
				$this->WorkMode = 'editblock';
			break;
        	case '_view' :
            	$this->TreeParams = array_slice($this->TreeParams,3);
                $this->WorkMode = 'viewblock';
			break;
        	case '_blocks' :
            	$this->TreeParams = array_slice($this->TreeParams,1);
                $this->WorkMode = 'detail';
			break;
        	case '_page' :
                $this->TreeParams = array_slice($this->TreeParams,1);
                $this->WorkMode = 'page';
			break;
        	case '_navigation' :
                $this->TreeParams = array_slice($this->TreeParams,1);
                $this->WorkMode = 'navigation';
			break;

        }


    	$_fl = $this->Tree->setNode($this->TreeParams);

		if (!$this->Tree->NodeParams['link'] && $this->WorkMode == 'module')
        {
        	array_shift($this->UrlParams);
        	$_fl = false;
        }


        if (!$_fl)
        {
            $_length = sizeof($this->UrlParams) - $this->Tree->NodeError['level'];
            $_redirect_params = array_slice($this->UrlParams,0,$_length);
            $_redirect_url = sizeof($_redirect_params)?implode('/',$_redirect_params).'/':'';
            $_link_url = sizeof($this->LinkParams)?implode('/',$this->LinkParams).'/':'';
            $_redirect_url = $this->Kernel->BaseUrl.$_link_url.$_redirect_url;
            global $Page;
            $Page->setRedirect($_redirect_url);
        }


    }

    function CorrectParts($_parts)
    {
        return $_parts;
    }

    function IsCorrectParts()
    {
        if ($this->WorkMode == 'editblock' || $this->WorkMode == 'viewblock')
        {
	        $_fl = $this->Tree->setNode($this->TreeParams);
	        if (!$_fl) return false;
        }
        return true;
    }
}
?>