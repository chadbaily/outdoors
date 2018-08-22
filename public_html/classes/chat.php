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
 * $Id: chat.php,v 1.3 2005/08/05 00:46:09 bps7j Exp $
 */

include_once("database_object.php");

class chat extends database_object {
    // {{{declarations
    var $c_screenname = null;
    var $c_type = null;
    var $c_primary = null;
    var $c_hidden = null;
    // }}}

    /* {{{constructor
     *
     */
    function chat() {
        $this->database_object();
    } //}}}

    /* {{{getScreenName
     *
     */
    function getScreenName() {
        return $this->c_screenname;
    } //}}}

    /* {{{setScreenName
     *
     */
    function setScreenName($value) {
        $this->c_screenname = $value;
    } //}}}

    /* {{{getPrimary
     *
     */
    function getPrimary() {
        return $this->c_primary;
    } //}}}

    /* {{{setPrimary
     *
     */
    function setPrimary($value) {
        $this->c_primary = $value;
    } //}}}

    /* {{{getType
     *
     */
    function getType() {
        return $this->c_type;
    } //}}}

    /* {{{setType
     *
     */
    function setType($value) {
        $this->c_type = $value;
    } //}}}

    /* {{{getHidden
     *
     */
    function getHidden() {
        return $this->c_hidden;
    } //}}}

    /* {{{setHidden
     *
     */
    function setHidden($value) {
        $this->c_hidden = $value;
    } //}}}

}
?>
