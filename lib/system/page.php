<?

//------------------------------------------------------------------------------
// Module : System
// Class  : CMS Page
// Ver    : 0.2 beta
// Date   : 25.09.2004
// Desc   : Страница
//------------------------------------------------------------------------------

class CSystem_Page
{
    public $MainObject = null;                    // Основной объект
    public $ControlObjects = null;                // ??? объект
    public $Template =  null;                     // Шаблон
    public $Template4Print = null;                // Шаблон для печати
    public $TemplateError = null;                 // Шаблон сообщения об ошибке
    public $Template403 = null;                   // Шаблон сообщения об ошибке 403
    public $Template404 = null;                   // Шаблон сообщения об ошибке 404

    public $Blocks = array();                     // Массив блоков
    public $Params = array();                     // Параметры для системы из адреса
    public $TypeParams = array();                 // Что то связанное с парсером
//    public $Executed = array();

    public $Error = null;                         // Номер ошибки HTTP
    public $ErrorContent = null;                  // Сообщение об ошибке
    public $Profile = null;                       // Профиль ?

    public $Parser = null;                        //
    public $ProccedPage = null;                   //
    public $RedirectUrl = null;                   //
    public $EncodingLevel = null;                 //

//------------------------------------------------------------------------------
// Инициализация модуля
//------------------------------------------------------------------------------
    function Init()
    {
        $this->EncodingLevel = 0;
        $this->Profile = PROFILE;
        $this->Params = $this->Kernel->Params;
        $this->ProccedPage = true;
    }

//------------------------------------------------------------------------------
// Разбор ?
//------------------------------------------------------------------------------
    function Parse($_cache = false)
    {
        $this->Parser = &$this->Kernel->Link("system.parser");
//        $_cache = false;
        $_result = $this->Parser->Execute($this->Profile,$this->Params,$_cache);
        if (!$_result) $this->Error = 404;
        $this->Map = $this->Parser->GetMap();
        $this->ApplyMap();
        $this->TypeParams = $this->Parser->TypeParams;
        return $_result;
    }

//------------------------------------------------------------------------------
// Применение карты
//------------------------------------------------------------------------------
    function ApplyMap()
    {
        if (isset($this->Map['mainobject'])) $this->MainObject = $this->Map['mainobject'];
        if (isset($this->Map['controlobjects'])) $this->ControlObjects = $this->Map['controlobjects'];
        if (isset($this->Map['template'])) $this->Template = $this->Map['template'];
        if (isset($this->Map['templ_forprint'])) $this->Template4Print = $this->Map['templ_forprint'];
        if (isset($this->Map['templ_404'])) $this->Template404 = $this->Map['templ_404'];
        if (isset($this->Map['templ_403'])) $this->Template403 = $this->Map['templ_403'];
        if (isset($this->Map['templ_error'])) $this->TemplateError = $this->Map['templ_error'];
        $this->Blocks = $this->Map['blocks'];
    }

//------------------------------------------------------------------------------
// Установка параметров
//------------------------------------------------------------------------------
    function SetParams($_params)
    {
        $this->Params = $_params;
    }

//------------------------------------------------------------------------------
// Установка профиля
//------------------------------------------------------------------------------
    function SetProfile($_profile)
    {
        $this->Profile = $_profile;
    }

//------------------------------------------------------------------------------
// Изменение карты
//------------------------------------------------------------------------------
    function ChangeMap($_map)
    {
        switch($_map)
        {
            case 'this':
                $this->Map = &$this->Parser->GetThisMap();
            break;
            case 'shuffle':
                $this->Map = &$this->Parser->GetShuffleMap();
            break;
            case 'childs':
                $this->Map = &$this->Parser->GetChildsMap();
            break;
            case 'parent':
                $this->Map = &$this->Parser->GetParentMap();
            break;
            case 'node':
                $this->Map = &$this->Parser->GetMap();
            break;
        }
        $this->ApplyMap();
    }


//------------------------------------------------------------------------------
// Формирование страницы для пользователя
//------------------------------------------------------------------------------
    function Execute()
    {
        if (!$this->isBlock('content'))
        {
            $this->Error = 404;
        }

        if ($this->Error)
        {
            $_worktype = PAGE_ERROR;
        }
        else
        {
            $_worktype = PAGE_MODE_NORMAL;

            if ($this->ControlObjects !== null)
            {
                for ($i=0;$i<sizeof($this->ControlObjects);$i++)
                {
                    $_ctrl_obj_name = $this->ControlObjects[$i]['name'];
                    if (!strpos($_ctrl_obj_name,'.')) $_ctrl_obj_name .= ".manager";
                    $ControlObject = &$this->Kernel->Link($_ctrl_obj_name,true);
                    $this->ProccedPage = $ControlObject->GetAccess();
                    if ($this->ProccedPage !== true) break;
                }

                if (!$this->ProccedPage)
                {
                    $_worktype = PAGE_ERROR;
                    $this->Error = 403;
                }
            }

            if ($this->MainObject !== null && $_worktype == PAGE_MODE_NORMAL)
            {
                if (!strpos($this->MainObject['name'],'.')) $this->MainObject['name'] .= ".manager";
                $MainObject = &$this->Kernel->Link($this->MainObject['name'],true);
                $MainObject->Version = isset($this->MainObject['version'])?$this->MainObject['version']:null;
                if( method_exists($MainObject,'getWorkType'))
                {
                    $_worktype = $MainObject->getWorkType();
                }
            }
        }


        switch ($_worktype)
        {
            case PAGE_ERROR:
                 $this->ProccessError();
            break;

            case PAGE_MODE_SINGLE:
                $MainObject->Control();
            break;

            case PAGE_MODE_NORMAL:
                if (isset($MainObject))
                {
                    $_params = array_slice($this->Params,$this->MainObject['level']);
                    $_link_params = array_slice($this->Params,0,$this->MainObject['level']);
                    $MainObject->LinkParams = $_link_params;
                    $MainObject->UrlParams = $_params;
					
					$linkUrl = $this->Kernel->BaseUrl . implode('/', $MainObject->LinkParams) . '/';
					
					if (method_exists($MainObject, 'setLinkUrl'))
					{
						$MainObject->setLinkUrl($linkUrl);
					}
					else
					{
						$MainObject->LinkUrl = $linkUrl;
					}
                    
                    if (isset($this->MainObject['params']))
                    {
                        $moduleParams = array();
                        if (isset($MainObject->Params))
                        {
                            $moduleParams = $MainObject->Params;                
                        }
                        $moduleParams = array_merge($moduleParams, $this->MainObject['params']);
                        
                        if (method_exists($MainObject, 'setModuleParams'))
                        {
							$MainObject->setModuleParams($moduleParams);
                        }
                        else
                        {
                            $MainObject->Params = $moduleParams;
                        }                    
                    }

                    if ( method_exists($MainObject,'Control') )
                    {
                        $MainObject->Control();
                    }
                }

                $_result = '';

                if ($this->ProccedPage === true)
                {
                    $_processed = array();
                    if (sizeof($_POST))
                    foreach ($this->Blocks as $_block)
                    if ($_block['source'] == 'd')
                    {
                        $_object = $_block['object'];
                        if (!strpos($_block['object'],'.')) $_object .= '.viewer';
                        if (!isset($_processed[$_object]))
                        {
                            $_processed[$_object] = 1;
                            $_obj = &$this->Kernel->Link($_object,true);
                            $_params = array_slice($this->Params,$_block['level']);
                            $_url_params = array_slice($this->Params,$_block['level']);
                            if (method_exists($_obj,'process')) $_obj->process($_params,$_url_params);
                        }
                    }
                }

                $_server = $_SERVER['HTTP_HOST'];

                if ($this->RedirectUrl !== null)
                {
                    $_url = 'http://'.$_server.$this->RedirectUrl;
                    header('Location: '.$_url);
                }
                else
                {
                    $_ds = &$this->Kernel->LinkClass('CPage_DS');
                    $_ds_params = array(
                        'current_url'  =>  'http://'.$_server.$this->Kernel->Url,
                    );

                    $_ds->setParams($_ds_params);
                    $TemplManager = &$this->Kernel->Link('template.manager');
                    if (isset($_GET['print']))
                    {
                        $_templ = $this->Template4Print;
                    }
                    else
                    {
                        $_templ = $this->Template;
                    }

                    if (!$_templ)
                    {
                        Error('Main template not setted!');
                    }
                    else
                    {
                        $Network = &$this->Kernel->Link('services.network',true);
                        $_type = $Network->getBrowserType();
                        $_templs = array(
                            $_templ
                        );

                        $_parts = explode('.', $_templ['file']);

                        if ($_type['stype'])
                        {
                            $_base = $_type['stype'];
                            $_tpl['file'] = $_base.'_'.$_parts[0].'.tpl';
                            array_unshift($_templs,$_tpl);
                            if (isset($_type['major']))
                            {
                                $_base .= $_type['major'];
                                $_tpl['file'] = $_base.'_'.$_parts[0].'.tpl';
                                array_unshift($_templs,$_tpl);

                                if (isset($_type['major']))
                                {
                                    $_ver = explode('.',$_type['version']);

                                    for($j = 1; $j < sizeof($_ver); $j++)
                                    {
                                        $_sub_base = '';
        /*
                                        if($_ver[$j][0] != '0')
                                        {
                                            $_file = $_base.'-'.'0'.'_'.$_parts[0].'.css';
                                            array_unshift($_names,$_file);
                                        }
        */
                                        for($i = 0; $i < strlen($_ver[$j]); $i++)
                                        {
                                            $_sub_base .= $_ver[$j][$i];
                                            $_tpl['file'] = $_base.'-'.$_sub_base.'_'.$_parts[0].'.tpl';
                                            array_unshift($_templs,$_tpl);
                                        }
                                        $_base .= '-'.$_sub_base;
                                    }
                                }
                            }

/*
                            $_new_templ = $_templ;
                            $_new_templ['file'] = $_type['stype'].'_'.$_new_templ['file'];
                            array_unshift($_templs,$_new_templ);

                            if ($_type['major'])
                            {
                                $_new_templ = $_templ;
                                $_new_templ['file'] = $_type['stype'].$_type['major'].'_'.$_new_templ['file'];
                                array_unshift($_templs,$_new_templ);
                            }
*/
                        }

//                        Dump($_templs);

                        $_result = $TemplManager->ExecuteExt($_ds,$_templs);

                        // Экстренное сообщени об ошибке
                        if ($this->Kernel->isError) $this->Error = 500;

                        if ($this->Error)
                        {
                   //       $this->ProccessError();
                            return false;
                        }

                               /*
                        global $MysqlManager;
                        $_debug_str = $this->Kernel->GetWorkTime()." | ".$MysqlManager->QCount;
                        $_result .= $_debug_str;  */

                        if ($this->RedirectUrl !== null)
                        {
                            $_url = 'http://'.$_server.$this->RedirectUrl;
                            header('Location: '.$_url);
                        }
                        else
                        {
                            header("Content-type: text/html; charset=windows-1251");
                            if ($this->EncodingLevel)
                            {
                                if (!headers_sent())
                                {
                                    header("Content-Encoding: gzip");
                                    $_size = strlen($_result);
                                    $_crc = crc32($_result);
                                    $_result = gzcompress($_result,$this->EncodingLevel);
                                    $_result = substr($_result, 0, strlen($_result) - 4);
                                    $_result = "\x1f\x8b\x08\x00\x00\x00\x00\x00".$_result.pack('V',$_crc).pack('V',$_size);
                                }
                            }
                            echo($_result);
                        }
                    }
                }
            break;
        }
    }

//------------------------------------------------------------------------------
// Обработка ошибки
//------------------------------------------------------------------------------
    function ProccessError()
    {
        if ($this->ErrorContent)
        {
            echo($this->ErrorContent);
        }
        else
        {
            switch ($this->Error)
            {
                case 404:
                    if ($this->Template404)
                    {
                        $_ds = &$this->Kernel->LinkClass('CPage_DS');

                        $_server = $_SERVER['HTTP_HOST'];
                        $_ds_params = array(
                            'current_url'   =>  'http://'.$_server.$this->Kernel->Url,
                        );

                        $_ds->setParams($_ds_params);
                        $TemplManager = &$this->Kernel->Link('template.manager');
                        $_result = $TemplManager->Execute($_ds,$this->Template404);
                    }
                    else $_result = 404;
                    echo($_result);
                break;
                case 403:
                    if ($this->Template403)
                    {
                        $_ds = &$this->Kernel->LinkClass('CPage_DS');

                        $_server = $_SERVER['HTTP_HOST'];
                        $_ds_params = array(
                          'current_url'   =>  'http://'.$_server.$this->Kernel->Url,
                        );

                        $_ds->setParams($_ds_params);
                        $TemplManager = &$this->Kernel->Link('template.manager');
                        $_result = $TemplManager->Execute($_ds,$this->Template403);
                    }
                    else $_result = 403;
                    echo($_result);
                break;

                case 500:
                    if ($this->TemplateError)
                    {
                        $_ds = &$this->Kernel->LinkClass('CPage_DS');

                        $_server = $_SERVER['HTTP_HOST'];
                        $_ds_params = array(
                            'current_url'   =>  'http://'.$_server.$this->Kernel->Url,
                        );

                        $_ds->setParams($_ds_params);
                        $TemplManager = &$this->Kernel->Link('template.manager');
                        $_result = $TemplManager->Execute($_ds,$this->TemplateError);
                    }
                    else
                    {
                        $_result = 500;
                    }
                    echo($_result);
                break;
            }
        }
    }

//------------------------------------------------------------------------------
// Установка ошибки HTTP
//------------------------------------------------------------------------------
    function setError($_error = 404)
    {
        $this->Error = $_error;
    }

//------------------------------------------------------------------------------
// Установить URL для перенаправления
//------------------------------------------------------------------------------
    function setRedirect($_url)
    {
        $this->RedirectUrl = $_url;
    }

//------------------------------------------------------------------------------
// Установка шаблона
//------------------------------------------------------------------------------
    function setTemplate($_file)
    {
        $this->Template['file'] = $_file;
    }

//------------------------------------------------------------------------------
// Проверка существования блока $_name
//------------------------------------------------------------------------------
    function isBlock($_name)
    {
        return isset($this->Blocks[$_name]);
    }

//------------------------------------------------------------------------------
// ?
//------------------------------------------------------------------------------
    function FillGlobalBlock($_name)
    {
        $this->FillBlock($this->Blocks[$_name]);
    }

//------------------------------------------------------------------------------
// ?
//------------------------------------------------------------------------------
    function FillBlock(&$_block)
    {
        $_name = $_block['name'];
        switch($_name)
        {
            case '_current_url':
                $_server = $_SERVER['HTTP_HOST'];
                $_block[$_name] = 'http://'.$_server.$this->Kernel->Url;
            break;
            default:
                $_block['source'] = strtolower($_block['source']);
                switch ($_block["source"])
                {
                    case "s":
                        $_dir = implode('/',array_slice($this->Params, 0, $_block['level']));
                        if ($_dir) $_dir .= '/';
                        if( $_block['scope'] ) $_dir .= 'child_';
                        $_data = $this->Kernel->ReadFile(PROFILES_DIR.$this->Profile.'/'.BLOCKS_DIR.'/'.$_dir.$_name.BLOCK_EXT);
                        $_block['content'] = $_data;
                    break;
                    case "d":

                        $_object = $_block['object'];
                        if (!strpos($_block['object'],'.')) $_object .= '.viewer';
                        $_obj = &$this->Kernel->Link($_object,true);

                        $_params = array_slice($this->Params,$_block['level']);
                        $_type_params = array_slice($this->TypeParams,$_block['level']);
                        $_link_params = array_slice ($this->Params,0, $_block['level']);

                        $_link_url = implode('/',$_link_params);
                        if ($_link_url) $_link_url .= '/';
                        $_link_url = $this->Kernel->BaseUrl.$_link_url;
                        $_level = $_block['level'];

                        $_data = $_obj->Execute($_block['params'],$_block['template'],$_type_params,$_params,$_link_url,$_level);
                        $_block['content'] = $_data;
                    break;
                    case "t":
                        $_ds = &$this->Kernel->Link('dataset.abstract');
                        $_arr = array();
                        for ($i=0;$i<sizeof($_block['slots']);$i++)
                        {
                            $_slot = $_block['slots'][$i];
                            if ($_slot['type'] == 's')
                            {
                                $_arr[$_slot['name']] = $_slot['value'];
                            }
                            else
                            {
                                $_arr[$_slot['name']] = $this->GetBlockContent($_slot['value']);
                            }
                        }
                        $_ds->SetParams($_arr);
                        $TplManager = &$this->Kernel->Link('template.manager');
                        $_result = $TplManager->Execute($_ds,$_block['template']['main'],'_blocks');
                        $_block['content'] = $_result;
                    break;
                }
            break;
        }
    }

//------------------------------------------------------------------------------
// Получить значение блока
//------------------------------------------------------------------------------
    function getBlockContent($_name)
    {
        if (isset($this->Blocks[$_name]))
        {
            if ($this->Blocks[$_name]['content'] == null)
            {
                $this->FillGlobalBlock($_name);
            }
            return $this->Blocks[$_name]['content'];
        }
    }

//------------------------------------------------------------------------------
// Получить параметры блока
//------------------------------------------------------------------------------
    function GetBlockParams($_name)
    {
        if (isset($this->Blocks[$_name]))
        {
            if ($this->Blocks[$_name]['source']=='s' && $this->Blocks[$_name]['content'] == null)
            {
                $this->FillGlobalBlock($_name);
            }
            return $this->Blocks[$_name];
        }
    }

}

//------------------------------------------------------------------------------
// Module : Dataset
// Class  : CMS CPage_DS
// Ver    : 0.1 beta
// Date   : 25.09.2004
// Desc   : Расширение CDataset_Abstract для получения параметра CSS
//------------------------------------------------------------------------------

global $Kernel;
$Kernel->LoadLib('abstract','dataset');
class CPage_DS extends CDataset_Abstract
{
    function getParam($_name)
    {
        if (strpos($_name,'css_') === 0)
        {
            $_parts = explode('_',$_name,2);
            if (sizeof($_parts)>1)
            {
                $Network = &$this->Kernel->Link('services.network',true);
                $_type = $Network->getBrowserType();
                $_names = array(
                     $_parts[1].'.css'
                );

                if ($_type['stype'])
                {
                    $_base = $_type['stype'];
                    $_file = $_base.'_'.$_parts[1].'.css';
                    array_unshift($_names,$_file);
                    if (isset($_type['major']))
                    {
                        $_base .= $_type['major'];
                        $_file = $_base.'_'.$_parts[1].'.css';
                        array_unshift($_names,$_file);

                        if (isset($_type['major']))
                        {
                            $_ver = explode('.',$_type['version']);

                            for($j = 1; $j < sizeof($_ver); $j++)
                            {
                                $_sub_base = '';
/*
                                if($_ver[$j][0] != '0')
                                {
                                    $_file = $_base.'-'.'0'.'_'.$_parts[1].'.css';
                                    array_unshift($_names,$_file);
                                }
*/
                                for($i = 0; $i < strlen($_ver[$j]); $i++)
                                {
                                    $_sub_base .= $_ver[$j][$i];
                                    $_file = $_base.'-'.$_sub_base.'_'.$_parts[1].'.css';
                                    array_unshift($_names,$_file);
                                }
                                $_base .= '-'.$_sub_base;
                            }
                        }
                    }
                }
                for($i=0;$i<sizeof($_names);$i++)
                {
                    $_file = $this->Kernel->findFile($_names[$i],TEMPLS_DIR,PROFILES_DIR);
                    if ($_file) break;
                }
                $_ret = substr($_file,strlen(ROOT_DIR)-1,strlen(($_file)));
                return substr($_file,strlen(ROOT_DIR)-1,strlen(($_file)));
            }
        }
        return parent::getParam($_name);
    }
}
?>
