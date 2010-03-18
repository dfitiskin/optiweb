<?php

class CBackend_access
{
    public $Tables = array();
    public $Name = null;
    public $Ruls = null;
    public $WarningDS = -1;
    public $FormValues = null;
    public $UserParams = null;

    function Init()
    {
        $this->Tables = array(
            'users'         => 'be_users',
            'profiles'      => 'be_profiles',
            'versions'      => 'be_module_versions',
            'menu'          => 'be_menu',
            'modules'       => 'be_modules',
            'mod_access'    => 'be_module_access',
            'ver_access'    => 'be_version_access',
            'user_profiles' => 'be_user_profiles',
            'user_menu'     => 'be_user_menu',
            'user_modules'  => 'be_user_modules',
            'user_versions' => 'be_user_versions',
        );

        $this->Name = 'system';

        $this->Ruls = array(
            'users_list' => array(
                'login'      => 'nn;match|[\w\d]+;len|20',
                'password'   => 'nn;match|[\w\d]+;len|20',
                'name'       => 'nn;len|30',
                'surname'    => 'mn;len|30',
                'phone'      => 'mn;len|30',
                'email'      => 'mn;email;len|30',
            ),
            'groups_list'        => array(
                'groupname'  => 'nn;match|[\w\d]+;len|30',
            )
        );
    }

    function Process()
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
            isset($_POST['action']) && isset($_POST['mode']))
        switch ($_POST['mode'])
        {
            case 'users_list':
                $this->ModifyUserList($_POST);
            break;
            case 'groups_list':
                $this->ModifyGroupList($_POST);
            break;
            case 'user':
                $this->ModifyUserGroups($_POST);
            break;
            case 'userprofiles':
                $this->ModifyUserProfiles($_POST);
            break;
            case 'usermenu':
                $this->ModifyUserMenu($_POST);
            break;
            case 'usermodules':
                $this->ModifyUserModules($_POST);
            break;
            case 'profiles':
                $this->ModifyProfiles($_POST);
            break;
            case 'menu':
                $this->ModifyMenu($_POST);
            break;
            case 'modules':
                $this->ModifyModules($_POST);
            break;

        }
    }

    function ModifyProfiles($_params)
    {
        switch ($_params['action'])
        {
            case 'upd':
                if (isset($_params['upd']) && is_array($_params['upd']) && sizeof($_params['upd']))
                {
                    $_upd = $_params['upd'];

                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    foreach($_upd as $_id => $_perms)
                    {
                        $_items = array(
                            'perms' => $_perms,
                        );
                        $DbManager->updateValues($this->Tables['profiles'], $_items, 'id="'.$_id.'"');
                    }
                }
            break;
        }
    }

    function ModifyMenu($_params)
    {
        switch ($_params['action'])
        {
            case 'upd':
                if (isset($_params['upd']) && is_array($_params['upd']) && sizeof($_params['upd']))
                {
                    $_upd = $_params['upd'];

                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    foreach($_upd as $_id => $_perms)
                    {
                        $_items = array(
                            'perms' => $_perms,
                        );
                        $DbManager->updateValues($this->Tables['menu'], $_items, 'id="'.$_id.'"');
                    }
                }
            break;
        }
    }

    function ModifyModules($_params)
    {
        switch ($_params['action'])
        {
            case 'upd':
                if (isset($_params['upd']) && is_array($_params['upd']) && sizeof($_params['upd']))
                {
                    $_upd = $_params['upd'];
                    $_ver = isset($_params['ver']) ? $_params['ver'] : array();

                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Delete($this->Tables['mod_access'], '1');
                    $DbManager->Delete($this->Tables['ver_access'], '1');

                    foreach($_upd as $_id => $_perms)
                    {
                        if ($_perms)
                        {
                            $_items = array(
                                'mid'   => $_id,
                                'perms' => 1,
                            );
                            $DbManager->insertValues($this->Tables['mod_access'], $_items);
                        }
                    }
                    
                    foreach($_ver as $_id => $_perms)
                    {
                        if ($_perms)
                        {
                            $_items = array(
                                'version_id'   => $_id,
                                'perms'        => 1,
                            );
                            $DbManager->insertValues($this->Tables['ver_access'], $_items);
                        }
                    }
                    
                }
            break;
        }
    }

    function ModifyUserList($_params)
    {
        switch ($_params['action'])
        {
            case 'del':
                if (isset($_params['kill']) && is_array($_params['kill']))
                {
                    global $BackendAuth;
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->DeleteValues($this->Tables['users'],'id',$_params['kill']);
                    $DbManager->DeleteValues($this->Tables['user_profiles'],'uid',$_params['kill']);
                    $DbManager->DeleteValues($this->Tables['user_menu'],'uid',$_params['kill']);
                    $DbManager->DeleteValues($this->Tables['user_modules'],'uid',$_params['kill']);
//                    $BackendAuth->UpdateStatus('groups');
                }
            break;
            case 'add':
                $_add = $_params['add'];

                $Checker = &$this->Kernel->Link('services.checker',true);
                $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'user_add');

                $_fl = $Checker->VerifyValues($_add,$_ruls);

                if ($_fl)
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $_count = $DbManager->GetRecsCount($this->Tables['users'],'login="'.$_add['login'].'"');

                    if (!$_count)
                    {
                        $_items = array(
                            'login'     =>  $_add['login'],
                            'password'  =>  $_add['password'],
                            'name'      =>  $_add['name'],
                            'surname'   =>  $_add['surname'],
                            'phone'     =>  $_add['phone'],
                            'email'     =>  $_add['email'],
                        );
                        $DbManager->InsertValues($this->Tables['users'],$_items);
                        $_user_id = $DbManager->GetLastID();
                        $_items = array(
                       		'prid' =>  5,
                          	'uid'  =>  $_user_id
                        );
                        $DbManager->InsertValues($this->Tables['user_profiles'], $_items);
                        
                        
                    }
                    else
                    {
                        $this->FormValues = $_add;
                        $Checker->addMessage($_ruls['_other']['alias_exists']);
                        $this->WarningDS = &$Checker->GetWarningDS($_ruls);
                    }
                }
                else
                {
                    $this->WarningDS = &$Checker->GetWarningDS($_ruls);
                    $this->FormValues = $_add;
                }
            break;
            case 'upd':
                $_upd = $_params['upd'];
                $_upd['login'] = $this->UserParams['login'];

                $Checker = &$this->Kernel->Link('services.checker',true);
                $_ruls = $this->Kernel->ConfigManager->GetAdminWarnings('backend',$this->Name,'user_add');
                $_fl = $Checker->VerifyValues($_upd,$_ruls);
                if ($_fl)
                {
                    $_items = array(
                        'password'  =>  $_upd['password'],
                        'name'      =>  $_upd['name'],
                        'surname'   =>  $_upd['surname'],
                        'phone'     =>  $_upd['phone'],
                        'email'     =>  $_upd['email'],
                    );

                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->UpdateValues($this->Tables['users'],$_items,'login="'.$_upd['login'].'"');

                    $this->UserParams = array_merge($this->UserParams,$_upd);
                }
                else
                {
                    $this->WarningDS = &$Checker->GetWarningDS($_ruls);
                    $this->FormValues = $this->UserParams;
                }
            break;
        }
    }

    function ModifyUserProfiles($_params)
    {
        if ($this->UserParams['master'] != 1)
        {
            global $Auth;
            switch ($_params['action'])
            {
                case 'add' :
                    $DbManager = &$this->Kernel->Link('database.manager',true);

                    if (isset($_params['add']) && sizeof($_params['add']))
                    {
                        foreach($_params['add'] as $k=>$v)
                        {
                            if ($DbManager->GetRecsCount($this->Tables['user_profiles'],
                                'uid="'.$this->UserParams['id'].'" and prid="'.$v.'"') == 0)
                            {

                                $_items = array(
                                    'prid' =>  $v,
                                    'uid'  =>  $this->UserParams['id']
                                );
                                $DbManager->InsertValues($this->Tables['user_profiles'],$_items);
                                //$Auth->UpdateStatus('groups');
                            }
                        }
                    }
                break;
                case 'del' :
                    if (isset($_params['kill']))
                    {
	                    $DbManager = &$this->Kernel->Link('database.manager',true);
	                    $DbManager->DeleteValues(
	                        $this->Tables['user_profiles'],
	                        'prid',
	                        $_params['kill'],
	                        'uid="'.$this->UserParams['id'].'"'
	                    );
                    }
                    //$Auth->UpdateStatus('groups');
                break;
            }
        }
    }

    function ModifyUserMenu($_params)
    {
        if ($this->UserParams['master'] != 1)
        {
            global $Auth;
            switch ($_params['action'])
            {
                case 'add' :
                    $DbManager = &$this->Kernel->Link('database.manager',true);

                    if (isset($_params['add']) && sizeof($_params['add']))
                    {
                        foreach($_params['add'] as $k=>$v)
                        {
                            if ($DbManager->GetRecsCount($this->Tables['user_menu'],
                                'uid="'.$this->UserParams['id'].'" and mid="'.$v.'"') == 0)
                            {

                                $_items = array(
                                    'mid' =>  $v,
                                    'uid'  =>  $this->UserParams['id']
                                );
                                $DbManager->InsertValues($this->Tables['user_menu'],$_items);
                                //$Auth->UpdateStatus('groups');
                            }
                        }
                    }
                break;
                case 'del' :
                    if(isset($_params['kill']))
                    {
	                    $DbManager = &$this->Kernel->Link('database.manager',true);
	                    $DbManager->DeleteValues(
	                        $this->Tables['user_menu'],
	                        'mid',
	                        $_params['kill'],
	                        'uid="'.$this->UserParams['id'].'"'
	                    );
                    }
                    //$Auth->UpdateStatus('groups');
                break;
            }
        }
    }

    function ModifyUserModules($_params)
    {
        if ($this->UserParams['master'] != 1)
        {
            global $Auth;
            switch ($_params['action'])
            {
                case 'add' :
                    $DbManager = &$this->Kernel->Link('database.manager',true);

                    if (isset($_params['add']) && sizeof($_params['add']))
                    {
                        foreach($_params['add'] as $moduleId => $versions)
                        {
                            $moduleAdded = $DbManager->GetRecsCount(
                                $this->Tables['user_modules'],
                                'uid="'.$this->UserParams['id'].'" and mid="'.$moduleId.'"'
                            );
                            
                            if ($moduleAdded == 0)
                            {
                                $_items = array(
                                    'mid' =>  $moduleId,
                                    'uid' =>  $this->UserParams['id']
                                );
                                
                                $DbManager->InsertValues($this->Tables['user_modules'],$_items);
                                
                            }
                            
                            if (is_array($versions))
                            {
                                foreach ($versions as $versionId => $flag)
                                {
                                    $versionAdded = $DbManager->GetRecsCount(
                                        $this->Tables['user_versions'],
                                        'uid="'.$this->UserParams['id'].'" AND version_id="'.$versionId.'"'
                                    );
                                    
                                    if ($versionAdded == 0)
                                    {
                                        $_items = array(
                                            'version_id' =>  $versionId,
                                            'uid'        =>  $this->UserParams['id']
                                        );
                                        $DbManager->InsertValues($this->Tables['user_versions'],$_items);
                                    }                                        
                                }
                            }
                        }
                    }
                break;
                case 'del' :
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    
                    $killModules = array();
                    $killVersions = array();
                    
                    foreach ($_params['kill'] as $moduleId => $versions)
                    {
                        if (is_array($versions))
                        {
                            $killVersions = array_merge(
                                $killVersions,
                                array_keys($versions)
                            );    
                        }
                        else
                        {
                            $killModules[] = $moduleId;  
                        }
                    }
                    
                    $DbManager->DeleteValues(
                        $this->Tables['user_modules'],
                        'mid',
                        $killModules,
                        'uid="'.$this->UserParams['id'].'"'
                    );

                    $DbManager->DeleteValues(
                        $this->Tables['user_versions'],
                        'version_id',
                        $killVersions,
                        'uid="'.$this->UserParams['id'].'"'
                    );
                    
                    //$Auth->UpdateStatus('groups');
                break;
            }
        }
    }

    function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
        switch ($_params['mode'])
        {
            case 'tab_menu' :
                $_ds = &$this->Kernel->Link('dataset.abstract');

                $_modes_ds = &$this->Kernel->Link('dataset.array');
                $_data = array(
                    array(
                        'name'    =>  'Пользователи',
                        'alias'   =>  'users/',
                        'mode'    =>  'users'
                    ),
                    array(
                        'name'    =>  'Профили',
                        'alias'   =>  'profiles/',
                        'mode'    =>  'profiles'
                    ),
                    array(
                        'name'    =>  'Главное меню',
                        'alias'   =>  'menu/',
                        'mode'    =>  'menu'
                    ),
                    array(
                        'name'    =>  'Модули',
                        'alias'   =>  'modules/',
                        'mode'    =>  'modules'
                    ),
                );
                $_modes_ds->setData($_data);

                $_base_url = implode('/',$this->LinkParams);
                if ($_base_url) $_base_url .= '/';
                $_base_url = $this->Kernel->BaseUrl.$_base_url;

                $_url = implode('/',$this->TreeParams);
                if ($_url) $_url .= '/';

                $_params_modes_ds = array(
                    'activemode'        =>        $this->WorkMode,
                    'url'                        =>         $_url,
                    'baseurl'                =>        $_base_url
                );
                $_modes_ds->setParams($_params_modes_ds);

                $_ds->addChildDS('tabmenu',$_modes_ds);


                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],'backend');
                return $_result;
            break;
            case 'users_list':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = $this->FormValues;
                $_ds_params['mode'] = $_params['mode'];
                $_ds_params['_object'] = $this->Name;
                $_ds_params['_url'] = $_link_url;

                $_ds->SetParams($_ds_params);
                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_ds_users = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('users',$_ds_users);
                $_ds_users->SetQuery(
                    $this->Tables['users'],
                    '*',
                    'login<>"root"',
                    'order by login'
                );
                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);

            break;
            case 'groups_list':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                    'mode'          =>  $_params['mode'],
                    'objectname'    =>  $this->Name,
                    '_url'          =>  $_link_url
                );
                $_ds->SetParams($_ds_params);
                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_ds_users = &$this->Kernel->Link('dataset.database');

                $_ds->AddChildDS('groups',$_ds_users);
                $_ds_users->SetQuery($this->Tables['groups'],'*',null,'order by groupname');

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
            break;
            case 'user':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                if($this->FormValues)
                {
                    $this->UserParams = array_merge($this->UserParams,$this->FormValues);
                }
                $_ds_params = $this->UserParams;
                $_ds_params['mode'] = $_params['mode'];
                $_ds_params['_object'] = $this->Name;
                $_ds_params['_url'] = $_link_url;

                $_ds->SetParams($_ds_params);

                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_ds_profiles_del = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('profiles_del',$_ds_profiles_del);
                $_ds_profiles_del->SetQuery(
                    $this->Tables['profiles'].' p LEFT JOIN '.$this->Tables['user_profiles'].' u ON p.id = u.prid AND u.uid="'.$this->UserParams['id'].'"',
                    'p.*',
                    'p.perms=0 OR !ISNULL(u.prid)',
                    'ORDER BY p.name'
                );

                $_ds_profiles_add = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('profiles_add',$_ds_profiles_add);
                $_ds_profiles_add->SetQuery(
                    $this->Tables['profiles'].' p LEFT JOIN '.
                    $this->Tables['user_profiles'].' u ON u.prid = p.id AND u.uid = '.$this->UserParams['id'],
                    'p.*',
                    'p.perms<>0 AND isnull(u.uid)',
                    'ORDER BY p.name'
                );

                $_ds_menu_del = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('menu_del',$_ds_menu_del);
                $_ds_menu_del->SetQuery(
                    $this->Tables['menu'].' m LEFT JOIN '.$this->Tables['user_menu'].' u ON m.id = u.mid AND u.uid="'.$this->UserParams['id'].'"',
                    'm.*',
                    'm.perms=0 OR !ISNULL(u.mid)',
                    'ORDER BY m.name'
                );

                $_ds_menu_add = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('menu_add',$_ds_menu_add);
                $_ds_menu_add->SetQuery(
                    $this->Tables['menu'].' m LEFT JOIN '.
                    $this->Tables['user_menu'].' u ON u.mid = m.id AND u.uid = '.$this->UserParams['id'],
                    'm.*',
                    'm.perms<>0 AND isnull(u.uid)',
                    'ORDER BY m.name'
                );

                $_ds_modules_del = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('modules_del',$_ds_modules_del);
                $_ds_modules_del->SetQuery(
                    $this->Tables['modules'].' m '
                    .   ' LEFT JOIN '.$this->Tables['mod_access'].' a ON a.mid=m.id '
                    .   ' LEFT JOIN '.$this->Tables['user_modules'].' u ON m.id = u.mid AND u.uid="'.$this->UserParams['id'].'" '
                    .   ' LEFT JOIN '.$this->Tables['versions'].' v ON v.mid=m.id'
                    .   ' LEFT JOIN '.$this->Tables['ver_access'].' va ON v.id=va.version_id'
                    .   ' LEFT JOIN '.$this->Tables['user_versions'].' uv ON v.id=uv.version_id AND uv.uid="'.$this->UserParams['id'].'"',
                    'm.*, v.id AS version_id, v.name AS version_name, va.perms AS version_perms, a.perms as perms',
                    'm.interactive=1 AND (ISNULL(a.perms) OR !ISNULL(u.mid)) AND (ISNULL(va.perms) OR !ISNULL(uv.version_id))',
                    'ORDER BY m.name, v.name'
                );

                $_ds_modules_add = &$this->Kernel->Link('dataset.database');
                $_ds->AddChildDS('modules_add',$_ds_modules_add);
                $_ds_modules_add->SetQuery(
                    $this->Tables['modules'].' m '
                    .   ' LEFT JOIN '.$this->Tables['mod_access'].' a ON a.mid=m.id '
                    .   ' LEFT JOIN '.$this->Tables['user_modules'].' u ON m.id = u.mid AND u.uid="'.$this->UserParams['id'].'" '
                    .   ' LEFT JOIN '.$this->Tables['versions'].' v ON v.mid=m.id'
                    .   ' LEFT JOIN '.$this->Tables['ver_access'].' va ON v.id=va.version_id'
                    .   ' LEFT JOIN '.$this->Tables['user_versions'].' uv ON v.id=uv.version_id AND uv.uid="'.$this->UserParams['id'].'"',
                    'm.*, v.id AS version_id, v.name AS version_name, va.perms AS version_perms, a.perms as perms',
                    'm.interactive=1 AND ((ISNULL(v.id) AND a.perms<>0 AND ISNULL(u.uid)) OR (va.perms<>0 AND ISNULL(uv.uid)))',
                    'ORDER BY m.name'
                );

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);

            break;
            case 'profiles':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                    'mode'       =>  $_params['mode'],
                    '_object'    =>  $this->Name,
                    '_url'       =>  $_link_url
                );
                $_ds->SetParams($_ds_params);
                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_profiles_ds = &$this->Kernel->Link('dataset.database');

                $_ds->AddChildDS('profiles',$_profiles_ds);
                $_profiles_ds->SetQuery(
                    $this->Tables['profiles'],
                    '*, if(perms=1,"selected","") as is_perms',
                    null,
                    'order by name'
                );

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
            break;
            case 'menu':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                    'mode'       =>  $_params['mode'],
                    '_object'    =>  $this->Name,
                    '_url'       =>  $_link_url
                );
                $_ds->SetParams($_ds_params);
                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_menu_ds = &$this->Kernel->Link('dataset.database');

                $_ds->AddChildDS('menu',$_menu_ds);
                $_menu_ds->SetQuery(
                    $this->Tables['menu'],
                    '*, if(perms=1,"selected","") as is_perms',
                    null,
                    'order by name'
                );

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
            break;
            case 'modules':
                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                    'mode'       =>  $_params['mode'],
                    '_object'    =>  $this->Name,
                    '_url'       =>  $_link_url
                );
                $_ds->SetParams($_ds_params);
                $_ds->AddChildDS('warnings',$this->WarningDS);

                $_modules_ds = &$this->Kernel->Link('dataset.database');
                $_modules_ds->SetQuery(
                    $this->Tables['modules'].' m '
                        .   ' LEFT JOIN '.$this->Tables['mod_access'].' a ON m.id=a.mid ',
                    'm.*, IF(a.perms=1,"selected","") AS is_perms, m.id AS module_id',
                    'm.interactive=1',
                    'ORDER BY m.name'
                );
                $_ds->AddChildDS('modules',$_modules_ds);
                
                $_versions_ds = &$this->Kernel->Link('dataset.database');
                $_versions_ds->SetQuery(
                    $this->Tables['versions'].' v'
                        .   ' LEFT JOIN '.$this->Tables['ver_access'].' a ON v.id=a.version_id ',
                    'v.*, IF(a.perms=1,"selected","") AS is_perms',
                    null,
                    'ORDER BY v.name'
                );
                $_modules_ds->AddChildDS('versions',$_versions_ds);
                

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
            break;
            default:
                Dump($_params);
                $_result = 'No Content!';
            break;
        }
        return $_result;
    }

    function GetAccess()
    {
        return true;
    }

    function CorrectParts($_parts)
    {
        switch ($_parts[0])
        {
            case 'users':
                $Checker = &$this->Kernel->Link('services.checker',false);
                if ( $Checker->Check($_parts[1],$this->Ruls['users_list']['login']))
                {
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->Select(
                        $this->Tables['users'],
                        '*',
                        'login<>"root" AND  login="'.$_parts[1].'"'
                    );
                    $_rec = $DbManager->GetNextRec();

                    if ($_rec && $DbManager->GetNumRows() == 1)
                    {
                        $this->UserParams = $_rec;
                        $_parts[1] = '_user';
                    }
                }
            break;
        }

        return $_parts;
    }

    function isCorrectParts()
    {
        return true;
    }

    function Control()
    {
        $this->WorkMode = 'manager';
        $this->TreeParams = $this->UrlParams;
        if (sizeof($this->UrlParams))
        {
            switch($this->UrlParams[0])
            {
                case 'users' :
                    $this->TreeParams = array_slice($this->TreeParams,3);
                    $this->WorkMode = 'users';
                break;
                case 'groups' :
                    $this->TreeParams = array_slice($this->TreeParams,3);
                    $this->WorkMode = 'groups';
                break;
                case 'rights' :
                    $this->TreeParams = array_slice($this->TreeParams,1);
                    $this->WorkMode = 'rights';
                break;
                case 'profiles' :
                    $this->TreeParams = array_slice($this->TreeParams,1);
                    $this->WorkMode = 'profiles';
                break;
                case 'menu' :
                    $this->TreeParams = array_slice($this->TreeParams,1);
                    $this->WorkMode = 'menu';
                break;
                case 'modules' :
                    $this->TreeParams = array_slice($this->TreeParams,1);
                    $this->WorkMode = 'modules';
                break;
            }
        }
    }

    function GetWorkType()
    {
        return PAGE_MODE_NORMAL;
    }
}
?>