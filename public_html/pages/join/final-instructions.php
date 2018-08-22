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
 * $Id: final-instructions.php,v 1.2 2005/08/02 03:05:24 bps7j Exp $
 */

$cfg['login_mode'] = 'partial';
include_once("includes/authorize.php");

$wrapper = file_get_contents("templates/join/final-instructions.php");

$total = 0;

# Plug in information about their choice of membership
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership/select-for-final-instructions.sql");
$cmd->addParameter("inactive", $cfg['status_id']['inactive']);
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();

# Edit made by Andrew Cassidy Janurary 18 2011
# If they don't have any memberships, they need to renew.
if (!$result->numRows) {
#  redirect("$cfg[base_url]/members/join/renew");
}

while ($row = $result->fetchRow()) {
    $wrapper = Template::block($wrapper, "MEMBERSHIPS",
        array_change_key_case($row, 1));
    $total += $row['c_total_cost'];
}

# Plug in the total amount
$wrapper = Template::replace($wrapper, array("TOTAL" => $total));

$res['content'] = $wrapper;
$res['title'] = "Final Steps";

?>
