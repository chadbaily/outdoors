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
 * $Id: Template.php,v 1.2 2009/03/12 03:13:36 pctainto Exp $
 *
 * Purpose:  A template-replacing engine that operates on templates (strings of
 * text with special keys in them).  Think of it as containing operations that
 * you can apply to a string.  When you invoke an operation, you pass it a
 * string and some data that you want it to use in its operations on the string.
 * All methods are 'static' in that you don't need to create an instance of the
 * Template class to call them.
 */

class Template {

    /* {{{replace
     * Accepts a string and an array of values in the form key=>value.  Replaces
     * each key in the string with the corresponding data.  Returns the modified
     * string.  If $repeat is set, it re-appends the key string after the
     * replaced value, so the replacement can be done iteratively.  Don't forget
     * to call finalize() to remove this superfluous key before printing to the
     * browser.
     *
     * Template keys can take an optional list of formatting functions, too, in
     * the form {KEY|func_name,arg1,arg2,argn|func_name2,arg1}  You can chain
     * together as many calls as you want.  They are evaluated from right to
     * left, and the VALUE that's getting processed is always the first argument
     * (arg0) to the function.  Sometimes PHP's built-ins, such as date(), don't
     * accept the value as the first argument, so there are some extra functions
     * defined as static methods of this class that you can use.  They're named
     * with a leading underscore.  Check below to see what's available.
     */
    function replace($data, $values, $repeat = 0) {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                if (is_scalar($value)) {

                    # NOTE: This can be useful to check for nonexistent keys in
                    # the input, but will slow the page generation down vastly
                    # because of the errors it will throw.
                    # if (strpos($data, "{$key}") === FALSE
                    #     && !preg_match("/\{$key\}/", $data)) {
                    #     trigger_error("Key $key not found in input", E_USER_WARNING);
                    # }

                    # Do the work of replacement, two different ways -- one for
                    # {KEY} and one for {KEY:}blabla{:KEY} formats
                    #$data = str_replace('{'."$key}",
                        #($repeat ? "$values[$key]\{$key}" : $values[$key]), $data);
                    if ($repeat) {
                        $data = preg_replace("/(\{$key(\|(.*?))?\})/e",
                            "('\$2' "
                                . "? (Template::preProcess(\$value, '\$3') . '\$1') "
                                . ": (\$value . '\$1'))",
                            $data);
                        //Don't get fancy with the second parameter -- PHP5 seems to have a bug
                        //where a backslash is added if you say "$value\{$key}"
                        $data = preg_replace("/\{$key:}.*?{:$key}/s",
                            $value . "{" . $key . "}", $data);
                    }
                    else {
                        $data = preg_replace("/(\{$key(\|(.*?))?})/e",
                            "('\$2' ? Template::preProcess(\$value, '\$3') : \$value)",
                            $data);
                        $data = preg_replace("/\{$key:}.*?\{:$key}/s",
                            $value, $data);
                    }
                }
                elseif (is_null($values[$key])) {
                    trigger_error("Null value passed in for key $key; "
                        . "can't replace this key", E_USER_NOTICE);
                }
                else {
                    trigger_error("Non-scalar passed in for key $key, "
                        . "can't replace this key", E_USER_NOTICE);
                }
            }
            return $data;
        }
        else {
            trigger_error("Parameter '\$values' is not an array", E_USER_ERROR);
        }
        return null;
    } //}}}

    /* {{{preProcess
     */
    function preProcess($val, $fmt) {
        if ($fmt == "") {
            return $val;
        }
        $funcs = explode("|", $fmt);
        $args = explode(",", $funcs[0], 2);

        if (count($funcs) > 1) {
            $val = Template::preProcess($val, implode("|", array_slice($funcs, 1)));
        }
        
        # Check if the function exists before trying to call it
        if (function_exists($args[0])) {
            if (count($args) > 1) {
                eval("\$result = \$args[0](\$val, $args[1]);");
                return $result;
            }
            else {
                return $args[0]($val);
            }
        }
        # The function may be defined as a helper function, too (check static
        # methods of this class to see which ones are defined, starting with an
        # underscore).
        elseif (is_callable(array("Template", $args[0]))) {
            if (count($args) > 1) {
                eval("\$result = Template::$args[0](\$val, $args[1]);");
                return $result;
            }
            else {
                return Template::$args[0]($val);
            }
        }
        else {
            trigger_error("Function $args[0] doesn't exist!  Arguments are '$val', '$fmt'", E_USER_WARNING);
            print_r(debug_backtrace());
            return $val;
        }
    } //}}}

    /* {{{_date_format
     * Helper function to format dates in templates.  The template key should be
     * formatted like {VALUE|_date_format,'Y-m-d'} with single quotes around the
     * argument.
     */
    function _date_format($dateString, $dateFormat) {
        return date($dateFormat, strtotime($dateString));
    } //}}}

    /* {{{_linkify
    */
    function _linkify($text) {
        return preg_replace("#((http(s?)://)|(www.))(\S+[^\s\.])#i",
            '<a target="_blank" href="http$3://$4$5">$2$4$5</a>', $text);
    } //}}}

    /* {{{delete
     */
    function delete($data, $key) {
        return preg_replace("/\{$key:\}.*\{:$key\}/s", "", $data);
    } //}}}

    /* {{{unhide
     * Templates can also be written in the form {KEY:}blablabla{:KEY}.  This
     * call will remove the delimiters, leaving only blablabla in its place.  If
     * you don't call this or replace the KEY key with something (which also
     * converts it to a standard {KEY} key), the call to finalize() will remove
     * the entire thing, including blablabla.
     * 
     * Like with the other functions, this accepts an array of blocks.  If you
     * only pass a single value, it'll be treated as an array of one.
     */
    function unhide($data, $keys) {
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        foreach ($keys as $key) {
            $data = str_replace('{'."$key:}", "", str_replace('{'.":$key}", "", $data));
        }
        return $data;
    } //}}}

    /* {{{extract
     * Extracts a block-delimited template from the text passed in and returns
     * it, without the delimiters.
     */
    function extract($data, $key) {
        $matches = array();
        preg_match("/\{$key:\}(.*?)\{:$key\}/s", $data, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        else {
            return "";
        }
    } //}}}

    /* {{{block
     * This function replaces data in a 'nested' template that's inside block
     * delimiters.  Since the block delimiters will be removed by default when
     * the template is finalized, repetition is the default.  The block can
     * either be a single block or an array of values, in which case the
     * function will iterate over the values and replace them all.
     *
     * Sample call: block($template, "ITEM", $rowFromDatabase);
     */
    function block($data, $blocks, $values, $repeat = 1) {
        # In case the parameters aren't arrays (which they usually won't be),
        # make them into arrays
        if (!is_array($blocks)) {
            $blocks = array($blocks);
        }

        foreach ($blocks as $block) {
            // First extract the block template from the data that's passed in
            preg_match("/\{$block:\}(.*?)\{:$block\}/s", $data, $matches);
            if (!isset($matches[1])) {
                trigger_error("Template block $block doesn't exist", E_USER_NOTICE);
            }
            else {
                # Then use it to generate results
                # (Don't try to combine this into one string.  the leading '{' causes problems.
                #  you may try to escape it, but, then the '\' will show up in the output)
                $data = str_replace('{'.$block.":}$matches[1]{:$block}",
                    Template::replace($matches[1], $values) . ($repeat ? $matches[0] : ""),
                    $data);
            }
        }
        return $data;
    } //}}}

    /* {{{finalize
     * In case not all placeholders in the template got parsed out and replaced
     * with some real data, you should call this method to remove them before
     * spitting the whole thing out to the browser.
     */
    function finalize($data) {
        $data = preg_replace("/\{[A-Za-z\d_]+(\|[^}]+)?\}/", "", $data);
        return preg_replace("/\{([A-Za-z\d_]*):\}.*?\{:\\1}/s", "", $data);
    } //}}}

}

?>
