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
 * $Id: default.php,v 1.6 2009/03/12 03:15:59 pctainto Exp $
 *
 * This is the default page for the Admin Tasks tab.
 */

# Check permissions.  Only members of the "root", "treasurer", or "officer"  
# groups are allowed to access this page.
if (!$obj['user']->isRootUser()
        && !$obj['user']->isInGroup('treasurer')
        && !$obj['user']->isInGroup('officer')) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$contents = file_get_contents("templates/admin/default.php");

$obj['table'] = new table("$cfg[table_prefix]membership");
if ($obj['table']->permits("list_all")) {
    $contents = Template::unhide($contents, "ACTIVATE");
}

if ($obj['user']->isRootUser()) {
    $contents = Template::unhide($contents, "DBCOMMON");
    $contents = Template::unhide($contents, "UNSUBSCRIBE");
    $contents = Template::unhide($contents, "CONFIG");
}

if ($obj['user']->isInGroup('wheel')) {
    $contents = Template::unhide($contents, "SU");
    $contents = Template::replace($contents,
        array("root_uid" => $cfg['root_uid']));
}

$res['content'] = $contents;

?>
