<?php

class CServices_ImageLib
{
    function ResizeImage($_from_file, $_new_width, $_new_height, $_to_file = false, $_real = true)
    {
        $_result = false;
        $_to_file = $_to_file ? $_to_file : $_from_file;

        $FManager = & $this->Kernel->Link('services.filemanager');
        $FManager->CreateFolder(dirname($_to_file));

        $_image_prop = getimagesize($_from_file);
        $_src_width = $_image_prop[0];
        $_src_height = $_image_prop[1];

        if ($_src_width <= $_new_width && $_src_height <= $_new_height)
        {
            $_result = copy($_from_file, $_to_file);
        }
        else
        {
            if($_real)
            {
				$_result = $this->RealResizeImage(
	                $_from_file,
	                $_new_width,
	                $_new_height,
	                $_to_file
	            );
            }
            else
            {
            	$_result = $this->edgeResizeImage(
	                $_from_file,
	                $_new_width,
	                $_new_height,
	                $_to_file
	            );
            }
            
        }
        return $_result;
    }

    function RealResizeImage($_from_file, $_new_width, $_new_height, $_to_file = false)
    {
        $_result = false;
        $_to_file = $_to_file ? $_to_file : $_from_file;

        //$FManager = & $this->Kernel->Link('services.filemanager');
        //$FManager->CreateFolder(dirname($_to_file));

        $_image_prop = getimagesize($_from_file);
        switch($_image_prop[2])
        {
            case 1: $_src = imagecreatefromgif($_from_file); break;
            case 2: $_src = imagecreatefromjpeg($_from_file); break;
            case 3: $_src = imagecreatefrompng($_from_file); break;
            case 6: $_src = imagecreatefromwbmp($_from_file); break;
            default: return false; break;
        }

        // определяем изначальную высоту и ширину картинки
        $_src_width = imagesx($_src);
        $_src_height = imagesy($_src);

        // следующий код проверяет если ширина больше высоты
        // или высота больше ширины картинки так, чтобы
        // при изменении сохранилась правильная пропорция

        $_width_ratio = $_src_width/$_new_width;
        $_height_ratio = $_src_height/$_new_height;

        if( $_width_ratio < $_height_ratio)
        {
            $_dst_width = $_src_width/$_height_ratio;
            $_dst_height = $_new_height;
        }
        else
        {
            $_dst_width = $_new_width;
            $_dst_height = $_src_height/$_width_ratio;
        }

        // создаем новую картинку с конечными данными ширины и высоты
        $_dst = imagecreatetruecolor($_dst_width, $_dst_height);

        // копируем srcImage (исходная) в destImage (конечную)
        imagecopyresampled($_dst, $_src, 0, 0, 0, 0, $_dst_width, $_dst_height, $_src_width, $_src_height );

        switch ($_image_prop[2])
        {
            case 1:
                if (function_exists('imagegif'))
                {
                    imagegif($_dst, $_to_file);
                }
            break;
            case 2:
                if (function_exists('imagejpeg'))
                {
                    imagejpeg($_dst, $_to_file, 90);
                }
            break;
            case 3:
                if (function_exists('imagepng'))
                {
                    imagepng($_dst, $_to_file);
                }
            break;
            case 6:
                if (function_exists('imagewbmp'))
                {
                    imagewbmp($_dst, $_to_file);
                }
            break;
        }

        imagedestroy($_src);
        imagedestroy($_dst);
        return true;
    }
    
	
    function edgeResizeImage($_from_file, $_new_x, $_new_y, $_to_file = false)
    {
        $_result = false;
        $_to_file = $_to_file ? $_to_file : $_from_file;

        //$FManager = & $this->Kernel->Link('services.filemanager');
        //$FManager->CreateFolder(dirname($_to_file));

        $_image_prop = getimagesize($_from_file);
        switch($_image_prop[2])
        {
            case 1: $_src = imagecreatefromgif($_from_file); break;
            case 2: $_src = imagecreatefromjpeg($_from_file); break;
            case 3: $_src = imagecreatefrompng($_from_file); break;
            case 6: $_src = imagecreatefromwbmp($_from_file); break;
            default: return false; break;
        }

        // определяем изначальную высоту и ширину картинки
        $_x = imagesx($_src);
        $_y = imagesy($_src);

        if ($_x < $_y)
        {
			$_edge_y = floor(0.5 * $_y * (1 - ($_x * $_new_y) / ($_y * $_new_x)));
			$_new_src = imagecreatetruecolor($_x, $_y - 2 * $_edge_y);
			imagecopy($_new_src, $_src, 0, 0, 0, $_edge_y, $_x, $_y - 2 * $_edge_y);
			$_src = $_new_src;
			$_x = imagesx($_src);
        	$_y = imagesy($_src);
        }
        else
        {
			$_edge_x = floor(0.5 * $_x * (1 - ($_y * $_new_x) / ($_x * $_new_y)));
			$_new_src = imagecreatetruecolor($_x - 2 * $_edge_x, $_y);
			imagecopy($_new_src, $_src, 0, 0, $_edge_x, 0, $_x - 2 * $_edge_x, $_y);
			$_src = $_new_src;
			$_x = imagesx($_src);
        	$_y = imagesy($_src);
        }
        // создаем новую картинку с конечными данными ширины и высоты
        $_dst = imagecreatetruecolor($_new_x, $_new_y);
        // копируем srcImage (исходная) в destImage (конечную)
        imagecopyresampled($_dst, $_src, 0, 0, 0, 0, $_new_x, $_new_y, $_x, $_y );

        switch ($_image_prop[2])
        {
            case 1:
                if (function_exists('imagegif'))
                {
                    imagegif($_dst, $_to_file);
                }
            break;
            case 2:
                if (function_exists('imagejpeg'))
                {
                    imagejpeg($_dst, $_to_file, 90);
                }
            break;
            case 3:
                if (function_exists('imagepng'))
                {
                    imagepng($_dst, $_to_file);
                }
            break;
            case 6:
                if (function_exists('imagewbmp'))
                {
                    imagewbmp($_dst, $_to_file);
                }
            break;
        }

        imagedestroy($_src);
        imagedestroy($_dst);
        return true;
    }

    function CreateStamp($_target, $_mask, $_position = null)
    {
        $_target_path = dirname($_target).'/';
        $_target_filename = basename($_target);

        $_im = null;
        $_info = getimagesize( $_target );

        switch($_info[2])
        {
            case IMAGETYPE_GIF:
                if (imagetypes() & IMG_GIF)
                {
                    $_im = imagecreatefromgif($_target);
                }
                else
                {
                    Error("System not support GIF format. Can`t create logo.");
                }
            break;
            case IMAGETYPE_JPEG:
                if (imagetypes() & IMG_JPG)
                {
                    $_im = imagecreatefromjpeg($_target);
                }
                else
                {
                    Error("System not support JPEG format. Can`t create logo.");
                }
            break;
            case IMAGETYPE_PNG:
                if (imagetypes() & IMG_PNG)
                {
                    $_im = imagecreatefrompng($_target);
                }
                else
                {
                    Error("System not support PNG format. Can`t create logo.");
                }
            break;
            default:
                Error("Unknoun file format (".$_info[2]."). Can`t create stamp.");
            break;
        }

        if ($_im)
        {
            $_im2 = imagecreatefrompng($_mask);

            $_srcWidth = imagesx($_im2);
            $_srcHeight = imagesy($_im2);

            $_target_width = imagesx($_im);
            $_target_height = imagesy($_im);

            imagecopyresampled(
                $_im,
                $_im2,
                0,
                $_target_height - $_srcHeight,
                0,
                0,
                $_srcWidth,
                $_srcHeight,
                $_srcWidth,
                $_srcHeight
            );

            switch($_info[2])
            {
                case IMAGETYPE_GIF:
                    imagegif($_im,$_target);
                break;
                case IMAGETYPE_JPEG:
                    imagejpeg($_im,$_target, 85);
                break;
                case IMAGETYPE_PNG:
                    imagepng($_im,$_target);
                break;
            }

            ImageDestroy( $_im );
            ImageDestroy( $_im2 );

            return "-1";
        }
    }

}

?>