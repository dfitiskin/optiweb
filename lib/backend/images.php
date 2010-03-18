<?

global $Kernel;
$Kernel->LoadLib('filemanager','backend');

class CBackend_Images extends CBackend_FileManager
{

	function Init()
    {
		parent::Init();
     	$this->Name = 'images';
        $this->FileTypes = array(
			'image/bmp'			=>	1,
            'image/gif'			=>	2,
			'image/pjpeg'		=>	3,
			'image/jpeg'		=>	4,
            'image/png'		=>	5,
        );
        $this->LibDir = 'images';
    }

    function ModifyProperty($_params)
    {
		switch ($_params['action'])
        {
            case 'edit':

                $_active_params = $this->ActiveParams;
                $_file = array_pop($_active_params);

	            $_add_dir = implode('/',$_active_params);
	            if ($_add_dir) $_add_dir .= '/';


                $_pathes = $this->getCurrentParams();
                $_filepath = $_pathes['current_path'].$_pathes['path'];
				list($_filename,$_fileext) = explode('.',$_file,2);
            	if ($_params['filemng']['newname'] != $_filename)
                {
	                global $Page;
                    $_redirect_url = '/_backend/images/_edit/'.$_pathes['profile_type'].$_add_dir.$_params['filemng']['newname'].'.'.$_fileext;
	                $Page->setRedirect($_redirect_url);

                	$_dirname = dirname($_filepath);
					$_dest_filepath = $_dirname.'/'.$_params['filemng']['newname'].'.'.$_fileext;
                    if ($_params['filemng']['action'] == 'rename') rename($_filepath,$_dest_filepath);
                    elseif ($_params['filemng']['action'] == 'copy') copy($_filepath,$_dest_filepath);
                    $_filepath = $_dest_filepath;
	            }

                if ($_params['filemng']['action'])
                {
                	$_width = false;
					$_height = false;

					switch ($_params['image']['action'])
                    {
                    	case '100x100':
                        	$_height = 100;$_width = 100;
                        break;
                    	case '200x200':
                        	$_height = 200;$_width = 200;
                        break;
                    	case 'other':
                        	$_height = $_params['image']['height'];
                            $_width = $_params['image']['width'];
                        break;
                        default:
                        	$_arr =  explode('x',$_params['image']['action']);
                            if (sizeof($_arr) == 2)
								list($_width,$_height) = $_arr;
                        break;
                    }

                    if ($_width && $_height)
					{
                    	$ImageLib = &$this->Kernel->Link('services.imagelib');
                        $ImageLib->resizeImage($_filepath,$_width,$_height);
                    }
                }
            break;
		}
    }

    function Execute($_params,$_templs,$_types_params,$_url_params,$_link_url)
    {
		switch($_params['mode'])
        {
            case 'edit':
				$_ds = $this->Kernel->Link('dataset.abstract');

		        $_cur_params = $this->getCurrentParams();
                $_image_info = null;
                $_image_params = getimagesize($_cur_params['current_path'].$_cur_params['path']);

                $_active_params = $this->ActiveParams;
                $_file = array_pop($_active_params);

                list($_filename,$_fileext) = explode('.',$_file,2);

                $_ds_params = array(
                	'image_path'	=>	'/images/'.$_cur_params['path'],
                    'filename'		=>	$_filename,
                    'fileext'		=>	$_fileext,
                    'width'			=>	$_image_params[0],
                    'height'		=>	$_image_params[1],
                );
                $_ds->setParams($_ds_params);

                $TplManager = &$this->Kernel->Link('template.manager',true);
                $_result = $TplManager->Execute($_ds,$_templs['main'],$this->Name);
                return $_result;
            break;
            default:
            	return parent::Execute($_params,$_templs,$_types_params,$_url_params,$_link_url);
            break;
        }
    }


    function CorrectParts($_parts)
    {
//    	Dump($_parts);
    	switch($_parts[0])
        {
			case '_edit':
				$this->WorkMode = array_shift($_parts);
            default:
	            if (array_search($_parts[0],$this->Libs) !== false)
	            {
	                $this->InitParams($_parts);
	                $_parts[0] = '_libtype';
                    $this->WorkType = 'library';

	            }
            break;
        }
        return $_parts;
    }


}

?>