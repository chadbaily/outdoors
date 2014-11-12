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
 * $Id: read.php,v 1.3 2009/03/12 03:16:02 pctainto Exp $
 */

$template = file_get_contents("templates/question/read.php");

# Insert the adventure into the template
$adventure = new adventure();
$adventure->select($object->getAdventure());
$template = Template::replace($template, array(
    "ADVENTURE" => $adventure->c_title));

# Insert a list of answers for the question
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/question/select-answers.sql");
$cmd->addParameter("question", $cfg['object']);
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "ITEM",
        array_change_key_case($row, 1));
}

if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "View Question Details";

?>
