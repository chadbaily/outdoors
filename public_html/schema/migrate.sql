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
 * $Id: migrate.sql,v 1.3 2005/08/02 03:04:19 bps7j Exp $
 *
 */

--------------------------------------------------------------------------------
-- This file may be useful for copying the data from previous versions of the
-- database schema, prior to version 2005-07-13.  Do a sed command to replace
-- the table prefixes.  Assuming you are migrating from o_ to n_, do this:
-- sed -e "s/\[old]/o_/g" migrate.sql | sed -e "s/\[new]/n_/g" | mysql dbname
-- This assumes that you have already run the setup script located in the same
-- directory as this script.
--------------------------------------------------------------------------------

--------------------------------------------------------------------------------
-- Declare constants.  Old values are prefixed with o, new ones with n.
--------------------------------------------------------------------------------

-- Declare group constants.
set @og_root = 1, @og_officer = 2, @og_quartermaster = 5, @og_member = 6,
    @og_guest = 7, @og_wheel = 8, @og_treasurer = 3, @og_leader = 4;
set @ng_root = 1, @ng_officer = 2, @ng_quartermaster = 16, @ng_member = 32,
    @ng_guest = 64, @ng_wheel = 128, @ng_treasurer = 4, @ng_leader = 8;

-- Declare flag constants.
set @of_private = 1, @of_email_private = 2, @of_imported = 4, @of_student = 8,
    @of_flexible = 16, @of_has_photo = 32, @of_receive_email = 64,
    @of_reimbursable = 128, @of_applies_to_object = 256, @of_primary = 512,
    @of_member_agreement = 1024, @of_generic = 2048;

-- Declare status value constants.
set @ns_default = 1, @ns_inactive = 2, @ns_active = 4,
    @ns_waitlisted = 8, @ns_cancelled = 16, @ns_pending = 32, @ns_paid = 64,
    @ns_checked_out = 128, @ns_checked_in = 256, @ns_missing = 512,
    @ns_submitted = 1024;
set @os_default = 1, @os_deleted = 2, @os_inactive = 3, @os_active = 4,
    @os_waitlisted = 5, @os_cancelled = 6, @os_pending = 7, @os_paid = 8,
    @os_checked_out = 9, @os_checked_in = 10, @os_missing = 11,
    @os_submitted = 12;

--------------------------------------------------------------------------------
-- Done with constants.
--------------------------------------------------------------------------------

delete from [new]absence;
insert into [new]absence (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_attendee, c_comment, c_severity)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_attendee, c_comment, c_severity
from [old]absence;

delete from [new]activity;
insert into [new]activity (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_category)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_category
from [old]activity;

delete from [new]activity_category;
insert into [new]activity_category (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title
from [old]activity_category;

delete from [new]address;
insert into [new]address (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_street, c_city, c_state, c_zip, c_country,
        c_primary, c_hidden)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_street, c_city, c_state, c_zip, c_country,
    case when c_flags & @of_primary then 1 else 0 end,
    case when c_flags & @of_private then 1 else 0 end
from [old]address;

delete from [new]adventure;
insert into [new]adventure (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_fee, c_max_attendees, c_signup_date, c_title, c_description,
        c_start_date, c_end_date, c_departure, c_destination, c_average_rating,
        c_num_ratings)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_fee, c_max_attendees, c_signup_date, c_title, c_description,
    c_start_date, c_end_date, c_departure, c_destination, c_average_rating,
    c_num_ratings
from [old]adventure;

delete from [new]adventure_comment;
insert into [new]adventure_comment (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_adventure, c_rating, c_subject, c_text, c_anonymous)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_adventure, c_rating, c_subject, c_text,
    case when c_flags & @of_private then 1 else 0 end
from [old]adventure_comment;

delete from [new]adventure_activity;
insert into [new]adventure_activity (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_adventure, c_activity)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_adventure, c_activity
from [old]adventure_activity;

delete from [new]answer;
insert into [new]answer (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_question, c_attendee, c_answer_text)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_question, c_attendee, c_answer_text
from [old]answer;

delete from [new]attendee;
insert into [new]attendee (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_adventure, c_member, c_amount_paid, c_joined_date)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_adventure, c_member, c_amount_paid, c_joined_date
from [old]attendee;

delete from [new]chat;
insert into [new]chat (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_screenname, c_type, c_primary, c_hidden)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_screenname, c_type,
    case when c_flags & @of_primary then 1 else 0 end,
    case when c_flags & @of_private then 1 else 0 end
from [old]chat;

delete from [new]chat_type;
insert into [new]chat_type (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_abbreviation, c_description)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_abbreviation, c_description
from [old]chat_type;

delete from [new]checkout;
insert into [new]checkout (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_member, c_activity)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_member, c_activity
from [old]checkout;

delete from [new]checkout_gear;
insert into [new]checkout_gear (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_checkout, c_type, c_qty, c_description, c_checkin_member)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_checkout, c_type, c_qty, c_description, c_checkin_member
from [old]checkout_gear;

delete from [new]checkout_item;
insert into [new]checkout_item (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_checkout, c_item)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_checkout, c_item
from [old]checkout_item;

delete from [new]classified_ad;
insert into [new]classified_ad (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_text)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_text
from [old]classified_ad;

delete from [new]email_list;
insert into [new]email_list (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_description, c_name, c_password, c_owner_address,
        c_mgmt_address, c_list_address, c_type, c_subject_prefix)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_description, c_name, c_password, c_owner_address, c_mgmt_address,
    c_list_address, c_type, c_subject_prefix
from [old]email_list;

delete from [new]expense;
insert into [new]expense (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_report, c_category, c_expense_date, c_adventure, c_merchant,
        c_description, c_amount, c_reimbursable)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_report, c_category, c_expense_date, c_adventure, c_merchant,
    c_description, c_amount,
    case when c_flags & @of_reimbursable then 1 else 0 end
from [old]expense;

delete from [new]expense_report;
insert into [new]expense_report (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_member)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_member
from [old]expense_report;

delete from [new]expense_report_note;
insert into [new]expense_report_note (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_report, c_new_status)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_report, c_new_status
from [old]expense_report_note;

delete from [new]expense_submission;
insert into [new]expense_submission (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end
from [old]expense_submission;

delete from [new]expense_submission_expense;
insert into [new]expense_submission_expense (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted,
        c_submission, c_expense)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_submission, c_expense
from [old]expense_submission_expense;

delete from [new]interest;
insert into [new]interest (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_member, c_activity)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_member, c_activity
from [old]interest;

delete from [new]item;
insert into [new]item (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_condition, c_type, c_description, c_purchase_date, c_qty)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_condition, c_type, c_description, c_purchase_date, c_qty
from [old]item;

delete from [new]item_note;
insert into [new]item_note (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_item, c_condition, c_note)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_item, c_condition, c_note
from [old]item_note;

delete from [new]item_feature;
insert into [new]item_feature (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_item, c_name, c_value)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_item, c_name, c_value
from [old]item_feature;

delete from [new]item_category;
insert into [new]item_category (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title
from [old]item_category;

delete from [new]item_type_feature;
insert into [new]item_type_feature (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_type, c_name)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_type, c_name
from [old]item_type_feature;

delete from [new]item_type;
insert into [new]item_type (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_category, c_primary_feature, c_secondary_feature)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_category, c_primary_feature, c_secondary_feature
from [old]item_type;

delete from [new]location;
insert into [new]location (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_description, c_zip_code)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_description, c_zip_code
from [old]location;

delete from [new]location_activity;
insert into [new]location_activity (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_location, c_activity)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_location, c_activity
from [old]location_activity;

delete from [new]member;
insert into [new]member (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_email, c_password, c_first_name, c_last_name, c_full_name,
        c_birth_date, c_gender, c_group_memberships, c_is_student,
        c_receive_email, c_hidden, c_email_hidden)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_email, c_password, c_first_name, c_last_name, c_full_name, c_birth_date,
    c_gender, 0,
    case when c_flags & @of_student then 1 else 0 end,
    case when c_flags & @of_receive_email then 1 else 0 end,
    case when c_flags & @of_private then 1 else 0 end,
    case when c_flags & @of_email_private then 1 else 0 end
from [old]member;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_root
where c_related_group = @og_root;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_officer
where c_related_group = @og_officer;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_quartermaster
where c_related_group = @og_quartermaster;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_member
where c_related_group = @og_member;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_guest
where c_related_group = @og_guest;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_wheel
where c_related_group = @og_wheel;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_treasurer
where c_related_group = @og_treasurer;

update m_member
    inner join t_member_group on t_member_group.c_member = m_member.c_uid
set c_group_memberships = c_group_memberships + @ng_leader
where c_related_group = @og_leader;

delete from [new]member_note;
insert into [new]member_note (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_member, c_note)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_member, c_note
from [old]member_note;

delete from [new]membership;
insert into [new]membership (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_member, c_type, c_begin_date, c_expiration_date, c_units_granted,
        c_unit, c_total_cost, c_amount_paid)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_member, c_type, c_begin_date, c_expiration_date, c_units_granted, c_unit,
    c_total_cost, c_amount_paid
from [old]membership;

delete from [new]membership_type;
insert into [new]membership_type (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_title, c_description, c_begin_date, c_expiration_date, c_show_date,
        c_hide_date, c_units_granted, c_unit, c_unit_cost, c_total_cost,
        c_flexible, c_hidden)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_title, c_description, c_begin_date, c_expiration_date, c_show_date,
    c_hide_date, c_units_granted, c_unit, c_unit_cost, c_total_cost,
    case when c_flags & @of_private then 1 else 0 end,
    case when c_flags & @of_flexible then 1 else 0 end
from [old]membership_type;

delete from [new]optout;
insert into [new]optout (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_member, c_category)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_member, c_category
from [old]optout;

delete from [new]phone_number;
insert into [new]phone_number (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_type, c_country_code, c_area_code, c_exchange, c_number,
        c_extension, c_phone_number, c_title, c_primary, c_hidden)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_type, c_country_code, c_area_code, c_exchange, c_number,
    c_extension, c_phone_number, c_title,
    case when c_flags & @of_primary then 1 else 0 end,
    case when c_flags & @of_private then 1 else 0 end
from [old]phone_number;

delete from [new]question;
insert into [new]question (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_adventure, c_type, c_text)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_adventure, c_type, c_text
from [old]question;

delete from [new]subscription;
insert into [new]subscription (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_list, c_email, c_password)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_list, c_email, c_password
from [old]subscription;

delete from [new]transaction;
insert into [new]transaction (
        c_uid, c_owner, c_creator, c_group, c_unixperms,
        c_created_date, c_last_modified, c_status, c_deleted, 
        c_category, c_amount, c_from, c_to, c_description, c_expense,
        c_reimbursable)
select c_uid, c_owner, c_creator,
    case when c_group = @og_root then @ng_root
        when c_group = @og_officer then @ng_officer
        when c_group = @og_quartermaster then @ng_quartermaster
        when c_group = @og_member then @ng_member
        when c_group = @og_guest then @ng_guest
        when c_group = @og_wheel then @ng_wheel
        when c_group = @og_treasurer then @ng_treasurer
        when c_group = @og_leader then @ng_leader
        else 0 end,
    c_unixperms, c_created_date, c_last_modified,
    case when c_status = @os_default then @ns_default
        when c_status = @os_deleted then @ns_deleted
        when c_status = @os_inactive then @ns_inactive
        when c_status = @os_active then @ns_active
        when c_status = @os_waitlisted then @ns_waitlisted
        when c_status = @os_cancelled then @ns_cancelled
        when c_status = @os_pending then @ns_pending
        when c_status = @os_paid then @ns_paid
        when c_status = @os_checked_out then @ns_checked_out
        when c_status = @os_checked_in then @ns_checked_in
        when c_status = @os_missing then @ns_missing
        when c_status = @os_submitted then @ns_submitted
        else @ns_default end,
    case when c_status = @os_deleted then 1 else 0 end,
    c_category, c_amount, c_from, c_to, c_description, c_expense,
    case when c_flags & @of_reimbursable then 1 else 0 end
from [old]transaction;
