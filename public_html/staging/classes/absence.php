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
 * $Id: absence.php,v 1.1.1.1 2005/03/27 19:54:30 bps7j Exp $
 */

include_once("database_object.php");

class absence extends database_object {
    # {{{declarations
    var $c_attendee = null;
    var $c_comment = null;
    var $c_severity = null;
    # }}}

    /* {{{constructor
     *
     */
    function absence() {
        $this->database_object();
    } #}}}

    /* {{{getAttendee
     *
     */
    function getAttendee() {
        return $this->c_attendee;
    } #}}}

    /* {{{setAttendee
     *
     */
    function setAttendee($value) {
        $this->c_attendee = $value;
    } #}}}

    /* {{{getComment
     */
    function getComment() {
        return $this->c_comment;
    } //}}}

    /* {{{setComment
     */
    function setComment($value) {
        $this->c_comment = $value;
    } //}}}

    /* {{{getSeverity
     */
    function getSeverity() {
        return $this->c_severity;
    } //}}}

    /* {{{setSeverity
     */
    function setSeverity($value) {
        $this->c_severity = $value;
    } //}}}

}
?>
