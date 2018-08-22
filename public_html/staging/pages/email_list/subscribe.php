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
 * $Id: subscribe.php,v 1.2 2005/08/02 03:05:22 bps7j Exp $
 */

$template = file_get_contents("templates/email_list/subscribe.php");

$error = false;

# Ensure that the member is not already subscribed to this email list with the
# same email address as s/he currently has
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/email_list/check-subscribed.sql");
$cmd->addParameter("owner", $cfg['user']);
$cmd->addParameter("list", $cfg['object']);
$cmd->addParameter("email", $obj['user']->getEmail());
if ($cmd->executeScalar() > 0) {
    $template = Template::unhide($template, "ALREADY");
    $error = true;
}

if (getval('continue') && !$error) {
    $object->subscribe($obj['user']);
    $object->processRequests();
    $template = Template::unhide($template, "SUCCESS");
}
elseif (!$error) {
    $template = Template::unhide($template, "CONFIRM");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Subscribe to Email List";

?>
