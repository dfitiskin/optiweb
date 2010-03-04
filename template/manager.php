<?
class CTemplate_Manager
{
    public $Module = null;
    public $CacheParams = null;
    public $Profile = null;

	function &Load($_fname,$_prefix = array())
    {
    	if (!sizeof($_prefix) && ($this->Profile != null)) $_prefix [] = $this->Profile;
    	$_modulename = null;
    	if ($this->Module !== null) $_modulename = "/".$this->Module;
    	$_data = $this->Kernel->GetFile($_fname,TEMPLS_DIR.$_modulename,PROFILES_DIR,array(),$_prefix);
        if ($_data === null) Error('Can not load template "'.$_fname.'" for module "'.$this->Module.'"',$this->Name);
        return $_data;
    }

    function findTemplate($_fname,$_prefix = array())
    {
    	$_modulename = null;
    	if ($this->Module !== null) $_modulename ="/".$this->Module;
    	$_path = $this->Kernel->findFile($_fname,TEMPLS_DIR.$_modulename,PROFILES_DIR,$_prefix);
        return $_path;
    }

    function setProfile($_alias)
    {
   		$this->Profile = $_alias;
    }

    function setCacheParams($_modulename,$_templ,$_ds_time,$_params = array(),$_cache='main')
    {
//    	clearstatcache();
		$this->Module = $_modulename;
        $_path = $this->findTemplate($_templ['file']);

        $_tpl_time = filemtime($_path);
        $_templ_file = str_replace('.','_',$_templ['file']);
        $_path = PROFILES_DIR . $this->Kernel->Profile.'/'.CACHE_DIR.'/'.BLOCKS_DIR.'/'.$_modulename.'/'.$_cache.'/'.$_templ_file.'/';
        $_cch_time = file_exists($_path)?filemtime($_path):0;

        $_param_str = implode('_',$_params);
        $_cache_code = md5($_param_str);

        $this->CacheParams = array(
        	'ds_time'		=>	$_ds_time,
        	'tpl_time'		=>	$_tpl_time,
            'cch_time'		=>  $_cch_time,
            'cache_path'	=>	$_path,
            'cache_code'	=>	$_cache_code,
            'templ'			=>	$_templ
        );

    }

    function getCache()
    {
        if ($this->CacheParams['cch_time']>$this->CacheParams['tpl_time'] &&
        	$this->CacheParams['cch_time']>$this->CacheParams['ds_time'])
        {
        	$_data = &$this->Kernel->ReadFile($this->CacheParams['cache_path'].$this->CacheParams['cache_code']);

	        $this->b = $this->Kernel->getCurTime();

            return $_data;
        }else{
        	$FManager = &$this->Kernel->Link('services.filemanager',true);
            $FManager->deleteFolder($this->CacheParams['cache_path']);
        }
        return null;
    }

    function setCache($_data)
   	{
   		if (strlen(trim($_data)))
   		{
	        $FManager = &$this->Kernel->Link('services.filemanager',true);
	        $FManager->WriteFile($this->CacheParams['cache_path'].$this->CacheParams['cache_code'],$_data);
		}
    	$this->CacheParams = null;

    }

	function Execute(&$_ds, &$_templ,$_modulename = null)
    {
        if ($_templ === null) user_error('Template not set',E_USER_ERROR);
        $this->Module = $_modulename;
        $_parts = explode('.',$_templ['file'],2);
        if (is_array($_templ))
        {
	        switch ($_parts[1])
	        {
	            case 'tpl':
	                $Builder = &$this->Kernel->Link('template.builder');
	                $Builder->SetManager($this);
	                $Builder->Prepare($_templ['file']);
	                $_result = $Builder->Execute($_ds);
	            break;
	        }
	    }
        else
        {
        	$Builder = &$this->Kernel->Link('template.builder');
            $Builder->PrepareTpl($_templ);
            $_result = $Builder->Execute($_ds);
        }


        if ($this->CacheParams) $this->setCache($_result);

        return $_result;
    }

	function ExecuteExt(&$_ds, &$_templs,$_modulename = null)
    {
    	$i=0;
		do
		{
			$_templ = $_templs[$i];
    		$_fl =  $this->findTemplate($_templ['file']);
    		$i++;
    	}
    	while (!$_fl && ($i < sizeof($_templs)));

//        Dump($_templ);

        $this->Module = $_modulename;
        $_parts = explode('.',$_templ['file'],2);

        switch ($_parts[1])
        {
			case 'tpl':
            	$Builder = &$this->Kernel->Link('template.builder');
                $Builder->SetManager($this);
                $Builder->Prepare($_templ['file']);
                $_result = $Builder->Execute($_ds);
            break;
        }

//        if ($this->CacheParams) $this->setCache($_result);

        return $_result;
    }

    function getSlotsList($_templ,$_modulename,$_profile)
    {
		$this->Module = $_modulename;
        $_data = &$this->Load($_templ,$_profile);
		preg_match_all('/<!--#slot[^>]*src\s*=\s*[\'"]([^\'"]*)[\'"][^>]*-->/i',$_data,$_tmp);
        $_slots = array_unique($_tmp[1]);
        ksort($_slots);
        return $_slots;
    }
}
?>