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
 * $Id: transaction.php,v 1.1.1.1 2005/03/27 19:54:29 bps7j Exp $
 */

include_once("database_object.php");

class transaction extends database_object {
    // {{{declarations
    var $c_category = null;
    var $c_from = null;
    var $c_to = null;
    var $c_amount = null;
    var $c_description = null;
    var $c_expense = null;
    // }}}

    /* {{{constructor
     */
    function transaction() {
        $this->database_object();
    } //}}}

    /* {{{getCategory
     */
    function getCategory() {
        return $this->c_category;
    } //}}}

    /* {{{setCategory
     */
    function setCategory($value) {
        $this->c_category = $value;
    } //}}}

    /* {{{getFrom
     */
    function getFrom() {
        return $this->c_from;
    } //}}}

    /* {{{setFrom
     */
    function setFrom($value) {
        $this->c_from = $value;
    } //}}}

    /* {{{getTo
     */
    function getTo() {
        return $this->c_to;
    } //}}}

    /* {{{setTo
     */
    function setTo($value) {
        $this->c_to = $value;
    } //}}}

    /* {{{getAmount
     */
    function getAmount() {
        return $this->c_amount;
    } //}}}

    /* {{{setAmount
     */
    function setAmount($value) {
        $this->c_amount = $value;
    } //}}}

    /* {{{getDescription
     */
    function getDescription() {
        return $this->c_description;
    } //}}}

    /* {{{setDescription
     */
    function setDescription($value) {
        $this->c_description = $value;
    } //}}}

    /* {{{getExpense
     */
    function getExpense() {
        return $this->c_expense;
    } //}}}

    /* {{{setExpense
     */
    function setExpense($value) {
        $this->c_expense = $value;
    } //}}}

}
?>
