<?

define('LOG_ERROR', 1);
define('DISPLAY_ERROR', 2);
define('SEND_ERROR', 4);

define('DISPLAY_AND_LOG_ERROR',3);
define('LOG_AND_SEND_ERROR',5);
define('DISPLAY_AND_LOG_AND_SEND_ERROR',7);


define('SPACES_IN_TABS', 8);


class CSystem_Error{

    public $Mode = LOG_ERROR;
    public $ErrorTypes;
    public $SymbolInTabs;
    public $isError;

    function CSystem_Error()
    {
        error_reporting(E_ALL);
        set_error_handler('PHPErrorHandler');
        ini_set('display_errors',0);
        $this->ErrorTypes = array(
            1     =>        'php run error',
            2     =>        'php run warning',
            8     =>        'php run notice',
            16    =>        'php core error',
            32    =>        'php core warning',
            64    =>        'php compile error',
            128   =>        'php compile warning',
            256   =>        'system error',
            512   =>        'system warning',
            1024  =>        'system notice',
            2048  =>        'strict error',
            4096  =>		'error',
        );

        $this->Templs = array(
            'small'        =>        array(
                'file'        =>        'error_smail.tpl'
            ),
            'full'        =>        array(
                'file'        =>        'error_lmail.tpl'
            )
        );
        $this->SymbolInTabs = 4;
        $this->ErrorsCount = 0;
        $this->Mode = LOG_AND_SEND_ERROR;
    }


    function Dump()
    {
        if (isset($_GET['system_dump']) && !$this->Kernel->isError)
        {
            $_err = array(
                'errno'     =>  1,
                'type'      =>  'dump',
                'message'   =>  'memmory dump',
                'filename'  =>  0,
                'line'      =>  0
            );
            $this->ProccedError(& $_err,SEND_ERROR);
            $this->Kernel->isError = 0;
            $this->ErrorsCount = 0;
        }
    }

    function SetReportingMode($_mode)
    {
        $this->Mode = $_mode;
        if ($_mode & DISPLAY_ERROR) ini_set('display_errors',1);
    }

    function LogError($_string)
    {
        $this->Kernel->WriteFile(LOG_DIR . 'error.'.date('Y_m_d').'.log', $_string, 'a+');
    }

    function GetStringForScreen($_err)
    {
        $_mess = '<br><i>' . $_err['type'] . '</i> : <b>' . $_err['message'] . '</b>';
        if (isset($_err['filename']) && isset($_err['line']))
        {
            $_mess .= ' in <b>' . $_err['filename'] . '</b> in  line ' . $_err['line'];
        }
        return $_mess . '<br>';
    }

    function GetStringForFile(& $_out)
    {
        $_tabs = '';
        $_tabs_count = floor(strlen($_out['type']) / SPACES_IN_TABS);
        if (strlen($_out['type']) % SPACES_IN_TABS > SPACES_IN_TABS / 2)
        {
            $_tabs_count++;
        }
        for ($i = 0; $i < 3 - $_tabs_count; $i++)
        {
            $_tabs .= "\t";
        }
        $_mess = date('H:i:s d.m.Y') . ' ' . $_out['type'] . $_tabs .'"'. $_out['message']. '"';
        if (isset($_out['filename']) && isset($_out['line']))
        {
            $_mess .= ' in ' . $_out['filename'] . ' at ' . $_out['line'] . ' line ';
        }
        $_mess .= ' at url '.$GLOBALS['_SERVER']['SCRIPT_NAME'];
        return $_mess . " \r\n";
    }

    function ProccedError(& $_err,$_mode = null)
    {
        global $Kernel;
        $Kernel->isError = 1;
        $this->ErrorsCount++;
        if ($_mode == null)
        {
            $_mode = $this->Mode;
        }

        if ($_mode & LOG_ERROR)
        {
            $this->LogError($this->GetStringForFile($_err));
        }

        if ($_mode & DISPLAY_ERROR)
        {
            echo($this->GetStringForScreen($_err));
        }

        if ($_mode & SEND_ERROR)
        {
            $_host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];

            $_ds = &$this->Kernel->Link('dataset.abstract');
            $_ds_params = $_err;
            $_ds_params['url'] = $GLOBALS['_SERVER']['SCRIPT_NAME'];
            $_ds_params['hostname'] = $_host;
            $_ds_params['time'] = date('H:i:s d.m.Y');

            ob_start();
            echo('server:');
            Dump($_SERVER);
            echo('cookie:');
            Dump($_COOKIE);
            echo('session:');
            Dump($_SESSION);
            echo('post:');
            Dump($_POST);
            echo('get:');
            Dump($_GET);
            $_server = trim(ob_get_contents());
            ob_end_clean();
            $_ds_params['server'] = $_server;

            $_ds->setParams($_ds_params);


            $Manager = &$this->Kernel->Link('template.manager');
            $_result = &$Manager->Execute($_ds, $this->Templs['full'],$this->Name);

            $Mailer = &$this->Kernel->Link('services.mailer');
            $Mailer->setSubject('Ошибки на сайте '.$_host);
            $Mailer->setFrom('optiweb@'.$_host);

            $Mailer->setContentType('text/html');
            $Mailer->setText($_result);
            $Mailer->buildMessage();
            $Mailer->sendMail('optiweb@dominion.ru');
        }

        if ((isset($_err['errno']) && $_err['errno'] === 256) || ($this->ErrorsCount>5))
        {
            global $Page;
            if ($Page)
            {
                $Page->Error = 500;
                $Page->ProccessError();
            }
        }

        if ($this->ErrorsCount>5) die();
    }

    function internalError($_err)
    {
        $_err['type'] = $this->ErrorTypes[$_err['errno']];
        $_err['filename'] = str_replace(LIB_DIR, '', str_replace('\\', '/', $_err['filename']));

        $this->ProccedError($_err);
    }

    function optiwebError($_err)
    {
        $this->ProccedError($_err);
    }

}

function PHPErrorHandler($_errno, $_str, $_file, $_line)
{
    $_arr = explode('|', $_str, 4);

    $_out = array();

    if (sizeof($_arr) >= 3)
    {
        $_out['object']  = @$_arr[0];
        $_out['func']    = @$_arr[1];
        $_out['message'] = @$_arr[2];
    }
    else
    {
        $_out['message'] = @$_arr[0];
    }

    $_out['filename'] = $_file;
    $_out['line'] = $_line;
    $_out['errno'] = $_errno;

   global $Error;
   $Error->internalError($_out);
}

function Error($_message,$_type = 'module',$_file = false, $_line = false)
{
    $_arr = array();
    $_arr['message'] = $_message;
    $_arr['type'] = $_type;
    $_arr['filename'] = $_file;
    $_arr['line'] = $_line;
    $_arr['errno'] = 808;
    global $Error;
    $Error->optiwebError($_arr);
}

?>