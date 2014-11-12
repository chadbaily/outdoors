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
 * $Id: TreeNode.php,v 1.2 2009/03/12 03:13:36 pctainto Exp $
 */

class TreeNode {
    // {{{declarations
    var $link = "";
    var $text = "";
    var $isActive = false;
    var $children = null;
    // }}}

    /* {{{constructor
     *
     */
    function TreeNode($link, $text) {
        $this->link = $link;
        $this->text = $text;
        $this->children = array();
    } //}}}

    function addNode($link, $text, $path = array()) {
        if (!count($path)) {
            $this->children[$text] = new TreeNode($link, $text);
        }
        else {
            if (isset($this->children[$path[0]])) {
                $this->children[$path[0]]->addNode($link, $text,
                    array_slice($path, 1));
            }
        }
    }

    /* {{{setActiveNode
     */
    function setActiveNode($path) {
        $this->isActive = true;
        if (count($path) && isset($this->children[$path[0]])) {
            $this->children[$path[0]]->setActiveNode(array_slice($path, 1));
        }
    } //}}}

    /* {{{toString
     * Returns an HTML string representation of the whole thing.
     */
    function toString() {
        $class = ($this->isActive ? " class='active'" : "");
        $result = "\r\n<li><a href='$this->link'$class>$this->text</a>";
        if ($this->isActive && count($this->children)) {
            $result .= "\r\n<ul>";
            foreach (array_keys($this->children) as $key) {
                $result .= $this->children[$key]->toString();
            }
            $result .= "\r\n</ul>";
        }
        return $result . "</li>";
    }

}

?>
