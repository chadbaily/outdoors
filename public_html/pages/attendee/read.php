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
 * $Id: read.php,v 1.3 2005/08/02 02:49:20 bps7j Exp $
 */

$template = file_get_contents("templates/attendee/read.php");

# Get info about the attendee & adventure
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/attendee/read.sql");
$cmd->addParameter("attendee", $cfg['object']);
$result = $cmd->executeReader();
$row = $result->fetchRow();

# Plug info into the template template
$res['content'] = Template::replace($template, $row);
$res['title'] = "View Attendee Details";

?>
