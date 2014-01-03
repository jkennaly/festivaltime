<?php
/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/


function CheckRights($right_level, $right_required)
{
    if ($right_level == "admin") {
        return true;
    } elseif ($right_level == "siteadmin") {
        if ($right_required == "SimFest" || $right_required == "ChangeGroup" || $right_required == "RemoveGroup" || $right_required == "RemoveOwnGroup" || $right_required == "CreateGroup" || $right_required == "CreateNotes" || $right_required == "AddFest" || $right_required == "EditFest" || $right_required == "EditSite" || $right_required == "SiteAdmin" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") {
            return true;
        }
        return false;
    } elseif ($right_level == "festadmin") {
        if ($right_required == "SimFest" || $right_required == "RemoveOwnGroup" || $right_required == "CreateGroup" || $right_required == "CreateNotes" || $right_required == "AddFest" || $right_required == "EditFest" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") {
            return true;
        }
        return false;
    } elseif ($right_level == "alphalead") {
        if ($right_required == "SimFest" || $right_required == "CreateGroup" || $right_required == "CreateNotes" || $right_required == "AddFest" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") {
            return true;
        }
        return false;
    } elseif ($right_level == "alphamember") {
        if ($right_required == "SimFest" || $right_required == "CreateGroup" || $right_required == "CreateNotes" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") {
            return true;
        }
        return false;
    } elseif ($right_level == "groupadmin") {
        if ($right_required == "SimFest" || $right_required == "RemoveOwnGroup" || $right_required == "CreateGroup" || $right_required == "CreateNotes" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") {
            return true;
        }
        return false;
    } elseif ($right_level == "member") {
        if ($right_required == "SimFest" || $right_required == "CreateGroup" || $right_required == "CreateNotes" || $right_required == "ViewNotes" || $right_required == "ModifySelf" || $right_required == "AddBands" || $right_required == "FollowLink" || $right_required == "SendComms") {
            return true;
        }
        return false;
    } elseif ($right_level == "public") {
        if ($right_required == "SimFest" || $right_required == "ViewNotes" || $right_required == "FollowLink") {
            return true;
        }
        return false;
    } else {
        return false;
    }

}


