<?
class CServices_Archive
{
    function Init()
    {

    }

    function add2Zip($_in_files,$_out_file,$_root_dir = ROOT_DIR)
    {
        $this->Kernel->LoadLib('pclzip.lib','extention');
        $PclZip = new PclZip($_out_file);
        $PclZip->Create($_in_files,"",$_root_dir);
    }

    function extractZip($_in_files,$_out_dir)
    {
        if (!is_array($_in_files)) $_in_files = array($_in_files);
        /*
        if (function_exists('zip_open'))
                        $this->_extractZLIB($_in_files,$_out_dir);
        else     */
           $this->_extractPCLZIP($_in_files,$_out_dir);

    }

    function extractTar($_in_files,$_out_dir)
    {
        if (!is_array($_in_files)) $_in_files = array($_in_files);
        /*
        if (function_exists('zip_open'))
                        $this->_extractZLIB($_in_files,$_out_dir);
        else     */
           return $this->_extractPCLTAR($_in_files,$_out_dir);

    }


    function _extractPCLZIP($_in_files,$_out_dir)
    {
        $this->Kernel->LoadLib('pclzip.lib','extention');
        $FManager = &$this->Kernel->Link('services.filemanager');
        if (sizeof($_in_files))
        while (sizeof($_in_files))
        {
            $_in_file = array_shift($_in_files);
            $PclZip = new PclZip($_in_file);
            $PclZip->Extract($_out_dir);
        }
    }


    function _extractZLIB($_in_files,$_out_dir)
    {
        $FManager = &$this->Kernel->Link('services.filemanager');
        if (sizeof($_in_files))
        while (sizeof($_in_files))
        {
            $_in_file = array_shift($_in_files);
            $_zip = @zip_open($_in_file);

            if ($_zip)
            {
                    while ($_zip_entry = zip_read($_zip))
                {
                        $_name = zip_entry_name($_zip_entry);
                    $_filepath = $_out_dir.$_name;
                    $_method = zip_entry_compressionmethod($_zip_entry);
                    $_size = zip_entry_filesize($_zip_entry);

                       if ($_size && zip_entry_open($_zip, $_zip_entry, "r"))
                   {
                           $_buffer = zip_entry_read($_zip_entry, $_size);
                       $FManager->WriteFile($_filepath,$_buffer);
                       }
                   zip_entry_close($_zip_entry);

                    }

                    zip_close($_zip);
                }
            }
    }

    function add2Tar($_in_files,$_out_file,$_root_dir = ROOT_DIR)
    {
        $this->Kernel->LoadLib('pcltar.lib','extention');
        $PclTar = new PclTar($_out_file);
        return $PclTar->create($_in_files,"",$_root_dir);
    }

    function _extractPCLTAR($_in_files,$_out_dir)
    {
        $err = '';
        $this->Kernel->LoadLib('pcltar.lib','extention');
        $FManager = &$this->Kernel->Link('services.filemanager');
        if (sizeof($_in_files))
        {
            $PclTar = new PclTar($_in_files);
            $err .= $PclTar->extract($_out_dir);
        }
        return $err;
    }

    function add2TarZip($_in_files, $_out_file, $_root_dir = ROOT_DIR)
    {
        $this->add2Tar($_in_files, $_out_file, $_root_dir);
        $this->add2Zip($_out_file, $_out_file, $_root_dir);
    }

    function _extractZIPTAR($_in_files,$_out_dir)
    {
        $this->_extractZIP($_in_files, $_out_dir);
        $this->_extractTAR($_in_files, $_out_dir);
    }

    function appendZip($_in_files, $_out_file, $_root_dir = ROOT_DIR)
    {
        if(!file_exists($_out_file)) return false;
        $this->Kernel->LoadLib('pclzip.lib','extention');
        $PclZip = new PclZip($_out_file);
        $PclZip->add($_in_files,"",$_root_dir);
        return true;
    }

    function appendTar($_in_files, $_out_file, $_root_dir = ROOT_DIR)
    {
        if(!file_exists($_out_file)) return false;
        $this->Kernel->LoadLib('pcltar.lib','extention');
        $PclTar = new PclTar($_out_file);
        $PclTar->add($_in_files,"",$_root_dir);
        return true;
    }

    function add2MultiTgz($_in_files, $_out_dir, $_out_file,$_root_dir = ROOT_DIR, $_limit = 1500000)
    {
        if(!is_array($_in_files)) return array();
        $_cnt = sizeof($_in_files);
        if($_cnt < 1) return array();
        $_cur_arj_id = 1;
        $_cur_arj_size = 0;
        $_cur_arj_name = $_out_file.$_cur_arj_id.'.tar';

        $_arj_list = array();

        $this->Kernel->LoadLib('pclzip.lib','extention');
        $this->Kernel->LoadLib('pcltar.lib','extention');
        $tar = new PclTar($_out_dir.$_cur_arj_name);
        $tar->create($_in_files[0],"",$_root_dir);

        for($i = 1; $i < $_cnt; $i++)
        {
            $_size = filesize($_out_dir.$_cur_arj_name);
            if($_size > $_limit)
            {
                $this->add2Zip($_out_dir.$_cur_arj_name, $_out_dir.$_cur_arj_name.'.zip', $_out_dir);
                unlink($_out_dir.$_cur_arj_name);
                $_arj_list[] = $_cur_arj_name.'.zip';
                $_cur_arj_id++;
                $_cur_arj_name = $_out_file.$_cur_arj_id.'.tar';
                $tar = new PclTar($_out_dir.$_cur_arj_name);
                $tar->create($_in_files[$i],"",$_root_dir);
            }
            else
            {
                $tar->add($_in_files[$i],"", $_root_dir);
            }
        }
        $this->add2Zip($_out_dir.$_cur_arj_name, $_out_dir.$_cur_arj_name.'.zip', $_out_dir);
        unlink($_out_dir.$_cur_arj_name);
        $_arj_list[] = $_cur_arj_name.'.zip';
        return $_arj_list;
    }
}

?>