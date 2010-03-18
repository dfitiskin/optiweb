<?

class CServices_Network
{

        function getIP()
    {
            if ($ip = getenv("HTTP_X_FORWARDED_FOR"))
            {
                    if (!($cp = strpos($ip, ",")))
                {
                    if (!strpos($ip, ".")) return getenv("REMOTE_ADDR");
                    else return trim($ip);
                }

                if (!($pp = strpos($ip, ".")) || ($pp > $cp)) return trim(substr($ip, $cp + 1));
                else  return trim(substr($ip, 0, $cp));
            }
            else return getenv("REMOTE_ADDR");
    }

//----------------------------------------------------------------
// Определение версии браузера
//----------------------------------------------------------------
    function getBrowserType($_in = false)
    {
        $_struct = array(
            "type"     =>        "other",
            "version"  =>        false,
            "stype"    =>        false,
            "major"    =>        false
        );


        if (!$_in)
        {
            if (!isset($_SERVER["HTTP_USER_AGENT"])) return $_struct;
            $_in = $_SERVER["HTTP_USER_AGENT"];
        }


        if (preg_match("/^([a-zA-Z]+)\\/([0-9\\.]+)[^(]*\\((.*)\\)\s*([^\s]*)\s*([^\s]*)\s?(.*)$/i",$_in,$_tmp))
        {
            if (sizeof($_tmp)<4) return $_struct;
            $_tmp[3] = explode("; ",$_tmp[3]);

            if (strtolower($_tmp[1]) != "mozilla")
            {
                    $_struct["type"] = $_tmp[1];
                    $_struct["version"] = $_tmp[2];
            }
            elseif ($_tmp[3][0] == "compatible")
            {
                if ($_tmp[4] != "")
                {
                    $_struct["type"] = $_tmp[4];
                    if ($_tmp[5] != "")         $_struct["version"] = $_tmp[5];
                }
                else
                {
                    $_struct["type"] = "Internet Explorer";
                    $_struct["stype"] = "ie";
                    if (isset($_tmp[3][1]))
                    {
                        $_ver = explode(" ",$_tmp[3][1]);
                        if (isset($_ver[1]))
                        {
                            $_struct["version"] = $_ver[1];
                            $_tmp = preg_split("/[\\.,]+/",$_ver[1]);
                            $_struct["major"] = $_tmp[0];
                        }
                    }
                }
            }
            else
            {
                if(isset($_tmp[3][4]) && preg_match('/netscape\/([\d.]+)/i',$_tmp[3][4],$_tmp_NS))
                {
                    $_struct["type"] = "Netscape";
                    $_struct["version"] = $_tmp_NS[1];
                }
                else
                {

                    $_struct["type"] = "Netscape";
                    if ($_tmp[5] != "")
                    {
                        $_ver = explode("/",$_tmp[5]);
                        if (isset($_ver[1])) $_struct["version"] = $_ver[1];
                    }
                    else
                    {
                        if ($_tmp[4] != "")
                        {
                            $_struct["type"] = "Mozilla";
                            if (isset($_tmp[3][4]))
                            {
                                $_ver = explode(":",$_tmp[3][4]);
                                if (isset($_ver[1]))
                                $_struct["version"] = $_ver[1];
                            }
                        }
                        else
                        {
                            $_struct["version"] = $_tmp[2];
                        }
                    }
                }
            }
            $_tmp = preg_split("/[\\.,]+/",$_struct["version"]);
            $_struct["major"] = $_tmp[0];
            if (!$_struct["stype"])
            {
                $_struct["stype"]        =        strtolower(substr($_struct["type"],0,2));
            }
        }
        return $_struct;
    }

        //----------------------------------------------------------------
        //        Загрузка страницы
        //----------------------------------------------------------------
        function &LoadPage($_url,$_type = null)
        {
//        Dump($_url);
                preg_match("/^(http:\\/\\/)?([^\\/]+)(([^?]*)\\??(.*))$/",trim($_url),$_parts);
                $_parts[1]="http://";
                if ($_parts[3] == "") $_parts[3] ="/";
                $_fid = fsockopen($_parts[2],80);
                if ($_fid)
                {

                        fputs($_fid,"GET ".$_parts[3]." HTTP/1.0\r\nHost: ".$_parts[2]."\r\n\r\n");
            $_answer = fgets($_fid,1024);

            $this->PageHeaders = '';
            $_header = null;
            while(trim($_header = fgets($_fid,1024)) != "") $this->PageHeaders .= $_header;

            preg_match_all('/([^;:\r\ ]+)[:=]\s*([^\r;]+)/',$this->PageHeaders,$_tmp);

            $this->PageHeaders = array();
            for ($i=0;$i<sizeof($_tmp[1]);$i++)
                $this->PageHeaders[trim(strtolower($_tmp[1][$i]))] = $_tmp[2][$i];

//            Dump($_tmp);
//            Dump($_answer);

            $this->PageContent = null;
//            $this->PageHeaders['content-type'] = explode(';',$this->PageHeaders['content-type']);
            if ($_type && $this->PageHeaders['content-type'] != $_type) return false;

            $i = 20;
            while(!feof($_fid) && $i--)  $this->PageContent .= fread($_fid,1024*30);
            //Dump($this->PageContent);

                        fclose($_fid);
                        if (!preg_match("/HTTP\\/1.1 (500|404|401)/", $_answer)) return $this->PageContent;
                }
                return false;
        }

    function getPageContent()
    {
            return $this->PageContent;
    }

    function getPageParam($_name)
    {
//            Dump($this->PageHeaders);
                return isset($this->PageHeaders[$_name])?$this->PageHeaders[$_name]:null;
    }


}


?>