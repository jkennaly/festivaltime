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
	$.post("includes/php/update_discussion.php", { user: user, comment: comment });
	document.getElementById(scrollid).scrollIntoView();
}