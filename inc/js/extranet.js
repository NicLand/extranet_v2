/*
 * Function for the interactome Proparacyto.
 * N. LAndrein
 */

function ajout(td, ad_id, bd_id){
	  if(td.getAttribute("clicked") ==1)return
	  td.setAttribute("clicked", "1");
	   tmp = td.firstChild.nodeValue;
	   td.removeChild(td.firstChild);

	   divform = document.createElement("form");
	   divform.setAttribute("action", "#");
	   divform.setAttribute("method", "post");
	   divform.setAttribute("name", "add");
	   divform.setAttribute("id", "add");

	   formSubmit = document.createElement("input");
	   formSubmit.setAttribute("type","submit");
	   formSubmit.setAttribute("value","Add");

	   formHidden = document.createElement("input");
	   formHidden.setAttribute("type","hidden");
	   formHidden.setAttribute("name","ad_id");
	   formHidden.setAttribute("value","ad_id");

	   formHidden2 = document.createElement("input");
	   formHidden2.setAttribute("type","hidden");
	   formHidden2.setAttribute("name","bd_id");
	   formHidden2.setAttribute("value","bd_id");

	   formHidden3 = document.createElement("input");
	   formHidden3.setAttribute("type","hidden");
	   formHidden3.setAttribute("name","MM_insert");
	   formHidden3.setAttribute("value","form1");


	   divtexte = document.createElement("textarea");
	   divtexte.setAttribute("name","interaction");
	   divtexte.setAttribute("id","interaction");


	   divform.appendChild(divtexte);
	   divform.appendChild(formSubmit);
	   divform.appendChild(formHidden);
	   divform.appendChild(formHidden2);
	   divform.appendChild(formHidden3);

	   td.appendChild(divform);
	   divtexte.innerHTML = tmp;
	}

	function update(td, ad_id, bd_id){
	  if(td.getAttribute("clicked") ==1)return
	  td.setAttribute("clicked", "1");
	   tmp = td.firstChild.nodeValue;
	   td.removeChild(td.firstChild);

	   divform = document.createElement("form");
	   divform.setAttribute("action", "#");
	   divform.setAttribute("method", "post");
	   divform.setAttribute("name", "update");
	   divform.setAttribute("id", "update");

	   formSubmit = document.createElement("input");
	   formSubmit.setAttribute("type","submit");
	   formSubmit.setAttribute("value","Update");

	   formHidden = document.createElement("input");
	   formHidden.setAttribute("type","hidden");
	   formHidden.setAttribute("name","ad_id");
	   formHidden.setAttribute("value","ad_id");

	   formHidden2 = document.createElement("input");
	   formHidden2.setAttribute("type","hidden");
	   formHidden2.setAttribute("name","bd_id");
	   formHidden2.setAttribute("value","bd_id");

	   formHidden3 = document.createElement("input");
	   formHidden3.setAttribute("type","hidden");
	   formHidden3.setAttribute("name","MM_update");
	   formHidden3.setAttribute("value","form2");

	   divtexte = document.createElement("textarea");
	   divtexte.setAttribute("name","interaction");
	   divtexte.setAttribute("id","interaction");

	   divform.appendChild(divtexte);
	   divform.appendChild(formSubmit);
	   divform.appendChild(formHidden);
	   divform.appendChild(formHidden2);
	   divform.appendChild(formHidden3);

	   td.appendChild(divform);
	   divtexte.innerHTML = tmp;
	}
