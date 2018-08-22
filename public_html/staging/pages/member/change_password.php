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
 * $Id: change_password.php,v 1.2 2009/03/12 03:15:59 pctainto Exp $
 */

$template = file_get_contents("templates/member/change_password.php");
$template = $object->insertIntoTemplate($template);

$form = new XMLForm("forms/member/change_password.xml");

$form->snatch();
$form->validate();
if ($form->isValid()) {
    # Update the database with the form values, but only if it's actually
    # different
    if ($object->getPassword() != $form->getValue("password1")) {
        $object->setPassword($form->getValue("password1"));
        $object->update();
    
        if ($cfg['object'] == $cfg['user']) {
            $template = Template::unhide($template, "YOUR_SUCCESS");
        }
        else {
            $template = Template::unhide($template, "OTHER_SUCCESS");
        }
    }
}

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));
$res['title'] = "Change Member Password";

?>
