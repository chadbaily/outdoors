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
 * $Id: navbar.php,v 1.2 2005/06/05 16:22:29 bps7j Exp $
 */

include_once("TreeNavbar.php");

$obj['navbar'] =& new TreeNavbar();

# ==============================================================

$obj['navbar']->addNode("{BASE_URL}", "Home");

$obj['navbar']->addNode("about/faq.shtml", "FAQ", "Home");
$obj['navbar']->addNode("about/schedule.shtml", "Upcoming Meetings", "Home");
$obj['navbar']->addNode("about/contact.shtml", "Contact Us", "Home");
$obj['navbar']->addNode("about/how-it-works.shtml", "How the Club Works", "Home");
$obj['navbar']->addNode("about/activities.shtml", "Activities", "Home");
$obj['navbar']->addNode("about/officers.shtml", "Club Officers", "Home");
$obj['navbar']->addNode("about/committees.shtml", "Committee Members", "Home");
$obj['navbar']->addNode("members/main/past-adventures", "Past Adventures", "Home");
$obj['navbar']->addNode("about/donate.shtml", "Donate / Pay Dues", "Home");
$obj['navbar']->addNode("http://www.flickr.com/photos/outdoorsuva/ 'target=_blank'", "Photo Gallery", "Home");

$obj['navbar']->addNode("members/join", "Join {CLUB_NAME}");

$obj['navbar']->addNode("members/main/member-home", "Member's Area");
$obj['navbar']->addNode("members/adventure", "Adventures", "Member's Area");
$obj['navbar']->addNode("members/member/list_all", "Members", "Member's Area");
$obj['navbar']->addNode("members/classified_ad", "Classified Ads", "Member's Area");
$obj['navbar']->addNode("members/profile", "Profile", "Member's Area");
$obj['navbar']->addNode("members/main/inventory", "Inventory", "Member's Area");
$obj['navbar']->addNode("../resources/", "Member Resources", "Member's Area");

# Add additional tabs depending on who's logged in
if (isset($obj['user'])) {

    if ( $obj['user']->isRootUser()
            || $obj['user']->isInGroup('officer')
            || $obj['user']->isInGroup('leader')
            || $obj['user']->isInGroup('treasurer') ) {
        $obj['navbar']->addNode("members/report", "Reports", "Member's Area");
    }

//    if ( $obj['user']->isRootUser()
//            || $obj['user']->isInGroup('leader')
//            || $obj['user']->isInGroup('officer')
//            || $obj['user']->isInGroup('treasurer')) {
        $obj['navbar']->addNode("members/expense_report", "Expenses", "Member's Area");
//    }

    if ( $obj['user']->isRootUser()
            || $obj['user']->isInGroup('officer')
            || $obj['user']->isInGroup('treasurer') ) {
        $obj['navbar']->addNode("members/admin", "Admin Tasks", "Member's Area");
    }

}

# Set the active node
$obj['navbar']->setActiveNode($res['navbar']);

?>
