<?

class CBackend_FileManager
{

    var $ActiveLib = null;
    var $ActiveParams = null;
    var $isCFGCorrect = true;
    var $WorkMode = null;
    var $FileTypes = null;
    var $LibDir = null;

    var $FileExtention = null;

    function Init()
    {
        $this->Libs = array(
                '_general',
                '_profile',
        );
        $this->WorkMode = 'main';
        $this->ActiveParams = array();
        $this->FileExtention = array('jpg','jpeg','gif','png','pdf','doc','txt','xls','rar','zip','html','htm');
    }

    function Process()
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
        isset($_POST['action']) && isset($_POST['mode']))
        {
                switch ($_POST['mode'])
                {
                    case 'dirs':
                        $this->ModifyDir($_POST);
                    break;
                    case 'files':
                        $this->ModifyFiles($_POST);
                    break;
                    case 'property':
                        $this->ModifyProperty($_POST);
                    break;

                }
        }
    }

    function ModifyDir($_params)
    {
        switch ($_params['action'])
        {
            case 'make':
                if(preg_match('/^[a-z\d]+$/is',$_params['dirname']) && $this->ActiveLib)
                {
                    $_pathes = $this->getCurrentParams();
                    if($this->ActiveLib == '_profile' || $this->ActiveLib == '_general')
                    {
                        $_path = ROOT_DIR.$this->LibDir.'/'.$_pathes['path'];
                    }
                    else
                    {
                        $_path = ROOT_DIR . $_GET['path'] .'/'. $_pathes['path'];
                    }
                    if (is_dir($_path) && !file_exists($_path.$_params['dirname']))
                        mkdir($_path.$_params['dirname']);
                }
            break;
            case 'del':
                if (isset($_params['del']) && sizeof($_params['del']))
                {
                        $FManager = &$this->Kernel->Link('services.filemanager');
                    $_dir = key($_params['del']);
                    $FManager->DeleteFolder($_dir);
                    $this->Redirect();
                }
            break;
        }

    }

    function ModifyFiles($_params)
    {

        switch ($_params['action'])
        {

                case 'upload':
                $_pathes = $this->getCurrentParams();
                if($this->ActiveLib == '_profile' || $this->ActiveLib == '_general')
                {
                    $_path = ROOT_DIR.$this->LibDir.'/'.$_pathes['path'];
                }
                else
                {
                    $_path = ROOT_DIR . $_GET['path'] . $_pathes['path'];
                }
                

                if (isset($_params['unarchive']))
                {
                    $Archive = &$this->Kernel->Link('services.archive');
                    $Archive->extractZip($_FILES['files']['tmp_name'],$_path);
                }
                $FManager = &$this->Kernel->Link('services.filemanager');
                $FManager->CopyFileSet($_FILES['files'],$_path,$this->FileTypes);

            break;
            case 'unarchive':
                $_pathes = $this->getCurrentParams();
                if($this->ActiveLib == '_profile' || $this->ActiveLib == '_general')
                {
                    $_path = ROOT_DIR.$this->LibDir.'/'.$_pathes['path'];
                }
                else
                {
                    $_path = ROOT_DIR . $_GET['path'] . $_pathes['path'];
                }
                $Archive = &$this->Kernel->Link('services.archive');
                $Archive->extractZip($_params['filename'],$_path);
            break;
                case 'upd':
                    if (isset($_params['files']) && sizeof($_params['files']))
                {
                        if (isset($_params['del_x']))
                        {
                            $_del = $_params['files'];
                            while (sizeof($_del))
                            {
                                $_file = array_shift($_del);
                                if (file_exists($_file))
                                    unlink($_file);
                            }
                        }

                        if (isset($_params['unarchive_x']))
                    {

                    }
                }
            break;
        }
		global $Page;
		$Page->setRedirect($this->Kernel->Url);			
    }

    function getCurrentParams()
    {
        $_path ='';
        $_profile_path = '';
        
        $_add_dir = implode('/',$this->ActiveParams);
        if ($_add_dir && $this->WorkMode == 'main') $_add_dir .= '/';
        
        $_curr_path = $_curr_dir = ROOT_DIR.$this->LibDir.'/'.$_path;
        
        if ($this->ActiveLib == '_profile')
        {
            global $User;
            $_profile = $User->GetCurrentProfile('alias');
            $_profile_path = '_'.$_profile.'/';
            $_profile_type = '_profile/';
            $_path .= $_profile_path;
        }
        else
        {
            $_profile_type = '_general/';   
        }

        

        $_path .= $_add_dir;
        
        
        
        $_result = array(
            'current_path'        =>        $_curr_path,
            'path'                =>        $_path,
            'add_dir'             =>        $_add_dir,
            'profile'             =>        $_profile_path,
            'profile_type'        =>        $_profile_type
        );
        return $_result;
    }

    function Execute($_params,$_templs,$_types_params,$_url_params,$_link_url)
    {
        switch($_params['mode'])
        {
            case 'tree':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_params_ds = array(
                    '_url'          =>        $_link_url,
                    'opened_lib'    =>        $this->ActiveLib,
                    'active_lib'    =>        sizeof($this->ActiveParams)==0,
                    'object_name'   =>        $this->Name
                );
                if (isset($_GET['path']))
                {
                    $_params_ds['_module_url'] = $_GET['path'];
                }
                
                $_ds->setParams($_params_ds);

                $_libs_ds = &$this->Kernel->Link('dataset.array');
                $_libs_ds_data = array(
                    array(
                        'name'        =>        'Общая библиотека',
                        'alias'       =>        '_general'
                    ),
                    array(
                        'name'        =>        'Библиотека профиля',
                        'alias'       =>        '_profile'
                    ),
                );
                $_libs_ds->setData($_libs_ds_data);
                $_ds->addChildDS('libs', $_libs_ds);
                
                $_pathes = $this->getCurrentParams();
                $_root_dir = ROOT_DIR.$this->LibDir.'/'.$_pathes['profile'];
                
                $_dir_ds = &$this->Kernel->Link('dataset.dir');

                $_root_url = $_link_url;
                
                if ($this->ActiveLib == '_profile')
                {
                    $_root_url .= '_profile/';
                }
                elseif ($this->ActiveLib == '_module')
                {
                    $_root_url .= '_module/';
                    $_root_dir = ROOT_DIR . $_GET['path'].$_pathes['profile'];
                }
                else
                {
                    $_root_url .= '_general/';
                }
                $_dir_ds->setRootDir($_root_dir);
                $_dir_ds->setActiveParts($this->ActiveParams);
                $_dir_ds->setRootUrl($_root_url);
                $_libs_ds->addChildDS('dirtree',$_dir_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
            case 'files':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                
                $_images_ds = &$this->Kernel->Link('dataset.array');

                $_pathes = $this->getCurrentParams();
               
                if($this->ActiveLib == '_profile' || $this->ActiveLib == '_general')
                {
                    $_curr_dir = ROOT_DIR.$this->LibDir.'/'.$_pathes['path'];
                    $_params_ds['_module_url'] = '/' . $this->LibDir.'/'.$_pathes['path'];
                }
                else
                {
                    $_curr_dir = ROOT_DIR . $_GET['path'] . $_pathes['path'];
                    $_params_ds['_module_url'] = $_GET['path'].$_pathes['path'];
                }
                $_ds->setParams($_params_ds);
                
                $_current_url = '/_backend/'.$this->LibDir.'/_edit/'.$_pathes['profile_type'].$_pathes['add_dir'];
                
                $_root_url = '/'.$this->LibDir.'/'.$_pathes['path'];
                                    
                $_data = array();
                if (file_exists($_curr_dir))
                {
                    $Dir = dir($_curr_dir);
                    while ($_file = $Dir->Read())
                        if (is_file($_curr_dir.$_file))
                        {
                            $_filesize = filesize($_curr_dir.$_file);
                            $_parts = explode('.',$_file,2);
                            $_data[] = array(
                                'filename'          =>        $_file,
                                'filesize'          =>        $_filesize,
                                'size_in_kb'        =>        number_format($_filesize/1024, 2, ',', ' '),
                                'size_in_bytes'     =>        number_format($_filesize, 0, ',', ' '),
                                'ext'               =>        $_parts[1],
                                'name'              =>        $_parts[0],
                                'is_exists'         =>        in_array($_parts[1],$this->FileExtention),
                                'date'              =>        date('d.m.Y',filemtime ($_curr_dir.$_file)),
                                'time'              =>        date('H:i',filemtime ($_curr_dir.$_file)),
                            );
                        }
                }
                $_images_ds->setData($_data);
                $_images_ds_params = array(
                    'root_url'        =>        $_root_url,
                    'curr_dir'        =>        $_curr_dir,
                    'edit_url'        =>        $_current_url
                );
                $_images_ds->setParams($_images_ds_params);

                $_ds->addChildDS('images',$_images_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                if (isset($_GET['path']))
                {
                    $_templs['main']['file'] = 'filemanager.tpl';
                }
                
                
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
           }
    }

    function InitParams($_params)
    {
        $this->ActiveLib = array_shift($_params);
        $this->ActiveParams = $_params;
        $_cur_params = $this->getCurrentParams();
        if (file_exists($_cur_params['current_path']))
        {
            $this->isCFGCorrect = true;
        }
        else
        {
            $this->isCFGCorrect = false;
        }
    }


    function findRedirectFolder()
    {
        $_cur_params = $this->getCurrentParams();
        while(!file_exists($_cur_params['current_path']))
        {
            array_pop($this->ActiveParams);
            $_cur_params = $this->getCurrentParams();
        }
        $_link_url = implode('/',$this->LinkParams);
        if ($_link_url) $_link_url .= '/';
        return $this->Kernel->BaseUrl.$_link_url.$this->ActiveLib.'/'.$_cur_params['add_dir'];
    }


    function GetAccess()
    {
        return true;
    }

    function Redirect()
    {
        $_cur_params = $this->getCurrentParams();
        if ($this->WorkMode == 'main' && !file_exists($_cur_params['current_path']))
        {
            $_redirect_url = $this->findRedirectFolder();
            global $Page;
            $Page->setRedirect($_redirect_url);
        }
    }

    function Control()
    {
        if ($this->WorkMode != 'library' && sizeof($this->ActiveParams))
        {
            $this->Redirect();
        }
        return true;
    }


    function GetWorkType()
    {
        return PAGE_MODE_NORMAL;
    }

    function CorrectParts($_parts)
    {
        if (array_search($_parts[0],$this->Libs) !== false)
        {
//          Dump($_parts);
            $this->InitParams($_parts);
            $_parts[0] = '_libtype';
        }
        return $_parts;
    }

    function isCorrectParts()
    {
            return true;
    }

}


?>