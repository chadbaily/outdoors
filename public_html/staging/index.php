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
 * $Id: index.php,v 1.4 2009/03/12 03:15:59 pctainto Exp $
 */

ini_set('include_path', ".:classes:controllers:utility:includes");
ini_set('magic_quotes_gpc','Off');
ini_set('register_globals','Off');
ini_set('allow_call_time_pass_reference','On');
ini_set('date.timezone',"US/Eastern");

include_once("includes/setup.php");

# ----------------------------------------------------------------------------
# The individual page is now executed, if it exists.  First do some sanity
# checking on the $_GET[page] parameter: it must contain only letters and
# dashes and underscores.  If the request is for ?page=foo where foo is a table
# in the database, then there is the possibility of generating a default page
# that shows actions the user is allowed to take on the table.
# ----------------------------------------------------------------------------
if ($cfg['page'] && preg_match("/[^\w-]/", $cfg['page'])) {
    include_once("pages/invalid.php");
}
elseif ($cfg['page'] && file_exists($cfg['page_file'])) {
    include_once($cfg['page_file']);
}
elseif ($cfg['page'] && in_array("$cfg[table_prefix]$cfg[page]", $cfg['tables'])
        && !file_exists("pages/$cfg[page]/default.php"))
{
    include_once("pages/common/default.php");
}
else {
    include_once("pages/main.php");
}

# ------------------------------------------------------------------------------
# Do any other stuff that needs to be done once the included page has finished
# ------------------------------------------------------------------------------

if ($cfg['piece'] || $cfg['response_type'] == 'JSON') {
    $page = $res['content'];

    #These are AJAX calls.  Some browsers will cache the responses if they
    #are GET requests.  We don't want this to happen.
    header('Cache-Control: no-cache');
}
else {
    # Create the navigation bar and the tabbed-box tabs
    include_once("nav/navbar.php");
    include_once("nav/tabbed-box.php");

    # Create the main template for the page
    $page = file_get_contents("templates/main/main.php");
    
    # Plug the content into the main page and finalize it
    $page = Template::replace($page, array(
        "TITLE" => $res['title'],
        "REQUEST_URI" => $_SERVER['REQUEST_URI'],
        "CONTENT" => $res['content'],
        "TABS" => $res['tabs'],
        "HELP" => ($res['help']
            ? "<div style='float:right'><a target='_blank' href='http://socialclub.sourceforge.net/wiki/?$res[help]'>Help</a></div>"
            : ""),
        "NAVBAR" => $obj['navbar']->toString()));
} 

# Plug in information about the user
if (isset($obj['user'])) {
    $page = Template::unhide($page, "RSSFEED");
    $page = Template::unhide($page, "LOGOUT");
    # Fill in the member's name
    $page = Template::replace($page, array(
        "C_FULL_NAME" => $obj['user']->getFullName()));
}

# If someone set the JQUERY config variable, unhide it from the header
if (isset($cfg['JQUERY'])) {
    $page = Template::unhide($page, "JQUERY");
}

# There are a couple of magical variables to replace.
$page = Template::replace($page, array(
    "CLUB_NAME" => $cfg['club_name'],
    "OBJECT" => $cfg['object'],
    "BASE" => "$cfg[site_url]$cfg[base_url]/",
    "PAGE" => $cfg['page']));

# Templates in the format {actions,class,object,style,cache?} get replaced by action forms.
$page = preg_replace("#\{actions,(\w+),(\d+),(\w+)\}#e",
    "actionform('\\1', '\\2', '\\3')", $page);
$page = preg_replace("#\{actions,(\w+),(\d+),(\w+),1\}#e",
    "actionform('\\1', '\\2', '\\3', true)", $page);

echo Template::finalize($page);

?>
