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
 * $Id: report.php,v 1.2 2005/06/05 16:13:38 bps7j Exp $
 */

include_once("database_object.php");

class report extends database_object {
    // {{{declarations
    var $c_title = null; // String
    var $c_description = null; // String
    var $c_query = null; // String
    // }}}

    /* {{{constructor
     *
     */
    function report() {
        $this->database_object();
    } //}}}

    /* {{{execute
     * This method returns a recordset with the results of the report.
     */
    function execute() {
        global $obj;
        return $obj['conn']->query($this->c_query);
    } //}}}

    /* {{{getTitle
     *
     */
    function getTitle() {
        return $this->c_title;
    } //}}}

    /* {{{setTitle
     *
     */
    function setTitle($value) {
        $this->c_title = $value;
    } //}}}

    /* {{{getDescription
     *
     */
    function getDescription() {
        return $this->c_description;
    } //}}}

    /* {{{setDescription
     *
     */
    function setDescription($value) {
        $this->c_description = $value;
    } //}}}

    /* {{{getQuery
     *
     */
    function getQuery() {
        return $this->c_query;
    } //}}}

    /* {{{setQuery
     *
     */
    function setQuery($value) {
        $this->c_query = $value;
    } //}}}

    /* {{{checkForAlter
     * Returns an array of any bad words in the query.
     */
    function checkForAlter() {
        $badWords = str_replace(" ", "\b|\b",
            "INSERT UPDATE DELETE TRUNCATE REPLACE CREATE DROP ALTER RENAME"
            . " EXPLAIN DESCRIBE OPTIMIZE ANALYZE FLUSH RESET PURGE KILL SHOW"
            . " GRANT REVOKE");
        $matches = array();
        preg_match_all("/\b$badWords\b/i", $this->c_query, $matches);
        return $matches[0];
    } //}}}

}
?>
