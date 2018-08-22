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
 * $Id: Document.php,v 1.3 2009/03/12 03:13:36 pctainto Exp $
 */

include_once("Node.php");
include_once("Element.php");
include_once("Text.php");

class Document extends Node {

    var $documentType;
    var $implementation;
    var $documentElement;
    // lookup table for retrieving elements by ID
    var $idCache = array();

    function Document() {
        $this->Node();
        $this->nodeType = DOM_DOCUMENT_NODE;
    }

    function createElement($tagName) {
        $element = new Element();
        $element->tagName = $tagName;
        $element->ownerDocument = $this;
        return $element;
    }

    function createDocumentFragment() {
        $fragment = new DocumentFragment();
        $fragment->ownerDocument = $this;
        return $fragment;
    }

    function createTextNode($data) {
        $text = new Text();
        $text->data = $data;
        $text->ownerDocument = $this;
        return $text;
    }

    function getElementsByTagName($tagname) {
        return $this->documentElement->getElementsByTagName($tagname);
    }


    function getElementByID($elementID) {
        if (isset($this->idCache[$elementID])) {
            return $this->idCache[$elementID];
        }
    }

    function addNodeToLookupCache(&$node) {
        if ($node->nodeType == DOM_ELEMENT_NODE
            && $node->getAttribute("id") != null) {
            if (isset($this->idCache[$node->attributes['id']])) {
                trigger_error("There is already a node with id '{$node->attributes['id']}'", 
                    E_USER_ERROR);
            }
            $this->idCache[$node->attributes['id']] = $node;
        }
    }

    function appendChild(&$newChild) {
        // Check to make sure there are none; throw exeption if so
        if (count($this->childNodes)) {
            trigger_error("The Document node can only have one child", E_USER_ERROR);
        }
        $this->childNodes[] = $newChild;
        $numNodes = count($this->childNodes);
        $this->lastChild = $newChild;
        $this->documentElement = $newChild;
        $this->firstChild = $newChild;
        $newChild->parentNode = $this;
        $this->addNodeToLookupCache($newChild);
        return $newChild;
    }

}

?>
