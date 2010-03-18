<?
class CServices_Filter
{
	public $Chars = array();
	public $Filters = array();

	function Init()
	{
		$this->Chars = array(
            'win'   => "Windows-1251",
            'utf'   => "UTF-8",
            'iso'   => "ISO-8859-1"
            );

            $this->Filters = array(
            'sts'         =>        array(
                'name'         =>        'sts',
                'descr'        =>        'Убрать слеши перед кавычками',
                'params'       =>        array(),
                'fname'        =>        'sts',
            ),
            'date'        =>        array(
                'name'         =>        'date',
                'descr'        =>        'Привести дату к указанному формату',
                'params'       =>        array(
                    'items' => array(
            array(
                            'type'  => 'text',
                            'name'  => 'slot1',
                            'descr' => 'Формат',
            ),
            ),
            ),
                'fname'        =>        'date',
            ),
            );
	}

	function &ParseRules(&$_ruls)
	{
		$_rul = explode(";",$_ruls);
		for($i=0;$i<sizeof($_rul);$i++)
		{
			$_rul[$i] = explode("|",$_rul[$i]);
		}
		return $_rul;
	}


	function ubbTranslate($_text,$_rules)
	{
		$_stack = array();

		$_parts = preg_split('/\\[\\/?\w\\]/im',$_text);
		preg_match_all('/\\[(\\/?)(\w)\\]/',$_text,$_tags);

		$_text = '';
		for($i=0;$i<sizeof($_parts);$i++)
		{
			if ($i)
			{

				$_tag = $_tags[2][$i-1];
				$_type = $_tags[1][$i-1]?0:1;

				if (!isset($_rules[$_tag])) $_text .= $_tags[0][$i-1];
				elseif ($_type)
				{
					array_push($_stack,$_tag);
					$_text .= $_rules[$_tag][0];
				}
				else
				{
					$_old_tag  = array_pop($_stack);
					if ($_tag != $_old_tag) return null;
					$_text .= $_rules[$_tag][1];
				}
			}
			$_text .= $_parts[$i];
		}

		if (sizeof($_stack)) return null;

		return $_text;
	}

	function Convert($_value)
	{
		$MIMETypes = array(
            "xls"=> "application/vnd.ms-excel",
            "ez" => "application/andrew-inset",
            "hqx" => "application/mac-binhex40",
            "cpt" => "application/mac-compactpro",
            "doc" => "application/msword",
            "bin" => "application/octet-stream",
            "dms" => "application/octet-stream",
            "lha" => "application/octet-stream",
            "lzh" => "application/octet-stream",
            "exe" => "application/octet-stream",
            "class" => "application/octet-stream",
            "so" => "application/octet-stream",
            "dll" => "application/octet-stream",
            "oda" => "application/oda",
            "pdf" => "application/pdf",
            "ai" => "application/postscript",
            "eps" => "application/postscript",
            "ps" => "application/postscript",
            "smi" => "application/smil",
            "smil" => "application/smil",
            "wbxml" => "application/vnd.wap.wbxml",
            "wmlc" => "application/vnd.wap.wmlc",
            "wmlsc" => "application/vnd.wap.wmlscriptc",
            "bcpio" => "application/x-bcpio",
            "vcd" => "application/x-cdlink",
            "pgn" => "application/x-chess-pgn",
            "cpio" => "application/x-cpio",
            "csh" => "application/x-csh",
            "dcr" => "application/x-director",
            "dir" => "application/x-director",
            "dxr" => "application/x-director",
            "dvi" => "application/x-dvi",
            "spl" => "application/x-futuresplash",
            "gtar" => "application/x-gtar",
            "hdf" => "application/x-hdf",
            "js" => "application/x-javascript",
            "skp" => "application/x-koan",
            "skd" => "application/x-koan",
            "skt" => "application/x-koan",
            "skm" => "application/x-koan",
            "latex" => "application/x-latex",
            "nc" => "application/x-netcdf",
            "cdf" => "application/x-netcdf",
            "sh" => "application/x-sh",
            "shar" => "application/x-shar",
            "swf" => "application/x-shockwave-flash",
            "sit" => "application/x-stuffit",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc" => "application/x-sv4crc",
            "tar" => "application/x-tar",
            "tcl" => "application/x-tcl",
            "tex" => "application/x-tex",
            "texinfo" => "application/x-texinfo",
            "texi" => "application/x-texinfo",
            "t" => "application/x-troff",
            "tr" => "application/x-troff",
            "roff" => "application/x-troff",
            "man" => "application/x-troff-man",
            "me" => "application/x-troff-me",
            "ms" => "application/x-troff-ms",
            "ustar" => "application/x-ustar",
            "src" => "application/x-wais-source",
            "xhtml" => "application/xhtml+xml",
            "xht" => "application/xhtml+xml",
            "zip" => "application/zip",
            "au" => "audio/basic",
            "snd" => "audio/basic",
            "mid" => "audio/midi",
            "midi" => "audio/midi",
            "kar" => "audio/midi",
            "mpga" => "audio/mpeg",
            "mp2" => "audio/mpeg",
            "mp3" => "audio/mpeg",
            "aif" => "audio/x-aiff",
            "aiff" => "audio/x-aiff",
            "aifc" => "audio/x-aiff",
            "m3u" => "audio/x-mpegurl",
            "ram" => "audio/x-pn-realaudio",
            "rm" => "audio/x-pn-realaudio",
            "rpm" => "audio/x-pn-realaudio-plugin",
            "ra" => "audio/x-realaudio",
            "wav" => "audio/x-wav",
            "pdb" => "chemical/x-pdb",
            "xyz" => "chemical/x-xyz",
            "bmp" => "image/bmp",
            "gif" => "image/gif",
            "ief" => "image/ief",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "jpe" => "image/jpeg",
            "png" => "image/png",
            "tiff" => "image/tiff",
            "tif" => "image/tif",
            "djvu" => "image/vnd.djvu",
            "djv" => "image/vnd.djvu",
            "wbmp" => "image/vnd.wap.wbmp",
            "ras" => "image/x-cmu-raster",
            "pnm" => "image/x-portable-anymap",
            "pbm" => "image/x-portable-bitmap",
            "pgm" => "image/x-portable-graymap",
            "ppm" => "image/x-portable-pixmap",
            "rgb" => "image/x-rgb",
            "xbm" => "image/x-xbitmap",
            "xpm" => "image/x-xpixmap",
            "xwd" => "image/x-windowdump",
            "igs" => "model/iges",
            "iges" => "model/iges",
            "msh" => "model/mesh",
            "mesh" => "model/mesh",
            "silo" => "model/mesh",
            "wrl" => "model/vrml",
            "vrml" => "model/vrml",
            "css" => "text/css",
            "html" => "text/html",
            "htm" => "text/html",
            "txt" => "text/plain",
            "asc" => "text/plain",
            "rtx" => "text/richtext",
            "rtf" => "text/rtf",
            "sgml" => "text/sgml",
            "sgm" => "text/sgml",
            "tsv" => "text/tab-seperated-values",
            "wml" => "text/vnd.wap.wml",
            "wmls" => "text/vnd.wap.wmlscript",
            "etx" => "text/x-setext",
            "xml" => "text/xml",
            "xsl" => "text/xml",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "mpe" => "video/mpeg",
            "qt" => "video/quicktime",
            "mov" => "video/quicktime",
            "mxu" => "video/vnd.mpegurl",
            "avi" => "video/x-msvideo",
            "movie" => "video/x-sgi-movie",
            "ice" => "x-conference-xcooltalk"
            );

            foreach ($MIMETypes as $_simple => $_mime)
            {
            	if ($_value === $_mime)
            	{
            		return $_simple;
            	}
            }
            return 'other';
	}

	function Bytes($_content, $_round = 2, $_unit = 'auto')
	{
		$_fix = array(' b', ' kb', ' mb', ' gb', ' tb');
		if(!is_numeric($_content))
		{
			return $_content;
		}

		$_postfix = " b";

		switch($_unit)
		{
			case 'b':
				$_mult = 1;
				$_postfix = '';
				break;
			case 'kb':
				$_mult = 1E3;
				$_postfix = '';
				break;
			case 'mb':
				$_mult = 1E6;
				$_postfix = '';
				break;
			default:
				$_mult = 1E3;
				$_range = 0;
				while(1 < $_content / pow($_mult,$_range)) $_range++;
				if ($_range > 1)
				{
					$_mult = pow($_mult, $_range - 1);
					$_postfix = $_fix[$_range - 1];
				}
				else
				{
					$_mult = 1;
					$_postfix[0];
				}
				break;
		}
		return round($_content / $_mult,$_round) . $_postfix;
	}

	//-------------------------------------------------------------------
	// Разделяет текст $_content на строчки по $_max_len символов
	// (пытается разделить текст между слов)
	//-------------------------------------------------------------------
	function DivWord($_content,$_max_len)//<<??
	{
		$_content_string = "";
		$_content_array = explode(" ",$_content);
		for ($i = 0; $i < sizeof($_content_array); $i++)
		{
			$_word_len = strlen($_content_array[$i]);
			if ($_word_len < $_max_len)
			{
				$_new_content_array[] = $_content_array[$i];
			}
			else
			{
				$_z = 0;
				$_new_word_len = $_word_len;
				while($_new_word_len > 0)
				{
					$_new_array[$_z] = substr ($_content_array[$i], $_max_len*$_z, $_max_len);
					$_new_word_len = $_new_word_len - $_max_len;
					$_z++;
				}

				for ( $y=0; $y<sizeof($_new_array);$y++)
				{
					$_new_content_array[] = $_new_array[$y];
				}
			}
		}
		for ($c = 0; $c < sizeof($_new_content_array); $c++)
		{
			$_content_string .= $_new_content_array[$c]." ";
		}

		return $_content_string;
	}


	//-------------------------------------------------------------------
	// Преобразует время $_in_date_time из формата yyyy-mm-dd hh:ii:ss
	// в формат заданый в строке $_format
	//-------------------------------------------------------------------

	function DateConv($_in_date_time,$_format)
	{
		$MonthesInDate = array(
		1        =>        'января',
		2        =>        'Февраля',
		3        =>        'марта',
		4        =>        'апреля',
		5        =>        'мая',
		6        =>        'июня',
		7        =>        'июля',
		8        =>        'августа',
		9        =>        'сентября',
		10        =>        'октября',
		11        =>        'ноября',
		12        =>        'декабря',
		);
		
		$MonthesInDateEng = array(
		1        =>        'January',
		2        =>        'February',
		3        =>        'March',
		4        =>        'April',
		5        =>        'May',
		6        =>        'June',
		7        =>        'July',
		8        =>        'Avgust',
		9        =>        'September',
		10       =>        'October',
		11       =>        'November',
		12       =>        'December',
		);		

		$_repl = array(
                'Y'        =>        '0000',
                'M'        =>        '00',
                'D'        =>        '00',
                'H'        =>        '00',
                'I'        =>        '00',
                'S'        =>        '00',
                'X'        =>        'января',
				'Xe'       =>        'jan',
		);

		if (preg_match('/^(\d{2,4})-(\d{2})-(\d{2})( (\d{1,2}):(\d{2})(:(\d{2}))?)?$/',$_in_date_time,$_tmp))
		{
			$_repl["Y"] = $_tmp[1];
			$_repl["M"] = $_tmp[2];
			$_repl["D"] = $_tmp[3];
			if (isset($_tmp[4]))
			{
				$_repl["H"] = $_tmp[5];
				$_repl["I"] = $_tmp[6];
				if (isset($_tmp[8])) $_repl["S"] = $_tmp[8];
			}
		}
		elseif(preg_match('/^(\d{2}).(\d{2}).(\d{2,4})( (\d{1,2}):(\d{2})(:(\d{2}))?)?$/',$_in_date_time,$_tmp))
		{
			$_repl["Y"] = $_tmp[3];
			$_repl["M"] = $_tmp[2];
			$_repl["D"] = $_tmp[1];
			if (isset($_tmp[4]))
			{
				$_repl["H"] = $_tmp[5];
				$_repl["I"] = $_tmp[6];
				if (isset($_tmp[8])) $_repl["S"] = $_tmp[8];
			}
		}
		elseif(preg_match('/^((\d{1,2}):(\d{2})(:(\d{2}))? )?(\d{2}).(\d{2}).(\d{2,4})$/',$_in_date_time,$_tmp))
		{

			$_repl["Y"] = $_tmp[8];
			$_repl["M"] = $_tmp[7];
			$_repl["D"] = $_tmp[6];
			if (isset($_tmp[1]))
			{
				$_repl["H"] = $_tmp[2];
				$_repl["I"] = $_tmp[3];
				if ($_tmp[5]) $_repl["S"] = $_tmp[5];
			}
		}

		$_repl["h"] = $_repl["H"];
		$_repl["i"] = $_repl["I"];
		$_repl["s"] = $_repl["S"];
		$_repl["X"] = isset($MonthesInDate[($_repl["M"] * 1)]) ? $MonthesInDate[($_repl["M"] * 1)] : 'мартабря';
		$_repl["Xe"] = isset($MonthesInDateEng[($_repl["M"] * 1)]) ? $MonthesInDateEng[($_repl["M"] * 1)] : 'not set';

		/*
		 $_repl["m"] = str_replace("0","",$_repl["M"]);
		 $_repl["d"] = str_replace("0","",$_repl["D"]);
		 */
		$_repl["m"] = $_repl["M"];
		$_repl["d"] = intval($_repl["D"]);
		$_repl["y"] = substr($_repl["Y"],2,2);
		return strtr($_format,$_repl);
	}

	function Filt($_st,$_rul)
	{
		$_rul = $this->ParseRules($_rul);
		foreach ($_rul as $k=>$v){
			switch ($v[0])
			{
				//                                case "base64"     : $_st = (isset($v[1]) && $v[1] == 'd') ? base64_decode($_st) : base64_encode($_st);
				case "mysql": $_st = mysql_escape_string($_st); break;
				case "convertmime" : $_st = $this->Convert($_st); break;
				case "iconv"      : $_st = iconv($this->Chars[$v[1]],$this->Chars[$v[2]],$_st); break;
				case "nbr"        : $_st = Nl2Br($_st); break;
				case "len"        : $_st = substr($_st,0,$v[1]); break;
				case "dw"        : $_st = $this->DivWord($_st,$v[1]); break;
				case "date"        : $_st = $this->DateConv($_st,$v[1]); break;
				case "dnc"        : $_st = ow_strtolower($_st); break;
				case "upc"        : $_st = ow_strtoupper($_st); break;
				case "ads"        : $_st = addslashes($_st); break;
				case "sts"        : $_st = stripslashes($_st); break;
				case "uns"         : $_st = unserialize($_st); break;
				case "trim" : $_st = trim($_st); break;
				case "int" : $_st = intval($_st); break;
				case "html" : $_st = stripslashes(HtmlSpecialChars($_st,ENT_COMPAT)); break;
				case "bytes":
					$_st = $this->Bytes($_st, isset($v[1]) ? $v[1] : 2,isset($v[2]) ? $v[2] : 'auto');
					break;
				case "substr" :
					if(strlen($_st)>$v[1])
					{
						$_st = substr($_st,0,$v[1]);
						if (isset($v[2])) $_st .= $v[2];
					}
					break;
				case "addbaseurl" :
					if (isset($v[1])) $_base_url = 'http://'.$v[1];
					else $_base_url = 'http://'.$_SERVER['HTTP_HOST'];

					$_st = preg_replace("/<img\s*([^>]*)\s*src\s*=\s*[\"']?(\\/[^>\"'\s]+)[\"']?/is","<img \\1 src=\"".$_base_url."\\2\" ",$_st);
					break;
				case "addbaseurl_all" :
					if (isset($v[1])) $_base_url = 'http://'.$v[1];
					else $_base_url = 'http://'.$_SERVER['HTTP_HOST'];

					$_st = preg_replace("/<img\s*([^>]*)\s*src\s*=\s*[\"']?(\\/[^>\"'\s]+)[\"']?/is","<img \\1 src=\"".$_base_url."\\2\" ",$_st);
					$_st = preg_replace("/<a\s*([^>]*)\s*href\s*=\s*[\"']?(\\/[^>\"'\s]+)[\"']?/is","<a \\1 href=\"".$_base_url."\\2\" ",$_st);
					break;
				case "delbaseurl" :
					$_st = str_replace('http://'.$_SERVER['HTTP_HOST'].'/','/',$_st);
					break;
                case "fullurl" :
                        if (0 !== stripos($_st, 'http://'))
                        {
                            $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
                            $_st = sprintf('http://%s%s', $host, $_st);
                        }
                break;
			}
		}

		return $_st;
	}

	function FiltValues(&$_sts,$_ruls)
	{
		foreach($_sts as $k=>$v)
		if (isset($_ruls[$k]))
		$_sts[$k] = $this->Filt($v,$_ruls[$k]);
	}

}
?>
