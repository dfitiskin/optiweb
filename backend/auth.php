<?

define('BE_ANONYMOUS_USER','0');
define('BE_REGISTERED_USER','1');

class CBackend_Auth
{
    public $Tables = null;

    public $UserType = null;
    public $Templs = array();
    public $Name = null;
    public $User = null;
    public $Status = array();

    function Init()
    {
    	$this->Tables = array(
            'groups'        =>   'be_groups',
            'users'         =>   'be_users',
            'user_groups'   =>   'be_user_groups',
            'user_profiles' =>   'be_user_profiles',
            'user_access'   =>   'be_user_access',
            'user_menu'     =>   'be_user_menu',
            'user_modules'  =>   'be_user_modules',
            'user_versions' =>   'be_user_versions',
            'profiles'      =>   'be_profiles',
            'menu'          =>   'be_menu',
            'modules'       =>   'be_modules',
            'modules_access'=>   'be_module_access',
            'versions'      =>   'be_module_versions',
            'versions_access'=>   'be_version_access',
            'profiles_menu' =>   'be_profiles_menu',
        );

        $this->Auth();
    }

    function Auth()
    {
    	$this->User = &$this->Kernel->Link('backend.user',true,'User');
    	
        if (isset($_SESSION['user']) && sizeof($_SESSION['user']) && isset($_SESSION['user']['uid']))
        {
            $this->User->Account = $_SESSION['user'];
        }
        else
        {
            $this->User->Account = array(
                 'login'    =>   'anonimys',
                 'uid'      =>   -1,
                 'status'   =>   BE_ANONYMOUS_USER
            );
        }

        if (isset($_SESSION['params']) && sizeof($_SESSION['params']))
        {
            $this->User->Params = $_SESSION['params'];
        }

        if (isset($_SESSION['groups']) && sizeof($_SESSION['groups']))
        {
            $this->User->Groups = $_SESSION['groups'];
        }

        if (isset($_SESSION['profiles']) && sizeof($_SESSION['profiles']))
        {
            $this->User->Profiles = $_SESSION['profiles'];
        }

        if (isset($_SESSION['menu']) && sizeof($_SESSION['menu']))
        {
            $this->User->Menu = $_SESSION['menu'];
        }
        
        if (isset($_SESSION['modules']) && sizeof($_SESSION['modules']))
        {
            $this->User->Modules = $_SESSION['modules'];
        }
        
        if (isset($_SESSION['versions']) && sizeof($_SESSION['versions']))
        {
            $this->User->Versions = $_SESSION['versions'];
        }
        

        if (isset($_SESSION['profile']) && $_SESSION['profile'] != null)
        {
            $this->User->Profile = $_SESSION['profile'];
        }

        $_SESSION['user']     = &$this->User->Account;
        $_SESSION['params']   = &$this->User->Params;
        $_SESSION['groups']   = &$this->User->Groups;
        $_SESSION['profiles'] = &$this->User->Profiles;
        $_SESSION['profile']  = &$this->User->Profile;
        $_SESSION['menu']  = &$this->User->Menu;
        $_SESSION['modules']  = &$this->User->Modules;
        $_SESSION['versions']  = &$this->User->Versions;
    }

    function Process()
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
            isset($_POST['action']) && isset($_POST['mode']))
        {
            switch ($_POST['mode'])
            {
                case 'auth':
                    $this->ModufyUser($_POST);
                break;
            }

            $_POST = array();
        }
    }

    function ModufyUser($_params)
    {
        switch($_params['action'])
        {
            case 'login':
                if (isset($_params['username']) && isset($_params['password']))
                {
                    $this->Login($_params['username'], $_params['password']);
                }
            break;
            case 'logout':
                $this->Logout();
            break;
            case 'change_profile':
                if ($this->IsUserRegistred())
                {
                    $this->User->setProfile($_params['profile']);
                }
            break;
        }
    }

    function Login($_name, $_pass)
    {
        if (preg_match('/^[\d\w]+$/',$_name) && preg_match('/^[\d\w]+$/',$_pass))
        {
            $this->Logout(true);
            $MysqlManager = &$this->Kernel->Link('database.manager',true);
            $MysqlManager->Select(
                $this->Tables['users'].' u ',
                'u.id, u.master, u.name, u.surname',
                'u.login = "'.$_name.'" and u.password = "'.$_pass.'"'
            );
            $_rec = $MysqlManager->GetNextRec();

            if ($MysqlManager->GetNumRows()==1)
            {
                $this->User->SetAccount(
                    $_name,
                    $_rec['id'],
                    BE_REGISTERED_USER,
                    $_rec['name'].'&nbsp;'.$_rec['surname'],
                    $_rec['master']
                );
                $this->getProfiles($_rec['master']);
                $this->getMenu($_rec['master']);
                $this->getModules($_rec['master']);
                $this->getVersions($_rec['master']);

                $_SESSION['user'] = &$this->User->Account;
                $_SESSION['params'] = &$this->User->Params;
                $_SESSION['groups'] = &$this->User->Groups;
                $_SESSION['profiles'] = &$this->User->Profiles;
                $_SESSION['profile'] = &$this->User->Profile;
                $_SESSION['menu'] = &$this->User->Menu;
                $_SESSION['modules'] = &$this->User->Modules;
                $_SESSION['versions'] = &$this->User->Versions;
                $GLOBALS['Page']->setRedirect($this->Kernel->Url);
            }
        }
    }

    function Logout($_save_session = false)
    {
        $this->User->SetAccount('anonimys',-1,BE_ANONYMOUS_USER);
        $_SESSION['user']     = array();
        $_SESSION['params']   = array();
        $_SESSION['groups']   = array();
        $_SESSION['profiles'] = array();
        $_SESSION['profile']  = array();
        $_SESSION['menu']     = array();
        $_SESSION['modules']  = array();
        $_SESSION['versions']  = array();
        if (!$_save_session && sizeof($_SESSION))
        {
            session_destroy();
        }
    }

    function getProfiles($_master)
    {
        if ($this->User->GetProfiles() == null)
        {
            $MysqlManager = &$this->Kernel->Link('database.manager',true);
            $MysqlManager->Select(
                $this->Tables['profiles'].' p LEFT JOIN '.
                $this->Tables['user_profiles'].' up ON p.id=up.prid AND up.uid="'.$this->User->Account['uid'].'"',
                'p.*,up.prid as upprid',
                $_master.' OR p.perms = 0 OR !ISNULL(up.prid)',
                'GROUP BY p.id'
            );

            while ($_rec = $MysqlManager->GetNextRec())
            {
                $this->User->addProfile($_rec);
            }
        }

        if ($this->User->GetProfile() == null)
        {
            $this->User->setProfile();
        }
    }

    function getMenu($_master)
    {
        if ($this->User->GetMenu() == null)
        {
            $MysqlManager = &$this->Kernel->Link('database.manager',true);
            $MysqlManager->Select(
                $this->Tables['menu'].' g LEFT JOIN '.
                $this->Tables['profiles_menu'].' l ON g.id=l.mid LEFT JOIN '.
                $this->Tables['user_menu'].' u ON u.mid=g.id AND u.uid="'.$this->User->Account['uid'].'"',
                'g.*, if('.$_master.',NULL,l.prid) as prid',
                $_master.' OR g.perms = 0 OR !ISNULL(u.mid)',
                'GROUP BY g.id ORDER BY g.sort'
            );

            while ($_rec = $MysqlManager->GetNextRec())
            {
                $this->User->addMenu($_rec);
            }
        }
    }

    function getModules($_master)
    {
        if ($this->User->GetModules() == null)
        {
            $MysqlManager = &$this->Kernel->Link('database.manager',true);
            $MysqlManager->Select(
                $this->Tables['modules'].' m LEFT JOIN '.
                $this->Tables['modules_access'].' a ON m.id=a.mid LEFT JOIN '.
                $this->Tables['user_modules'].' u ON u.mid=m.id AND u.uid="'.$this->User->Account['uid'].'"',
                'm.*, u.perms as perms',
                'm.interactive = 1 AND ('.$_master.' OR ISNULL(a.perms) OR !ISNULL(u.mid))',
                'GROUP BY m.id ORDER BY m.name'
            );

            while ($_rec = $MysqlManager->GetNextRec())
            {
                $this->User->addModule($_rec);
            }
        }
    }
    
    function getVersions($_master)
    {
        if ($this->User->getVersions() == null)
        {
            $MysqlManager = &$this->Kernel->Link('database.manager',true);
            $MysqlManager->Select(
                $this->Tables['versions'].' v LEFT JOIN '.
                $this->Tables['versions_access'].' a ON v.id=a.version_id LEFT JOIN '.
                $this->Tables['user_versions'].' u ON u.version_id=v.id AND u.uid="'.$this->User->Account['uid'].'"',
                'v.*, u.perms AS perms',
                $_master.' OR ISNULL(a.perms) OR !ISNULL(u.version_id)',
                'GROUP BY v.id ORDER BY v.name'
            );

            while ($_rec = $MysqlManager->GetNextRec())
            {
                $this->User->addVersion($_rec);
            }
        }
    }    

    function GetUser($_type)
    {
        return ($this->User[$_type])?$this->User[$_type]:null;
    }

    function IsUserRegistred()
    {
    	return ($this->User->IsRegistred());
    }

    function GetAccess()
    {
    	if (isset($_GET['kill']))
        {
            $this->Logout();
        }
        if (sizeof($_POST))
        {
            $this->Process();
        }

        $_access = false;
        if ($this->IsUserRegistred())
        {
            $_access = true;
            // Проверка ограничений профиля
            if (!$this->User->Profile && !$this->User->isMaster())
            {
                $_access = false;
            }
            // Проверка ограничений меню
            if (!sizeof($this->User->Menu) && !$this->User->isMaster())
            {
                $_access = false;
            }
            elseif (isset($this->Kernel->Params[0]) && !$this->User->isMaster())
            {
                $_current_menu = $this->Kernel->Params[0];
                $_access = $this->User->IsCorrectMenu($_current_menu);
            }
        }
        
        return $_access;
    }
}
?>