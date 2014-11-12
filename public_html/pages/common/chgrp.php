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
 * $Id: chgrp.php,v 1.2 2005/06/05 17:11:03 bps7j Exp $
 */

# Create templates
$template = file_get_contents("templates/common/chgrp.php");

# If the form's been submitted, update the object's group
$newGroup = postval('group');

if ($newGroup) {
    $object->setGroup($newGroup);
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

# Plug the groups into the template, selecting the correct one
foreach ($cfg['group_id'] as $title => $id) {
    $template = Template::block($template, "GROUP", array(
        "C_UID" => $id,
        "C_TITLE" => $title,
        "SELECTED" => ($id == $object->getGroup())
            ? "selected"
            : ""));
}

# Plug some other common stuff into the template
$template = Template::replace($template, array(
    "TABLE" => get_class($object)));

# Plug it all into the template
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Change Group";
$res['help'] = "HelpOnGroupOwnership";

?>
