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
 * $Id: phone_number.php,v 1.3 2005/08/05 00:46:09 bps7j Exp $
 */

include_once("database_object.php");

class phone_number extends database_object {
    // {{{declarations
    var $c_type = null;
    var $c_title = null;
    var $c_country_code = null;
    var $c_area_code = null;
    var $c_exchange = null;
    var $c_number = null;
    var $c_extension = null;
    var $c_phone_number = null;
    var $c_primary = null;
    var $c_hidden = null;
    // }}}

    /* {{{constructor
     *
     */
    function phone_number() {
        $this->database_object();
    } //}}}

    /* {{{toString
     *
     */
    function toString() {
        return "($this->c_area_code) $this->c_exchange-$this->c_number";
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
        $this->prepPhoneNumber();
    } //}}}

    /* {{{getCountryCode
     *
     */
    function getCountryCode() {
        return $this->c_country_code;
    } //}}}
                               
    /* {{{setCountryCode
     *
     */
    function setCountryCode($value) {
        $this->c_country_code = $value;
        $this->prepPhoneNumber();
    } //}}}

    /* {{{getAreaCode
     *
     */
    function getAreaCode() {
        return $this->c_area_code;
    } //}}}

    /* {{{setAreaCode
     *
     */
    function setAreaCode($value) {
        $this->c_area_code = $value;
        $this->prepPhoneNumber();
    } //}}}

    /* {{{getExchange
     *
     */
    function getExchange() {
        return $this->c_exchange;
    } //}}}

    /* {{{setExchange
     *
     */
    function setExchange($value) {
        $this->c_exchange = $value;
        $this->prepPhoneNumber();
    } //}}}

    /* {{{getNumber
     *
     */
    function getNumber() {
        return $this->c_number;
    } //}}}

    /* {{{setNumber
     *
     */
    function setNumber($value) {
        $this->c_number = $value;
        $this->prepPhoneNumber();
    } //}}}

    /* {{{getExtension
     *
     */
    function getExtension() {
        return $this->c_extension;
    } //}}}

    /* {{{setExtension
     *
     */
    function setExtension($value) {
        $this->c_extension = $value;
        $this->prepPhoneNumber();
    } //}}}

    /* {{{prepPhoneNumber
     * This is "private" in that it's called to update c_phone_number whenever
     * there's a change to one of its constituent parts...
     */
    function prepPhoneNumber() {
        $this->c_phone_number = "($this->c_area_code)"
            . " $this->c_exchange-$this->c_number"
            . ($this->c_extension ? " ext. $this->c_extension" : "");
    } //}}}

    /* {{{getPhoneNumber
     */
    function getPhoneNumber() {
        return $this->c_phone_number;
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
