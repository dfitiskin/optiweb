<?

class CBackend_System
{

	function Execute($_params,$_templs,$_type_params,$_url_params,$_link_url)
    {
    	switch($_params['mode'])
        {
        	case 'tree':
            	$_ds = $this->Kernel->Link('dataset.abstract');
                $_tree_ds_data = array(
                    array(
                    	'name'	=>	'Доступ',
                        'alias'	=>	'access'
                    ),
                    array(
                    	'name'	=>	'Профили',
                        'alias'	=>	'profiles'
                    ),
/*
                    array(
                    	'name'	=>	'Настройки',
                        'alias'	=>	'settings'
                    ),
                    array(
                    	'name'	=>	'Поддержка',
                        'alias'	=>	'support'
                    ),
*/
                );
                $_tree_ds_params = array(
                	'_url'		=>	$_link_url,
                    '_active'   => 	isset($_url_params[0])?$_url_params[0]:null
                );

				$_tree_ds = $this->Kernel->Link('dataset.array');
                $_tree_ds->setParams($_tree_ds_params);
                $_tree_ds->setData($_tree_ds_data);
                $_ds->AddChildDS('tree',$_tree_ds);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
        }
    }


}

?>