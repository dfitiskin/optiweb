<?

global $Kernel;
$Kernel->LoadLib('abstract','dataset');

class CDataset_Form extends CDataset_Abstract
{
    public $Type = 'form';
    public $Object = null;
    public $Fields = null;
    public $Items = null;
    public $Struct = null;
    public $Warnings = null;

    public $RecsCount = null;
    public $Current = 0;
    public $CurrentField = null;

    function setObject(&$_obj)
    {
        $this->Object = &$_obj;
        $this->RecsCount = sizeof($this->Object->Struct);
        $this->Fields = array_keys($this->Object->Struct);
    }

    function SetParent(&$_ds)
    {
        parent::setParent($_ds);
        if (isset($this->Parent->Object))
        {
            $this->setObject($this->Parent->Object);
        }
        return true;
    }

    function Refresh()
    {
        $this->Current = 0;
        $this->CurrentField = $this->Fields[$this->Current];
    }

    function &GetChildDS($_name)
    {
        switch($_name)
        {
            case '_field_warnings':
                $_ds = &$this->Kernel->Link('dataset.array');
                $_ds->SetData($this->Warnings);
                $_ds->SetParent($this);
            break;
        }
    }

    function Next()
    {
        return false;
    }

    function isLast()
    {
        return ($this->Current >= $this->RecsCount);
    }

    function &getParam($_name)
    {
        if ($_name == '_current' ) return $this->Current;
        if ($_name == '_count' ) return $this->RecsCount;
        if ($_name == '_parent_current' ) $_name = '_current';
        if (isset($this->Items[$_name])) return $this->Items[$_name];
        if (isset($this->Params[$_name])) return $this->Params[$_name];

        if (isset($this->Parent)) return  $this->Parent->GetParam($_name);
        return null;
    }

    function GetParentParam($_name,$_uplevel)
    {
        if ($_uplevel)
        {
            $_uplevel--;
            return ($this->Parent!=null)?$this->Parent->GetParentParam($_name,$_uplevel):null;
        }
        else
        {
            return $this->GetParam($_name);
        }
    }

    function HasElements()
    {
        if ($this->RecsCount)
        {
            return true;
        }
        return false;
    }

    function setParams($_data)
    {
        $this->Params = &$_data;
    }

    function addParams($_data)
    {
        $this->Params += $_data;
    }

    function GetXML()
    {
        return null;
    }
}

?>