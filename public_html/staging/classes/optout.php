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
 * $Id: optout.php,v 1.2 2009/11/14 22:51:10 pctainto Exp $
 */

include_once("database_object.php");

class optout extends database_object {
    // {{{declarations
    var $c_member = null;
    var $c_category = null;
    var $c_category_type = null;
    // }}}

    /* {{{constructor
     *
     */
    function optout() {
        $this->database_object();
    } //}}}

    /* {{{getMember
     *
     */
    function getMember() {
        return $this->c_member;
    } //}}}
    
    /* {{{setMember
     *
     */
    function setMember($value) {
        $this->c_member = $value;
    } //}}}

    /* {{{getCategory
     *
     */
    function getCategory() {
        return $this->c_category;
    } //}}}

    /* {{{setCategory
     *
     */
    function setCategory($value) {
        $this->c_category = $value;
    } //}}}

    /* {{{getCategoryType
     *
     */
    function getCategoryType() {
        return $this->c_category_type;
    } //}}}

    /* {{{setCategoryType
     *
     */
    function setCategoryType($value) {
        $this->c_category_type = $value;
    } //}}}

    /* {{{getCategoryTypeAndID
     *
     */
    function getCategoryTypeAndID() {
        return $this->getCategoryType() . "_" . $this->getCategory();
    } //}}}

    /* {{{setCategoryTypeAndID
     *
     */
    function setCategoryTypeAndID($value) {
        preg_match("/([^_]+)_([^_]+)/",$value,$matches);
        $this->setCategoryType($matches{1});
        $this->setCategory($matches{2});
    } //}}}


}
?>
