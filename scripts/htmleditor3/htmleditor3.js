tinyMCE.init({
    language : "ru",
    mode : "none",
    plugins : "table,safari,fullscreen,noneditable",
    elements : 'absurls',
    theme : "advanced",
    theme_advanced_buttons1 : "undo,redo,separator,bold,italic,separator,bullist,numlist,separator,formatselect,separator,removeformat",
    theme_advanced_buttons2 : "link,unlink,anchor,image,separator,table,delete_table,row_after,delete_row,col_after,delete_col,merge_cells,split_cells,separator,fullscreen",
    theme_advanced_buttons3 : "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_path_location : "none",
    //theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : false,
    valid_elements : "b[id|class],i[id|class],a[name|href|target|title|id|class|rel],img[id|class|src|border=0|alt|title|width|height|name],hr[id|class],span[id|class],p[id|class],br,ul[id|class],li[id|class],ol[id|class],h1[id|class],h2[id|class],h3[id|class],h4[id|class],h5[id|class],h6[id|class],table[id|class|cellspacing],tr[id|class],td[id|class|colspan|rowspan],tbody[id|class],th[id|class|colspan|rowspan],thead[id|class],div[id|class],strong[id|class],em[id|class],blockquote[id|class],colgroup[id|class],col[id|class]",
    relative_urls : false,
    document_base_url : "/",
    content_css : "/scripts/htmleditor3/content.css",
    file_browser_callback : 'OptiWebFileBrowser',
    object_resizing : false,
    apply_source_formatting : true,
    entity_encoding: "named",
    table_styles: "Данные=data",
    entities : "160,nbsp,38,amp,34,quot,162,cent,8364,euro,163,pound,165,yen,169,copy,174,reg,8482,trade,8240,permil,60,lt,62,gt,8804,le,8805,ge,176,deg,8722,minus",
    auto_resize: true,
    width: "100%"
});

function OptiWebFileBrowser(field_name, url, type, win)
{
    var cmsURL = "/_backend/imagesdlg/";
    var searchString = window.location.search;  // possible parameters
    if (searchString.length < 1)
    {
        searchString = "?";
    }

    tinyMCE.activeEditor.windowManager.open(
        {
            file : cmsURL + searchString + "&type=" + type + "&path=1",
            title : 'File Browser',
            width : 640,  // Your dimensions may differ - toy around with them!
            height : 480,
            resizable : "yes",
            inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
            close_previous : "no"
        }, 
        {
            window : win,
            input : field_name
        }
    );
    return false;
}

function toggleEditor(id)
{
    var elm = document.getElementById(id);

    if (tinyMCE.getInstanceById(id) == null)
    {
        removeEditArea(id);
        tinyMCE.execCommand('mceAddControl', false, id);
    }
    else
    {
        tinyMCE.execCommand('mceRemoveControl', false, id);
        createEditArea(id);
    }
}


function createEditArea(id)
{
    if (!window.editArea)
    {
        window.editArea = new Array();
    }
    editAreaLoader.init({
        id: id,// id of the textarea to transform		
        start_highlight: true,	// if start with highlight
        allow_resize: "both",
        allow_toggle: false,
        word_wrap: false,
        language: "en",
        syntax: "html"
    });
    window.editArea[id] = true;
}

function removeEditArea(id)
{
    if (window.editArea && window.editArea[id])
    {
        editAreaLoader.delete_instance(id);
        window.editArea[id] = false;
    }
}

