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
 * $Id: Text.php,v 1.2 2009/03/12 03:13:36 pctainto Exp $
 */

include_once("CharacterData.php");

class Text extends CharacterData {
        
    // {{{Text
    function Text() {
        $this->CharacterData();
        $this->nodeType = DOM_TEXT_NODE;
    } //}}}

    // {{{splitText
    function splitText($offset) {
        // Check whether to throw Exceptions
        if ($offset < 0 || $offset > $this->length) {
            trigger_error("DOM_INDEX_SIZE_ERR", E_USER_ERROR);
        }
        if ($this->readOnly) {
            trigger_error("DOM_NO_MODIFICATION_ALLOWED_ERR", E_USER_ERROR);
        }
        // Split the text, create a new node, and insert it after this one
        $afterText = substr($this->data, $offset);
        $this->setData(substr($this->data, $offset));
        $newChild = new Text($afterText);
        return $this->parentNode->insertBefore($newChild, $this->nextSibling);
    } //}}}

}

?>
