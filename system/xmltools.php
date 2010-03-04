<?php
/**
 * CSystem_XmlTools5
 */

class CSystem_XmlTools
{

//------------------------------------------------------------------------------
// ?
//------------------------------------------------------------------------------
    function & getDomXml(& $_XMLText)
    {
        $_elem = null;
        $doc = new DOMDocument('1.0', 'Windows-1251');
        if ($doc->loadXml($_XMLText, XML_OPTION_SKIP_WHITE))
        {
            $_elem = $doc->documentElement;
        }
        return $_elem;
    }

    function & getDocumentRootElement(& $_doc)
    {
        return $_doc->documentElement;
    }

//------------------------------------------------------------------------------
// ?
//------------------------------------------------------------------------------
    function & getDomDoc(& $_XMLText)
    {
        $doc = new DOMDocument('1.0', 'Windows-1251');
        $doc->preserveWhiteSpace = false;

        if ($doc->loadXml($_XMLText))
        {
            $doc->normalizeDocument();
            return $doc;
        }
        else return null;
    }

//------------------------------------------------------------------------------
// Открыть XML файл
//------------------------------------------------------------------------------
    function & openDomDoc($_filename)
    {
        $_XMLText = $this->Kernel->ReadFile($_filename);
        return $this->getDomDoc($_XMLText);
    }

//------------------------------------------------------------------------------
// Получить атребут $_name узла $_node
//------------------------------------------------------------------------------
    function getNodeAttribute(&$_node, $_name)
    {
        if ($_node->nodeType == XML_ELEMENT_NODE)
        {
            return $this->DecodeStr($_node->getAttribute($_name));
        }
    }

//------------------------------------------------------------------------------
// Декодировать текст $_text
//------------------------------------------------------------------------------
    function DecodeStr($_text)
    {
        return iconv('UTF-8', 'windows-1251', $_text);
    }

//------------------------------------------------------------------------------
// Кодировать текст $_text
//------------------------------------------------------------------------------
    function EncodeStr($_text)
    {
        return iconv('windows-1251','UTF-8',  $_text);
    }

    function GetChildNodes(& $_node)
    {
        $_node->child_nodes();
    }

//------------------------------------------------------------------------------
// Получить дочерний узел $_tagname узла $_parent
//------------------------------------------------------------------------------
    function & getChildNodeByTagName(& $_parent, $_tagname)
    {
        $_childs = $_parent->childNodes;
        $_node = $this->getElementNode($_childs,$_tagname);
        return $_node;
    }

//------------------------------------------------------------------------------
// Получить элемент $_tagName узла $_collection
//------------------------------------------------------------------------------
    function & getElementNode(& $_collection, $_tagName)
    {
        $node = null;
        for ($i = 0; $i < $_collection->length; $i++)
        {
            $node = $_collection->item($i);
            if ($node->nodeType == XML_ELEMENT_NODE && $node->nodeName == $_tagName)
            {
                return $node;
            }
        }
        return $node;
    }

//------------------------------------------------------------------------------
// Получить следующий элемент $_tagName узла $_elem
//------------------------------------------------------------------------------
    function & GetNextElement(& $_elem, $_tag_name=null)
    {
        $_elem = $_elem->nextSibling;
        while ($_elem)
        {
            if ($_elem->nodeType == XML_ELEMENT_NODE && ($_tag_name == null || $_elem->nodeName == $_tag_name))
            {
                return $_elem;
            }
            $_elem = $_elem->nextSibling;
        }
        return $_elem;
    }

//------------------------------------------------------------------------------
// Получить элемент $_tagName узла $_collection
// по значеню $_attrValue атребута $_attrName
//------------------------------------------------------------------------------
    function & getElementNodeByAttribute(& $_collection, $_tagName, $_attrName, $_attrValue)
    {
        $_node = null;
        for ($i = 0; $i < $_collection->length; $i++)
        {
            $node = $_collection->item($i);
            if ($node->nodeType == XML_ELEMENT_NODE && $node->nodeName == $_tagName && $node->getAttribute($_attrName) == $_attrValue)
            {
                return $node;
            }
        }
        return $_node;
    }

//------------------------------------------------------------------------------
// Получить значение атребута $_name узла $_elem
// ДЛЯ РУССКОГО ТЕКСТА
//------------------------------------------------------------------------------
    function getRusAttribute($_elem, $_name)
    {
        return $this->DecodeStr($this->getNodeAttribute($_elem, $_name));
    }
//------------------------------------------------------------------------------
//Получаем значение узла для русского текста
//------------------------------------------------------------------------------
    function getRusNodeValue($_elem)
    {
        return $this->DecodeStr($_elem->nodeValue);
    }
}

?>