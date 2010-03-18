<?
//------------------------------------------------------------------------------
// Module : System
// Class  : CMS Profile
// Ver    : 0.2 beta
// Date   : 25.03.2004
// Desc   : �������
//------------------------------------------------------------------------------

class CSystem_Profile
{
    public $Alias = null;                  // ��������� �������
    public $ID = null;                     // ������������� �������
    public $RootDir = null;                // �������� �������
    public $ProfileName = null;            // �������� �������
    public $Tables = array();              // ������� ��

//------------------------------------------------------------------------------
// ������������� �������
//------------------------------------------------------------------------------
    function Init()
    {
        $this->Tables = array(
            'profiles'          =>        'be_profiles',
            'profiles_access'   =>        'be_profiles_access',
            'tree'              =>        'be_tree'
        );

        $DbManager = &$this->Kernel->Link('database.manager',true);
        if(0 && $DbManager->noDataBase)
        {
            $this->Alias = 'install';
            $this->ID = '-1';
            $this->RootDir = ROOT_DIR;
            $this->ProfileName = '��������� OptyWeb';
        }
        else
        {

            $DbManager->Select(
                $this->Tables['profiles'].' as p, '.$this->Tables['profiles_access'].' a',
                'p.id,p.alias,a.rootdir,p.name','a.pid=p.id and a.hostname="'.$_SERVER['HTTP_HOST'].'" and instr("'.$this->Kernel->Url.'",rootdir)=1'
            );

            $_rec = $DbManager->getNextRec();
            if ($_rec)
            {
                $this->Alias = $_rec['alias'];
                $this->ID = $_rec['id'];
                $this->RootDir = $_rec['rootdir'];
                $this->ProfileName = $_rec['name'];
            }
        }
    }

//------------------------------------------------------------------------------
// ��������� ���������� �������
//------------------------------------------------------------------------------
    function getAlias()
    {
        return $this->Alias;
    }

//------------------------------------------------------------------------------
// ��������� ��������� URL
//------------------------------------------------------------------------------
    function getBaseUrl()
    {
        return $this->RootDir;
    }

//------------------------------------------------------------------------------
// ��������� �������� �������
//------------------------------------------------------------------------------
    function getProfileName()
    {
        return $this->ProfileName;
    }

//------------------------------------------------------------------------------
// ��������� �������������� �������
//------------------------------------------------------------------------------
    function getID()
    {
        return $this->ID;
    }

//------------------------------------------------------------------------------
// ������������� ���������� URL
//------------------------------------------------------------------------------
    function correctParams($_params)
    {
        $_root_params =  explode("/", $this->RootDir);
        $_count = sizeof($_root_params);
        if (sizeof($_root_params))
        {
            $_count--;
            if  ($_root_params[count($_root_params) - 1] === '') $_count--;
        }
        if ($_count<0)
        {
            return null;
        }
        return array_slice($_params,$_count);
    }
}
?>