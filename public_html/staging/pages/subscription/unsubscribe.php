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
 * $Id: unsubscribe.php,v 1.2 2009/03/12 03:15:59 pctainto Exp $
 */

include_once("email_list.php");

$template = file_get_contents("templates/subscription/unsubscribe.php");

if (getval('continue')) {
    $list = new email_list();
    $list->select($object->getList());
    $list->unsubscribe($object);
    $list->processRequests();
    $template = Template::unhide($template, "SUCCESS");
}
else {
    $template = Template::unhide($template, "CONFIRM");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Unsubscribe From Email List";

?>
