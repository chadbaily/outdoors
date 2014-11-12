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
 * $Id: setup.php,v 1.6 2005/08/31 00:38:16 bps7j Exp $
 *
 * Create the variables and stuff the individual pages need, including
 * setting up error handling and global variables.
 */

# Include some global functions
require_once("functions.php");

# Require various classes
include_once("table.php");
include_once("Template.php");
include_once("XmlForm.php");

# You need to include this file after the ones above or PHP will throw an
# "undefined class database_object" from privilege.php
include_once("member.php");

# -----------------------------------------------------------------------------
# Miscellaneous stuff
# -----------------------------------------------------------------------------

ignore_user_abort(true);
# Call srand() just once per page invocation.
srand ((double) microtime() * 1000000);

# -----------------------------------------------------------------------------
# Global variables.  There are ONLY FOUR global variables, $cfg $res, $err, and
# $obj.  $cfg holds config information, $res holds generated content to send to
# the browser, and $obj holds global objects.  See below for definitions of
# what's expected to be in the $res variable.  $err holds a list of errors that
# can be spit out for debugging.
# -----------------------------------------------------------------------------

$cfg = array();
$obj = array();
$err = array();
$res = array();

# Set the level of error that should trigger something to happen.
$cfg['error_types'] = array(
	E_ERROR => "E_ERROR",
	E_WARNING => "E_WARNING",
	E_PARSE => "E_PARSE",
	E_NOTICE => "E_NOTICE",
	E_CORE_ERROR => "E_CORE_ERROR",
	E_CORE_WARNING => "E_CORE_WARNING",
	E_COMPILE_ERROR => "E_COMPILE_ERROR",
	E_COMPILE_WARNING => "E_COMPILE_WARNING",
	E_USER_ERROR => "E_USER_ERROR",
	E_USER_WARNING => "E_USER_WARNING",
	E_USER_NOTICE => "E_USER_NOTICE",
	E_ALL => "E_ALL");
error_reporting(E_ALL);

# Define the levels of errors that I want to cause a webmaster email or log
define("ERROR_EMAILING", E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR
    | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR);
define("ERROR_LOGGING", E_ALL);

# -----------------------------------------------------------------------------
# Check for GET variables and store them in the $cfg array so there will be no
# attempts to access undefined variables.  The 'object' is initialized from the
# GET first, and if this fails from POST.
# -----------------------------------------------------------------------------
$cfg['action'] = isset($_GET['action']) ? $_GET['action'] : "";
$cfg['page']   = isset($_GET['page'])   ? $_GET['page'] : "";

$cfg['object'] = isset($_GET['object']) 
    ? intval($_GET['object']) 
    : (isset($_POST['object']) 
        ? intval($_POST['object']) 
        : 0);

# -----------------------------------------------------------------------------
# Paths and URLs.  These should NOT have a trailing slash.
# -----------------------------------------------------------------------------

# The base path on the filesystem where the website's files live
$cfg['base_path'] = str_replace("\\", "/", dirname($_SERVER['PATH_TRANSLATED']));

# The address to the webserver that the site is hosted on.
$cfg['site_url'] = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

# The base URL to the site.
$cfg['base_url'] = dirname($_SERVER['PHP_SELF']);
if ($cfg['base_url'] == "/") {
    $cfg['base_url'] = "";
}

# The path to any page file's action directory, assuming that $cfg[page] is
# set... this is a safe assumption as long as this variable is only used from a
# /pages/something page, which only runs if $cfg[page] is actually set.
$cfg['page_path'] = "$cfg[base_path]/pages/$cfg[page]";

# A magic value for the filename of the current page (this is the current
# page that should be executed, NOT the same as any of the built-in PHP
# variables).
$cfg['page_file'] = "$cfg[base_path]/pages/$cfg[page].php";

# Include user-defined settings.
require("includes/config.php");

# ------------------------------------------------------------------------------
# Define a function to handle errors.  This will only handle user-defined
# errors.  The built-in errors must be handled by the output buffering.
# ------------------------------------------------------------------------------
if ($cfg['error_log'] || $cfg['error_email']) {
    function userErrorHandler($errno, $errstr, $errfile, $errline) {
        global $cfg;
        $logMessage = "{$cfg['error_types'][$errno]} at line $errline "
            . "in $errfile: $errstr"
            . "\r\n$_SERVER[REMOTE_HOST] on page $_SERVER[REQUEST_URI]";
        if (isset($_SERVER['HTTP_REFERER'])) {
            $logMessage .= "\r\nreferred from $_SERVER[HTTP_REFERER]";
        }
        if (isset($cfg['user'])) {
            $logMessage .= "\r\nuser: $cfg[user]";
        }
        if ($cfg['error_log'] && (intval($errno) & ERROR_EMAILING) != 0) {
            error_log($logMessage, 1, $cfg['error_email']);
        }
        if ($cfg['error_log'] && (intval($errno) & ERROR_LOGGING) != 0) {
            error_log(date("Y-m-d h:i:s ", time()) . $logMessage
                . "\r\n", 3, $cfg['error_log']);
        }
    }
    set_error_handler("userErrorHandler");
}

# ------------------------------------------------------------------------------
# Define an error handling function.  This gets called in case the buffer, which
# the system inspects before it flushes it to the browser, contains an error.
# This is for "emergency" errors that the normal PHP error handling mechanisms
# didn't catch, and is just an intervention to make sure *something* gets done
# with the error besides just barfing all sorts of stuff out to the user.
# ------------------------------------------------------------------------------
if ($cfg['error_log'] || $cfg['error_email']) {
    function fatalErrorHandler(&$buffer) {
        global $cfg;

        if (ereg("error</b>:(.+)<br", $buffer, $regs)) {

            # Build error message
            $time = date("Y-m-d H:i:s");
            $userid = serialize($cfg['auth']);

            $logMessage = "
                [$time] $regs[1]
                URL:           $_SERVER[REQUEST_URI]
                Referring URL: $_SERVER[HTTP_REFERER]
                User Cookie:   $userid
                ";
            # Trim leading space off the message and log it
            $logMessage = preg_replace('/(?m)^\s*/', "", $logMessage);
            if ($cfg['error_email']) {
                error_log($logMessage . "\r\n", 1, $cfg['error_email']);
            }
            if ($cfg['error_log']) {
                error_log($logMessage, 3, $cfg['error_log']);
            }
            
            # Display a friendly error message
            return "<html><head><title>Error</title></head><body>
            <h1>Fatal Error</h1>
            <p>We're sorry, but there was a fatal error while processing your 
            request.  You don't need to do anything.  The website has already
            emailed the error to the webmasters.</p></body></html>";
        }
        else {
            # All is well, so return the buffer itself; the buffer is safe to
            # display to the user.
            return $buffer;
        }
    }
    ob_start('fatalErrorHandler');
}

# ------------------------------------------------------------------------------
# Create the database connection.
# ------------------------------------------------------------------------------
include_once("{$cfg['db']['type']}.php");
$obj['conn'] =& new $cfg['db']['type']($cfg['db']);
$obj['conn']->open();

# ------------------------------------------------------------------------------
# Login mode.  See the comments in the header of includes/authorize.php.  Pages
# that want to require someone to log in should include authorize.php.  The
# login status indicates success or failure of the login attempt.  The
# login_password indicates if the user entered the right password.  The
# login_exists indicates if the email address even exists in the database.
# ------------------------------------------------------------------------------
$cfg['login_mode'] = "full";
$cfg['login_status'] = true;
$cfg['login_password'] = true;
$cfg['login_exists'] = true;

# The userid and password cookie, for logging in the user.
if (isset($_COOKIE['auth'])) {
    $cfg['auth'] = unserialize(base64_decode($_COOKIE['auth']));
}
else {
    $cfg['auth'] = array("user" => "", "pass" => "");
}

# ------------------------------------------------------------------------------
# Holds a list of references to objects that have been 'visited' before.  This
# is to avoid cycling when deciding which objects to delete (when cascading
# deletes).
# ------------------------------------------------------------------------------
$obj['visited-objects'] = array();

# ------------------------------------------------------------------------------
# The following indices are defined in the $res array:
# Index         Meaning
# -----         -------
# title         Page title
# content       Content that the page generates; higher-level pages can handle
#               it as they see fit.
# description   The description META tag
# keywords      The keywords META tag
# navbar        The navbar contents
# tabs          The tabbed-box tabs HTML
# help          The WikiName of the help topic (should begin with 'HelpOn').
# ------------------------------------------------------------------------------
$res['keywords'] = "";
$res['description'] = "";
$res['title'] = "";
$res['navbar'] = "Home";
$res['tabs'] = "";
$res['meta'] = "";
$res['help'] = "";

# ------------------------------------------------------------------------------
# ------------------------------------------------------------------------------
# Definitions of constants that are required to run the website.
# ------------------------------------------------------------------------------
# ------------------------------------------------------------------------------

# ------------------------------------------------------------------------------
# User Groups
# ------------------------------------------------------------------------------
$cfg['group_id'] = array(
    "root" => 1,
    "officer" => 2,
    "treasurer" => 4,
    "leader" => 8,
    "quartermaster" => 16,
    "member" => 32,
    "guest" => 64,
    "wheel" => 128,
    "activator" => 256
    );

# ------------------------------------------------------------------------------
# Unix permission bitmasks, set in an object's c_unixperms.  These are the
# rightmost 9 bits, as you would see in UNIX files.
# ------------------------------------------------------------------------------
$cfg['perm'] = array(
    "owner_read" => 256,
    "owner_write" => 128,
    "owner_delete" => 64,
    "group_read" => 32,
    "group_write" => 16,
    "group_delete" => 8,
    "other_read" => 4,
    "other_write" => 2,
    "other_delete" => 1
    );

# ------------------------------------------------------------------------------
# Status values.  You can set statuses on any database_object with the xxxStatus
# functions.
# ------------------------------------------------------------------------------
$cfg['status_id'] = array(
    "default" => 1,
    "inactive" => 2,
    "active" => 4,
    "waitlisted" => 8,
    "cancelled" => 16,
    "pending" => 32,
    "paid" => 64,
    "checked_out" => 128,
    "checked_in" => 256,
    "missing" => 512,
    "submitted" => 1024
    );

# ------------------------------------------------------------------------------
# Actions.
# ------------------------------------------------------------------------------
$cfg['actions'] = array();

# This is a list of actions that simply can't be done without an object.
$cfg['require_object_actions'] = array();

# Set up the actions.
$result = $obj['conn']->query("select * from [_]action order by c_title");
while ($row = $result->fetchRow()) {
    $cfg['actions'][$row['c_title']] = $row;
    if ($row['c_apply_object']) {
        $cfg['require_object_actions'][] = $row['c_title'];
    }
}

# ------------------------------------------------------------------------------
# Tables in the database.  There are actually a few other tables that are "meta"
# tables; these are the ones that hold information the site can manipulate in a
# uniform way.
# ------------------------------------------------------------------------------
$cfg['tables'] = array();
$result = $obj['conn']->query("select c_name from [_]table order by c_name");
while ($row = $result->fetchRow()) {
    $cfg['tables'][] = $row['c_name'];
}

# ------------------------------------------------------------------------------
# Configuration that is stored in the database.
# ------------------------------------------------------------------------------
$result = $obj['conn']->query(
    "select c_name, c_value, c_type from [_]configuration");
while ($row = $result->fetchRow()) {
    switch ($row['c_type']) {
    case "integer":
        $cfg[$row['c_name']] = intval($row['c_value']);
        break;
    case "number":
        $cfg[$row['c_name']] = floatval($row['c_value']);
        break;
    case "bool":
        $cfg[$row['c_name']] = ($row['c_value'] === "true");
        break;
    default:
        $cfg[$row['c_name']] = $row['c_value'];
        break;
    }
}

?>
