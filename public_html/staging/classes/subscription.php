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
 * $Id: subscription.php,v 1.1.1.1 2005/03/27 19:54:25 bps7j Exp $
 */

include_once("database_object.php");

class subscription extends database_object {
    // {{{declarations
    var $c_list = null;
    var $c_email = null;
    var $c_password = null;
    // }}}

    /* {{{constructor
     *
     */
    function subscription() {
        $this->database_object();
    } //}}}

    /* {{{getList
     *
     */
    function getList() {
        return $this->c_list;
    } //}}}
    
    /* {{{setList
     *
     */
    function setList($value) {
        $this->c_list = $value;
    } //}}}

    /* {{{getEmail
     *
     */
    function getEmail() {
        return $this->c_email;
    } //}}}
    
    /* {{{setEmail
     *
     */
    function setEmail($value) {
        $this->c_email = $value;
    } //}}}

    /* {{{getPassword
     *
     */
    function getPassword() {
        return $this->c_password;
    } //}}}
    
    /* {{{setPassword
     *
     */
    function setPassword($value) {
        $this->c_password = $value;
    } //}}}

}
?>
