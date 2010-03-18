<?

global $Kernel;
$Kernel->LoadLib('array','dataset');

class CBackend_Navigator
{
    var $Tables;

    function CBackend_Navigator()
    {
        $this->Tables = array(
            'profiles'        =>        'be_profiles',
        );
    }

    function Execute($_params,$_templs,$_type_params,$_url_params)
    {
        switch ($_params['mode'])
        {
            case 'personal':

                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_menu = &$this->Kernel->Link('dataset.array');
                $_data = $this->Kernel->LoadConfig('backend','navigator_personal');
                $_ds_menu->setData($_data);
                $_ds->addChildDS('menu',$_ds_menu);

                global $User;
                $_ds_params = $User->Account;
                $_ds_params['system_access'] = $User->IsCorrectMenu('system');
                $_ds->setParams($_ds_params);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['default'],$this->Name);
                return $_result;
            break;
            case 'main' :
                $_ds_main = &$this->Kernel->Link('dataset.abstract');

                global $User;
                $_ds_profiles = & $User->GetProfilesDS();
                $_ds_main->AddChildDS('profiles',$_ds_profiles);

                $_menu_ds = & $User->GetMenuDS();
                if (isset($_url_params[0]))
                {
                    $_ds_params = array(
                        'active'    => $_url_params[0]
                    );
                    $_menu_ds->AddParams($_ds_params);
                }
                $_ds_main->AddChildDS('mainlist',$_menu_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds_main,$_templs['default'],$this->Name);
                return $_result;
            break;
        }
    }

}


?>