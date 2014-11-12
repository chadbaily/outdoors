<?php
/*
 * This software implements a simplified DOM interface for PHP.
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
 * $Id: Element.php,v 1.2 2005/08/02 23:46:18 bps7j Exp $
 */

include_once("Node.php");

class Element extends Node {

    var $tagName = null;

    function Element() {
        $this->Node();
        $this->nodeType = DOM_ELEMENT_NODE;
        $this->attributes = array();
    }

    function getAttribute($name) {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    function setAttribute($name, $value) {
        if ($this->containsInvalidCharacter($name)) {
            trigger_error("DOM_INVALID_CHARACTER_ERR", E_USER_ERROR);
        }
        if ($this->isReadOnly()) {
            trigger_error("DOM_NO_MODIFICATION_ALLOWED_ERR", E_USER_ERROR);
        }
        $this->attributes[$name] = "$value";
    }

    function removeAttribute($name) {
        if ($this->isReadOnly()) {
            trigger_error("DOM_NO_MODIFICATION_ALLOWED_ERR", E_USER_ERROR);
        }
        unset($this->attributes[$name]);
    }

    function getElementsByTagName($name) {
        $result = array();
        if ($name === "*") {
            $result[] =& $this;
        }
        elseif (isset($this->tagName) && $this->tagName === $name) {
            $result[] =& $this;
        }
        foreach (array_keys($this->childNodes) as $key) {
            if ($this->childNodes[$key]->nodeType == DOM_ELEMENT_NODE) {
                $result = array_merge($result,
                    $this->childNodes[$key]->getElementsByTagName($name));
            }
        }
        return $result;
    }

}

?>
