var ParentState = null;

function ParentStateInit(state)
{
  ParentState = state;
}

function sectStateInit(name,state)
{ 
    return false;
}


function switch_blocks()
{                                     
     
      var ParentDivs = document.all.parent_block
      ParentState = ParentState?0:1;
      document.cookie = setCookie('params[hide_blocks]',ParentState);

      if (ParentDivs)      
      for (var i=0;i<ParentDivs.length;i++)
      {
	      Div = ParentDivs[i];
	      if ( ParentState==1)
		  Div.style.display = 'none';
	      else
		 Div.style.display = 'block';
      }
      
	if (ParentState==1) 
	{ 
		document.all('parentfilter').src='/images/__backend/common/chkgray1.gif' 
	} 
	else 
	{
		document.all('parentfilter').src='/images/__backend/common/chkgray0.gif' 
	}
}

function switch_section(name)
{                                     
    var sectionDiv = document.all[name];
    var state = sectionDiv.style.display == 'none' ? 1 : 0;

    document.cookie = setCookie('params['+name+']', state);
    sectionDiv.style.display = state ? '' : 'none';
}


