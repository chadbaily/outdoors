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
 * $Id: TreeNavbar.php,v 1.2 2009/03/12 03:13:36 pctainto Exp $
 */

include_once("TreeNode.php");

class TreeNavbar {
    // {{{declarations
    var $nodes;
    // }}}

    /* {{{constructor
     *
     */
    function TreeNavbar() {
        $this->nodes = array();
    } //}}}

    /* {{{setActiveNode
     */
    function setActiveNode($path) {
        $path = explode("/", $path);
        if (isset($this->nodes[$path[0]])) {
            $this->nodes[$path[0]]->setActiveNode(array_slice($path, 1));
        }
    } //}}}

    function addNode($link, $text, $path = "") {
        if ($path == "") {
            $this->nodes[$text] = new TreeNode($link, $text);
        }
        else {
            $names = explode("/", $path);
            $node = $names[0];
            if (isset($this->nodes[$node])) {
                $this->nodes[$node]->addNode($link, $text,
                    array_slice($names, 1));
            }
        }
    }

    /* {{{toString
     * Returns an HTML string representation of the whole thing.
     */
    function toString() {
        $result = "<ul>";
        foreach (array_keys($this->nodes) as $key) {
            $result .= $this->nodes[$key]->toString();
        }
        return $result . "</ul>";
    }

}

?>
