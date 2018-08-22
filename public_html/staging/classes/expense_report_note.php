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
 * $Id: expense_report_note.php,v 1.1.1.1 2005/03/27 19:54:27 bps7j Exp $
 */

include_once("database_object.php");

class expense_report_note extends database_object {
    // {{{declarations
    var $c_report = null;
    var $c_new_status = null;
    // }}}

    /* {{{constructor
     *
     */
    function expense_report_note() {
        $this->database_object();
    } //}}}

    /* {{{getReport
     *
     */
    function getReport() {
        return $this->c_report;
    } //}}}
    
    /* {{{setReport
     *
     */
    function setReport($value) {
        $this->c_report = $value;
    } //}}}

    /* {{{getNewStatus
     */
    function getNewStatus() {
        return $this->c_new_status;
    } //}}}

    /* {{{setNewStatus
     */
    function setNewStatus($value) {
        $this->c_new_status = $value;
    } //}}}

}
?>
