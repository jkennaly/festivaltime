/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/ 

function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
    return false;
}

function toggle(divid, aid, user, comment, scrollid) {
	var ele = document.getElementById(divid);
	var text = document.getElementById(aid);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "show";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "hide";
	}
//	post_to_url("includes/php/update_discussion.php", { user: user, comment: comment }, 'post');
//	alert("user "+user+" comment "+comment);
  $.ajax({
    type: "POST",
    url: "includes/php/update_discussion.php",
    data: { user: user, comment: comment },
    success: function() {
    }
  });
	
	document.getElementById(scrollid).scrollIntoView();
}

function simpleToggle(divid, scrollid) {
	var ele = document.getElementById(divid);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
  	}
	else {
		ele.style.display = "block";
	}
	
	document.getElementById(scrollid).scrollIntoView();
}

function addText(divid, textid) {
	var ele = document.getElementById(divid);
	var txt = document.getElementById(textid);
	
	ele.value = txt.innerHTML;
	
}

function screenWidth(divid) {
	var ele = document.getElementById(divid);
	ele.style.width = window.innerWidth*0.9 + "px"; 
	
//	alert("el width is "+ele.style.width);
}

function leftMargin(divid) {
	var ele = document.getElementById(divid);
	ele.style.margin = 0 + "px"; 
	
//	alert("el width is "+ele.style.width);
}
