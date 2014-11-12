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
 * $Id: expense_report.php,v 1.2 2009/03/12 03:16:00 pctainto Exp $
 */

include_once("database_object.php");
include_once("expense_report_note.php");

class expense_report extends database_object {

    var $c_member = null;

    /* {{{constructor
     */
    function expense_report() {
        $this->database_object();
    } //}}}

    function getMember() {
        return $this->c_member;
    }

    function setMember($value) {
        $this->c_member = $value;
    }

    function addNote() {
        $this->ensureUID(__LINE__, __FILE__);
        $note = new expense_report_note();
        $note->setReport($this->c_uid);
        $note->setNewStatus($this->c_status);
        $note->insert();
    }

}
?>
