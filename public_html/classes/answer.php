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
 * $Id: answer.php,v 1.1.1.1 2005/03/27 19:54:29 bps7j Exp $
 */

include_once("database_object.php");

class answer extends database_object {
    // {{{declarations
    var $c_question = null;
    var $c_attendee = null;
    var $c_answer_text = null;
    // }}}

    /* {{{constructor
     *
     */
    function answer() {
        $this->database_object();
    } //}}}

    /* {{{getQuestion
     *
     */
    function getQuestion() {
        return $this->c_question;
    } //}}}

    /* {{{setQuestion
     *
     */
    function setQuestion($value) {
        $this->c_question = $value;
    } //}}}

    /* {{{getAttendee
     *
     */
    function getAttendee() {
        return $this->c_attendee;
    } //}}}

    /* {{{setAttendee
     *
     */
    function setAttendee($value) {
        $this->c_attendee = $value;
    } //}}}

    /* {{{getAnswerText
     *
     */
    function getAnswerText() {
        return $this->c_answer_text;
    } //}}}

    /* {{{setAnswerText
     *
     */
    function setAnswerText($value) {
        $this->c_answer_text = $value;
    } //}}}

}
?>
