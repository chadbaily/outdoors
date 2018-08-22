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
 * $Id: Email.php,v 1.3 2006/03/27 03:46:25 bps7j Exp $
 */

class Email {
    // {{{declarations
    // This is an array.  Do not set it directly, use the addXXX method.
    var $to;
    var $headers;
    var $bcc;
    var $subject        = "";
    var $body           = "";
    var $footer         = "";

    var $nl             = "\r\n";
    var $subjectPrefix  = "";
    var $wordWrap       = false;
    var $wrapCol        = 64;
    // }}}

    /* {{{constructor
     *
     */
    function Email() {
        $this->to = array();
        $this->headers = array();
        $this->bcc = array();
    } //}}}

    /* {{{toString
     *
     */
    function toString() {
        return
            "To: " . implode(",", $this->to)
            . "{$this->nl}Subject: $this->subject"
            . (count($this->headers) ? $this->nl . implode($this->nl, $this->headers) : "")
            . $this->nl . $this->nl
            . ($this->wordWrap
                ? wordwrap($this->body, $this->wrapCol)
                : $this->body)
            . $this->footer;
    } //}}}

    /* {{{send
     *
     */
    function send() {
        global $cfg;
        $result = true;

        $headers = count($this->headers)
            ? implode($this->nl, $this->headers)
            : "";
        if (count($this->bcc)) {
            $headers .= $this->nl . implode($this->nl, $this->bcc);
        }

        if ($cfg['send_emails']) {
            $result = mail(
                // Parameter 1: to
                implode(",", $this->to),
                // Parameter 2: subject
                $this->subjectPrefix . $this->subject,
                // Parameter 3: text
                ($this->wordWrap
                    ? wordwrap($this->body, $this->wrapCol)
                    : $this->body)
                . $this->footer,
                // Parameter 4: additional headers
                $headers);
        }
        return $result;
    } //}}}

    /* {{{loadFooter
     * Loads a text file as a footer for the email.
     */
    function loadFooter($file) {
        $this->footer = file_get_contents($file);
    } //}}}

    /* {{{getTo
     *
     */
    function getTo() {
        return $this->to;
    } //}}}

    /* {{{addTo
     *
     */
    function addTo($email) {
        $this->to[] = $email;
    } //}}}

    /* {{{addBCC
     *
     */
    function addBCC($email) {
        $this->bcc[] = "Bcc: $email";
    } //}}}

    /* {{{addCC
     *
     */
    function addCC($email) {
        $this->headers[] = "Cc: $email";
    } //}}}

    /* {{{addHeader
     */
    function addHeader($name, $val) {
        $this->headers[] = preg_replace("/[^a-zA-Z0-9]+/", "-", $name)
            . ": $val";
    } //}}}

    /* {{{setFrom
     *
     */
    function setFrom($value) {
        $this->headers[] = "From: $value";
    } //}}}

    /* {{{setSubject
     *
     */
    function setSubject($value) {
        $this->subject = $value;
    } //}}}

    /* {{{setBody
     */
    function setBody($value) {
        $this->body = $value;
    } //}}}

    /* {{{setWordWrap
     *
     */
    function setWordWrap($value) {
        $this->wordWrap = $value;
    } //}}}

    /* {{{setWordWrapWidth
     *
     */
    function setWordWrapWidth($value) {
        $this->wrapCol = $value;
    } //}}}

}
?>
