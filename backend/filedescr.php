<?
class CBackend_fileDescr
{
	public $DescriptFile = null;

    function init()
	{
    	$this->DescriptFile = 'desc.info';
    }

	function SetDescript($_path,$_items)
    {
		$_path .= $this->DescriptFile;

        $_arr = array();
		if (file_exists($_path))
        {
        	$_data = $this->Kernel->ReadFile($_path);
            if (trim($_data)) $_arr = unserialize($_data);
        }
        $_arr = array_merge($_arr,$_items);
        $_data = serialize($_arr);
        $Fmanager = &$this->Kernel->Link('services.filemanager');
        $Fmanager->WriteFile($_path,$_data);
    }

    function DelDescript($_path,$_name)
    {
		$_path .= $this->DescriptFile;

        $_arr = array();
		if (file_exists($_path))
        {
        	$_data = &$this->Kernel->ReadFile($_path);
            if (trim($_data)) $_arr = unserialize($_data);
        }
        if (isset($_arr[$_name])) unset($_arr[$_name]);
        $_data = serialize($_arr);
        $Fmanager = &$this->Kernel->Link('services.filemanager');
        $Fmanager->WriteFile($_path,$_data);
    }

    function GetDescript($_path)
    {
		$_path .= $this->DescriptFile;

        $_arr = array();
		if (file_exists($_path))
        {
        	$_data = $this->Kernel->ReadFile($_path);
            if (trim($_data))
            {
            	$_arr = unserialize($_data);
            	return $_arr;
            }
        }
        return null;
    }
}


?>