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
 * $Id: address.php,v 1.3 2005/08/05 00:46:09 bps7j Exp $
 */

include_once("database_object.php");

class address extends database_object {
    // {{{declarations
    var $c_title = null;
    var $c_street = null;
    var $c_city = null;
    var $c_state = null;
    var $c_zip = null;
    var $c_country = null;
    var $c_primary = null;
    var $c_hidden = null;
    var $phoneNumbers;
    // }}}

    /* {{{constructor
     *
     */
    function address() {
        $this->database_object();
    } //}}}

    /* {{{getLine1
     *
     */
    function getLine1() {
        return $this->c_street;
    } //}}}

    /* {{{getLine2
     *
     */
    function getLine2() {
        return "$this->c_city, $this->c_state $this->c_zip";
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

    /* {{{getStreet
     *
     */
    function getStreet() {
        return $this->c_street;
    } //}}}

    /* {{{setStreet
     *
     */
    function setStreet($value) {
        $this->c_street = $value;
    } //}}}

    /* {{{getCity
     *
     */
    function getCity() {
        return $this->c_city;
    } //}}}

    /* {{{setCity
     *
     */
    function setCity($value) {
        $this->c_city = $value;
    } //}}}

    /* {{{getState
     *
     */
    function getState() {
        return $this->c_state;
    } //}}}

    /* {{{setState
     *
     */
    function setState($value) {
        $this->c_state = $value;
    } //}}}

    /* {{{getZIP
     *
     */
    function getZIP() {
        return $this->c_zip;
    } //}}}

    /* {{{setZIP
     *
     */
    function setZIP($value) {
        $this->c_zip = $value;
    } //}}}

    /* {{{getCountry
     *
     */
    function getCountry() {
        return $this->c_country;
    } //}}}

    /* {{{setCountry
     *
     */
    function setCountry($value) {
        $this->c_country = $value;
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
