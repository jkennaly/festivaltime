<?php

function CheckRights($right_level, $right_required){
	if($right_level == "admin"){
		return true;
	}
	elseif($right_level == "siteadmin"){
		if($right_required == "CreateNotes" || $right_required == "AddFest" || $right_required == "EditFest" || $right_required == "EditSite" || $right_required == "SiteAdmin" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") { return true; }
		return false;
	}
	elseif($right_level == "festadmin"){
		if($right_required == "CreateNotes" || $right_required == "AddFest" || $right_required == "EditFest" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") { return true; }
		return false;
	}
	elseif($right_level == "groupadmin"){
		if($right_required == "CreateNotes" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") { return true; }
		return false;
	}
	elseif($right_level == "member"){
		if($right_required == "CreateNotes" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") { return true; }
		return false;
	}
	elseif($right_level == "public"){
		if($right_required == "ViewNotes" || $right_required == "FollowLink") { return true; }
		return false;
	}
	else{
		return false;
	}

}

?>