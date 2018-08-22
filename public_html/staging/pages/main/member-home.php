<?php
/*
 * This file is part of SocialClub (http://socialclub.sourceforge.net)
 * Copyright (C) 2004 Baron Schwartz <baron at sequent dot org>
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307  USA
 * 
 * $Id: member-home.php,v 1.5 2009/11/15 23:10:01 pctainto Exp $
 *
 * Purpose: the member homepage that members see after they log in.
 */

include_once("includes/authorize.php");
include_once("EventCalendar.php");

$wrapper = file_get_contents("templates/main/member-home.php");

# Plug in address, phone, and chat stuff
if (($address = $obj['user']->getPrimaryAddress()) != null) {
    $address->getAllowedActions();
    $wrapper = Template::block($wrapper, "ADDRESS",
        $address->getVarArray(), false);
}
if (($phone = $obj['user']->getPrimaryPhoneNumber()) != null) {
    $wrapper = Template::block($wrapper, "PHONE",
        $phone->getVarArray(), false);
}
if (($chat = $obj['user']->getPrimaryChat()) != null) {
    require_once("chat_type.php");
    $type = new chat_type();
    $type->select($chat->getType());
    $wrapper = Template::block($wrapper, "CHAT",
        $chat->getVarArray() 
        + array("C_ABBREVIATION" => $type->getAbbreviation()), 
        false);
}

# Add a message if the officers are accepting applications and the user
# hasn't applied in the past 2 months
$days = 60;
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/application/select-recent-applications.sql");
$cmd->addParameter("days", $days);

if (isset($cfg['taking_applications']) && $cfg['taking_applications'] == true) {
    if ($obj['user']->isInGroup($cfg['group_id']['officer'])) {
        $num_recent = $cmd->executeScalar();
        if ($num_recent > 1) {
            $wrapper = Template::unhide($wrapper, "RECENT_APPS_MULTIPLE");
            $wrapper = Template::replace($wrapper, array("NUM_APPS"=>$num_recent));
        } else if ($num_recent == 1) {
            $wrapper = Template::unhide($wrapper, "RECENT_APPS_SINGLE");
        }
    } else {
        $cmd->addParameter("member", $cfg['user']);
        if (($num_recent = $cmd->executeScalar()) == 0) {
            $wrapper = Template::unhide($wrapper, "APPLICATIONS");
        }
    }
}    

$cmd = $obj['conn']->createCommand();


# Add a message if the member needs to renew within $days days
$days = 30;
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership/needs-to-renew.sql");
$cmd->addParameter("member", $cfg['user']);
if (($daysLeft = $cmd->executeScalar()) < $days) {
    $wrapper = Template::unhide($wrapper, "NEED_TO_RENEW");
    $wrapper = Template::replace($wrapper,
        array("DAYS_LEFT" => $daysLeft));
}

# Get upcoming adventures
$favImg = "<img src='assets/smiley-tiny.png' width='12' height='12' "
    . "title='This adventure matches your interests' "
    . "alt='This adventure matches your interests'>";
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/select-top-upcoming.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$cmd->addParameter("number", 10);
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $wrapper = Template::block($wrapper, "UPCOMING",
        $row + array("img" => ($row['fav'] > 0 ? $favImg : "")));
}

# Get recent classified ads
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/classified_ad/select-newest.sql");
$cmd->addParameter("default", $cfg['status_id']['default']);
$cmd->addParameter("limit", 5);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $wrapper = Template::block($wrapper, "CLASSIFIEDS",
        array_change_key_case($row, 1));
}

# Check if the member needs to choose interests
if (count($obj['user']->getChildren("interest"))) {
    $wrapper = Template::unhide($wrapper, "FAV_LOCATIONS");
    # Get the most popular locations that match the user's interests
    $limit = 5;
    $count = 0;
    $seen = array();
    if (isset($_GET['limit']) && intval($_GET['limit']) > 0) {
        $limit = intval($_GET['limit']);
    }
    else {
        $wrapper = Template::unhide($wrapper, "MORE_ACTIVITIES");
    }
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/location/select-by-interest.sql");
    $cmd->addParameter("active", $cfg['status_id']['active']);
    $cmd->addParameter("member", $cfg['user']);
    # Not used anymore in the query -- needs a subselect
    # $cmd->addParameter("limit", $limit);
    $result = $cmd->executeReader();
    $evenOdd = 0;
    while (($row = $result->fetchRow()) && $count < $limit) {
        # The query itself can't return just one row per activity without using
        # a subselect, and for that reason the 'limit' parameter is useless too.
        # So we simulate the same effect here with $seen and $count:
        if (!in_array($row['ac_title'], $seen)) {
            $seen[] = $row['ac_title'];
            $count++;
            $wrapper = Template::block($wrapper, "pop_loc", $row
                + array("class" => (($evenOdd++ % 2) ? "even" : "odd")));
        }
    }
}
else {
    $wrapper = Template::unhide($wrapper, "CHOOSE_INTERESTS");
}

$wrapper = Template::replace($wrapper, array(
    "CALENDAR" => EventCalendar::generateMonthView(
        new DateTimeSC(date("Y-m-01")), 25, false, true, true)));
        
# Plug the user's ID into the page
$wrapper = Template::replace($wrapper, array("MEMBER" => $cfg['user']));

$res['navbar'] = "Member's Area/Member Home Page";
$res['title'] = "Member Home Page";
$res['content'] = $obj['user']->insertIntoTemplate($wrapper);

?>
