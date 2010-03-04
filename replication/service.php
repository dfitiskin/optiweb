<?

class CReplication_service
{
    public $Name = null;

    public $Templs = null;
    public $Tables = null;
    public $WorkMode = null;

    public $WarningsDS = '-1';
    public $FormValues = array();
    public $UserParams = array();
    public $ManagerParams = array();

    public $Password = null;
    public $SessionName = null;
    public $RequestResult = null;

    function init()
    {
        $this->Tables = array(
            'modules'        =>        'be_modules'
        );

        $this->BeTables = array(
            'be_profiles',
            'be_profiles_access',
            'be_tree',
            'be_module_links',
            'be_module_versions',
            'be_modules',
            'be_navigation',
//                'be_templatetypes',
        );



        $this->Templs = array(
            'main'        =>        array(
                'file'        =>        '_services/main.tpl'
            ),
            'recive'        =>        array(
                'file'        =>        '_services/recive.tpl'
            ),
        );

        $this->WorkMode = 'main';

        global $BackendServices;
        $this->User = &$BackendServices->User;
        $this->ProfileID = $BackendServices->ProfileID;
        $this->ProfileAlias = $BackendServices->ProfileAlias;
    }

    function Process($_url_params)
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
        isset($_POST['action']) && isset($_POST['mode']))
        switch ($_POST['mode'])
        {
            case 'main':
            $this->ModifyReplication($_POST,$_url_params);
            break;
        }
    }

    function ModifyReplication($_params)
    {
        switch ($_params['action'])
        {
            case 'save':
                $_data = $_params['trans'];
                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $_data_old = $FManager->loadInfo($this->Name,$this->ProfileAlias);
                if($_data_old)
                {
                	$_data = array_merge($_data_old, $_data);
                }
                $FManager->saveInfo($_data,$this->Name,$this->ProfileAlias);
            break;
            case 'transver':
                Dump($_params);
                $Archive = $this->Kernel->Link('services.archive');

                $Archive->add2Zip(ROOT_DIR.'files/',ROOT_DIR.'/files.zip');
            break;
        }
    }

    function getMenuDS()
    {
        $_modes_ds = &$this->Kernel->Link('dataset.array');

        $_data = array(
            array(
                'name'      =>  'Передача',
                'alias'     =>  '',
                'mode'      =>  null
            ),
            array(
                'name'      =>  'Прием',
                'alias'     =>  'recive/',
                'mode'      =>  'recive'
            ),
        );
        $_modes_ds->setData($_data);
        return $_modes_ds;
    }

    function GetContent($_url_params,$_link_url)
    {
        return $this->Execute($this->WorkMode,$_link_url);
    }

    function Execute($_mode,$_link_url)
    {
        switch($_mode)
        {
            case 'main':

                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $_data = $FManager->loadInfo($this->Name,$this->ProfileAlias);
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds->AddChildDS('warnings',$this->WarningsDS);
                if($_data)
                {
                	$_ds_params = array_merge($_data,$this->FormValues);
                }
                $_ds_params['_url'] = $_link_url;
                $_ds_params['_object'] = $this->Name;
                $_ds->SetParams($_ds_params);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$this->Templs['main'],$this->Name);
                return $_result;
            break;
            case 'recive':
                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $_data = $FManager->loadInfo($this->Name,$this->ProfileAlias);

                $_ds = &$this->Kernel->Link('dataset.abstract');

                $_ds->AddChildDS('warnings',$this->WarningsDS);
                if($_data)
                {
                $_ds_params = array_merge($_data,$this->FormValues);
                }
                $_ds_params['_url'] = $_link_url;
                $_ds_params['_object'] = $this->Name;
                $_ds->SetParams($_ds_params);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$this->Templs['recive'],$this->Name);
                return $_result;
            break;
            case 'transver':

                $_type = isset($_GET['type'])?$_GET['type']:'all';
                $_from = isset($_GET['from'])?$_GET['from']:'date';
                if (!isset($_GET['action'])) $_action = 'create';
                else $_action = $_GET['action'];

                $_url = '/_terminal/replication/123/';

                switch($_action)
                {
                    case 'create':
                        $FManager = &$this->Kernel->Link('services.filemanager',true);
                        $_data = $FManager->loadInfo($this->Name,$this->ProfileAlias);
                        $FManager->deleteFolder(ROOT_DIR.'replication/log/');
                        $FManager->deleteFolder(ROOT_DIR.'replication/out/');
                        $FManager->createFolder(ROOT_DIR.'replication/out/');

                        if($_type == 'all' || $_type == 'full')
                        {
                            $_from = 'all';
                        }

                        switch($_from)
                        {
                            case 'date':
                                $Filter = &$this->Kernel->Link('services.filter');
                                $_parts = $Filter->DateConv($_data['datetime'],'H-I-S-M-D-Y');
                                $_parts = explode('-',$_parts);
                                $_mktime = mktime($_parts[0],$_parts[1],$_parts[2],$_parts[3],$_parts[4],$_parts[5]);
                            break;
                            case 'today':
                                $today = getdate();
                                $_mktime = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
                            break;
                            case 'last':
                                $Filter = &$this->Kernel->Link('services.filter');
                                $_parts = $Filter->DateConv($_data['lastrepl'],'H-I-S-M-D-Y');
                                $_parts = explode('-',$_parts);
                                $_mktime = mktime($_parts[0],$_parts[1],$_parts[2],$_parts[3],$_parts[4],$_parts[5]);
                            break;
                            case 'all':
                                $_mktime = -1;
                            break;
                        }

                        $_data['lastrepl'] = date('H:i d.m.Y');

                        $_path_files = ROOT_DIR.'replication/out/files/';
                        $_path_tables = ROOT_DIR.'replication/out/tables/';
                        $_path_modules = ROOT_DIR.'replication/out/modules/';

                        $FManager->CreateFolder($_path_files);
                        $FManager->CreateFolder($_path_tables);
                        $FManager->CreateFolder($_path_modules);

                        $_files_list = array();
                        $_tables_list = array();
                        $_tables_files = array();
                        $_modules_list = array();

                        $_files_list = $this->packFiles($_path_files, $_mktime);

                        $_list = $FManager->getFilesList(ROOT_DIR.'profiles/');



                        $index = array();
//                        $_index[] = array_search(ROOT_DIR.'profiles/_config/system/database.inc',$_list);
//                        $_index[] = array_search(ROOT_DIR.'profiles/_config/mysql/manager.inc',$_list);

                        $_index[] = array_search(ROOT_DIR.'profiles/_backend/',$_list);
                        $_index[] = array_search(ROOT_DIR.'profiles/_config/',$_list);
                        $_index[] = array_search(ROOT_DIR.'profiles/_data/',$_list);
                        $_index[] = array_search(ROOT_DIR.'profiles/_catch/',$_list);

                        foreach($_index as $i)
                        {
                            if(is_numeric($i))
                            {
                                unset($_list[$i]);
                            }
                        }

                        $_list = array_values($_list);

                        if($_mktime != -1)
                        {
                            $_list = $FManager->getFilesListExt($_list,$_mktime);
                        }
                        else
                        {
                            $_new_list = array();
                            foreach($_list as $_path)
                            {
                                $_new_list = array_merge($_new_list,$FManager->getFilesList($_path));
                            }
                            $_list = $_new_list;
                        }

                        $Archive = $this->Kernel->Link('services.archive');
                        $_new_list = $Archive->add2MultiTgz($_list,$_path_files,'profiles');

//                        $Archive->add2Zip($_list,$_path_files.'profiles.zip');

                        $_files_list = array_merge($_files_list, $_new_list);

                        $DbManager = &$this->Kernel->Link('database.manager',true);
                        $_res = $DbManager->Select($this->Tables['modules'],'*','replication=1');
                        while($_rec = $DbManager->getNextRec($_res))
                        {
                            $_alias = $_rec['alias'];
                            if($_type == 'full')
                            {
                                $_modules_list[] = $_alias;
                                $this->packModule($_alias, $_path_modules);
                            }
                            $_tables = $this->packTables($_alias, $_path_tables);
                            if($_tables['tables'])
                            {
                            	$_tables_list = array_merge($_tables_list, $_tables['tables']);
	                            $_tables_files = array_merge($_tables_files, $_tables['files']);
                            }
                        }

                        $_tables = $this->packBeTables($_path_tables);
                        $_tables_list = array_merge($_tables_list, $_tables['tables']);
                        $_tables_files = array_merge($_tables_files, $_tables['files']);

                        $_tables_files = $Archive->add2MultiTgz($_tables_files,$_path_tables,'tables', $_path_tables);

                        $FManager->deleteFolder($_path_tables.'tmp/');


                        $_files = array(
                            'files'     =>        $_files_list,
                            'tables'    =>        $_tables_list,
                            'tables_files'    =>  $_tables_files,
                            'modules'   =>        $_modules_list,
                        );

                        $FManager->saveInfo($_files,$this->Name,$this->ProfileAlias,'files_map');
                        $FManager->saveInfo($_data,$this->Name,$this->ProfileAlias);

                        global $Page;
                        $Page->setRedirect($this->Kernel->Url.'?action=transver&type='.$_type);

                    break;
                    case 'transver':

                        $FManager = &$this->Kernel->Link('services.filemanager',true);
                        $_files = $FManager->loadInfo($this->Name,$this->ProfileAlias,'files_map');
                        $_info = $FManager->loadInfo($this->Name,$this->ProfileAlias);
                        $_url = '/_terminal/replication/123/';

                        $this->startSession();
                        $_vars = array(
                            'action'    => 'login',
                            'session'   => $this->SessionName,
                        );
                        $this->postRequest($_info['host'],$_url,$_vars);
                        $_genID = trim($this->RequestResult);
                        $_pass = $_info['password'];

                        $this->Password = $this->securePass($_pass, $_genID);
                        $this->savePassword();

                        $FManager = &$this->Kernel->Link('services.filemanager',true);
                        $_files = $FManager->loadInfo($this->Name,$this->ProfileAlias,'files_map');
                        $_info = $FManager->loadInfo($this->Name,$this->ProfileAlias);
                        $_url = '/_terminal/replication/123/';

                        if (!isset($_files['transver']))
                        {
                            $_transver = array();
                            for($i=0;$i<sizeof($_files['files']);$i++)
                            {
                                $_transver['files'][$_files['files'][$i]] = 0;
                            }

                            for($i=0;$i<sizeof($_files['tables']);$i++)
                            {
                                $_transver['tables'][$_files['tables'][$i]] = 0;
                            }

                            for($i=0;$i<sizeof($_files['tables_files']);$i++)
                            {
                                $_transver['tables_files'][$_files['tables_files'][$i]] = 0;
                            }


                            for($i=0;$i<sizeof($_files['modules']);$i++)
                            {
                                $_transver['modules'][$_files['modules'][$i]] = 0;
                            }

                            $_files['transver'] = $_transver;
                        }

//                      Dump($_info);

                        $_transver = $_files['transver'];

                        if (sizeof($_transver['files']))
                        {
                            $_path = ROOT_DIR.'replication/out/files/';
                            foreach($_transver['files'] as $k=>$v)
                            {
                                $_file = $k;
                                $_filepath = $_path.$_file;
                                $_content = $this->ReadFileEx($_filepath);
                                $_tfile['name'] = $_file;
                                $_tfile['content'] = $_content;
                                $_vars = array(
                                        'action'        =>        'trans_files',
                                        'session'       =>        $this->SessionName,
                                        'genid'         =>        $this->Password,
                                );
                                $this->postRequest($_info['host'],$_url,$_vars,$_tfile);
                            }
                        }

                        if (sizeof($_transver['tables_files']))
                        {
                            $_path = ROOT_DIR.'replication/out/tables/';
                            foreach($_transver['tables_files'] as $k=>$v)
                            {
                                $_file = $k;
                                $_filepath = $_path.$_file;
                                $_content = $this->ReadFileEx($_filepath);
                                $_tfile['name'] = $_file;
                                $_tfile['content'] = $_content;
                                $_vars = array(
                                    'action'        =>        'trans_tables',
                                    'session'       =>        $this->SessionName,
                                    'genid'         =>        $this->Password,
                                );
                                $this->postRequest($_info['host'],$_url,$_vars,$_tfile);
                            }
                        }

                        if(isset($_transver['modules']) && sizeof($_transver['modules']))
                        {
                            $_path = ROOT_DIR.'replication/out/modules/';
                            foreach($_transver['modules'] as $k=>$v)
                            {
                                $_file = $k;
                                $_filepath = $_path.$_file.'.zip';
                                $_content = $this->ReadFileEx($_filepath);
                                $_tfile['name'] = $_file.'.zip';
                                $_tfile['content'] = $_content;
                                $_vars = array(
                                    'action'        =>        'trans_modules',
                                    'session'       =>        $this->SessionName,
                                    'genid'         =>        $this->Password,
                                );
                                $this->postRequest($_info['host'],$_url,$_vars,$_tfile);
                            }
                        }

                        global $Page;
                        $Page->setRedirect($this->Kernel->Url.'?action=update&type='.$_type);
                        $FManager->saveInfo($_files,$this->Name,$this->ProfileAlias,'files_map');
                    break;
                    case 'update':

                        $FManager = &$this->Kernel->Link('services.filemanager',true);
                        $_files = $FManager->loadInfo($this->Name,$this->ProfileAlias,'files_map');
                        $_info = $FManager->loadInfo($this->Name,$this->ProfileAlias);

                        $this->startSession();

                        $_vars['files'] = $_files['files'];
                        $_vars['tables'] = $_files['tables'];
                        $_vars['tables_files'] = $_files['tables_files'];
                        $_vars['modules'] = $_files['modules'];
                        $_vars['type'] = $_type;
                        $_vars['action'] = 'update';
                        $_vars['session'] = $this->SessionName;
                        $_vars['genid'] = $this->Password;
                        $_file['content'] = 1;
                        $_file['name'] = 1;
                        $this->postRequest($_info['host'],$_url,$_vars,$_file);
                        $this->stopSession();
                    break;
                }
            break;
        }
    }

    /*Функция для защиты*/
    function securePass($_pass,$_genID)
    {
        $_pass = $_genID . $_pass . $_genID;
        return md5($_pass);
    }

    function startSession()
    {
        if (!$this->SessionName)
        {
            //session_start();
            if(isset($_SESSION['ses_name']))
            {
                $this->SessionName = $_SESSION['ses_name'];
                $this->Password = $_SESSION['ses_pass'];
            }
            else {
                $this->SessionName = uniqid(time());
                $_SESSION['ses_name'] = $this->SessionName;
            }
        }
    }

    function savePassword() {
        $_SESSION['ses_pass'] = $this->Password;
    }


    function stopSession() {
        unset($_SESSION['ses_name']);
        unset($_SESSION['ses_pass']);
    }


    /*Упаковка програмной части модуля*/
    function packModule($_mod_name, $_save_path)
    {
        /*Сбор файлов модуля*/
        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $_tmp_path = $_save_path;
        $_tmp_path .= $_mod_name . '/';
        $FManager->CreateFolder($_tmp_path);
        $_folders = array('lib/','modules/','profiles/_config/','profiles/_templs/', 'profiles/_data/');

        foreach($_folders as $_folder)
        {
            $FManager->CreateFolder($_tmp_path.$_folder);
            $FManager->Copy(ROOT_DIR.$_folder.$_mod_name.'/', $_tmp_path.$_folder.$_mod_name.'/');
        }

        /*Получение структуры таблиц, необходимых данному модулю*/
        $DbManager = &$this->Kernel->Link('database.manager',true);
        $ParamsObject = &$this->Kernel->Link($_mod_name.'.params');
        $_tables = $ParamsObject->getTables();
        $_sql = array();
        /*
        if(is_array($_tables) && count($_tables))
        {
	        foreach($_tables as $table)
	        {
	            $_sql[] = $DbManager->getTableStructure($table, $table);
	        }
	        $_sql = implode('\r\n',$_sql);
	        $FManager->writeFile($_tmp_path.'modules/'.$_mod_name.'/sql/tables.sql', $_sql);
	        
        }
		*/
        /*Архивация файлов модуля*/
        $_to_arj = $FManager->getFilesList($_tmp_path);
        $Archive = $this->Kernel->Link('services.archive');
        $Archive->add2Zip($_to_arj, $_save_path.$_mod_name.'.zip', $_tmp_path);

        /*Удаление временных файлов*/
        $FManager->deleteFolder($_tmp_path);
    }

    /*Упаковка содержимого БД*/
    function packTables($_mod_name, $_save_path)
    {
        $DbManager = &$this->Kernel->Link('database.manager',true);
        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $ParamsObject = &$this->Kernel->Link($_mod_name.'.params');
        $_tables = $ParamsObject->getTables();
        $_tmp_path = $_save_path;
        $_tables_list = array();
        if($_tables)
        {
            for($i=0;$i<sizeof($_tables);$i++)
            {
                $_cvs = $DbManager->getCVS($_tables[$i]);
                $FManager->WriteFile($_tmp_path.$_tables[$i].'.cvs',$_cvs);
                $_tables_list[] = $_tmp_path.$_tables[$i].'.cvs';
            }
        }
        return array(
                'tables' => $_tables,
                'files'  => $_tables_list,
        );
    }

    function packBeTables($_save_path)
    {
        $DbManager = &$this->Kernel->Link('database.manager',true);
        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $Archive = $this->Kernel->Link('services.archive');

        $_tmp_path = $_save_path;
        $_tables = $this->BeTables;
        for($i=0;$i<sizeof($_tables);$i++)
        {
            $_cvs = $DbManager->getCVS($_tables[$i]);
            $FManager->WriteFile($_tmp_path.$_tables[$i].'.cvs',$_cvs);
            $_tables_list[] = $_tmp_path.$_tables[$i].'.cvs';
        }
        return array(
                'tables' => $_tables,
                'files'  => $_tables_list,
        );
    }

    /*Упаковка файлов с данными за период или всех*/
    function packFiles($_save_path, $_mktime = 0)
    {
        $_files_list = array();
        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $Archive = $this->Kernel->Link('services.archive');
        $_folders = array('images','data','files');
        for($i=0;$i<sizeof($_folders);$i++)
        {
            /*
            if($_mktime != -1)
            {
                $_list = $FManager->getFilesListExt(ROOT_DIR.$_folders[$i].'/',$_mktime);
            }
            else
            {
                $_list = $FManager->getFilesListExt(ROOT_DIR.$_folders[$i].'/',0);
            } */

            $_list = $FManager->getFilesListExt(ROOT_DIR.$_folders[$i].'/',$_mktime);

            $_add_list = $Archive->add2MultiTgz($_list,$_save_path,$_folders[$i]);
            $_files_list = array_merge($_files_list, $_add_list);
        }
        return $_files_list;
    }

    function splitFile($_file_name,$_path, $_limit=1500000)
    {
        $_split_list = array();
        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $crc = md5_file($_path.$_file_name);
        $fp = fopen($_path.$_file_name, 'r');
        $i = 0;
        while(!feof($fp))
        {
            $content = fread($fp, $_limit);
            $i++;
            $_part_name = $_file_name . '.' . $i;
            $FManager->WriteFile($_path.$_part_name,$content);
            $_split_list[] = $_part_name;
        }
        fclose($fp);

        $_file_info = array(
            'name'  =>  $_file_name,
            'parts' =>  $i,
            'crc'   =>  $crc,
        );

        $_file_info = serialize($_file_info);
        $FManager->WriteFile($_path.$_file_name.'.crc',$_file_info);
        $_split_list[] = $_file_name.'.crc';
//        unlink($_path.$_file_name);
        return $_split_list;
    }

    function ReadFileEx($_name,$_count = null)
    {
        if (file_exists($_name))
        if ($_opened_file = fopen($_name,"rb")){
            if (!$_count) $_count = filesize($_name);
            $_data = fread($_opened_file, $_count);
            fclose($_opened_file);
            return $_data;
        }
        return false;
    }


    function postRequest($_host, $_url, $_vars=null, $_file=null)
    {
        $_port = 80;
        $_fid = fsockopen($_host,$_port);
        if ($_fid)
        {

            srand((double)microtime()*1000000);
            $boundary = "---------------------------".substr(md5(rand(0,32000)),0,10);

            $data = "";

            if ($_vars)
            foreach($_vars as $k=>$v)
            {
                if (is_array($v))
                {
                    foreach($v as $k2=>$v2)
                    {
                        $data .= "--$boundary\r\n";
                        $data .= "Content-Disposition: form-data; name=\"".$k."[".$k2."]\"\r\n\r\n".
                        $v2."\r\n";
                    }
                }
                else
                {
                    $data .= "--$boundary\r\n";
                    $data .= "Content-Disposition: form-data; name=\"".$k."\"\r\n\r\n".
                    $v."\r\n";
                }
            }

//            Dump($data);

            if($_file)
            {
                $data .= "--$boundary\r\n";
                $data.="Content-Disposition: form-data; name=\"file\"; filename=\"".$_file['name']."\"\r\n".
                "Content-Type: application/zip\r\n\r\n".
                $_file['content']."\r\n";
            }

            $data .= "--$boundary--\r\n";
//            $data .= "--\r\n";

            $_msg =
                "POST ".$_url." HTTP/1.0\n".
                "Host: ".$_host."\n".
                "Content-Type: multipart/form-data; boundary=".$boundary."\n".
                "Content-Length: ".strlen($data)."\r\n\r\n";

            $result="";

            // open the connection
            $f = fsockopen($_host, $_port);

            fputs($f,$_msg.$data);

            // get the response
            while (!feof($f)) $result .= fread($f,32000);

    //            Dump($_url);
            $from = strpos($result, "\r\n\r\n");
            if($from)
            {
                $result = substr($result, $from);
            }
            if(!empty($result))
            {
                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $FManager->writeFile(ROOT_DIR.'replication/log/'.date("H_i_s").'.html', $result);
                $this->RequestResult = $result;
            }
            fclose($f);
        }
    }

    function isCorrectParts($_parts)
    {
        switch ($_parts[0])
        {
            case 'transver':
                        $this->WorkMode = 'transver';
                    return true;
            break;
            case 'recive':
                        $this->WorkMode = 'recive';
                    return true;
            break;
        }
    }
}
?>