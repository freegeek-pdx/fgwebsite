/* ajax.Request */
function fb_ajaxRequest(url,submitdata,fieldname) {

  jQuery.get(
  	url, 
	submitdata,
    function(data){
      fb_getResponse(data, fieldname);
    }
  );

}

/* ajax.Response */
function fb_getResponse(data, fieldname) {
	jQuery("#"+fieldname).html(data);
}

/* Disable the form once submitted to prevent multiple hits */
function fb_disableForm(theform) {
	if (document.all || document.getElementById) {
		for (i = 0; i < theform.length; i++) {
		var tempobj = theform.elements[i];
		if (tempobj.type.toLowerCase() == "submit" || tempobj.type.toLowerCase() == "reset")
		tempobj.disabled = true;
		}
		return true;
	}
	else {
		alert("The form is currently processing. Please be patient.");
		return false;
	}
}

jQuery(document).ready(function(){
	jQuery('.fb-date-pick').datePicker(
	{
		startDate:'01/01/1900'
	}	
	);
 });
