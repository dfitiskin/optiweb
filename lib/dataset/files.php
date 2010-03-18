<?

global $Kernel;
$Kernel->LoadLib('array','dataset');

class CDataset_Files extends  CDataset_Array
{

    public $RootDir;
    public $RootUrl;
    public $ActiveParts = array();


    function Refresh()
    {
            $_data = array();
            if (file_exists($this->RootDir))
        {
                $Dir = dir($this->RootDir);
                while ($_dir = $Dir->Read())
                if ($_dir[0] != '_' && $_dir != '.' && $_dir != '..' && is_file($this->RootDir.$_dir) )
                {
                    $_data[] = array(
                            'name'        =>        $_dir
                    );
                }
        }
        $this->setData($_data);
        $this->Current = 0;
    }

        function setRootDir($_dir)
    {
                $this->RootDir = $_dir;
        $this->Params['root_dir'] = $_dir;
    }

        function setRootUrl($_url)
    {
                $this->RootUrl = $_url;
    }


        function setActiveParts($_parts)
    {
                $this->ActiveParts = $_parts;
    }

    function getParam($_name)
    {
            switch ($_name)
        {
                        case 'file_url':
                                return $this->RootUrl.$this->Items['name'];
            break;
                        case 'size':
                                return filesize($this->RootDir.$this->Items['name']);
            break;

        }
                return parent::getParam($_name);
    }

}


?>