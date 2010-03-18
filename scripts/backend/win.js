function OpenWin(name, w, h)
{
    var ww = w + 40;
    var hh = h + 40;
    if (hh > 600){hh = 600};
    Start = "<html><head><title>Большая фотография</title><style>body {margin:0; padding:0; background: #fff url(/images/t/pop_left_bg.gif) repeat-y; text-align:left;} .pop_top { padding-bottom: 20px; width: 100%; text-align:left; background: url(/images/t/pop_top_bg.gif) repeat-x; } .pop_bottom { width: 90%; text-align: right; background: url(/images/t/pop_bottom_bg.gif) repeat-x; margin-top: 20px; }</style></head><body><div style='padding=0 20px'>";
    Middle = "<img src='" + name;
    End = "' onClick='window.close()' alt='Кликните на изображении чтобы закрыть окно' style='cursor: hand'></div></body></html>";
    WinOpt = "_blank,resizable=no,scrollbars=auto,location=no,status=yes,width=" + ww + ",height=" + hh + ",toolbar='0'";
    ZoomWin = window.open("","ZoomWindow",WinOpt);
    ZoomWin.document.write(Start + Middle + End);
}