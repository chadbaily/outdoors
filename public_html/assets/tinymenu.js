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
 * $Id: tinymenu.js,v 1.1.1.1 2005/03/27 19:53:38 bps7j Exp $
 *
 * link is the link the user clicked on to get this menu
 * page is the ?page param
 * uid is the ?object param
 * divId is the ID of the div to create the menu in
 * actions is an array of [[uid, summary],...] actions that are allowed
*/
function tinyMenu(link, page, uid, divId, actions) {
    if (document.getElementById) {
        var div = document.getElementById(divId);
        if (div !== undefined && div != null) {
            showTinyMenu(div, divId);
            div.innerHTML = "";
            for (var a in actions) {
                div.innerHTML += "<a href='members/" + page
                    + "/" + actions[a][0] + "/" + uid + "'>" + actions[a][1] + "</a>";
            }
        }
    }
    return false;
}

function showTinyMenu(div, divId) {
    div.style.visibility = "visible";
    div.style.display = "block";
    div.style.position = "absolute";
    document.tinyMenuDivId = divId;
    setTimeout('document.onclick = function(e) {hideTinyMenu(document.tinyMenuDivId); return true}', 100);
}

function hideTinyMenu(divId) {
    if (document.getElementById) {
        var div = document.getElementById(divId);
        if (div !== undefined) {
            div.style.visibility = "hidden";
            div.style.display = "none";
            div.innerHTML = "";
        }
    }
    document.onclick = null;
}
