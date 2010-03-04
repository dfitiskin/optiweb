<?

//------------------------------------------------------------------------------
// Module : Services
// Class  : CMS FileManager
// Ver    : 0.1 beta
// Date   : 25.09.2004
// Desc   : Операции с файлами
//------------------------------------------------------------------------------

class CServices_FileManager
{
    public $Kernel;                                // Ссылка на ядро

//------------------------------------------------------------------------------
// Создает папку
// $_path - либо абс. путь либо отн. (отн. корня)
//------------------------------------------------------------------------------
    function CreateFolder($_path, $_mode = 0775)
    {
        $_path = strtr($_path,"\\","/");
        if (strpos($_path,ROOT_DIR) === 0) $_parts = explode("/",substr($_path, strlen(ROOT_DIR)));
        else $_parts = explode("/",$_path);

        $_folder = substr(ROOT_DIR,0,-1);

        for($i = 0; $i < sizeof($_parts); $i++)
        {
            if ($_parts[$i])
            {
                $_folder .= "/".$_parts[$i];
                  if (!is_dir($_folder)) mkdir($_folder, 0777);
            }
        }
    }

//------------------------------------------------------------------------------
// Удаление папки $_folder и всего содержимого
//------------------------------------------------------------------------------
    function DeleteFolder($_folder)
    {
        if (file_exists($_folder))
        {
            $_dirs = opendir($_folder);
            while ($_file = readdir($_dirs))
            {
                if ($_file != "." && $_file != "..")
                {
                    if (is_dir($_folder."/".$_file))
                        $this->DeleteFolder($_folder."/".$_file);
                    else unlink($_folder."/".$_file);
                }

            }
            closedir($_dirs);
            rmdir($_folder);
        }
    }

//------------------------------------------------------------------------------
// Получить список файлов и/или папок
//------------------------------------------------------------------------------
    function getFilesList($_folder,$_type='all')
    {
        $_list = array();
        if (file_exists($_folder))
        {
            $_dirs = opendir($_folder);
            while ($_file = readdir($_dirs))
            {
                if ($_file != "." && $_file != "..")
                {
                    if (($_type=='folder'||$_type=='all') && is_dir($_folder.$_file))
                    {
                        $_list[] = $_folder.$_file.'/';
                    }
                    if (($_type=='file'||$_type=='all')  && !is_dir($_folder.$_file))
                    {
                        $_list[] = $_folder.$_file;
                    }
                }
            }
            closedir($_dirs);
        }
        return $_list;
    }

//------------------------------------------------------------------------------
// Получить список файлов в папках $_folders измененных после $_timestamp
//------------------------------------------------------------------------------
    function getFilesListExt($_folders,$_timestamp)
    {
        if (!is_array($_folders)) $_folders = array($_folders);
        $_list = array();
        for($i=0;$i<sizeof($_folders);$i++)
        {
            $_folder = $_folders[$i];
            if (file_exists($_folder))
            {
                $_dirs = opendir($_folder);
                while ($_file = readdir($_dirs))
                if ($_file != "." && $_file != "..")
                {
                    if (is_dir($_folder.$_file))
                    {
                        $_list = array_merge($_list,$this->getFilesListExt($_folder.$_file.'/',$_timestamp));
                    }
                    else
                    {
                        if ($_timestamp < max(filectime($_folder.$_file),filemtime($_folder.$_file)))
                        {
                            $_list[] = $_folder.$_file;
                        }
                    }
                }
                closedir($_dirs);
            }
        }
        return $_list;
    }

//------------------------------------------------------------------------------
// Записать данные $_data в файл $_name
//------------------------------------------------------------------------------
    function WriteFile($_name, $_data, $_mode = 0755)
    {
        $this->CreateFolder(dirname($_name),$_mode);
        $this->Kernel->WriteFile($_name, $_data);
    }

//------------------------------------------------------------------------------
// Копирование списка файлов $_files в папку $_folder по типу $_types
//------------------------------------------------------------------------------
    function CopyFileSet(&$_files,$_folder,$_types = null)
    {
        $_out_files = array();

        for($i = 0; $i<sizeof($_files["name"]); $i++)
        {
            if (!$_types || isset($_types[$_files["type"][$i]]))
            {
                $_tmp = str_replace(".","_",$_files["name"][$i]);
                if ($_tmp != "")
                {
                    $_out_files[$_tmp] = array(
                        'name'     => $_files["name"][$i],
                        'tmp_name' => $_files["tmp_name"][$i],
                        'error'    => $_files["error"][$i]
                    );
                }
            }
        }
        $this->CopyFiles($_out_files,$_folder);
    }


//------------------------------------------------------------------------------
// Копирование файлов $_files в папку $_folder
//------------------------------------------------------------------------------
    function CopyFiles(&$_files,$_folder)
    {
        if (isset($_files) && sizeof($_files)>0)
        {
            if (!file_exists($_folder)) $this->CreateFolder($_folder,0775);
            {
                foreach ($_files as $k=>$v)
                {
                    $_file_out = strtolower($_folder.$v["name"]);
                    $_file_in = $v["tmp_name"];
                    if ($_file_in !="" && file_exists($_file_in))
                    {
                        if (file_exists($_file_out)) unlink($_file_out);
                        copy($_file_in,$_file_out);
                    }
                }
            }
        }
    }

//------------------------------------------------------------------------------
// Копирование содержимого каталога $_src_folder в $_des_folder
//------------------------------------------------------------------------------
    function Copy($_src_folder,$_des_folder)
    {
        if ($_src_folder[sizeof($_src_folder)-1] != '/') $_src_folder .= '/';
        if ($_des_folder[sizeof($_des_folder)-1] != '/') $_des_folder .= '/';

        if (!is_dir($_src_folder)) return null;
        if (!is_dir($_des_folder)) $this->CreateFolder($_des_folder);
        $Dir = dir($_src_folder);
        while ($_file = $Dir->Read())
        if ($_file != '.' && $_file != '..')
        {
            if (is_dir($_src_folder.$_file)) $this->Copy($_src_folder.$_file.'/',$_des_folder.$_file.'/');
            else
            {
                if (file_exists($_des_folder.$_file))
                {
                    unlink($_des_folder.$_file);
                }
                copy($_src_folder.$_file,$_des_folder.$_file);
            }
        }
    }

    function Move($_src_folder, $_des_folder)
    {
        $this->Copy($_src_folder, $_des_folder);
        $this->DeleteFolder($_src_folder);
    }

//------------------------------------------------------------------------------
// Cохранить данные $_info объекта $_object
// $_profile - профиль, $_file - имя файла
//------------------------------------------------------------------------------
    function saveInfo($_info,$_object,$_profile = null,$_file='data')
    {
        $_dir = PROFILES_DIR;
        if ($_profile) $_dir .= $_profile.'/';
        $_dir .= '_data/'.$_object.'/'.$_file.'.dat';
        $_data = serialize($_info);
        $this->WriteFile($_dir,$_data);
    }

//------------------------------------------------------------------------------
//  Загрузить данные объекта $_object
// $_profile - профиль, $_file - имя файла
//------------------------------------------------------------------------------
    function loadInfo($_object,$_profile = null,$_file = 'data')
    {
        $_dir = PROFILES_DIR;
        if ($_profile) $_dir .= $_profile.'/';
        $_dir .= '_data/'.$_object.'/'.$_file.'.dat';
        $_info = $this->Kernel->ReadFile($_dir);
        if ($_info)
        {
            $_info = unserialize($_info);
        }
        else
        {
            $_info = null;
        }
        return $_info;
    }
}

?>
