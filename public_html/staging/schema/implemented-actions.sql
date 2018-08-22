/*
 * This file is part of SocialClub (http://socialclub.sourceforge.net)
 * Copyright (C) 2004 Baron Schwartz <baron at sequent dot org>
 *
 * This program is free software.  You can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY, without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307  USA
 *
 * $Id: implemented-actions.sql,v 1.7 2006/03/27 03:46:25 bps7j Exp $
 *
 * Create correspondences between tables and actions to say which actions apply
 * to which tables, and in what status.
 */

-- Declare status value constants.
set @ns_default = 1, @ns_inactive = 2, @ns_active = 4,
    @ns_waitlisted = 8, @ns_cancelled = 16, @ns_pending = 32, @ns_paid = 64,
    @ns_checked_out = 128, @ns_checked_in = 256, @ns_missing = 512,
    @ns_submitted = 1024;

delete from [_]implemented_action;

-- These actions apply to ALL tables.  These are the defaults.
insert into [_]implemented_action (c_table, c_action)
    select c_name, c_title
    from [_]action, [_]table
    where c_title in ("read", "write", "delete", "list_all", "list_owned_by",
        "create", "stat", "chmod", "chgrp", "chown", "chmeta", "view_acl",
        "copy", "add_privilege");

insert into [_]implemented_action (c_table, c_action, c_status)
values 
    ("[_]checkout", "accept", @ns_default),
    ("[_]checkout", "check_in", @ns_checked_out);
-- Only allow editing when it is in default status.
update [_]implemented_action set c_status = @ns_default
where c_table = "[_]checkout" and c_action = "write";

insert into [_]implemented_action (c_table, c_action, c_status)
values
    ("[_]expense_submission", "accept", @ns_submitted),
    ("[_]expense_submission", "submit", @ns_default);
-- Only allow editing when it is in default status.
update [_]implemented_action set c_status = @ns_default
where c_table = "[_]expense_submission" and c_action = "write";

insert into [_]implemented_action (c_table, c_action, c_status)
values
    ("[_]adventure", "join", @ns_default | @ns_active | @ns_inactive),
    ("[_]adventure", "withdraw", @ns_active),
    ("[_]adventure", "edit_questions", 0),
    ("[_]adventure", "choose_activities", 0),
    ("[_]adventure", "view_report", 0),
    ("[_]adventure", "announce", @ns_active),
    ("[_]adventure", "activate", @ns_default | @ns_inactive | @ns_cancelled),
    ("[_]adventure", "email_attendees", 0),
    ("[_]adventure", "comment", @ns_default | @ns_active),
    ("[_]adventure", "view_waitlist", @ns_default | @ns_active);

insert into [_]implemented_action (c_table, c_action, c_status)
values
    ("[_]attendee", "withdraw", @ns_default | @ns_active | @ns_waitlisted),
    ("[_]attendee", "join", @ns_waitlisted),
    ("[_]attendee", "waitlist", @ns_active | @ns_default),
    ("[_]attendee", "mark_absent", @ns_active | @ns_default),
    ("[_]attendee", "view_answers", 0);

insert into [_]implemented_action (c_table, c_action)
    select "[_]item_type", c_title from [_]action
    where c_title in ("edit_features");

insert into [_]implemented_action (c_table, c_action)
    select "[_]item", c_title from [_]action
    where c_title in ("edit_features", "view_notes", "view_history");

insert into [_]implemented_action (c_table, c_action)
    select "[_]location", c_title from [_]action
    where c_title in ("choose_activities");

insert into [_]implemented_action (c_table, c_action, c_status)
values
    ("[_]membership", "activate", @ns_inactive);

insert into [_]implemented_action (c_table, c_action)
    select "[_]member", c_title from [_]action
    where c_title in ("chgrp_secondary", "view_absences", "su",
        "change_password", "view_history", "choose_activities", "view_notes",
        "optout", "view_waitlist");

insert into [_]implemented_action (c_table, c_action)
    select "[_]report", c_title from [_]action
    where c_title in ("execute");

insert into [_]implemented_action (c_table, c_action)
    select "[_]subscription", c_title from [_]action
    where c_title in ("unsubscribe");

insert into [_]implemented_action (c_table, c_action)
    select "[_]email_list", c_title from [_]action
    where c_title in ("subscribe", "view_members");

insert into [_]implemented_action (c_table, c_action, c_status)
values
    ("[_]classified_ad", "deactivate", @ns_default | @ns_active);

insert into [_]implemented_action (c_table, c_action, c_status)
values
    ("[_]expense_report", "submit", @ns_default),
    ("[_]expense_report", "accept", @ns_pending),
    ("[_]expense_report", "view_notes", 0);
