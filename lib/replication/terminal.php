<?php

class CReplication_Terminal
{
    public $ID = null;

    function Init()
    {
        $this->Tables = array(
            'profiles'        =>        'be_profiles'
        );

/*
        global $BackendServices;
        $this->User = &$BackendServices->User;
        $this->ProfileID = $BackendServices->ProfileID;
        $this->ProfileAlias = $BackendServices->ProfileAlias;
*/

    }

    function checkPassword()
    {
        return true;
    }

    function Execute()
    {
        if(isset($_POST['action']))
        {
            $_action = $_POST['action'];
        }
        else
        {
            return;
        }

        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $_info = $FManager->loadInfo($this->Name,'root');

        switch($_action)
        {
            case 'login':
/*            	
                $this->ID = $this->generateID();
                $_session_name = $_POST['session'];
                session_id($_session_name);
                $_SESSION['genID'] = $this->ID;
                echo $this->ID;
*/
            break;
            case 'trans_files':

/*
            	$_session_name = $_POST['session'];
                session_id($_session_name);
                $this->ID = $_SESSION['genID'];
                $_rem_pass = $_POST['genid'];
                $_loc_pass = $this->securePass($_info['inpassword'], $this->ID);

/*
                if($_rem_pass !== $_loc_pass)
                {
                    echo 'Error'.__LINE__;
                    echo '<br>';
                    echo $_rem_pass . '<br>';
                    echo $_loc_pass . '<br>';
  //                  break;
                }
*/

                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $_path = ROOT_DIR.'replication/in/files/';
                $_filepath = $_path.$_FILES['file']['name'];

                $FManager->CreateFolder($_path);
                copy($_FILES['file']['tmp_name'],$_filepath);
            break;
            case 'trans_tables':
/*            	
                $_session_name = $_POST['session'];
                session_id($_session_name);
                session_start();
                $this->ID = $_SESSION['genID'];
                $_rem_pass = $_POST['genid'];
                $_loc_pass = $this->securePass($_info['inpassword'], $this->ID);
/*
                if($_rem_pass !== $_loc_pass)
                {
                    echo 'Error'.__LINE__;
                    echo '<br>';
                    echo $_rem_pass . '<br>';
                    echo $_loc_pass . '<br>';
  //                  break;
                }
  */

                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $_path = ROOT_DIR.'replication/in/tables/';
                $_filepath = $_path.$_FILES['file']['name'];
                $FManager->CreateFolder($_path);
                copy($_FILES['file']['tmp_name'],$_filepath);

            break;
            case 'trans_modules':
/*            	
                $_session_name = $_POST['session'];
                session_id($_session_name);
                session_start();
                $this->ID = $_SESSION['genID'];
                $_rem_pass = $_POST['genid'];
                $_loc_pass = $this->securePass($_info['inpassword'], $this->ID);
/*
                if($_rem_pass !== $_loc_pass)
                {
                    echo 'Error'.__LINE__;
                    echo '<br>';
                    echo $_rem_pass . '<br>';
                    echo $_loc_pass . '<br>';
 //                   break;
                }
 */

                $FManager = &$this->Kernel->Link('services.filemanager',true);
                $_path = ROOT_DIR.'replication/in/modules/';
                $_filepath = $_path.$_FILES['file']['name'];
                $FManager->CreateFolder($_path);
                copy($_FILES['file']['tmp_name'],$_filepath);
            break;
            case 'update':
/*            	
                trigger_error($_type);            
                $_session_name = $_POST['session'];
                session_id($_session_name);
                $this->ID = $_SESSION['genID'];
                $_rem_pass = $_POST['genid'];
                $_loc_pass = $this->securePass($_info['inpassword'], $this->ID);
/*
                if($_rem_pass !== $_loc_pass)
                {
                    echo 'Error'.__LINE__;
                    echo '<br>';
                    echo $_rem_pass . '<br>';
                    echo $_loc_pass . '<br>';
//                    break;
                }
*/

                $DbManager = &$this->Kernel->Link('database.manager',true);
                $FManager = &$this->Kernel->Link('services.filemanager',true);
				$_type = $_POST['type'];
                switch($_type)
                {
                    case 'full':
                        //Распаковка модулей
                        if(isset($_POST['modules']))
                        {
	                        $_modules = $_POST['modules'];
	                        $_path = ROOT_DIR.'replication/in/modules/';
	
	                        for($i=0;$i<sizeof($_modules);$i++)
	                        {
	                            $this->unpackModule($_modules[$i], $_path);
	                        }
	                        //Далее все как в остальных режимах работы
                        }
                    case 'all':
                        //Предварительное удаление ...
                        $_mktime = 0;
                        $_folders = array('images','data','files');

                        $_temp_dir = ROOT_DIR.'replication/temp/';
                        $FManager->createFolder($_temp_dir);
                        for($i=0;$i<sizeof($_folders);$i++)
                        {
//                            $FManager->Copy(ROOT_DIR.$_folders[$i].'/',$_temp_dir.$_folders[$i].'/');
                            $FManager->deleteFolder(ROOT_DIR.$_folders[$i].'/');
                            $FManager->createFolder(ROOT_DIR.$_folders[$i].'/');

                        }
/*
                        $_list = $FManager->getFilesList(ROOT_DIR.'profiles/');
                        $_list = array_values($_list);

                        $FManager->deleteFolder($_list);
  */
                        //Вставка идентична режиму обновления

                    case 'small':
                        $_files = $_POST['files'];
                        $Archive = $this->Kernel->Link('services.archive');
                        $_arc_files = array();
                        $_path = ROOT_DIR.'replication/in/files/';
                        $FManager = &$this->Kernel->Link('services.filemanager',true);

                        for($i=0;$i<sizeof($_files);$i++)
                        {
                            $_parts = explode('.', $_files[$i]);
                            $_count = count($_parts);
                            $_ext = $_parts[$_count - 1];

                            if($_ext == 'crc')
                            {
                                $_real_name = $this->combineFile($_files[$i],$_path);
                                if($_real_name)
                                {
                                    $_arc_files[] = $_path.$_real_name;
                                    $_tar_files[] = $_path.$_parts[0].'.tar';
                                }
                            }
                            else if($_ext == 'zip')
                            {
                                $_arc_files[] = $_path.$_files[$i];
                                $_tar_files[] = $_path.$_parts[0].'.tar';
                            }
                        }

                        $_res1 = $Archive->extractZip($_arc_files,$_path);
                        $_res2 = $Archive->extractTar($_tar_files,ROOT_DIR);

//                        Dump($_res1);
//                        $FManager->DeleteFolder($_path);

                        /*
                        $Archive->extractZip($_arc_files,$_temp_dir.'tar/');
                        $_tar_files = $FManager->getFilesList($_temp_dir.'tar/');
                        Dump($_tar_files);
                        Dump($_arc_files);
                        $Archive->extractTar($_tar_files,ROOT_DIR);
                          */
/*
                        $_path = ROOT_DIR.'replication/in/tables/';
                        $_files = $_POST['tables'];
                        $_arc_files = array();
                        for($i=0;$i<sizeof($_files);$i++)
                        {
        //                                $_arc_files[] = $_path.$_files[$i].'.zip';
                            $Archive->extractZip($_path.$_files[$i].'.zip',$_path);
                        }
*/
                        $_files = $_POST['tables_files'];
                        $_path = ROOT_DIR.'replication/in/tables/';

                        for($i= 0;$i<sizeof($_files);$i++)
                        {
                            $_parts = explode('.', $_files[$i]);
                            $_count = count($_parts);
                            $_ext = $_parts[$_count - 1];

                            $_files[$i] = $_path.$_files[$i];
                            $_tar_files[] = $_path.$_parts[0].'.tar';
                        }
                        $_res1 = $Archive->extractZip($_files,$_path);
                        $_res2 = $Archive->extractTar($_tar_files,$_path);

                        $_files = $_POST['tables'];
                        for($i=0;$i<sizeof($_files);$i++)
    //                        if($_files[$i][0] == "b")
                        {
                            $DbManager->Delete($_files[$i]);
                            $_cvs = $this->Kernel->ReadFile($_path.$_files[$i].'.cvs');
                            $DbManager->InsertCVS($_files[$i],$_cvs);
                        }
                        $FManager->DeleteFolder($_path);
                    break;
                }

                $DbManager->Select($this->Tables['profiles']);
                while ($_rec = $DbManager->getNextRec())
                {
                    $_path = PROFILES_DIR.$_rec['alias'].'/_cache/';
                    $FManager->DeleteFolder($_path);
                }
            break;
        }
    }

    function generateID()
    {
        list($msec, $sec) = explode(' ', microtime());
        $now = $sec + $msec;
        return md5(uniqid($now));
    }

    /*Функция для защиты*/
    function securePass($_pass,$_genID)
    {
        $_pass = $_genID . $_pass . $_genID;
        return md5($_pass);
    }


    function unpackModule($_file_name, $_path)
    {
        /*Распаковка файлов модуля*/
        $Archive = $this->Kernel->Link('services.archive');
        $Archive->extractZip($_path.$_file_name.'.zip',ROOT_DIR);

        /*Создание таблиц*/
        $DbManager = &$this->Kernel->Link('database.manager',true);
        $_sql_file = ROOT_DIR.'modules/'.$_file_name.'/sql/tables.sql';
//        Dump($_sql_file);
        $_sql = $this->Kernel->ReadFile($_sql_file);
        if(trim($_sql))
        {
			$sqlItem = explode(";", $_sql);
			foreach($sqlItem as $i => $sql)
			{
        		if(trim($sql))
        		{
					$DbManager->query(trim($sql));
        		}
			}	
        }
    }

    function combineFile($_file_name, $_path)
    {
        $_file_info = trim($this->Kernel->readFile($_path.$_file_name));
        $_file_info = unserialize($_file_info);
        if(!is_array($_file_info)) return false;
        $_real_name = $_file_info['name'];
        $_parts = $_file_info['parts'];
        $_crc = $_file_info['crc'];

        $fp = fopen($_path.$_real_name, 'w+');
        for($i = 1; $i <= $_parts; $i++)
        {
            $_part_content = $this->Kernel->readFile($_path.$_real_name.'.'.$i);
            fwrite($fp,$_part_content);
        }
        fclose($fp);
        $_new_crc = md5_file($_path.$_real_name);
        if($_new_crc !== $_crc) return false;
        return $_real_name;
    }
}

?>