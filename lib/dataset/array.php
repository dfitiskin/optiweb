<?
global $Kernel;
$Kernel->LoadLib('abstract','dataset');

class CDataset_Array extends CDataset_Abstract
{
	public $Type = 'array';
    public $Tree = null;
    public $Data = array();

    function Refresh()
    {
       //	echo('Array DataSet Refresh');
		$this->Current = 0;
//        $this->Next();
    }

    function SetTree($_tree)
    {
    	// isarray(); !!!!
		$this->Tree = $_tree;
		if (isset($this->Tree['items']))
        {
        	$this->Data = &$this->Tree['items'];
            $this->RecsCount = sizeof($this->Data);
        }

		if (isset($this->Tree['vars']))
        	$this->Params = &$this->Tree['vars'];
    }

    function SetData($_data)
    {
      	$this->Data = &$_data;
        $this->RecsCount = sizeof($this->Data);
    }

    function AddData($_data)
    {
      	$this->Data[] = &$_data;
        $this->RecsCount++;
    }

    function GetChildDS($_name)
    {
        $_ds = parent::GetChildDS($_name);
        if ($_ds === null)
        {
        	$_arr = null;
        	if (isset($this->Items[$_name]))
            	$_arr = $this->Items[$_name];
            else
            	if (isset($this->Tree[$_name]))
	                $_arr = $this->Tree[$_name];
            if ($_arr !== null)
            {
            	$_ds = $this->Kernel->Link('dataset.array');
                $_ds->SetTree($_arr);
                $_ds->SetParent($this);
                //$this->AddChildDS($_name,$_ds);
            }
        }
	    return $_ds;
    }

    function Next()
    {

    	if ($this->Current<$this->RecsCount)
        {
			$this->Items = $this->Data[$this->Current];
			$this->Current++;
            return true;
        }
        return false;
    }

}
?>