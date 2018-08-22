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
 * $Id: inactive.php,v 1.1.1.1 2005/03/27 19:53:12 bps7j Exp $
 */

# Require the user to be logged in at least partially
$cfg['login_mode'] = "partial";
include_once("includes/authorize.php");

$wrapper = file_get_contents("templates/join/inactive.php");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/join/current_membership.sql");
$cmd->addParameter("member", $cfg['user']);
$cmd->addParameter("inactive", $cfg['status_id']['inactive']);
$cmd->addParameter("paypal_charge", $cfg['paypal_handling_cost']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
  $wrapper = Template::block($wrapper, "PAYPAL",
                        array_change_key_case($row, 1));
}

$wrapper = Template::replace($wrapper, array("PAYPAL_EMAIL" => $cfg['paypal_email']));
$wrapper = Template::replace($wrapper, array("PAYPAL_FEE" => $cfg['paypal_handling_cost']));
$wrapper = Template::replace($wrapper, array("PAYPAL_URL" => $cfg['paypal_url']));

$res['content'] = $wrapper;
$res['title'] = "Inactive Membership";

?>
