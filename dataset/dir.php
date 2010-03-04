<?

global $Kernel;
$Kernel->LoadLib('array','dataset');

class CDataset_Dir extends  CDataset_Array
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
	        if ($_dir[0] != '_' && $_dir != '.' && $_dir != '..' && is_dir($this->RootDir.$_dir))
	        {
	            $_data[] = array('dirname'=>$_dir);
	        }
        }
		sort($_data);
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

    function &getChildDS($_name)
    {

    	if ($_name == 'subdir')
        {
            $_ds = &$this->Kernel->LinkClass('CDataset_Dir');
            /*
            $_root_dir = $this->RootDir.$_active_part.'/';
            $_root_url = $this->RootUrl.$_active_part.'/';
            */
            $_root_dir = $this->RootDir.$this->Items['dirname'].'/';
            $_root_url = $this->RootUrl.$this->Items['dirname'].'/';
            $_ds->setRootDir($_root_dir);
            $_ds->setRootUrl($_root_url);

            $_active_parts = $this->ActiveParts;
            $_active_part = array_shift($_active_parts);
            if ($this->Items['dirname'] == $_active_part)
	            $_ds->setActiveParts($_active_parts);
            return $_ds;
        }
		return parent::getChildDS($_name);
    }


    function getParam($_name)
    {
    	switch ($_name)
        {
			case '_url':
				return $this->RootUrl.$this->Items['dirname'].'/';
            break;
			case '_is_opened':
				return sizeof($this->ActiveParts) && $this->Items['dirname'] == $this->ActiveParts[0];
            break;
			case '_is_active':
				return sizeof($this->ActiveParts)==1 && $this->Items['dirname'] == $this->ActiveParts[0];
            break;

            case '_is_terminal':
	            $Dir = dir($this->RootDir.$this->Items['dirname'].'/');
	            while ($_dir = $Dir->Read())
	            if ($_dir[0] != '_' && $_dir != '.' && $_dir != '..' && is_dir($this->RootDir.$this->Items['dirname'].'/'.$_dir))
                	return false;
				return true;
            break;
        }
		return parent::getParam($_name);
    }

}


?>