<?

if (!defined('CRLF'))
{
    define('CRLF', "\r\n");
}

class CServices_Mailer{

    public $CharSets = array(
        "w" =>  "Windows-1251",
        "i" =>  "iso-8859-5",
        "k" =>  "koi8-r",
        "m" =>  "x-mac-cyrillic"
    );

    public $MIMETypes = array(
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
        "asc" => "text/plain",
        "txt" => "text/plain",
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

    public $EncodingTypes = array(
        "7bit",
        "8bit",
        "binary",
        "quoted-printable",
        "base64",
    );

    public $Kernel;
    public $TReport;

    public $Templs;

    public $BaseCharSet = "w";
    public $DestCharSet = "w";
    public $Subject = "";
    public $Text = "";

    public $AttachedFiles = array();
    public $Message = null;
    public $BoundId = null;
    public $ContentType = 'text/plain';

    
    public $xmailer = 'phpScript';
    public $xpriority = 3;
    public $itemXpriority = array(
        '3 (Normal)',
        '1 (Highest)',
        '2 (High)',
        '3 (Normal)',
        '4 (Low)',
        '5 (Lowest)'
    );

    public $replyTo;
    
    function Init()
    {
        $this->setCharSet("w");
    }

    function setCharSet($_charset)
    {
        $this->DestCharSet = $_charset;
    }

    function setSubject($_subj)
    {
        $_subj_charset = $this->CharSets[$this->DestCharSet];
        $_subj = "=?".$_subj_charset."?B?".base64_encode($_subj)."?=";
        # =?koi8-r?B?7cHUxdLJwczZ?=

        $this->Subject = $_subj;
    }

    function setFrom($_from)
    {
        $this->From = $_from;
    }

    function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }

    function setXpriority($_xpriority)
    {
        $this->xpriority = $_xpriority ? $_xpriority : 3;
    }
    
    function setXmailer($_xmailer)
    {
        $this->xmailer = $_xmailer ? $_xmailer : $this->xmailer;
    }
    
    function setContentType($_content)
    {
        $this->ContentType = $_content;
    }

    function setText($_text)
    {
        $this->Text = $_text;
    }

    function AttachFile($_filename,$_filetype,$_filedata)
    {
        if(!$_filetype)
        {
            $_ext = substr($_filename, strrpos($_filename,'.') + 1);
            $_filetype = isset($this->MIMETypes[$_ext]) ?  $this->MIMETypes[$_ext] : 'application/octet-stream';
        }

        $this->AttachedFiles[] = array(
            "filename" => &$_filename,
            "ctype"    => &$_filetype,
            "data"     => chunk_split(base64_encode($_filedata))
        );
    }

    function buildBoundId()
    {
        $this->BoundId = substr(md5(time()),0,13);
    }

    function buildHead()
    {
        $_cnt[] = 'From: '.$this->From;
        $_cnt[] = 'X-Priority: ' . $this->itemXpriority[$this->xpriority];
        $_cnt[] = 'MIME-Version: 1.0';
        $_cnt[] = 'X-Mailer: ' . $this->xmailer;
        $_cnt[] = 'Content-Type: multipart/mixed; boundary="----------'.$this->BoundId.'"';
        if($this->replyTo != '')
        {
            $_cnt[] = 'Reply-To: <'.$this->replyTo.'>';
        }

        $this->Head = implode(CRLF, $_cnt);
        $this->Head = $this->convertCRLF($this->Head);
    }

    function buildContent()
    {
        $_cnt[] = '';
        $_cnt[] = '------------'.$this->BoundId;
        $_cnt[] = 'Content-Type: '.$this->ContentType.'; charset='.$this->CharSets[$this->DestCharSet];
        $_cnt[] = 'Content-Transfer-Encoding: 8bit';
        $_cnt[] = '';
        $_cnt[] = $this->Text;
        $_cnt[] = '';

        $_files = sizeof($this->AttachedFiles);
        for($i = 0; $i < $_files; $i++)
        {
            $_cnt[] = '------------'.$this->BoundId;
            $_cnt[] = 'Content-Type: '.$this->AttachedFiles[$i]['ctype'].'; name="'.$this->AttachedFiles[$i]['filename'].'"';
            $_cnt[] = 'Content-Transfer-Encoding: base64';
            $_cnt[] = 'Content-ID: <'. md5($this->AttachedFiles[$i]['filename']) .'>';
            $_cnt[] = 'Content-Disposition: attachment; filename="'.$this->AttachedFiles[$i]['filename'].'"';
            $_cnt[] = '';
            $_cnt[] = $this->AttachedFiles[$i]['data'];
            $_cnt[] = '';
        }
        $_cnt[] = '------------'.$this->BoundId.'--';

        $this->Message = implode(CRLF, $_cnt);
        $this->Message = $this->convertCRLF($this->Message);
    }

    function buildMessage()
    {
        $this->buildBoundId();
        $this->buildHead();
        $this->buildContent();
    }


    function sendMail($_mail)
    {
        $_res = @mail($_mail,$this->Subject,$this->Message,$this->Head) != 0;
        return $_res;
    }

    function unix2win($_text)
    {
        $_text =  str_replace("\r\n","@endstring@",$_text);
        $_text =  str_replace("\n","\r\n",$_text);
        return str_replace("@endstring@","\r\n",$_text);
    }

    function win2unix($_text)
    {
        return str_replace("\r\n","\n",$_text);
    }

    function convertCRLF($_text)
    {
        if (stristr($_SERVER["SERVER_SOFTWARE"], 'win') && $_SERVER["DOCUMENT_ROOT"][1] == ':')
        {
            $_ntext = $this->unix2win($_text);
        }
        else
        {
            $_ntext = $this->win2unix($_text);
        }
        return $_ntext;
    }
};

?>
