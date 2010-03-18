function reversCheckBox(form_id, check_name)
{
    var form_tag = document.getElementById(form_id);
    if (form_tag && form_tag.tagName.toLowerCase() == 'form')
    {
        var items = getChildNodesByTagName(form_tag, 'INPUT');
        for (var i = 0; i < items.length; i++)
        {
            if (items[i].type.toLowerCase() == 'checkbox' && items[i].name == check_name)
            {
                items[i].checked = !items[i].checked;
            }
        }
    }
}

function setCheckBox(form_id, state)
{
    var form_tag = document.getElementById(form_id);
    if (form_tag && form_tag.tagName.toLowerCase() == 'form')
    {
        var items = getChildNodesByTagName(form_tag, 'INPUT');
        for (var i = 0; i < items.length; i++)
        {
            if (items[i].type.toLowerCase() == 'checkbox')
            {
                items[i].checked = state;
            }
        }
    }
}


// Extends DOM API

function getChildNodesByTagName(node, tagName)
{
        var nodes = new Array();
        var test_nodes = node.childNodes;

        for (var i = 0; i < test_nodes.length; i++)
        {
                if (test_nodes[i].tagName == tagName)
                {
                        nodes.push(test_nodes[i]);
                }
                else
                {
                        nodes = nodes.concat(getChildNodesByTagName(test_nodes[i], tagName));
                }
        }
        return nodes;
}