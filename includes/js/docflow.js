function toggle(divid, aid) {
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
}