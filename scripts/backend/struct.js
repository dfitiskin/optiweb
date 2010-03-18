var Opened = new Array();
var Closed = new Array();

function addValue(array,value)
{

	var fl = true;
	for (i=0;i<array.length;i++)
	if (array[i]==value)
	{
		fl = false;
		break;		
	}

  if (fl)
	{
		array[array.length] = value;
	}

	return array;
}

function delValue(array,value)
{

	var idx = -1;

	for (i=0;i<array.length;i++)
	if (array[i]==value)
	{
		idx = i;
		break;		
	}

  if (idx>-1)
	{
		array[idx] = array[array.length-1];
		array = array.slice(0,array.length-2)
	}
	return array;
}

function change(i, node_type, cont, type)
{
  var Div = getElement("div"+i);
  var Img = getElement("img"+i);
  var Cor = getElement("cor"+i);
  
  if ( Div.style.display ==  "block")
  {
	Closed = addValue(Closed,i);
  	Opened = delValue(Opened,i);

 	document.cookie = setCookie("c",Closed.toString());
  	document.cookie = setCookie("o",Opened.toString());

	Div.style.display = 'none';
	Img.src='/images/__backend/struct/folder-' + type + cont + 'c.gif';
	Cor.src='/images/__backend/struct/c-plus-' + node_type + '.gif';
  }
  else
  {
	Opened = addValue(Opened,i);
  Closed = delValue(Closed,i);
 	document.cookie = setCookie("c",Closed.toString());
  document.cookie = setCookie("o",Opened.toString());
//	document.cookie = setCookie("s["+i+"]","1");
	Div.style.display = 'block';
	Img.src='/images/__backend/struct/folder-' +type+ cont + 'o.gif';
	Cor.src='/images/__backend/struct/c-minus-' + node_type + '.gif';
  }

}
