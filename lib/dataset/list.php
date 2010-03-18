<?
global $Kernel;
$Kernel->LoadLib('array','dataset');

class CDataset_List extends CDataset_Array
{
    public $Type = 'list';
    public $FileName = null;
    public $Fields = array();
    public $FieldsLimit = 1;
    public $fSeparator = "|";
    public $rSeparator = "\n";

    public $CurrentItems = array();
    public $CurrentField = null;

    function init()
    {
        $this->setFields('item');
    }

    function setFile($_file)
    {
        if (is_file($_file))
        {
            $this->FileName = $_file;
        }
    }

    function setFields($_fields)
    {
        if (is_array($_fields))
        {
            $this->Fields = $_fields;
        }
        else
        {
            $this->Fields = func_get_args();
        }
        $this->FieldsLimit = sizeof($this->Fields);
    }

    function setSeparator($_fsep = ";", $_rsep = "\r\n")
    {
        $this->fSeparator = $_fsep;
        $this->rSeparator = $_rsep;
    }

    function Refresh()
    {
        if ($this->FileName)
        {
            $_content = $this->Kernel->readFile($this->FileName);
            $_strings = explode($this->rSeparator,trim($_content));
            foreach($_strings as $i => $_string)
            {
                $_fields = array();
                if ($this->FieldsLimit > 1)
                {
                    $_fileds = explode($this->fSeparator, trim($_string), $this->FieldsLimit);
                }
                else
                {
                    $_fileds[0] = $_string;
                }

                foreach($this->Fields as $_num => $_field)
                {
                    $_data[$i][$_field] = isset($_fileds[$_num]) ? $_fileds[$_num] : null;
                }
            }
            $this->setData($_data);
        }
        $this->Current = 0;
    }

    function setCurrent($_items, $_fld = null)
    {
        if (!$_fld)
        {
            $_fld = $this->Fields[0];
        }
        $this->CurrentItems = $_items;
        $this->CurrentField = $_fld;
    }

    function getParam($_name)
    {
        switch ($_name)
        {
            case 'is_current':
                if ($this->CurrentField && isset($this->Items[$this->CurrentField]))
                {
                    return in_array($this->Items[$this->CurrentField], $this->CurrentItems);
                }
            break;
            default:
                return parent::getParam($_name);
            break;

        }
    }
}
?>