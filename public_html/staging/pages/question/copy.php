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
 * $Id: copy.php,v 1.2 2005/08/05 20:27:12 bps7j Exp $
 */

# We need to know which adventure to copy the question into.
if (!getval("adventure")) {
    $res['content'] = file_get_contents("templates/question/copy.php");
    $res['title'] = "Can't Copy";
    return;
}

# Create a copy of the question, then set the adventure to the new adventure and
# insert the object
$newQuestion = $object->copy();
$newQuestion->setAdventure(getval("adventure"));
$newQuestion->insert();

# Redirect the user to the adventure's edit_questions page
redirect("$cfg[base_url]/members/adventure/edit_questions/$_GET[adventure]#current");

?>
