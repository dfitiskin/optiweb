<?php
/**
 * CKernel класс
 */

class CKernel
{
    public $StartTime = 0;                         // Время начала работы системы
    public $LinkedObjects  = array();              // Массив подключенных объектов
    public $Url = null;                            // Текущий адрес
    public $BaseUrl = '/';                         // Базовый адрес профиля
    public $Params = array();                      // Параметры для системы из адреса
    public $Profile = null;                        // Псевдоним профиля [для версии ниже 0.6]
    public $ProfileAlias = null;                   // Псевдоним профиля
    public $ProfileID = null;                      // Идентификатор профиля
    public $Page = null;                           // Объект обработки страниц
    public $Mode = null;                           // Режим работы
    public $isError = null;                        // Флаг - наличие ошибок
    public $Config = null;                         // Конфигурация ядра ?

//------------------------------------------------------------------------------
// Конструктор ядра
//------------------------------------------------------------------------------
    function CKernel()
    {
        $this->StartTime = $this->GetCurTime();
        $this->CorrectVars();
        $this->Name = 'Kernel';

    }

//------------------------------------------------------------------------------
// Инициализация ядра
//------------------------------------------------------------------------------
    function Init()
    {
        $this->Error = &$this->Link("system.error", true,'Error');
        $this->ConfigManager = &$this->Link("system.config",true,'SystemConfig');
        
        $this->LoadKernelConfig('filesystem');
    }

//------------------------------------------------------------------------------
// Загрузка конфигурации ядра
//------------------------------------------------------------------------------
    function LoadKernelConfig($_name)
    {
        $this->Config[$_name] = &$this->LoadConfig('kernel',$_name);
    }

//------------------------------------------------------------------------------
// Запуск ядра
//------------------------------------------------------------------------------
    function Execute($_url)
    {
        if (preg_match("/^.*\\/[^\\.\\/]+$/",$_url))
        {
            $_url .= "/";
        }

        $this->Url = str_replace("//","/",strtolower($_url));
        $this->Params = explode("/", $this->Url);
        array_shift($this->Params);
        if (count($this->Params) && $this->Params[count($this->Params) - 1] === '')
        {
            array_pop($this->Params);
        }

        $_mode = isset($this->Params[0])?$this->Params[0]:null;
        switch ($_mode)
        {
            case BACKEND_PREFIX :
                $this->Mode = "backend";
                $this->ProfileAlias = '_backend';
                $this->Profile = $this->ProfileAlias;              // Для версии ниже 0.6
                define("PROFILE",$this->Profile);
                $this->LoadKernelConfig('autolink');
                $this->OnStart();

                $BackEnd = &$this->Link('backend.manager',true);
                $_prefix = array_shift($this->Params);
                $this->BaseUrl = '/'.$_prefix.'/';
                $BackEnd->Execute($this->Params);
                $this->OnStop();
            break;
            case DIALOG_PREFIX:
                $Profile = &$this->Link("system.profile");
                $this->ProfileAlias = $Profile->GetAlias();
                $this->Profile = $this->ProfileAlias;              // Для версии ниже 0.6
                $this->ProfileID = $Profile->GetID();
                $this->BaseUrl = $Profile->GetBaseUrl();

                $_params = $this->Params;
                if (sizeof($_params)>=3)
                {
                    array_shift($_params);
                    $_object = array_shift($_params);
                    $_object = explode('.',$_object);
                    $Dialog = &$this->Link($_object[0].'.dialog');
                    $_result = $Dialog->Execute($_params);
                    echo($_result);
                }
            break;
            case PLANNER_PREFIX:
                $Planner = &$this->Link('system.planner',true);
                $Planner->Execute();
            break;
            case TERMINAL_PREFIX:
                $this->Error->SetReportingMode(LOG_ERROR);
                $Profile = &$this->Link("system.profile");
                $this->ProfileAlias = $Profile->GetAlias();
                $this->Profile = $this->ProfileAlias;              // Для версии ниже 0.6
                $this->ProfileID = $Profile->GetID();

                $this->Mode = "terminal";
                $_params = $this->Params;
                if (sizeof($_params)>=3)
                {
                    array_shift($_params);
                    $_object = array_shift($_params);
                    $_password = array_shift($_params);
                    $_object = explode('.',$_object);
                    $_table = 'be_modules';
                    if (preg_match('/[\w\d]+/',$_object[0]))
                    {
                        $Terminal = &$this->Link($_object[0].'.terminal');
                        if ($Terminal->checkPassword($_password))
                        {
                            $Terminal->Execute($_params);
                        }
                    }
                }
            break;
            case INSTALL_PREFIX:
                $controller = &$this->Link('system.install');
                $controller->execute();            
            break;
            default:
                $this->Mode = "normal";

                // определение профиля работы
                $Profile = &$this->Link("system.profile",true);
                $this->ProfileAlias = $Profile->GetAlias();
                $this->Profile = $this->ProfileAlias;              // Для версии ниже 0.6
                $this->ProfileID = $Profile->GetID();
                $this->BaseUrl = $Profile->GetBaseUrl();
                $this->Params = $Profile->correctParams($this->Params);
                define("PROFILE",$this->ProfileAlias);

                $this->LoadKernelConfig('autolink');
                $this->OnStart();

                // инициализация
                $_dir = PROFILES_DIR.$this->ProfileAlias.'/_data/system/info.dat';
                $_info = $this->ReadFile($_dir);
                if ($_info)
                {
                    $this->Info = unserialize($_info);
                }
                else
                {
                    $this->Info = array();
                }

                $this->Page = &$this->Link('system.page',true,'Page');
                if ($this->ProfileAlias && $this->ProfileID > 0)
                {
                    // сборка узлов дерева
                    $this->Page->Parse(false);
                    $this->Page->Execute();
                }
                else
                {
                    echo('no page');
                }
                $this->OnStop();
            break;
        }
    }

/*******************************************************************************
***                     <Работа с файлами>
*******************************************************************************/

//------------------------------------------------------------------------------
// Запись данных $_data в файл $_name
//------------------------------------------------------------------------------
    function writeFile($_name, &$_data, $_type = "w+")
    {
        $_opened_file = fopen($_name,$_type);
        fwrite($_opened_file, $_data);
        fclose($_opened_file);
        
        $this->chFile($_name);
    }
    
    public function chFile($filename)
    {
        if (isset($this->Config['filesystem']['chmod']))
        {
            chmod($filename, $this->Config['filesystem']['chmod']);
        }
        
        if (isset($this->Config['filesystem']['chown']))
        {
            chown($filename, $this->Config['filesystem']['chown']);
        }
        
        if (isset($this->Config['filesystem']['chgroup']))
        {
            chgrp($filename, $this->Config['filesystem']['chgroup']);
        }
        
    }

//------------------------------------------------------------------------------
// Считывание содержимого из файла $_name
//------------------------------------------------------------------------------
    function readFile($_name)
    {
        $_result = null;
        if (is_file($_name))
        {
            $_result = file_get_contents($_name);
        }
        return $_result;
    }

//------------------------------------------------------------------------------
// Загрузка файла $_name расположенного в одной из папок $_pathes
//------------------------------------------------------------------------------
    function loadFile($_name,$_pathes = array(),$_root = null)
    {
        if ($_root === null)
        {
            $_root = ROOT_DIR;
        }
        for ($i=0;$i<sizeof($_pathes);$i++)
        {
            $_path = $_root.$_pathes[$i].$_name;
            if (($_data = $this->ReadFile($_path)) !== null)
            {
                return $_data;
            }
        }

        return null;
    }

//------------------------------------------------------------------------------
// Поиск пути к файлу ???
//------------------------------------------------------------------------------
    function findFile($_filePath, $_constant = '.', $_root = null, $_postfix = array(), $_prefix = array())
    {
        if( !is_array($_prefix) )
        {
            $pref = array($_prefix);
        }
        elseif (sizeof($_prefix))
        {
            $pref = $_prefix;
        }
        else
        {
            $pref = array($this->Profile);
        }
        array_unshift($pref, '.');
        if ($_root == null)
        {
            $root = ROOT_DIR;
        }
        else
        {
            $root = $_root;
        }

        do
        {
            $postf = $_postfix;
            array_unshift($postf, '.');
            do
            {
                $path = $root . implode('/', $pref) . '/' . $_constant . '/' . implode('/', $postf) . '/' . $_filePath;
                if (file_exists($path))
                {
                    return $path;
                }
                array_pop($postf);
            }
            while(count($postf));
            array_pop($pref);
        }
        while (count($pref));
        return null;
    }

    /**
     * Находит файл и возвращает его данные
     */
    function getFile($_filePath, $_constant = '.', $_root = null, $_postfix = array(), $_prefix = array())
    {
        $_path = $this->findFile($_filePath, $_constant, $_root, $_postfix, $_prefix);
        $_data = null;
        if ($_path !== null)
        {
            $_data = $this->readFile($_path);
        }
        return $_data;
    }

/*******************************************************************************
***                     <Работа с конфигурацией объектов>
*******************************************************************************/

//------------------------------------------------------------------------------
// Загрузка конфигурации $_config для объекта $_object, тип упаковки $_type
//------------------------------------------------------------------------------
    function & loadConfig($_object,$_config='main',$_type='eval')
    {
        $_data = $this->GetFile($_config.CONFIG_EXT,CONFIG_DIR.'/'.$_object,PROFILES_DIR);
        switch($_type)
        {
            case 'eval':
                $_data = eval($_data);
            break;
            case 'ser':
                $_data = unserialize($_data);
            break;
        }
        return $_data;
    }

//------------------------------------------------------------------------------
// Сохранение конфигурации $_config, объекта $_object, содержимое $_data
//------------------------------------------------------------------------------
    function saveConfig($_object, $_config='main', $_data, $_type='ser')
    {
        if ($this->Profile)
        {
            $_path = PROFILES_DIR.PROFILE.'/'.CONFIG_DIR.'/'.$_object.'/'.$_config.CONFIG_EXT;
        }
        else
        {
            $_path = PROFILES_DIR.CONFIG_DIR.'/'.$_object.'/'.$_config.CONFIG_EXT;
        }
        
        switch($_type)
        {
            case 'eval':
                $_data = sprintf(
                    'return %s;',
                    var_export($_data, true)
                );
            break;
            case 'ser':
                $_data = serialize($_data);
            break;
        }
        
        $this->WriteFile($_path, $_data);
    }

/*******************************************************************************
***                <Подключение библиотек и создание объектов>
*******************************************************************************/

    /*
     * Возвращает ИСТИНА если существует библиотека
     */
//------------------------------------------------------------------------------
// Подключение библиотеки $_lib_name из группы $_group_name,
// $_group_name - версия
//------------------------------------------------------------------------------
    function LoadLib($_lib_name, $_group_name = 'system', $_version = false)
    {
        $_lib_name = strtolower($_lib_name);
        $_group_name = strtolower($_group_name);

        $_filename = LIB_DIR . $_group_name . "/" . $_lib_name;
        if ($_version) $_filename .= "_" . $_version;
        $_filename .= LIB_EXT;

        $_ret = NULL;
        if (file_exists($_filename) && is_file($_filename)) $_ret = include_once($_filename);
        else Error('Can not load library '.$_group_name.'.'.$_lib_name,$this->Name);

        return $_ret;
    }

//------------------------------------------------------------------------------
// Создание объекта класса $_class_name, группы $_group_name
// $_version - версия, $_single - единственный экземпляр
// $_name - уникальное имя
//------------------------------------------------------------------------------
    function & LinkExt($_class_name, $_group_name = "system", $_version = false, $_single = false, $_name = false)
    {
        $_class_name = strtolower($_class_name);
        $_group_name = strtolower($_group_name);

        if (!$_name)
        {
            $_global_name  = ucfirst($_group_name).ucfirst($_class_name);
        }
        else
        {
            $_global_name = $_name;
        }

        $_global_full_name  = $_group_name . '.' . $_class_name;

        if (isset($this->LinkedObjects[$_global_full_name]) && ($_single || (isset($this->LinkedObjects[$_global_full_name]->Singleton) && $this->LinkedObjects[$_global_full_name]->Singleton)))
        {
            return $this->LinkedObjects[$_global_full_name];
        }
        $_res = $this->LoadLib($_class_name, $_group_name, $_version);
        if (!$_res)
        {
            $_a = null;
            return $_a;
        }
        $_class_name = "c" . $_group_name . '_' . $_class_name;
        if (!class_exists($_class_name))
        {
            Error('Class '.$_class_name.' do not exists',$this->Name);
            $_a = null;
            return $_a;
        }

        $_obj = new $_class_name();
        $_obj->Kernel = &$this;
        $_obj->Name = $_group_name;

        if ($_single  || (isset($_obj->Singleton) && $_obj->Singleton))
        {
            $GLOBALS[$_global_name] = & $_obj;
            $this->LinkedObjects[$_global_full_name] = & $_obj;
        }

        if (method_exists($_obj, "init"))
        {
            $_obj->Init();
        }
        return $_obj;
    }

    function LibExists($_complex_class_name)
    {
        list ($_group_name, $_lib_name) = explode('.', $_complex_class_name);

        if (!$_lib_name)
        {
            $_lib_name = $_group_name;
            $_group_name = 'system';
        }

        $_lib_name = strtolower($_lib_name);
        $_group_name = strtolower($_group_name);

        $_filename = LIB_DIR . $_group_name . "/" . $_lib_name;
        $_filename .= LIB_EXT;

        return is_file($_filename);
    }

//------------------------------------------------------------------------------
// Подключение объекта класса по полному имени $_complex_class_name
// $_single - единственный экземпляр
// $_name - уникальное имя
//------------------------------------------------------------------------------
    function & link($_complex_class_name, $_single = false, $_name = false)
    {
        list ($groupName, $className) = explode('.', $_complex_class_name);

        if (!$className)
        {
            $className = $groupName;
            $groupName = '';
        }
        return $this->LinkExt($className, $groupName, false, $_single, $_name);
    }

//------------------------------------------------------------------------------
// Подключение объекта существующего класса $_class_name
//------------------------------------------------------------------------------
    function & LinkClass($_class_name)
    {
        $_obj = & new $_class_name();
        $_obj->Kernel = &$this;
        if (method_exists($_obj, "init"))
        {
            $_obj->Init();
        }
        return $_obj;
    }


/*******************************************************************************
***                     <Функции работы с временем>
*******************************************************************************/

//------------------------------------------------------------------------------
// Текущее время в секундах
//------------------------------------------------------------------------------
    function GetCurTime()
    {
        $_mtime = explode(" ", microtime());
        return $_mtime[1] + $_mtime[0];
    }


//------------------------------------------------------------------------------
// Время работы системы
//------------------------------------------------------------------------------
    function getWorkTime()
    {
        $_mtime = $this->getCurTime();
        return number_format($_mtime - $this->StartTime, 7);
    }

/*******************************************************************************
***                     <Другие функции>
*******************************************************************************/

//------------------------------------------------------------------------------
// Корректировка переменных окружения
//------------------------------------------------------------------------------
    function CorrectVars()
    {
        global $_SERVER;

        if (getenv("QUERY_STRING") != "" && (strpos(getenv("QUERY_STRING"),"?") != false || strpos(getenv("QUERY_STRING"),"=") == false))
        {
            $_SERVER["REQUEST_URI"] = getenv("QUERY_STRING");
            @putenv("REQUEST_URI=".$_SERVER["REQUEST_URI"]);
        }

        $_SERVER["SCRIPT_NAME"] = ereg_Replace("\\?.*","",$_SERVER["REQUEST_URI"]);
        @putenv("SCRIPT_NAME=".$_SERVER["SCRIPT_NAME"]);

        if (preg_match("/[^?]*\\?[^?]*/",$_SERVER["REQUEST_URI"]))
        {
            $_SERVER["QUERY_STRING"] = preg_replace("/[^?]*\\?/","",$_SERVER["REQUEST_URI"]);
        }
        else
        {
            $_SERVER["QUERY_STRING"] = "";
        }
        @putenv("QUERY_STRING=".$_SERVER["QUERY_STRING"]);
    }

//------------------------------------------------------------------------------
// Запуск AutoLink объектов в начале обработки
//------------------------------------------------------------------------------
    function OnStart()
    {
        if (isset($this->Config['autolink']['start']))
        {
            $_start = $this->Config['autolink']['start'];
            for ($i=0;$i<sizeof($_start);$i++)
            {
                $_obj = &$this->Link($_start[$i],true);
                $_obj->Start();
            }
        }
    }

//------------------------------------------------------------------------------
// Запуск AutoLink объектов в конце обработки
//------------------------------------------------------------------------------
    function OnStop()
    {
        if (isset($this->Config['autolink']['stop']))
        {
            $_start = $this->Config['autolink']['stop'];
            for ($i=0;$i<sizeof($_start);$i++)
            {
                $_obj = &$this->Link($_start[$i],true);
                $_obj->Stop();
            }
        }
        $this->Error->Dump();
    }

//------------------------------------------------------------------------------
// Обработка ошибок системы
//------------------------------------------------------------------------------
    function Error($_message,$_module=null,$_type = 'module',$_file = false, $_line = false)
    {
        $this->Error->optiwebError($_message,$_module=null,$_type = 'module',$_file = false, $_line = false);
    }
}
