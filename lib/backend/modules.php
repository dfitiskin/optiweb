<?
class CBackend_Modules
{
    public $Name;
    public $Tables;
    public $isUrlCorrect = true;
    public $CurrentUrl = null;
    public $ModuleParams = null;
    public $ModeType = null;
    public $ConfigType = null;
    public $Protect = null;
    public $fileDescr = null;

    function Init()
    {
        $this->Name        ='modules';
        $this->Tables = array(
            'modules'        =>        'be_modules'
        );

        $this->fileDescr = &$this->Kernel->Link('backend.filedescr');

        $this->Labels = array(
            'protect'        =>        array(
                'folder' => array(
                    '_inter'        =>        'Интрактив',
                    '_services'                =>        'Сервис'
                )
            )
        );
    }

    function Process($_url_params)
    {
        if (isset($_POST['object']) && $_POST['object'] == $this->Name &&
            isset($_POST['action']) && isset($_POST['mode']))
        {
            if ($this->ModeType != 'config')
            {
                switch ($_POST['mode'])
                {
                    case 'setup':
                        $this->ModuleSetup($_POST,$_url_params);
                    break;
                    case 'file_descr':
                        $this->UpdateDescr($_POST,$_url_params);
                    break;
                    case 'lib_mng':
                        $this->UpdateProtectLibrary($_POST,$_url_params);
                    break;
                    case 'file_mng':
                        $this->UpdateProtectFile($_POST,$_url_params);
                    break;
                    case 'config':
                        $this->UpdateArrConfig($_POST,$_url_params);
                    break;
                    case 'data_config':
                        $this->UpdateDataConfig($_POST,$_url_params);
                    break;
                    case 'warnings':
                        $this->UpdateWarningsList($_POST,$_url_params);
                    break;
                    case 'warnings_field':
                        $this->UpdateWarningsField($_POST,$_url_params);
                    break;
                    case 'warnings_other':
                        $this->UpdateWarningsOther($_POST,$_url_params);
                    break;
                }
            }
        }
    }

    function ModuleSetup($_params)
    {
        switch ($_params['action'])
        {
            case 'inst':
                $_items = $_params['setup'];
                $DBManager = $this->Kernel->Link('database.manager',true);
                $DBManager->InsertValues($this->Tables['modules'],$_items);
            break;
            case 'uninst':
                if(isset($_params['del']) && is_array($_params['del']))
                {
                    $_del = $_params['del'];
                    $DbManager = &$this->Kernel->Link('database.manager',true);
                    $DbManager->deleteValues($this->Tables['modules'],"id", $_del);
                }
            break;
        }
    }

    function UpdateDescr($_params,$_url_params)
    {
            switch ($_params['action'])
            {
                case 'upd':

                $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/';
                $_name = $this->Protect['lib'];
                    if ($_params['type'] == 'file')
                {
                    $_path .= $this->Protect['lib'].'/';
                    $_name = $this->Protect['file'];
                }
                $_descr = $this->fileDescr->GetDescript($_path);
                $_descr[$_name] = $_params['descr'];
                $this->fileDescr->SetDescript($_path,$_descr);

            break;
        }
    }

    function UpdateProtectLibrary($_params)
    {
                switch ($_params['action'])
        {
                case 'add':
                 if ($_params['add']['type'])
                 {
                                        $_name = $_params['add']['type'];
                    if (!$_params['add']['descr'])
                            $_params['add']['descr'] = $this->Labels['protect']['folder'][$_name];
                 }
                 else $_name = $_params['add']['filename'];

                 if ($_name)
                 {
                        $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/';
                    if ($_params['add']['descr'])
                    {
                            $_descr = $this->fileDescr->GetDescript($_path);
                                    $_descr[$_name] = $_params['add']['descr'];
                            $this->fileDescr->SetDescript($_path,$_descr);
                                        }
                    $FManager = &$this->Kernel->Link('services.filemanager');
                    $FManager->CreateFolder($_path.$_name.'/');
                 }
            break;
                case 'del':
                $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/';
                $_descr = $this->fileDescr->GetDescript($_path);
                $_del = $_params['del'];
                while (sizeof($_del))
                {
                    $_name = array_shift($_del);
                    rmdir($_path.$_name.'/');
                        unset($_descr[$_name]);
                                }
                    $this->fileDescr->SetDescript($_path,$_descr);
            break;
                }
    }

    function UpdateProtectFile($_params)
    {
                switch ($_params['action'])
        {
                case 'add':

                $_filename = $_params['add']['type'].'_'.$_params['add']['filename'];
                $_file = $_params['add']['filename'];
                $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/'.$this->Protect['lib'].'/';

                $_descr = $this->fileDescr->GetDescript($_path);
                $_descr[$_filename] = $_params['add']['descr'];
                $this->fileDescr->SetDescript($_path,$_descr);

                    $Config = &$this->Kernel->Link('system.config');
                    $_cfg_params = array(
                        'type'      =>  $_params['add']['type'],
                        'module'    =>  $this->ModuleParams['alias'],
                        'lib'       =>  $this->Protect['lib'],
                        'file'      =>  $_file
                    );

                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);

            break;
                case 'del':
                $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/'.$this->Protect['lib'].'/';
                $_descr = $this->fileDescr->GetDescript($_path);
                $_del = $_params['del'];
                while (sizeof($_del))
                {
                    $_name = array_shift($_del);
                    unlink($_path.$_name.CONFIG_XML_EXT);
                        unset($_descr[$_name]);
                                }
                    $this->fileDescr->SetDescript($_path,$_descr);
            break;
                }
    }

    function UpdateArrConfig($_params,$_templs)
    {

                $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
                       'type'                =>        'c',
            'module'        =>  $this->ModuleParams['alias'],
            'lib'                =>        $this->Protect['lib'],
            'file'                =>        $_file[1]
        );

                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);
        $CFGUpdater->SetActiveNode($this->Protect['params']);

            switch ($_params['action'])
            {
                case 'add':
                if (!$CFGUpdater->isNodeExists($_params['name']))
                {
                                        $_node_params = array();
                                        $_node_params['name'] = $_params['name'];
                        $_node_params['descr'] = $_params['descr'];
                        $_node_params['type'] = $_params['type'];

                                        $CFGUpdater->AddNode($_node_params);
                        $CFGUpdater->SaveConfig();
                }
            break;
                case 'upd':
                                $_del = isset($_params['del'])?$_params['del']:null;
                $_upd = isset($_params['upd'])?$_params['upd']:null;
                if (is_array($_del))
                while (sizeof($_del))
                {
                                        $_name = array_shift($_del);
                    if (isset($_upd[$_name]))  unset($_upd[$_name]);
                    $CFGUpdater->DelNode($_name);
                }

                if (is_array($_upd))
                while (sizeof($_upd))
                {
                        $_name = key($_upd);
                                        $_arr = current($_upd);
                    array_shift($_upd);
                    $CFGUpdater->UpdateNode($_name,$_arr);
                }

                    $CFGUpdater->SaveConfig();
            break;
        }
    }

    function UpdateDataConfig($_params)
    {
        $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
                       'type'                =>        'c',
            'module'        =>  $this->ModuleParams['alias'],
            'lib'                =>        $this->Protect['lib'],
            'file'                =>        $_file[1]
        );

                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);
        $CFGUpdater->SetActiveNode($this->Protect['params']);

                switch ($_params['action'])
        {
                case 'addcol':
                    $_params['name'] = trim($_params['name']);
                    if (!$CFGUpdater->isDataColExists($_params['name']))
                {
                        $_col_params = array(
                            'name'      =>  $_params['name'],
                            'descr'     =>  $_params['descr'],
                            'type'      =>  $_params['type'],
                        );
                        $CFGUpdater->DataAddCol($_col_params);
                        $CFGUpdater->SaveConfig();
                }
            break;
                case 'addrow':
                    $_row_params = $_params['add'];
                    $CFGUpdater->DataAddRow($_row_params);
                $CFGUpdater->SaveConfig();
            break;
            case 'upd':
                    if (isset($_params['del_col']))
                {
                        $_del_cols = $_params['del_col'];
                        while (sizeof($_del_cols))
                        {
                            $_name = array_shift($_del_cols);
                            $CFGUpdater->DataDeleteCol($_name);
                        }
                }
                    if (isset($_params['del_row']))
                {
                        $_del_rows = $_params['del_row'];
                                        asort($_del_rows);
                        while (sizeof($_del_rows))
                        {
                            $_num = array_pop($_del_rows);
                            $CFGUpdater->DataDeleteRow($_num);
                        }
                }

                $CFGUpdater->SaveConfig();
                        break;
        }
    }

    function UpdateWarningsList($_params)
    {
                $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
                       'type'                =>        'w',
            'module'        =>  $this->ModuleParams['alias'],
            'lib'                =>        $this->Protect['lib'],
            'file'                =>        $_file[1]
        );

                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);
        $CFGUpdater->SetActiveNode();

            switch ($_params['action'])
            {
                case 'del':
                 if (isset($_params['del']) && sizeof($_params['del']))
                 {
                         for ($i=0;$i<sizeof($_params['del']);$i++)
                    {
                            $_name = $_params['del'][$i];
                            $CFGUpdater->DelNode($_name);
                    }
                    $CFGUpdater->SaveConfig();
                 }

            break;
                case 'add':
                if (!$CFGUpdater->isNodeExists($_params['add']['name']))
                {
                                        $_node_params = array();
                                        $_node_params['name'] = $_params['add']['name'];
                    $_node_params['type'] = 'a';
                        $_node_params['descr'] = $_params['add']['descr'];

                                        $CFGUpdater->AddNode($_node_params);
                    $CFGUpdater->SetActiveNode(array($_params['add']['name']));

                    $_node_params['type'] = 's';
                                        $_node_params['name'] = '_ruls';
                        $_node_params['descr'] = '';
                    $CFGUpdater->AddNode($_node_params);

                        $CFGUpdater->SaveConfig();
                }
            break;
        }

    }

    function UpdateWarningsField($_params,$_url_params)
    {
                $_name = $_url_params[4];
                $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
                       'type'                =>        'w',
            'module'        =>  $this->ModuleParams['alias'],
            'lib'                =>        $this->Protect['lib'],
            'file'                =>        $_file[1]
        );

                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);
        $CFGUpdater->SetActiveNode(array($_name));

        switch ($_params['action'])
        {
                case 'upd_fields':
                $_upd = $_params['upd'];
                foreach($_upd as $k=>$v)
                {
                        $_cfg_params = array(
                            'value'        =>        $v
                    );
                                        $CFGUpdater->UpdateNode($k,$_cfg_params);
                }
                $CFGUpdater->SaveConfig();
            break;
                case 'upd_filter':
                    $Checker = &$this->Kernel->Link('services.checker');
                    $_fields = $Checker->GetFields($_params['ruls']);

                    if (!sizeof($_fields['errors']))
                    {
                    $_params['ruls'] = stripslashes($_params['ruls']);
                        $_cfg_params = array(
                            'value'        =>        $_params['ruls']
                    );
                                        $CFGUpdater->UpdateNode('_ruls',$_cfg_params);

                    $_old_fields = array();
                    while($_child_params = $CFGUpdater->GetChildParams())
                    {
                            if ($_child_params['name'] != '_ruls')
                                $_old_fields[$_child_params['name']] = 1;
                    }

                                        for ($i=0;$i<sizeof($_fields['descr']);$i++)
                    {
                            $_field = $_fields['descr'][$i];
                        if (!isset($_old_fields[$_field['name']]))
                        {
                                $_new_params = array(
                                    'type'        =>        's',
                                'name'        =>        $_field['name'],
                                'descr'        =>        $_field['descr']
                            );
                                                        $CFGUpdater->AddNode($_new_params);
                        }else unset($_old_fields[$_field['name']]);
                    }

                    foreach ($_old_fields as $k=>$v)
                            $CFGUpdater->DelNode($k);

                                        $CFGUpdater->SaveConfig();
                    }
            break;
        }
    }

   function UpdateWarningsOther($_params,$_url_params)
    {
                $_name = $_url_params[4];
                $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
                       'type'                =>        'w',
            'module'        =>  $this->ModuleParams['alias'],
            'lib'                =>        $this->Protect['lib'],
            'file'                =>        $_file[1]
        );

                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);
        $CFGUpdater->SetActiveNode(array($_name));

        switch ($_params['action'])
        {
                case 'add':
                $_add = $_params['add'];
                $_add['type'] = 's';
                $_add['value'] = '';

                 if (!$CFGUpdater->isNodeExists($_add['name']))
                {
                                        $CFGUpdater->AddNode($_add);
                    $CFGUpdater->SaveConfig();
                }
            break;
                case 'upd':
                $_del = isset($_params['del'])?$_params['del']:array();
                $_upd = $_params['upd'];
                for ($i=0;$i<sizeof($_del);$i++)
                {
                                        $CFGUpdater->DelNode($_del[$i]);
                        unset($_upd[$_del[$i]]);
                }
                foreach($_upd as $k=>$v)
                {
                        $_cfg_params = array(
                            'value' =>  $v
                        );
                        $CFGUpdater->UpdateNode($k,$_cfg_params);
                }
                $CFGUpdater->SaveConfig();
            break;
        }
    }

    function Execute($_params,$_templs,$_url_params)
    {
        switch ($_params['mode'])
        {
            case 'tree':
                $_ds = $this->Kernel->Link('dataset.abstract');
                $_ds_params['current'] = isset($_url_params[0]) ? $_url_params[0] : null;
                $_ds->setParams($_ds_params);
                $_tree_ds = $this->Kernel->Link('dataset.database');
                $_tree_ds->SetQuery(
                    $this->Tables['modules'],
                    '*, if(id = "'.$this->ModuleParams['id'].'", 1, 0) as active'
                );
                $_ds->AddChildDS('tree',$_tree_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'setup':
                $_ds = $this->Kernel->Link('dataset.abstract');
                $_tree_ds = $this->Kernel->Link('dataset.database');
                $_tree_ds->SetQuery($this->Tables['modules']);
                $_ds->AddChildDS('tree',$_tree_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
                break;
            case 'info':
                    $_ds = $this->Kernel->Link('dataset.abstract');
                                $_ds_params = $this->ModuleParams;
                                $_ds->SetParams($_ds_params);
                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'config':
                $_obj = &$this->Kernel->Link($this->ModuleParams['alias'].'.config');
                if (sizeof($_POST))
                                $_obj->Process($_url_params);
                return $_obj->GetContent($_url_params);
            break;
            case 'protect':

                    $_ds = $this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                        '_url'        =>        $this->Kernel->Url
                );
                $_ds->SetParams($_ds_params);
                                $_configs_ds = $this->Kernel->Link('dataset.array');
                    $_services_ds = $this->Kernel->Link('dataset.array');

                    $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/';
                $_descripts = $this->fileDescr->GetDescript($_path);
                $Dir = dir($_path);
                if (is_object($Dir))
                while($_file = $Dir->read())
                if ($_file != "." && $_file != '..' && is_dir($_path.'/'.$_file))
                {
                    $_desc = isset($_descripts[str_replace('.','_',$_file)])?$_descripts[str_replace('.','_',$_file)]:'неизвестная';
                    $_items = array(
                        'folder'  =>  $_file,
                        'descr'  =>  $_desc
                    );

                    if ($_file[0]=='_')
                                                $_services_ds->AddData($_items);
                    else $_configs_ds->AddData($_items);
                }

                $_ds->AddChildDS('configs',$_configs_ds);
                $_ds->AddChildDS('services',$_services_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            case 'protect_lib':
                                $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/';
                $_descripts = $this->fileDescr->GetDescript($_path);
                    $_ds = $this->Kernel->Link('dataset.abstract');

                $_ds_params = array(
                        '_url'        =>        $this->Kernel->Url,
                    '_lib'        =>        $this->Protect['lib'],
                    'descr'        =>        isset($_descripts[$this->Protect['lib']])?$_descripts[$this->Protect['lib']]:''
                );

                $_ds->SetParams($_ds_params);
                                $_folder_ds = $this->Kernel->Link('dataset.array');

                    $_path .= $this->Protect['lib'].'/';
                $_descripts = $this->fileDescr->GetDescript($_path);
                $Dir = dir($_path);
                if (is_object($Dir))
                while($_file = $Dir->read())
                if ($_file != "." && $_file != '..' && !is_dir($_path.'/'.$_file))
                {
                    $_parts = explode('.',$_file);
                    if ($_parts[1] != 'xml') continue;
                    $_desc = isset($_descripts[$_parts[0]])?$_descripts[$_parts[0]]:'неизвестная';
                    $_items = array(
                        'file'  =>  $_file,
                        'filename'        =>        $_parts[0],
                        'descr'  =>  $_desc
                    );
                    $_folder_ds->AddData($_items);
                }

                $_ds->AddChildDS('folder',$_folder_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;

            break;
            case 'protect_file':
                $_path = PROFILES_DIR.'_config/'.$this->ModuleParams['alias'].'/'.$this->Protect['lib'].'/';
                    $_descr = $this->fileDescr->GetDescript($_path);
                $_file = explode('_',$this->Protect['file'],2);
                if ($_file[0] == 'w')
                {
                        $_editor = $this->WarningsManager($_templs,$_url_params);
                }
                if ($_file[0] == 'c')
                        $_editor = $this->ConfigManager($_templs);

                                $_ds = &$this->Kernel->Link('dataset.abstract');
                $_ds_params = array(
                        'editor'        => $_editor,
                    '_file'                => $this->Protect['file'],
                    'descr'                =>         isset($_descr[$this->Protect['file']])?$_descr[$this->Protect['file']]:'Неизвесный'
                );
                $_ds->SetParams($_ds_params);
                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
        }
        return 'no';
    }

    function WarningsManager($_templs,$_params)
    {
            $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
                       'type'                =>        'w',
            'module'        =>  $this->ModuleParams['alias'],
            'lib'                =>        $this->Protect['lib'],
            'file'                =>        $_file[1]
        );
                $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);
        $CFGUpdater->SetActiveNode($this->Protect['params']);
        $_ds = &$this->Kernel->Link('dataset.abstract');
        $_ds_params = array(
                '_url'        =>        $this->Kernel->Url
        );

        if (!isset($_params[0]))
                {
            $_templ = $_templs['wlist'];
                $_ds_fields = &$this->Kernel->Link('dataset.array');
                while ($_child_params = $CFGUpdater->GetChildParams())
                {
                                if ($_child_params['name'] != '_other')
                    $_ds_fields->AddData($_child_params);
                }
                $_ds->AddChildDS('fields',$_ds_fields);
        }
        else
        {
                if ($_params[0] == '_other')
            {
                                $_templ = $_templs['wother'];
                    $_ds_warnings = &$this->Kernel->Link('dataset.array');
                    while ($_child_params = $CFGUpdater->GetChildParams())
                    {
                            $_ds_warnings->AddData($_child_params);
                    }
                    $_ds->AddChildDS('warnings',$_ds_warnings);
            }
            else
            {
                                $_templ = $_templs['wfield'];
                    $_ds_fields = &$this->Kernel->Link('dataset.array');
                    while ($_child_params = $CFGUpdater->GetChildParams())
                    {
                        if ($_child_params['name'] == '_ruls')
                            $_ds_params['ruls'] = $_child_params['value'];
                    else
                                       $_ds_fields->AddData($_child_params);
                    }
                    $_ds->AddChildDS('fields',$_ds_fields);
            }
        }
        $_ds->SetParams($_ds_params);

            $TplManager = &$this->Kernel->Link('template.manager',true);
            $_result = $TplManager->Execute($_ds,$_templ,$this->Name);
            return $_result;
    }

    function ConfigManager($_templs)
    {
        $Config = &$this->Kernel->Link('system.config');
        $_file = explode('_',$this->Protect['file'],2);
        $_cfg_params = array(
            'type'    =>  'c',
            'module'  =>  $this->ModuleParams['alias'],
            'lib'     =>  $this->Protect['lib'],
            'file'    =>  $_file[1]
        );

        $CFGUpdater = &$Config->GetProtectConfig($_cfg_params);

        $CFGUpdater->SetActiveNode($this->Protect['params']);

        $_ds = &$this->Kernel->Link('dataset.abstract');
        $_ds_params = array(
            '_url' => $this->Kernel->Url
        );
        $_ds->SetParams($_ds_params);


        if ($CFGUpdater->Type == 'a')
        {
            $_config_ds = &$this->Kernel->Link('dataset.array');
            while ($_child_params = $CFGUpdater->GetChildParams())
            {
                $_config_ds->AddData($_child_params);
            }

            $_ds->AddChildDS('config',$_config_ds);
            $_templ = $_templs['array'];
        }
        else
        {
            $_header = $CFGUpdater->GetHeader();
            $_header_ds = &$this->Kernel->Link('dataset.array');
            ksort($_header);
            $_header_data = array_values($_header);
            $_header_ds->SetData($_header_data);
            $_ds->AddChildDS('header',$_header_ds);

            $_data_ds = &$this->Kernel->Link('dataset.array');
            while ($_row = $CFGUpdater->GetNextRow())
            {
                ksort($_row);
                $_row_data = array_values($_row);
                $_items = array(
                    'row'   =>  array(
                        'items' =>  $_row_data
                    )
                );
                $_data_ds->AddData($_items);
            }
            $_ds->AddChildDS('data',$_data_ds);

            $_templ = $_templs['data'];
        }

        $TplManager = &$this->Kernel->Link('template.manager',true);
        $_result = $TplManager->Execute($_ds,$_templ,$this->Name);
        return $_result;
    }

    function GetAccess()
    {
        return true;
    }

    function GetWorkType()
    {
        return PAGE_MODE_NORMAL;
    }

    function Control(){}

    function CorrectParts($_parts)
    {
        $_url = '/'.implode('/',$_parts);
        if ($_url) $_url .= '/';

        $this->CurrentUrl = $_url;

        if (isset($_parts[0]))
        {
            if("_setup" == $_parts[0] && isset($_parts[1]))
            {
                $DBManager = &$this->Kernel->Link('database.manager',true);
                $DBManager->Select($this->Tables['modules'],'*','alias ="'.$_parts[1].'"');
                $_rec = $DBManager->getNextRec();
                if($_rec)
                {
                    $this->Module = $_rec;
                    $this->isUrlCorrect = false;
                    $this->ConfigParams = array_slice($_parts,2);
                }
                else
                {
                    $this->isUrlCorrect = true;
                }
            }
            else
            {
                $DBManager = &$this->Kernel->Link('database.manager',true);
                $DBManager->Select($this->Tables['modules'],'*','alias ="'.$_parts[0].'"');
                if ($_rec = $DBManager->GetNextRec())
                {
                    $this->ModuleParams = $_rec;
                    $_parts[0] = '_module';
                    if (isset($_parts[1]))
                    {
                        $this->ConfigType = $_parts[1];
                        if ($this->ConfigType == 'config')
                        {
                            if (isset($_parts[2]))
                            {
                                $this->Library = $_parts[2];
                                $this->ConfigParams = array_slice($_parts,3);
                            }
                            else $this->isUrlCorrect = false;
                        }
                        else if ($this->ConfigType == 'protect')
                        {
                            if (isset($_parts[2]))
                            {
                                $this->Protect['lib'] = $_parts[2];
                                $_parts[2] = '_lib';
                                if (isset($_parts[3]))
                                {
                                    $this->Protect['file'] = $_parts[3];
                                    $this->Protect['params'] = array_slice($_parts,4);
                                    $_parts[3] = '_file';
                                }
                            }
                        }
                        else $this->isUrlCorrect = false;
                    }
                }
                else
                {
                    $this->isUrlCorrect = false;
                }
            }
        }
        return $_parts;
    }

    function IsCorrectParts()
    {
        return $this->isUrlCorrect;
    }
}
?>