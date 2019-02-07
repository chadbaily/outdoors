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
 * $Id: authorize.php,v 1.1.1.1 2005/03/27 19:54:19 bps7j Exp $
 *
 * Purpose: requires authentication.  The variable $cfg['login_mode'] is
 * used to instruct this file whether the current page requires the user to be
 * totally logged in, or if 'partial' mode is acceptable (where the user can
 * access things like the renewal page, etc), or if 'optional' mode is
 * acceptable (the user doesn't have to log in, but if s/he IS logged in
 * already, this file will create the usual Member object).
 *
 * The $cfg['login_mode'] variable has the default value of 'full' and might
 * have been overriden to 'partial', 'none', or 'optional' by an including
 * script.
 */

# Assume that the user isn't logged in.
$cfg['user'] = 0;

# Gather login information
$form =& new XmlForm("forms/main/login.xml");
$form->snatch();
$form->validate();
if ($form->isValid()) {
    $cfg['auth']['user'] = $form->getValue("user");
    $cfg['auth']['pass'] = $form->getValue("pass");
}

# Include the correct file to do the authentication.
if (file_exists("includes/authorize-$cfg[login_mode].php")) {
    require_once("includes/authorize-$cfg[login_mode].php");
}
else {
    trigger_error("There is no authentication mode '$cfg[login_mode]'", E_USER_ERROR);
}

# Check the webserver's time against the database server's time.
# Allow 60 seconds of difference before aborting the page execution.
if (isset($cfg['db_server_time']) && $cfg['db_server_time']
        && abs(strtotime($cfg['db_server_time']) - time()) > 70) {
    $res['navbar'] = "Member's Area";
    $res['content'] = "Error: the database server's time is incorrect.  "
        . "I have notified the webmasters. Time difference is: " . abs(strtotime($cfg['db_server_time']) - time()) . " seconds";
    trigger_error("DB server time: $cfg[db_server_time].  "
        . "Webserver time: " .  time(), E_USER_ERROR);

    # Create the main template for the page
    $page = file_get_contents("templates/main/main.php");

    # Plug the content into the main page
    $page = Template::replace($page, array(
        "TITLE" => "Database Time Server Error",
        "CONTENT" => $res['content']));

    echo Template::finalize($page);
    exit;
}

if (!$cfg['login_status']) {
    # Login failed.  Delete the existing cookie.
    setcookie("auth", "", time() - 3600, "/");

    $res['navbar'] = "Member's Area";
    $res['content'] = $form->toString();

    # Create the main template for the page
    $page = file_get_contents("templates/main/main.php");

    # Plug the content into the main page
    $page = Template::replace($page, array(
        "TITLE" => "Please Log In",
        "BASE" => "$cfg[site_url]$cfg[base_url]/",
        "CONTENT" => $res['content']));

    # Unhide stuff if the login failed
    if (!$cfg['login_password']) {
        $page = Template::unhide($page, "PASSWORD");
    }
    if (!$cfg['login_exists']) {
        $page = Template::unhide($page, "USER");
    }

    echo Template::finalize($page);
    exit;
}
elseif ($form->isValid()) {
    # Login succeeded.
    setcookie("auth", base64_encode(serialize($cfg['auth'])), null, "/");
}

?>
