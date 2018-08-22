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
 * $Id: checkout.php,v 1.3 2006/03/27 03:46:24 bps7j Exp $
 */

include_once("database_object.php");
include_once("checkout_item.php");
include_once("checkout_gear.php");

class checkout extends database_object {

    var $c_member = null;
    var $c_activity = null;
    var $c_due_date = null;

    /* {{{constructor
     */
    function checkout() {
        $this->database_object();
    } //}}}

    function getMember() {
        return $this->c_member;
    }

    function setMember($value) {
        $this->c_member = $value;
    }

    function getActivity() {
        return $this->c_activity;
    }

    function setActivity($value) {
        $this->c_activity = $value;
    }

    function getDueDate() {
        return $this->c_due_date;
    }

    function setDueDate($value) {
        $this->c_due_date = $value;
    }

}
?>
