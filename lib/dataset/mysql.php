<?
global $Kernel;
$Kernel->LoadLib('abstract','dataset');

class CDataset_Mysql extends CDataset_Abstract
{

	public $Type = 'mysql';
    public $Resource = null;
    public $SQL = null;
    public $Manager = null;
    public $Data = null;
    public $ViewSQL = false;

    function Init()
    {
		//$this->Kernel->Link('mysql.manager',true);
        $this->Manager = &$this->Kernel->Link('database.manager', true);
    }

    function SetQueryRes(&$_res)
    {
        $this->Current = 0;		
        $this->Resource = & $_res;
    }

    function Refresh()
    {
        if ($this->Manager)
        if ($this->SQL || $this->Resource)
    	{
		    if ($this->Resource == null || ($this->Current && $this->SQL))
	        {
	            $_sql = $this->SQL;// ???
                $this->Resource = $this->Manager->Query($_sql,$this->ViewSQL);
	        }
	        elseif ($this->Resource && $this->Current)
	        {
	            mysql_data_seek($this->Resource, 0);   
	        }
        	$this->Current = 0;
       	    $this->RecsCount = sizeof($this->Data) + $this->Manager->GetNumRows($this->Resource);
	        
            if (sizeof($this->Data))
	        {
		        $this->Params['dstype'] = 'arr';
	        }
	        else $this->Params['dstype'] = 'db';
        }

    }

    function setSQL($_sql)
    {
    	$this->Resource = null;
		$this->SQL = $_sql;
    }

    function setQuery ($_table, $_fields = '*', $_cond = null, $_params = null, $_view_sql = false)
    {
        $this->Resource = null;
		if ($_cond !== null) $_cond = " WHERE " . $_cond;
		$this->SQL = 'SELECT ' . $_fields . ' FROM '. $_table . ' ' . $_cond.' ' . $_params . ' ;';
		$this->ViewSQL = $_view_sql;
		
		if($_view_sql) dump($this->SQL);
    }


    function addData($_data)
    {
      	$this->Data[] = &$_data;
    }

    function setData($_data)
    {
      	$this->Data = &$_data;
    }

    function Next()
    {
    	if ($this->Current<$this->RecsCount)
        {
            if (sizeof($this->Data))
            {
            	$this->Items = array_shift($this->Data);
                $this->Current++;
            }
            else
            {
            	$this->Params['dstype'] = 'db';
            	$this->Items = $this->Manager->GetNextRec($this->Resource);
				$this->Current++;
            }
            return true;
        }

        return false;
    }

}

?>