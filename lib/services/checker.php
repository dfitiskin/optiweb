<?

define('CH_NULL',0);
define('CH_NOT_NULL',1);
define('CH_LENGTH',2);
define('CH_EMAIL',3);
define('CH_TYPE',4);
define('CH_RANGE',5);
define('CH_HTML',6);
define('CH_MATCH',7);
define('CH_NOT_MATCH',8);
define('CH_URL',8);

class CServices_Checker
{
    public $Warnings = array();
    public $Ruls        = array();
    public $RulsList = array();
    public $Messages = array();

    function Init()
    {
        $this->Ruls = array(
            'nn'         =>        array(
                'name'         =>        'nn',
                'descr'        =>        '������ �� ������ ����',
                'index'        =>        CH_NOT_NULL,
                'params'       =>        array(),
                'fname'        =>        'nn',
            ),
            'n'         =>        array(
                'name'         =>        'n',
                'descr'        =>        '�������� ������ �� ������ ����',
                'index'        =>        CH_NOT_NULL,
                'params'       =>        array(),
                'fname'        =>        'n',
            ),
            'len'        =>        array(
                'name'         =>        'len',
                'descr'        =>        '����������� �� ���������� ��������',
                'index'        =>        CH_LENGTH,
                'params'       =>  array(
                    'items' => array(
                        array(
                            'type'  => 'text',
                            'name'  => 'slot1',
                            'descr' => '��������',
                        ),
                        array(
                            'type'  => 'text',
                            'name'  => 'slot2',
                            'descr' => '�������',
                        ),
                    ),
                ),
                'fname'        =>        'len',
            ),
            'email'      =>        array(
                'name'         =>        'email',
                'descr'        =>        '���������� ����� ����������� �����',
                'index'        =>        CH_EMAIL,
                'params'       =>        array(),
                'fname'        =>        'email',
            ),
            'type'       =>        array(
                'name'         =>        'type',
                'descr'        =>        '������������ ���������� ���� ������',
                'index'        =>        CH_TYPE,
                'params'       =>  array(
                    'items' => array(
                        array(
                            'type'   => 'list',
                            'name'   => 'slot1',
                            'descr'  => '���',
                            'values' => array(
                                'items'  => array(
                                    array(
                                        'name'  => '����� �����',
                                        'value' => 'i',
                                    ),
                                    array(
                                        'name'  => '������� �����',
                                        'value' => 'f',
                                    ),
                                    array(
                                        'name'  => '���� �  �����',
                                        'value' => 'dt',
                                    ),
                                    array(
                                        'name'  => '����',
                                        'value' => 'd',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'fname'        =>        'type',
            ),
            'range'      =>        array(
                'name'         =>        'range',
                'descr'        =>        '��������� � ��������',
                'index'        =>        CH_RANGE,
                'params'       =>   array(
                    'items' => array(
                        array(
                            'type'  => 'text',
                            'name'  => 'slot1',
                            'descr' => '��',
                        ),
                        array(
                            'type'  => 'text',
                            'name'  => 'slot2',
                            'descr' => '��',
                        ),
                    ),
                ),
                'fname'        =>        'range',
            ),
            'htm'        =>        array(
                'name'         =>        'htm',
                'descr'        =>        '������ ������� HTML ����',
                'index'        =>         CH_HTML,
                'params'       =>        array(),
                'fname'        =>        'htm',
            ),
            'match'        =>        array(
                'name'         =>        'match',
                'descr'        =>        '������������ ����������� ���������',
                'index'        =>        CH_MATCH,
                'params'       =>   array(
                    'items' => array(
                        array(
                            'type'  => 'text',
                            'name'  => 'slot1',
                            'descr' => '���������',
                        ),
                    ),
                ),
                'fname'        =>        'match',
            ),
            'nmatch'        =>        array(
                'name'         =>        'nmatch',
                'descr'        =>        '�� ������������ ����������� ���������',
                'index'        =>        CH_NOT_MATCH,
                'params'       =>   array(
                    'items' => array(
                        array(
                            'type'  => 'text',
                            'name'  => 'slot1',
                            'descr' => '���������',
                        ),
                    ),
                ),
                'fname'        =>        'nmatch',
            ),
            'url'        =>        array(
                'name'         =>        'url',
                'descr'        =>        '���������� URL',
                'index'        =>        CH_NOT_MATCH,
                'params'       =>        array(),
                'fname'        =>        'url',
            )
        );

        $this->RulsList = array(
            CH_NOT_NULL     =>   'nn',
            CH_LENGTH       =>   'len',
            CH_EMAIL        =>   'email',
            CH_TYPE         =>   'type',
            CH_RANGE        =>   'range',
            CH_HTML         =>   'htm',
            CH_MATCH        =>   'match',
            CH_NOT_MATCH    =>   'nmatch',
            CH_NOT_MATCH    =>   'url',
        );
    }

    function ClearWarnings()
    {
                $this->Warnings = array();
    }

    function AddWarning($_field,$_warn)
    {
                $this->Warnings[] = array($_field,$_warn);
    }

    function GetFields($_st)
    {
        $_ruls = $this->ParseRules($_st);
        $_out_descr = array();
        $_errors = array();
//        $_out_rules
        for ($i=0;$i<sizeof($_ruls);$i++)
        {
                        $_rul = $_ruls[$i][0];
            if (isset($this->Ruls[$_rul]))
            {
                        $_out_descr[] = $this->Ruls[$_rul];
            }
            else
            {
                    if ($_rul !== 'mn')
                                        $_errors[] = $_rul;
            }
        }
        $_out = array(
                'errors'        =>        $_errors,
            'descr'        =>        $_out_descr
        );
        return $_out;
    }

        //-------------------------------------------------------------------
        // ��������� �������� �� ������ $_st ������
        //-------------------------------------------------------------------
        function isInt($_st)
        {
                return preg_match("/^[0-9]+$/",$_st);
        }

        //-------------------------------------------------------------------
        // ������ ������ ��������/����������
        //-------------------------------------------------------------------
        function &ParseRules(&$_ruls)
        {
                $_rul = explode(";",$_ruls);
                for($i=0;$i<sizeof($_rul);$i++)
        {
                        $_rul[$i] = explode("|",$_rul[$i]);
        }
        return $_rul;
        }

        var $nFieldWarning = array();
        var $nFieldNoWarning = array();
        function setNFieldWarning($field)
        {
			$this->nFieldWarning[] = $field; 
        }
        
        function setNFieldNoWarning($field)
        {
			$this->nFieldNoWarning[] = $field; 
        }
        
        function warningNField()
        {
        	$warnings = array();
        	if (count($this->nFieldNoWarning) == 0)
        	{
        		foreach ($this->nFieldWarning as $i => $nField)
        		{
        			$warnings[] = array($nField, 'n');
        		}
        	}
        	return $warnings;
        }
        
        function Check($_st,$_rul,$_field='default')
        {
                $_rul = $this->ParseRules($_rul);
                $_cor = true;
		foreach ($_rul as $k=>$v)
		{
	        switch ($v[0])
	        {
	                // ����� ���� ������ ( mn - may be null)
	            case 'mn' :
	                if ($_st === '')  return true;
	            break;
	            // �� ����� ���� ������ ( nn - not null)\
	            case 'nn' :
	                if (trim($_st) === '')
	                {
	                    $this->AddWarning($_field,'nn');
	                    return false;
	                }
	            break;
	            // ����� ���� ������ � �� ������
	            case 'n' :
	            	if (trim($_st) === '')
	                {
	                	$this->setNFieldWarning($_field);
	                }
	                else
	                {
						$this->setNFieldNoWarning($_field);
	                }
	            break;
	            // ����� (len - length)
	            case 'len' :
	                if ((isset($v[1]) && strlen($_st)>$v[1]) ||
	                    (isset($v[2]) && strlen($_st)<$v[2]))
	                {
	                    $_cor = false;
	                                        $this->AddWarning($_field,'len');
	                }
	            break;
	            // ����� ����� (mail - email)
	            case 'email' :
	            	if (trim($_st) !== '')
	            	{
		                if (!preg_match('/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\\.[A-Z]{2,4}$/i',$_st))
		                {
		                    $_cor = false;
		                    $this->AddWarning($_field,'email');
		                }
	            	}
	            break;
	            // �������� ���� (i-integer , f - float, d - data)
	            case 'type' :
	                $fl = false;
	                switch ($v[1])
	                {
	                    case "i" : $fl = $this->IsInt($_st); break;
	                    case "f" : $fl = is_numeric($_st); break;
	                    case "dt" :
	                        $fl = (preg_match("/^(\d{1,2})\\.(\d{1,2})\\.(\d{2,4})\s\d{2}:\d{2}:\d{2}$/",$_st,$_tmp) && checkdate($_tmp[2],$_tmp[1],$_tmp[3])) ||
	                                        (preg_match("/^(\d{2,4})-(\d{1,2})-(\d{1,2})\s\d{2}:\d{2}:\d{2}$/",$_st,$_tmp) && checkdate($_tmp[2],$_tmp[3],$_tmp[1]) );
	                    break;
	                    case "d" :
	                        $fl = (preg_match("/^(\d{1,2})\\.(\d{1,2})\\.(\d{2,4})$/",$_st,$_tmp) && checkdate($_tmp[2],$_tmp[1],$_tmp[3])) ||
	                                        (preg_match("/^(\d{2,4})-(\d{1,2})-(\d{1,2})$/",$_st,$_tmp) && checkdate($_tmp[2],$_tmp[3],$_tmp[1]) );
	                    break;
	
	                }
	                          if (!$fl)
	                {
	                    $this->AddWarning($_field,'type');
	                    return false;
	                }
	            break;
	                        // ������� �����
	            case 'range' :
	                           if ((isset($v[1]) && $_st>$v[1]) || (isset($v[2]) && $_st<$v[2]))
	                {
	                                        $this->AddWarning($_field,'range');
	                    return false;
	                }
	            break;
	
	                        // html ��� (htm - html)
	            case 'htm' :
	                if ($_st !== HtmlSpecialChars($_st))
	                {
	                    $_cor = false;
	                                        $this->AddWarning($_field,'htm');
	                }
	            break;
	            case 'match' : // html ��� (htm - html)
	                if (!preg_match("/^".$v[1]."$/",$_st))
	                {
	                    $_cor = false;
	                    $this->AddWarning($_field,'match');
	                }
	            break;
	            case 'nmatch' : // html ��� (htm - html)
                    if (preg_match("/^".$v[1]."$/",$_st))
	                {
                        $_cor = false;
	                    $this->AddWarning($_field,'nmatch');
	                }
	            break;
	            case 'url' : // html ��� (htm - html)
	                if (!preg_match("/^(http:\/\/)?(www\.)?([\w-_]+\.)?[\w-_]+\.([\w-_]+\\/)*[\w_-]*$/",$_st))
	                {
	                    $_cor = false;
	                    $this->AddWarning($_field,'url');
	                }
	            break;
	        }
		}
		return $_cor;
    }

    function CheckValues($_arr,$_ruls)
    {
    	$_fl = true;
                foreach($_ruls as $k=>$v)
                {
                if (!isset($_arr[$k])) $_arr[$k] = '';
                        $_fl = $this->Check($_arr[$k],$v,$k) && $_fl;
                }
                return $_fl;
    }

    function &GetWarningDS(&$_messes)
    {
        $_data = $this->Messages;
        for ($i=0;$i<sizeof($this->Warnings);$i++)
        {
            list($fld,$err) = $this->Warnings[$i];

            if (isset($_messes[$fld][$err]))
            {
                $_data[] = array(
                    'message' => $_messes[$fld][$err],
                    'field'   => $fld,
                );
            }
            else
            {
                $_data[] = array(
                    'message' => 'in field '.$fld.' finded warning '.$err,
                    'field'   => $fld,
                );
            }
                    
        }
        if (sizeof($_data))
        {
                    $WarningDS = &$this->Kernel->Link('dataset.array');
                        $WarningDS->SetData($_data);
        }
        else $WarningDS = "-1";
                return $WarningDS;
    }

    function VerifyValues(&$_items,&$_ruls)
    {
        $_fl = true;
        $this->Warnings = array();
        $this->Messages = array();
        foreach($_ruls as $k=>$v)
        {
        	if ($k !== '_other')
			{
                if (!isset($_items[$k])) 
                {
                	$_items[$k] = '';
                }
            	$_fl &= $this->Check($_items[$k],$v['_ruls'],$k);
			}
        }
		return $_fl;
    }

    function isWarnings()
    {
                return sizeof($this->Warnings)+sizeof($this->Messages);
    }

    function addMessage($_message)
    {
            $this->Messages[] = array(
                'message'        => $_message
        );
    }

}


?>