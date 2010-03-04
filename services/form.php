<?php

class CServices_Form
{

        function &updLinksInHTML($_in_file_name,$_base_url,$_to_file_name = null)
    {
            $_file = $this->Kernel->ReadFile($_FILES[$_in_file_name]['tmp_name']);

        $_file = str_replace('http://'.$_SERVER['HTTP_HOST'],'',$_file);
            if (!preg_match("/<\s*body[^>]*>(.+)<\s*\\/\s*body[^>]*>/is",$_file,$_tmp)) $_tmp[1] = $_file;
            $_file = preg_replace("/<img\s*([^>]*)\s*src\s*=\s*[\"']?([^>\\/\"'\s]+)[\"']?/is","<img \\1 src=\"".$_base_url."\\2\" ",$_tmp[1]);
                if ($_to_file_name)
        {
                        $FManager = &$this->Kernel->Link('services.filemanager',true);
            $FManager->WriteFile($_to_file_name,$_file);
        }
            else return $_file;
    }

    function updHTMLLinks($_html,$_base_url,$_temp_url = null)
    {
        $_html = str_replace('http://'.$_SERVER['HTTP_HOST'],'',$_html);
        if ($_temp_url)
        {
            $_html = str_replace($_temp_url,'',$_html);
        }
        if (!preg_match("/<\s*body[^>]*>(.+)<\s*\\/\s*body[^>]*>/is",$_html,$_tmp))
        {
            $_tmp[1] = $_html;
        }
        $_html = preg_replace("/<img\s*([^>]*)\s*src\s*=\s*[\"']?([^>\\/\"'\s]+)[\"']?/is","<img \\1 src=\"".$_base_url."\\2\" ",$_tmp[1]);
        return $_html;
    }

    function addBaseUrl($_html,$_base_url)
    {
            $_html = preg_replace("/<img\s*([^>]*)\s*src\s*=\s*[\"']?([^>\"'\s]+)[\"']?/is","<img \\1 src=\"".$_base_url."\\2\" ",$_html);
                return $_html;
    }


    function CopyFileSet(&$_files,$_folder,$_types = null)
    {
                $FManager = &$this->Kernel->Link('services.filemanager',true);
        $FManager->CopyFileSet(&$_files,$_folder,$_types);
    }


}

?>