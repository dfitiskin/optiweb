<?

//------------------------------------------------------------------------------
// Module : System
// Class  : CMS Cacher
// Ver    : 0.1 beta
// Date   : 25.09.2004
// Desc   : Кэширование
//------------------------------------------------------------------------------

class CSystem_Cacher
{
    public $Profile;                                     // Профиль

//------------------------------------------------------------------------------
// Установка профиля
//------------------------------------------------------------------------------
    function setProfile($_alias)
    {
        $this->Profile = $_alias;
    }

//------------------------------------------------------------------------------
// получение пути
//------------------------------------------------------------------------------
    function getPath($_params,$_folder,$_file = '')
    {
        $_dir = implode('/',$_params);
        if ($_dir) $_dir .= '/';
        $_path = PROFILES_DIR.$this->Profile.'/'.CACHE_DIR.'/'.TREE_DIR.'/'.$_dir.$_file;
        return $_path;
    }

//------------------------------------------------------------------------------
// Установка кэша дерева
//------------------------------------------------------------------------------
    function setTreeCache($_params,$_data)
    {
        $_path = $this->getPath($_params,TREE_DIR,NODE_CACHE_FILE);
        $FManager = &$this->Kernel->Link('services.filemanager');
        $_data = serialize($_data);
        $FManager->WriteFile($_path,$_data);
    }

//------------------------------------------------------------------------------
// Получение кэша дерева
//------------------------------------------------------------------------------
    function getTreeCache($_params)
    {
        $_path = $this->getPath($_params,TREE_DIR,NODE_CACHE_FILE);
        $_data = $this->Kernel->ReadFile($_path);
        if ($_data) return unserialize($_data);
        else return null;
    }

//------------------------------------------------------------------------------
// Очистка кэша дерева
//------------------------------------------------------------------------------
    function clearTreeCache($_params)
    {
        $FManager = &$this->Kernel->Link('services.filemanager',true);
        $_path = $this->getPath($_params,TREE_DIR);
        $FManager->DeleteFolder($_path);
    } 
}
?>