<?

class CBackend_Templates
{
	public $Name;
    public $Tables;

    public $User;

    public $WorkMode = null;
    public $Object = null;
    public $Library = null;
    public $File = null;

    public $FormValues = null;
    public $WarningsDS = '-1';

    public $fileDescr = null;

    function Init()
    {
        $this->FormValues = array();
	    global $User;
		$this->User = &$User;
        $this->ProfileAlias = $this->User->GetCurrentProfile('alias');
        $this->ProfileID = $this->User->GetCurrentProfile('id');

		$this->Name = 'templates';
        $this->Tables = array(
        	'types'		=>	'be_templatetypes',
        	'modules'	=>	'be_modules'
        );

        $this->fileDescr = &$this->Kernel->Link('backend.filedescr');
        $this->WorkMode = 'list';
    }

  	function Process($_url_params)
    {
		if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
        	isset($_POST['action']) && isset($_POST['mode']))
	    switch ($_POST['mode'])
        {
			case 'main':
            	$this->ModifyTemplates($_POST,$_url_params);
            break;
			case 'types':
            	$this->ModifyTypes($_POST,$_url_params);
            break;

        }
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
                $_ruls = &$this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'add_type');

                $_fl = $Checker->VerifyValues($_add,$_ruls);

                if ($_fl)
                {

                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select($this->Tables['types'],'id',
                    	'alias="'.$_add['alias'].'"'
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


    function ModifyTemplates($_params,$_url_params)
    {

    	switch ($_params['action'])
    	{
        	case 'edit':

				$_profile = $this->User->GetCurrentProfile('alias');
                $_path = PROFILES_DIR;
                if ($this->Library == '_profile') $_path .= $_profile.'/';
                $_path .= TEMPLS_DIR.'/';
                if ($this->Object !== '_main' ) $_path .= $this->Object.'/';

                $_items = array(
                	str_replace('.','_',$this->File)	=>	$_params['desript']
                );

                $this->fileDescr->SetDescript($_path,$_items);

                $_path .= $this->File;
                $_data = $_params['content'];
                $_data = stripslashes($_data);
                $FManager = &$this->Kernel->Link('services.filemanager');
                $FManager->WriteFile($_path,$_data);

            break;
        	case 'del':
            	if (isset($_params['del_x']))
                {
                	$_profile = $this->User->GetCurrentProfile('alias');
	                $_path = PROFILES_DIR;
	                if ($_params['lib'] == 'profile') $_path .= $_profile.'/';
	                $_path .= TEMPLS_DIR.'/';
	                if ($this->Object !== '_main' ) $_path .= $this->Object.'/';

                    $_del = $_params['files'];
                    for ($i=0;$i<sizeof($_del);$i++)
                    {
	                    $_file = $_del[$i];
	                    $this->fileDescr->DelDescript($_path,str_replace('.','_',$_file));
	                    if (file_exists($_path.$_file)) unlink($_path.$_file);
                    }
                }
            break;
            case 'add':
	            $_profile = $this->User->GetCurrentProfile('alias');
            	$_path_to = PROFILES_DIR;
                if ($_params['to'] == 'profile') $_path_to .= $_profile.'/';
            	$_path_to .= TEMPLS_DIR.'/';
                if ($this->Object !== '_main' ) $_path_to .= $this->Object.'/';
                if ($_params['from'])
                {
	                if ($_params['from'] == 'new')
	                {
	                    if ($_params['filename'] != '' &&  $_params['type'] != '')
	                    {
                        	$_file_path_to = null;
                        	if ($this->Object == '_main')
                            {
	                            $_filename = $_params['filename'].'.'.$_params['type'];
	                            $_file_path_to = $_path_to . $_filename;
                            }
                        	else
                            {
	                            $_filename = $_params['type'].'_'.$_params['filename'].'.tpl';
	                            $_file_path_to = $_path_to . $_filename;
                            }

	                        if ($_file_path_to && !file_exists($_file_path_to))
	                        {
	                            $FManager = &$this->Kernel->Link('services.filemanager');
	                            $_str = ' ';
	                            $FManager->WriteFile($_file_path_to,$_str);
                                if (!isset($_params['descript'])) $_params['descript'] = '';
                                $_items = array(
                                    str_replace('.','_',$_filename) =>	$_params['descript']
                                );
                                $this->fileDescr->SetDescript($_path_to,$_items);
	                        }
	                    }

	                }
	                else
	                {
	                    list($_lib,$_file) = explode('_',$_params['from'],2);
                        list($_filename,$_ext) = explode('.',$_file);


                        $_file_path_to = null;
                        if ($this->Object == '_main')
                        {
	                        if ($_params['filename'] != '') $_filename = $_params['filename'];
	                        $_filename_to =  $_filename.'.'.$_ext;
	                        $_file_path_to = $_path_to . $_filename_to;
                        }
                        else
                        {
                        	if ($_params['filename'] != '')
                            {
                           		$_type = explode('_',$_filename,2);
                                $_type = $_type[0];
                                $_filename_to = $_type.'_'.$_params['filename'].'.'.$_ext;
	                        }
                            else
	                            $_filename_to =  $_filename.'.'.$_ext;

                            $_file_path_to = $_path_to . $_filename_to;
                        }

                        if (!file_exists($_file_path_to))
                        {
	                        $_path_from = PROFILES_DIR;
	                        if ($_lib == 'profile') $_path_from .= $_profile.'/';
	                        $_path_from .= TEMPLS_DIR.'/';
	                        if ($this->Object !== '_main' ) $_path_from .= $this->Object.'/';
                            $_file_path_from = $_path_from . $_file;

                            $FManager = &$this->Kernel->Link('services.filemanager');
                            $_data = &$this->Kernel->ReadFile($_file_path_from);
                            $FManager->WriteFile($_file_path_to,$_data);

							if (isset($_params['descript']) && $_params['descript'])
                            {
                        		$_desc = $_params['descript'];
                            }
                            else
                            {
	                            $_descripts = $this->fileDescr->GetDescript($_path_from);
								$_desc = isset($_descripts[str_replace('.','_',$_file)])?$_descripts[str_replace('.','_',$_file)]:'';
                            }
                            $_items = array(
                            	str_replace('.','_',$_filename_to)	=> $_desc
                            );
                            $this->fileDescr->SetDescript($_path_to,$_items);
                        }
	                }
                }
            break;
		}
    }

    function GetTemplateFiles($_object, $_profile = null, $_mode=null, $_reg_exp='/^tpl|css$/')
    {
    	$_path = sprintf(
    	   '%s%s%s%s', 
    	   PROFILES_DIR,
    	   $_profile ? $_profile.'/' : null,
    	   TEMPLS_DIR.'/',
    	   $_object != '_main' ? $_object.'/' : null
        );

        if (file_exists($_path))
        {
            $_descripts = $this->fileDescr->GetDescript($_path);
            $filemanager = $this->Kernel->Link('services.filemanager');
            $files = $filemanager->getFilesList($_path, 'file');
            
			$_items = array();
            foreach ($files as $file)
            {
                $fileinfo = pathinfo($file);
                
                if (isset($fileinfo['extension']) && preg_match($_reg_exp, $fileinfo['extension']))
                {
                    $nameParts = explode('_', $fileinfo['filename'], 2);
                    
                    if (count($nameParts) < 2)
                    {
                        $nameParts[1] = $nameParts[0];
                        $nameParts[0] = null;
                    }
                    
                    if (!$_mode || $_mode == $nameParts[0])
                    {
                        $_desc = null;
                        if (isset($_descripts[str_replace('.', '_', $fileinfo['basename'])]))
                        {
                            $_desc =  $_descripts[str_replace('.', '_', $fileinfo['basename'])];
                        }
                        
                        $_items[] = array(
                            'filename'  =>  $fileinfo['basename'],
                            'file'      =>  $fileinfo['filename'],
                            'type'      =>  $nameParts[0],
                            'name'      =>  $nameParts[1],
                            'ext'       =>  $fileinfo['extension'],
                            'descript'  =>  $_desc
                        );
                    }
                }
            }
            return $_items;
        }
        return array();
    }

    function &GetAvailTemplateFiles($_object,$_profile = null,$_mode = null)
    {
        $_templs_list = $this->GetTemplateFiles($_object,null,$_mode,'/^tpl$/');
        $_templs_list_profile = $this->GetTemplateFiles($_object,$_profile,$_mode,'/^tpl$/');

        $_profile_count = sizeof($_templs_list_profile);
        for ($i=0;$i<$_profile_count;$i++)
        	$_templs_list_profile[$i]['lib'] = 'p';
        for ($i=0;$i<sizeof($_templs_list);$i++)
        {
            $_name = $_templs_list[$i]['filename'];

            $fl = true;
            for ($j=0;$j<$_profile_count;$j++)
                if ($_templs_list_profile[$j]['filename'] == $_name)
                {
                    $fl = false;break;
                }
            if ($fl)
            {
            	$_templs_list[$i]['lib'] = 'g';
            	array_push($_templs_list_profile,$_templs_list[$i]);
            }
        }
        for ($i=0;$i<sizeof($_templs_list_profile);$i++)
			if ($_templs_list_profile[$i]['ext'] == 'css')
            	unset($_templs_list_profile[$i]);
        $_templs_list_profile = array_values($_templs_list_profile);


        return $_templs_list_profile;
    }

    function getTemplateLib($_name,$_object,$_profile)
    {
    	$_templ_path = TEMPLS_DIR.'/';
        if ($_object !== '_main' ) $_templ_path .= $_object.'/';

        $_filepath = PROFILES_DIR.$_profile.'/'.$_templ_path.$_name;
        if (file_exists($_filepath)) return '_profile';

        $_filepath = PROFILES_DIR.$_templ_path.$_name;
        if (file_exists($_filepath)) return '_general';

		return null;
    }

    function sortTemplates(&$_data)
    {
        usort($_data,"user_templates_sort");
    }

    function sortTemplates2(&$_data)
    {
        usort($_data,"user_templates_sort2");
    }

    function sortTemplates3(&$_data)
    {
        usort($_data,"user_templates_sort3");
    }



	function Execute($_params,$_templs,$_url_params,$_type_params,$_link_url)
    {
        switch ($_params['mode'])
        {
        	case 'tree':
				$_ds = &$this->Kernel->Link('dataset.abstract');

                /*
				$_ds_modules = new CBackend_Templates_Tree_DS();
                $_ds_modules->Kernel = &$this->Kernel;*/
                $_ds_modules = &$this->Kernel->Link('dataset.database');
                $_ds_modules->SetQuery($this->Tables['modules'],'*','templates = 1','order by name');


                $_ds_params = array(
                	'_active'	=> $this->Object,
                    '_url'	=>	$_link_url
                );

                $_ds_modules->SetParams($_ds_params);

                $_items = array(
                	'name'	=>	'Главный',
                    'alias'	=>	'_main'
                );
                $_ds_modules->AddData($_items);
                $_items = array(
                	'name'	=>	'Блоки',
                    'alias'	=>	'_blocks'
                );
                $_ds_modules->AddData($_items);


                $_ds->AddChildDS('tree',$_ds_modules);
                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
            case 'main':

                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_profile = $this->User->GetCurrentProfile('alias');
                $_modes = array(
                	'ie'	=>	'Internet Explorer',
                    'op'	=>	'Opera',
                    'mo'	=>	'Mozilla',
                    'ne'	=>	'Netscape'
                );

                $_ds_main = $this->Kernel->LinkClass('CTemplates_ListDS');
                $_ds_params = array(
                	'object'	=>	$this->Object,
                    'library'	=>	'general',
                    'lib_name'	=>	'Общая библиотека'
                );
                $_ds_main->SetParams($_ds_params);
                $_ds_main->setModes($_modes);

                $_items = $this->GetTemplateFiles($this->Object);
                $this->sortTemplates($_items);
                $_ds_main->SetData($_items);
                $_ds->AddChildDS('general',$_ds_main);

                $_ds_profile = $this->Kernel->LinkClass('CTemplates_ListDS');
                $_ds_params = array(
                	'object'	=>	$this->Object,
                    'library'	=>	'profile',
                    'lib_name'	=>	'Библиотека профиля'
                );

                $_ds_profile->SetParams($_ds_params);
                $_ds_profile->setModes($_modes);

                $_items = $this->GetTemplateFiles($this->Object,$_profile);
                $this->sortTemplates($_items);
                $_ds_profile->SetData($_items);
                $_ds->AddChildDS('profile',$_ds_profile);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'blocks':

                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_profile = $this->User->GetCurrentProfile('alias');

                $DbManager = &$this->Kernel->Link('database.manager',true);
				$DbManager->Select($this->Tables['types']);
                $_modes = array();
                $_data = array();
                while($_rec = $DbManager->getNextRec())
                {
                	$_data[] = $_rec;
	                $_modes[$_rec['alias']] = $_rec['name'];
                }

                $_ds_modes = $this->Kernel->Link('dataset.array');
                $_ds_modes->setData($_data);
                $_ds->AddChildDS('modes',$_ds_modes);


                $_ds_main = $this->Kernel->LinkClass('CTemplates_ListDS');
                $_ds_params = array(
                	'object'	=>	$this->Object,
                    'library'	=>	'general',
                    'lib_name'	=>	'Общая библиотека'
                );
                $_ds_main->SetParams($_ds_params);
                $_ds_main->setModes($_modes);

                $_items = $this->GetTemplateFiles($this->Object);
                $this->sortTemplates3($_items);
                $_ds_main->SetData($_items);
                $_ds->AddChildDS('general',$_ds_main);

                $_ds_profile = $this->Kernel->LinkClass('CTemplates_ListDS');
                $_ds_params = array(
                	'object'	=>	$this->Object,
                    'library'	=>	'profile',
                    'lib_name'	=>	'Библиотека профиля'
                );
                $_ds_profile->SetParams($_ds_params);
                $_ds_profile->setModes($_modes);

                $_items = $this->GetTemplateFiles($this->Object,$_profile);
                $this->sortTemplates3($_items);
                $_ds_profile->SetData($_items);
                $_ds->AddChildDS('profile',$_ds_profile);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'blocktypes':

            	$_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = $this->FormValues;
                $_ds_params['_object'] = $this->Name;
                $_ds_params['_url'] = $this->Kernel->Url;
                $_ds->SetParams($_ds_params);

                $_ds->addChildDS('warnings',$this->WarningsDS);

				$_types_ds = &$this->Kernel->Link('dataset.database');
                $_types_ds->SetQuery($this->Tables['types'],'*',null,'order by name');
            	$_ds->addChildDS('types',$_types_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'module':

//            	Dump($this->Object);

                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_profile = $this->User->GetCurrentProfile('alias');

                $_object = &$this->Kernel->Link($this->Object.'.params');
                $_modes = $_object->getModesDescr();
                $_data = $_object->Modes;

                $_ds_modes = $this->Kernel->Link('dataset.array');
                $_ds_modes->setData($_data);
                $_ds->AddChildDS('modes',$_ds_modes);


                $_ds_main = $this->Kernel->LinkClass('CTemplates_ListDS');
                $_ds_params = array(
                	'object'	=>	$this->Object,
                    'library'	=>	'general',
                    'lib_name'	=>	'Общая библиотека'
                );
                $_ds_main->SetParams($_ds_params);
                $_ds_main->setModes($_modes);

                $_items = $this->GetTemplateFiles($this->Object);
                $_ds_main->SetData($_items);
                $_ds->AddChildDS('general',$_ds_main);

                $_ds_profile = $this->Kernel->LinkClass('CTemplates_ListDS');
                $_ds_params = array(
                	'object'	=>	$this->Object,
                    'library'	=>	'profile',
                    'lib_name'	=>	'Библиотека профиля'
                );
                $_ds_profile->SetParams($_ds_params);
                $_ds_profile->setModes($_modes);

                $_items = $this->GetTemplateFiles($this->Object,$_profile);
                $_ds_profile->SetData($_items);
                $_ds->AddChildDS('profile',$_ds_profile);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'edit':
				$_profile = $this->User->GetCurrentProfile('alias');
                $_path = PROFILES_DIR;
                if ($this->Library == '_profile') $_path .= $_profile.'/';
                $_path .= TEMPLS_DIR.'/';
                if ($this->Object !== '_main' ) $_path .= $this->Object.'/';
				$_file_path = $_path . $this->File;
				$_descripts = $this->fileDescr->GetDescript($_path);

                $_desc = isset($_descripts[str_replace('.','_',$this->File)])?$_descripts[str_replace('.','_',$this->File)]:'';
                $_data = $this->Kernel->ReadFile($_file_path);

				$_main_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                	'library'	=>	$this->Library,
                	'filename'	=>	$this->File,
                    'content'	=>   $_data,
                    'descript'	=>	$_desc
                );

                $_main_ds->SetParams($_ds_params);

				$TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_main_ds,$_templs['main'],$this->Name);
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
    	if (sizeof($this->UrlParams))
    	switch ($this->WorkMode)
        {
        	case 'edit':
	            $this->Mode = $this->UrlParams[0];
	            $this->Object = $this->UrlParams[1];
	            if (isset($this->UrlParams[2])) $this->Library = $this->UrlParams[2];
	            if (isset($this->UrlParams[3])) $this->File = $this->UrlParams[3];
            break;
        	case 'list':
	            $this->Object = $this->UrlParams[0];
            break;
        }
    }

    function CorrectParts($_parts)
    {
		switch($_parts[0])
        {
			case '_edit':
				$this->WorkMode = 'edit';
            break;
            default:
            	$DbManager = &$this->Kernel->Link('database.manager',true);
                $DbManager->Select($this->Tables['modules'],'*','templates = 1 and alias="'.$_parts[0].'"');
                $_rec = $DbManager->getNextRec();
                if ($_rec)
                {
                	$_parts[0] = '_module';
                }
            break;
        }
        return $_parts;
    }
    function IsCorrectParts()
    {
    	return true;
    }

}

function user_templates_sort($a_templ,$b_templ)
{
    if ($a_templ['ext']==$b_templ['ext'])
    {
        if ($a_templ['name']==$b_templ['name']) return 0;
        else return ($a_templ['name']>$b_templ['name'])?1:-1;
    }
    else return ($a_templ['ext']>$b_templ['ext'])?1:-1;
}

function user_templates_sort2($a_templ,$b_templ)
{
	if ($a_templ['name']==$b_templ['name'])
    {
    	if ($a_templ['ext']==$b_templ['ext']) return 0;
    	else return ($a_templ['ext']>$b_templ['ext'])?1:-1;
    }
    else return ($a_templ['name']>$b_templ['name'])?1:-1;
}

function user_templates_sort3($a_templ,$b_templ)
{
	if ($a_templ['type']==$b_templ['type'])
    {
    	if ($a_templ['name']==$b_templ['name']) return 0;
    	else return ($a_templ['name']>$b_templ['name'])?1:-1;
    }
    else return ($a_templ['type']>$b_templ['type'])?1:-1;
}


global $Kernel;
$Kernel->LoadLib('array','dataset');
class CTemplates_ListDS extends CDataset_Array
{

	public $Modes = array();

	function setModes($_modes)
    {
    	$this->Modes = $_modes;
    }

	function getParam($_name)
    {
        switch($_name)
        {
        	case 'mode':
        		$_st = substr($this->Items['type'],0,2);
        		$_ver = substr($this->Items['type'],2,3);
        		if (isset($this->Modes[$this->Items['type']])) return $this->Modes[$this->Items['type']];
    	        return isset($this->Modes[$_st])?$this->Modes[$_st].' '.$_ver:null;
			break;
        }

        return parent::getParam($_name);
    }


}


?>