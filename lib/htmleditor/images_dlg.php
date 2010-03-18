<?

class CHTMLEditor_Images_dlg
{
    var $Libs = null;

    function Init()
    {
        $this->AllowedLibs = array(
            '_general'       =>        1,
            '_profile'       =>        1,
            '_object'        =>        1
        );

        $this->FileTypes = array(
            'image/bmp'                       =>  1,
            'image/gif'                       =>  2,
            'image/pjpeg'                     =>  3,
            'image/jpeg'                      =>  4,
            'application/x-shockwave-flash'   =>  5,
        );

        $this->Templs = array(
            'main'  =>  array(
                    'file'      =>  'dlg_images_main.tpl'
            ),
            'frame'  =>  array(
                    'file'      =>  'dlg_images.tpl'
            ),
        );

        $this->FileExtention = array('jpg','jpeg','gif','pdf','doc','txt','xls','rar','zip','html','htm','css','swf');

    }

    function InitParams($_link_params,$_params)
    {
        $_in_params = $_params;
        $_profile = $this->Kernel->Profile;


        if (isset($_params[0]))
        {

                $this->WorkMode = 'main';
            //$this->WorkMode = 'frame';
                if ($_params[0] == '_frame')
            {
                    $this->WorkMode = 'frame';
                    array_shift($_params);
            }
            else


                $this->Libs = array();

            $_module_params = array();
            while(sizeof($_params))
            {
//                    Dump($_params);
                    if (isset($this->AllowedLibs[$_params[0]])) break;
                else $_module_params[] = array_shift($_params);
            }

            if (sizeof($_params)) $_lib = array_shift($_params);
            else $_lib = null;

            if ($_module_params[0] != 'system' )
            {

                                $ImageLib = &$this->Kernel->Link($_module_params[0].'.imagelib');
                                $ImageLib->setParams(array_slice($_module_params,1));
//                $_profile = $ImageLib->getProfile();
//                                $_version = $ImageLib->getVersion();
                                $this->ObjectRootDir = $ImageLib->getRootDir();
                $this->ObjectRootUrl = $ImageLib->getRootUrl();
                                $this->Libs = $ImageLib->getLibs();
            }
            $this->Libs[] = array(
                    'name'  =>  'Общая библитока',
                    'alias' =>  '_general'
            );
            $this->Libs[] = array(
                    'name'  =>  'Библитока профиля',
                    'alias' =>  '_profile'
            );

                $this->ActiveLib = $_lib;
            $this->ActiveParams = $_params;


            $_module_url = implode('/',$_module_params);
            if ($_module_url) $_module_url = $_module_url.'/';

            $_params_url = implode('/',$_link_params);
            if ($_params_url) $_params_url = $_params_url.'/';

            $this->LinkUrl = '/_dialog/htmleditor/'.$_params_url.'_frame/'.$_module_url;
            $this->FrameUrl = '/_dialog/htmleditor/'.$_params_url.'_frame/'.$_module_url;


            if ($_POST)
            {
                if ($_POST['object'] == $this->Name)
                {
                        switch($_POST['action'])
                    {
                            case 'upd':
                                if (($this->ActiveLib == '_object' && $ImageLib->allowDelete()) || true)
                            {
                                if (isset($_POST['files']) )
                                {
                                        $_files = $_POST['files'];
                                                                        for ($i=0;$i<sizeof($_files);$i++)
                                                                        {
                                                                                if (is_file($_files[$i]))
                                                    unlink($_files[$i]);
                                                                        }
                                }
                                }
                            break;
                            case 'upload':

                            $_path = null;
                                if ($this->ActiveLib == '_object')
                            {
                                           //Dump($_POST);
                                if ($ImageLib->allowUpload())
                                {
                                    $_path = $this->ObjectRootDir;
                                }
                            }
                            else
                            {
                                     if (true)
                                {
                                    if ($this->ActiveLib == '_profile')
                                                $_path = ROOT_DIR.'images/_'.$this->Kernel->Profile.'/';
                                    else
                                        $_path = ROOT_DIR.'images/';
                                }
                            }
                            if ($_path)
                            {
                                    if (isset($_params['unarchive']))
                                    {
                                        $Archive = &$this->Kernel->Link('services.archive');
                                        $Archive->extractZip($_FILES['files']['tmp_name'],$_path);
                                    }
                                    $FManager = &$this->Kernel->Link('services.filemanager');
                                    $FManager->CopyFileSet($_FILES['files'],$_path,$this->FileTypes);
                                }

                        break;
                    }
                }
                                //Dump($_POST);
            }

        }


        }

    function getTemplate()
    {
            return $this->Templs[$this->WorkMode];
    }

    function &getDS()
    {
            if ($this->WorkMode == 'frame')
        {
                        return $this->get_FrameDS();
        }
                else return $this->get_MainDS();
    }


    function &get_MainDS()
    {
        $_ds = &$this->Kernel->Link('dataset.abstract');
        $_ds_params = array(
                'frame_url' => $this->FrameUrl
        );

        $_ds->setParams($_ds_params);

             return $_ds;
    }

    function &get_FrameDS()
    {
        $_ds = &$this->Kernel->Link('dataset.abstract');

        $_ds_params = array(
                'object_name' => 'filemanager'
        );

        $_ds->setParams($_ds_params);


        $_libs_ds = &$this->Kernel->Link('dataset.array');

            $_params_ds_lib = array(
                '_url'                 =>  $this->LinkUrl,
                'opened_lib'    =>  $this->ActiveLib,
                'active_lib'    =>  sizeof($this->ActiveParams)==0,
            );

            $_libs_ds->setParams($_params_ds_lib);

            $_libs_ds->setData($this->Libs);

            $_ds->addChildDS('libs',$_libs_ds);

            $_dir_ds = &$this->Kernel->Link('dataset.dir');

            $_root_url = $this->LinkUrl;
        $_profile = $this->Kernel->Profile;
        $_root_dir = null;
        $_lib_url = null;

        switch($this->ActiveLib)
        {
                case '_profile':
                    $_root_url .= '_profile/';
                    $_root_dir = ROOT_DIR.'images/_'.$_profile.'/';
                $_lib_url = '/images/_'.$_profile.'/';
            break;
                case '_general':
                    $_root_url .= '_general/';
                    $_root_dir = ROOT_DIR.'images/';
                $_lib_url = '/images/';
            break;
                case '_object':
                    $_root_url .= '_object/';
                    $_root_dir = $this->ObjectRootDir;
                                $_lib_url = $this->ObjectRootUrl;
            break;
        }


             $_dir_ds->setRootDir($_root_dir);
            $_dir_ds->setActiveParts($this->ActiveParams);
            $_dir_ds->setRootUrl($_root_url);
            $_libs_ds->addChildDS('dirtree',$_dir_ds);

        $_images_ds = &$this->Kernel->Link('dataset.array');


//        Dump($this->ActiveParams);
        $_act_url = implode('/',$this->ActiveParams);
        if ($_act_url) $_act_url .= '/';
        $_curr_dir = $_root_dir.$_act_url;
        $_curr_url = $_lib_url.$_act_url;


        $_data = array();
        if ($_lib_url && file_exists($_curr_dir))
        {
            $Dir = dir($_curr_dir);
            while ($_file = $Dir->Read())
                if (is_file($_curr_dir.$_file))
                {
                    $_filesize = filesize($_curr_dir.$_file);
                    $_parts = explode('.',$_file,2);
                    $_data[] = array(
                        'filename'      =>  $_file,
                        'filesize'      =>  $_filesize,
                        'size_in_kb'    =>  number_format($_filesize/1024, 2, ',', ' '),
                        'size_in_bytes' =>  number_format($_filesize, 0, ',', ' '),
                        'ext'           =>  isset($_parts[1])?$_parts[1]:'',
                        'name'          =>  $_parts[0],
                        'is_exists'     =>  isset($_parts[1]) && in_array($_parts[1],$this->FileExtention),
                        'date'          =>  date('d.m.Y',filemtime ($_curr_dir.$_file)),
                        'time'          =>  date('H:i',filemtime ($_curr_dir.$_file)),
                    );
                }
        }
        $_images_ds->setData($_data);
        $_images_ds_params = array(
            'root_url'  =>  $_root_url,
            'curr_dir'  =>  $_curr_dir,
            'curr_url'  =>  $_curr_url
        );
        $_images_ds->setParams($_images_ds_params);

        $_ds->addChildDS('images',$_images_ds);

        return $_ds;
    }


}

global $Kernel;
$Kernel->LoadLib('array','dataset');
class CHTMLEditor_LibsDS extends CDataset_Array
{

        function getChildDS($_name)
    {
                if ($_name == 'dirtree')
        {
                $_dir_ds = &$this->Kernel->Link('dataset.dir');
            $_dir_ds->setRootDir($this->Items['rootdir']);
            return $_dir_ds;
        }
        return parent::getChildDS($_name);
    }

    function getParam($_name)
    {
                switch ($_name)
        {
                case '_is_terminal':
                    if (!is_dir($this->Items['rootdir'])) return false;
                    $Dir = dir($this->Items['rootdir']);
                    while ($_dir = $Dir->Read())
                    if ($_dir[0] != '_' && $_dir != '.' && $_dir != '..' && is_dir($this->Items['rootdir'].$_dir))
                        return false;
                                return true;
            break;
        }

        return parent::getParam($_name);
    }

}

?>