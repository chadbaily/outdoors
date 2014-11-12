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
 * $Id: functions.php,v 1.3 2005/08/02 02:38:32 bps7j Exp $
 */

# ------------------------------------------------------------------------------
# Create functions in the global scope
# ------------------------------------------------------------------------------
# Define the file_get_contents() function if it's not defined for this version of PHP
if (!function_exists("file_get_contents")) {
    function file_get_contents($filename) {
        return implode("", file($filename));
    }
}

/* {{{getval
 * Get a sanitized version of a $_GET variable from a browser.
 */
function getval($key) {
    if (isset($_GET[$key])) {
        if (is_array($_GET[$key])) {
            // build a new array and return it, sanitized
            $toReturn = array();
            while (list($key, $val) = each($_GET[$key])) {
                $toReturn[$key] = stripslashes($val);
            }
            return $toReturn;
        }
        return stripslashes($_GET[$key]); 
    }
    return null;
} //}}}

/* {{{postval
 * Return a sanitized version of a browser's $_POST variable.
 */
function postval($key) {
    if (isset($_POST[$key])) {
        if (is_array($_POST[$key])) {
            // build a new array and return it, sanitized
            $temp = $_POST[$key];
            $toReturn = array();
            while (list($key, $val) = each($temp)) {
                $toReturn[$key] = stripslashes($val);
            }
            return $toReturn;
        }
        return stripslashes($_POST[$key]); 
    }
    return null;
} //}}}

/* {{{redirect
 */
function redirect($url) {
    header("Location:$url");
    exit;
} //}}}


/* {{{getRandomString
 */
function getRandomString($length, $dictionary = null) {
    if (is_null ($dictionary)) {
        $dictionary = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    $dictionaryLength = strlen($dictionary);
    $result = "";
    for ($i = 0; $i < $length; ++$i) {
        $result .= substr($dictionary, rand(0, $dictionaryLength - 1), 1);
    }
    return $result;
} //}}}

/* {{{highlightSql
 */
function highlightSql($sql) {
    $gray = str_replace(" ", "\b|\b", 
        "ALL AND BETWEEN CROSS EXISTS JOIN IN LIKE NOT OR NULL OUTER SOME");

    $blue = str_replace(" ", "\b|\b", 
        "ADD ALTER AS ASC BIGINT BINARY BY CASCADE CHAR CHARACTER CHECK"
        . "COLLATE COLUMN COLUMNS CONNECTION CONSTRAINT CREATE CURRENT_DATE "
        . "CURRENT_TIME CURSOR DATABASE DEC DECIMAL DECLARE DEFAULT DELETE DESC "
        . "DESCRIBE CHANGE DISTINCT DROP ELSE ON EXPLAIN FALSE FOR FROM "
        . "GRANT GROUP HASH HAVING IF IGNORE INDEX INNER INSERT "
        . "INTERVAL INTO IS KEY LIMIT LOAD OPTIMIZE ORDER OUT RENAME REVOKE "
        . "SELECT SET SHOW TABLE THEN TO TRUE TRUNCATE UNION UNIQUE UPDATE USE "
        . "VALUES WHEN WHERE WHILE WITH");

    $pink = str_replace(" ", "\b|\b", 
        "CASE CURRENT_TIMESTAMP LEFT REPLACE RIGHT");

    $sql = preg_replace("/('|\")(.*?)(\\1)/", "<tt style='color:red'>\\1\\2\\3</tt>", $sql);
    $sql = preg_replace("/(\b$blue\b)/i", "<tt style='color:blue'>\\1</tt>", $sql);
    $sql = preg_replace("/(\b$pink\b)/i", "<tt style='color:#FF00FF'>\\1</tt>", $sql);
    return preg_replace("/(\b$gray\b)/i", "<tt style='color:gray'>\\1</tt>", $sql);
} //}}}


function titlecase($text) {
    $articles = "to|with|at|in|it|the|from|for|and|of|on|or|a|this|by";
    $bad = array("!!!");
    $good = array("!");
    return ucfirst(preg_replace("/\b($articles)\b/ie", 'strtolower("\\1");',
        preg_replace("/\b([\w']+)\b/e", "ucfirst(strtolower('\\1'));",
            str_replace($bad, $good, $text))));
}

# Create a form that will allow selecting allowed actions on objects of the
# given type, with the given ID using the given style.  Set $cache to true, to
# avoid hitting the DB again; this will fetch the privileges once and then
# re-use them, even for different objects.
function actionform($class, $id, $style, $cache = false) {
    global $object;
    global $cfg;
    static $privs;
    static $cacheType;
    if (!$cache || !is_array($privs) || $cacheType != $class) {
        if (isset($object) 
            && !is_array($privs)
            && get_class($object) == $class
            && $object->getUID() == $id)
        {
            # The object is already fetched from the DB.  No need to re-fetch it.
            # Don't assign by reference; this is a static variable.  See the
            # PHP manual.
            $privs = $object->getAllowedActions();
            $cacheType = $class;
        }
        elseif (!$cache || !is_array($privs) || $cacheType != $class) {
            $cacheType = $class;
            $obj =& new $class();
            $obj->select($id);
            # Don't assign by reference.
            $privs = $obj->getAllowedActions();
        }
    }
    $template = file_get_contents("templates/misc/actionform-$style.txt");
    foreach ($privs as $action) {
        $template = Template::block($template, "actions", $action);
    }
    return Template::replace($template, array(
        "PAGE" => $class,
        "BASE" => $cfg['base_url'],
        "OBJECT" => $id));
}

function smiley($text) {
    $find = array(
        "/(?<!O):-?\\)/",
        "/:-?\\(/",
        "/;-?\\)/",
        "/:-?P/",
        "/:-?D/",
        "/:-\\[/",
        "#:-/#",
        "/=-(O|0)/",
        "/:-\\*/",
        "/(>:o|&gt;:o)/",
        "/8-?\\)/",
        "/:-\\$/",
        "/:-!/",
        "/O:-?\\)/",
        "/:'\\(/",
        "/:-X/");

    $replace = array(
        "smile",
        "frown",
        "wink",
        "tongue-out",
        "laughing",
        "embarassed",
        "undecided",
        "surprised",
        "kiss",
        "yell",
        "cool",
        "money-mouth",
        "foot-in-mouth",
        "innocent",
        "cry",
        "sealed");
    foreach ($replace as $key => $val) {
        $replace[$key] = "<img alt=\"\\0\" title=\"\\0\" height=\"18\" width=\"18\" src=\"assets/smiley-$val.png\">";
    }
    return preg_replace($find, $replace, $text);
}

/*
 * Converts a bitmask value to its string representation: comma-separated names.
 * Note that this can be used in templates.
 * @param value The value to convert
 * @param type the name of the $cfg[] entry that holds the values
 * @return string
 */
function bitmaskString($value, $type) {
    global $cfg;
    $tmp = array();
    foreach ($cfg[$type] as $name => $val) {
        if (($val & $value) != 0) {
            $tmp[] = $name;
        }
    }
    return join(", ", $tmp);
}

?>
