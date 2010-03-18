<?

global $Kernel;
$Kernel->LoadLib('filemanager','backend');

class CBackend_Files extends CBackend_Filemanager
{

	function Init()
    {
    	parent::Init();
		$this->Name = 'files';
        $this->LibDir = 'files';
    }

}


?>