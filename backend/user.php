<?

class CBackend_User
{
    public $Account = null;
    public $Status = null;
    public $Groups = null;
    public $Access = null;
    public $Profile = null;
    public $Profiles = array();
    public $Menu = array();
    public $Modules = array();
    public $Versions = array();
    public $Params = null;

    function SetAccount($_login,$_uid,$_status,$_name = 'anonymous', $master = 0)
    {
        $this->Account = array(
            'login'    =>  $_login,
            'uid'      =>  $_uid,
            'status'   =>  $_status,
            'name'     =>  $_name,
            'master'   =>  $master,
        );
    }

    function isMaster()
    {
        return $this->Account['master'];
    }

    function SetGroups($_groups)
    {
        $this->Groups = $_groups;
    }

    function SetProfiles($_profiles)
    {
        $this->Profiles = $_profiles;
    }

    function addProfile($_profile)
    {
        $this->Profiles[] = $_profile;
    }

    function SetProfile($_profile = null)
    {
        if ($_profile && $_ch_profile = $this->IsCorrectProfile($_profile))
        {
            $this->Profile = $_ch_profile;
        }
        elseif (sizeof($this->Profiles))
        {
            $this->Profile = $this->Profiles[0];
        }
        else
        {
            $this->Profile = array(
                'id'    => "0",
                'alias' => "void",
                'name'  => "void",
                'main'  => "0",
            );
        }
    }

    function & GetProfilesDS()
    {
        $_ds = &$this->Kernel->Link('dataset.array');
        foreach ($this->Profiles as $_profile)
        {
            if ((int)$_profile['id'] == (int)$this->Profile['id'])
            {
                $_profile['active'] = true;
            }
            else
            {
                $_profile['active'] = false;
            }
            $_ds->addData($_profile);
        }
        $result = isset($_ds) ? $_ds : null; 
        return $result;
    }

    function GetProfiles($_type = null)
    {
        switch ($_type)
        {
            case 'id':
                $_ids = array();
                foreach($this->Profiles as $_profile)
                {
                    $_ids[] = $_profile['id'];
                }
                return $_ids;
            break;
            case 'alias':
                $_aliases = array();
                foreach($this->Profiles as $_profile)
                {
                    $_aliases[] = $_profile['alias'];
                }
                return $_aliases;
            break;
            default:
                return $this->Profiles;
            break;
        }
    }

    function GetProfile($_param = null)
    {
        if (!$this->Profile)
        {
            return null;
        }

        switch ($_param)
        {
            case 'id':
                return $this->Profile['id'];
            break;
            case 'name':
                return $this->Profile['name'];
            break;
            case 'alias':
                return $this->Profile['alias'];
            break;
            default:
                return $this->Profile;
            break;
        }
    }

    function IsCorrectProfile($_ch_profile = null)
    {
        if (!$_ch_profile)
        {
            $_ch_profile = $this->Profile['id'];
        }

        foreach($this->Profiles as $_profile)
        {
            if ((int)$_profile['id'] == (int)$_ch_profile)
            {
                return $_profile;
            }
        }
        return false;
    }

    function GetCurrentProfile($_type = 'id')
    {
        return $this->getProfile($_type);
    }

    function addMenu($_menu)
    {
        $this->Menu[] = $_menu;
    }

    function getMenu()
    {
        return $this->Menu;
    }

    function IsCorrectMenu($_alias)
    {
        foreach($this->Menu as $_menu)
        {
            if (($_menu['alias'] == $_alias) && (!$_menu['prid'] || (int)$_menu['prid'] == (int)$this->Profile['id']))
            {
                return true;
            }
        }
        return false;
    }

    function & getMenuDS()
    {
        $_ds = &$this->Kernel->Link('dataset.array');
        foreach ($this->Menu as $_menu)
        {
            if (!$_menu['prid'] || ((int)$_menu['prid'] == (int)$this->Profile['id']))
            {
                $_ds->addData($_menu);
            }
        }
        return $_ds;
    }

    function addModule($_module)
    {
        $this->Modules[] = $_module;
    }
    
    function addVersion($ver)
    {
        $this->Versions[] = $ver;
    }
    

    function getModules()
    {
        return $this->Modules;
    }
    
    function getVersions()
    {
        return $this->Versions;
    }
    

    function IsCorrectModule($_id)
    {
        foreach($this->Modules as $_module)
        {
            if (((int)$_module['id'] == (int)$_id) && !$_module['perms'])
            {
                return 2;
            }
            elseif ((int)$_module['id'] == (int)$_id)
            {
                return $_module['perms'];
            }
        }
        return false;
    }

    function & getModulesDS()
    {
        $_ds = &$this->Kernel->Link('dataset.array');
        foreach ($this->Modules as $_module)
        {
            if (!$_module['perms'] || (int)$_module['id'] == (int)$_id)
            {
                $_ds->addData($_module);
            }
        }
        return $_ds;
    }
    
    function & getVersionsDs($modileId, $profileId)
    {
        $ds = & $this->Kernel->link('dataset.array');
        
        foreach ($this->Versions as $i => $ver)
        {
            if (!$ver['perms'] && $ver['mid'] == $modileId && ($ver['prid'] == $profileId || 1 == $ver['type']))
            {
                $ds->addData($ver);
            }
        }
        return $ds;
    }
    

    function IsRegistred()
    {
        return isset($this->Account['status']) && ($this->Account['status'] == BE_REGISTERED_USER);
    }

    function GetAccount($_name)
    {
        return isset($this->Account[$_name])?$this->Account[$_name]:null;
    }

    function GetGroups()
    {
        return ($this->Groups != null)?$this->Groups:null;
    }


    function setParams($_params)
    {
        $this->Params = $_params;
    }

    function updParams($_params)
    {
        $this->Params = array_merge($this->Params,$_params);
    }

    function getParam($_name)
    {
        return isset($this->Params[$_name])?$this->Params[$_name]:null;
    }

    function setParam($_name,$_value)
    {
        $this->Params[$_name] = $_value;
    }
}
?>