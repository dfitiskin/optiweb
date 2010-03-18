<?

class CBackend_Services
{
	public $Name = null;
    public $Tables;

	public $isUrlCorrect = true;
	public $ModuleParams = null;

	function Init()
    {
//    	$this->Name = 'services';
        $this->Tables = array(
			'modules'	=>	'be_modules'
        );
	    global $User;
		$this->User = &$User;
        $this->ProfileID = $this->User->GetCurrentProfile('id');
        $this->ProfileAlias = $this->User->GetCurrentProfile('alias');
        $this->WorkMode = 'main';
    }

	function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
        switch($_params['mode'])
        {
    		case 'tree':
            	$_ds = $this->Kernel->Link('dataset.abstract');
				$_tree_ds = $this->Kernel->Link('dataset.database');

                $_tree_ds_params = array(
                	'_url'		=>	$_link_url,
                    '_active'   =>  $this->ModuleParams['alias'],
                );
                $_tree_ds->setParams($_tree_ds_params);
                $_tree_ds->SetQuery($this->Tables['modules'],'*','service = 1','order by name');
                $_ds->AddChildDS('tree',$_tree_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'module_menu':
                $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.service',true);

                if ( method_exists($_obj,'getMenuDS') )
                {
	                $_ds = &$this->Kernel->Link('dataset.abstract');

                    $_modes_ds = $_obj->GetMenuDS($_type_params);

	                $_params_modes_ds = array(
	                	'activemode'	=>  isset($_type_params[0])?$_type_params[0]:null,
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
                $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.service',true);
                if (sizeof($_POST))
					$_obj->Process($_url_params);
                return $_obj->GetContent($_url_params,$_link_url);
            break;
        }
    }


    function CorrectParts($_parts)
    {
    	if (isset($_parts[0]))
        {
	        if (preg_match('/\w+/is',$_parts[0]))
	        {
	            $DbManager = &$this->Kernel->Link('database.manager',true);
	            $DbManager->Select($this->Tables['modules'],'*','alias = "'.$_parts[0].'"');
	            $this->ModuleParams = $DbManager->GetNextRec();
                if ($this->ModuleParams)
                {
                	$_parts[0] = '_module';
                	$this->WorkMode = 'module';
                    if (isset($_parts[1]))
                    {
	                   	$_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.service',true);
                        $this->isUrlCorrect = $_obj->IsCorrectParts(array_slice($_parts,1));

                    }
                }
                else $this->isUrlCorrect = false;
	        }
            else $this->isUrlCorrect = false;
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
        	$_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.service',true);
            $_obj->LinkUrl = $this->LinkUrl.$this->ModuleParams['alias'].'/';
            if ( method_exists($_obj,'Control') )
                $_obj->Control();
        }
    }

}

?>