<?

class CBackend_Manager
{
	public $Auth;
    public $Params;
    public $Info;

    function Init()
    {
    	$this->FManager = &$this->Kernel->Link('services.filemanager');
    }

	function Execute($_params)
    {
        $this->Params  = $_params ;
        $this->Auth = &$this->Kernel->Link('backend.auth',true);
        $this->Page = &$this->Kernel->Link('system.page',true,'Page');
        $this->Page->Parse();

        if($this->Auth->IsUserRegistred())
			$this->loadInfo();
	$NetWork = &$this->Kernel->Link('services.network');
        $_ip = $NetWork->getIP();

	if ($_ip == "195.128.149.40")
		$this->Page->EncodingLevel = 9;

        $this->Page->Execute();

        if($this->Auth->IsUserRegistred())
        {
			$this->saveInfo();
        }

    }

    function loadInfo()
    {
		$_alias = $this->Auth->User->GetCurrentProfile('alias');
    	$_dir = PROFILES_DIR.$_alias.'/_data/system/info.dat';
    	$_info = $this->Kernel->ReadFile($_dir);
        if ($_info)
		    $this->Info = unserialize($_info);
        else
			$this->Info = array();
    }

    function saveInfo()
    {
		$_alias = $this->Auth->User->GetCurrentProfile('alias');
    	$_dir = PROFILES_DIR.$_alias.'/_data/system/info.dat';
		$_info = serialize($this->Info);
    	$this->FManager->WriteFile($_dir,$_info);
    }

    function setInfoValue($_key,$_value)
    {
    	$this->Info[$_key] = $_value;
    }

}


?>