<?
class CNavigation_Viewer
{

    function Execute(&$_params, &$_templs,$_type_params,$_url_params,$_link_url,$_level)
    {

        switch($_params['mode'])
        {
			case 'menu' :
            case 'header' :
            case 'alias' :
            case 'hierarchy' :
            case 'info' :
                $Menu = &$this->Kernel->Link('navigation.menu');
                $_result = $Menu->Execute($_params, $_templs,$_type_params, $_url_params,$_link_url,$_level);
                return $_result;
            break;
            case 'tree' :
                $Tree = &$this->Kernel->Link('navigation.tree');
                $_result = $Tree->Execute($_params, $_templs,$_type_params, $_url_params,$_link_url,$_level);
                return $_result;
            break;
        }
	}
}

?>