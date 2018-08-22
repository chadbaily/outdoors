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
 * $Id: 2005-09-01_2006-03-27.sql,v 1.1 2006/03/28 03:15:46 bps7j Exp $
 *
 */

-- This file will upgrade your database schema.  Make sure you back up your
-- data before running it!

-- FROM: 2005-09-01
-- TO:   2006-03-26

alter table [_]absence change c_deleted c_deleted tinyint not null default 0;
alter table [_]activity change c_deleted c_deleted tinyint not null default 0;
alter table [_]activity_category change c_deleted c_deleted tinyint not null default 0;
alter table [_]address change c_deleted c_deleted tinyint not null default 0;
alter table [_]adventure change c_deleted c_deleted tinyint not null default 0;
alter table [_]adventure_activity change c_deleted c_deleted tinyint not null default 0;
alter table [_]adventure_comment change c_deleted c_deleted tinyint not null default 0;
alter table [_]answer change c_deleted c_deleted tinyint not null default 0;
alter table [_]attendee change c_deleted c_deleted tinyint not null default 0;
alter table [_]chat change c_deleted c_deleted tinyint not null default 0;
alter table [_]chat_type change c_deleted c_deleted tinyint not null default 0;
alter table [_]checkout change c_deleted c_deleted tinyint not null default 0;
alter table [_]checkout_gear change c_deleted c_deleted tinyint not null default 0;
alter table [_]checkout_item change c_deleted c_deleted tinyint not null default 0;
alter table [_]classified_ad change c_deleted c_deleted tinyint not null default 0;
alter table [_]condition change c_deleted c_deleted tinyint not null default 0;
alter table [_]email_list change c_deleted c_deleted tinyint not null default 0;
alter table [_]expense change c_deleted c_deleted tinyint not null default 0;
alter table [_]expense_category change c_deleted c_deleted tinyint not null default 0;
alter table [_]expense_report change c_deleted c_deleted tinyint not null default 0;
alter table [_]expense_report_note change c_deleted c_deleted tinyint not null default 0;
alter table [_]expense_submission change c_deleted c_deleted tinyint not null default 0;
alter table [_]expense_submission_expense change c_deleted c_deleted tinyint not null default 0;
alter table [_]interest change c_deleted c_deleted tinyint not null default 0;
alter table [_]item change c_deleted c_deleted tinyint not null default 0;
alter table [_]item_note change c_deleted c_deleted tinyint not null default 0;
alter table [_]item_feature change c_deleted c_deleted tinyint not null default 0;
alter table [_]item_category change c_deleted c_deleted tinyint not null default 0;
alter table [_]item_type_feature change c_deleted c_deleted tinyint not null default 0;
alter table [_]item_type change c_deleted c_deleted tinyint not null default 0;
alter table [_]location change c_deleted c_deleted tinyint not null default 0;
alter table [_]location_activity change c_deleted c_deleted tinyint not null default 0;
alter table [_]member change c_deleted c_deleted tinyint not null default 0;
alter table [_]member_note change c_deleted c_deleted tinyint not null default 0;
alter table [_]membership change c_deleted c_deleted tinyint not null default 0;
alter table [_]membership_type change c_deleted c_deleted tinyint not null default 0;
alter table [_]optout change c_deleted c_deleted tinyint not null default 0;
alter table [_]phone_number change c_deleted c_deleted tinyint not null default 0;
alter table [_]phone_number_type change c_deleted c_deleted tinyint not null default 0;
alter table [_]privilege change c_deleted c_deleted tinyint not null default 0;
alter table [_]question change c_deleted c_deleted tinyint not null default 0;
alter table [_]rating change c_deleted c_deleted tinyint not null default 0;
alter table [_]report change c_deleted c_deleted tinyint not null default 0;
alter table [_]subscription change c_deleted c_deleted tinyint not null default 0;
alter table [_]transaction change c_deleted c_deleted tinyint not null default 0;

alter table [_]action change c_row c_row tinyint not null default 0;
alter table [_]action change c_apply_object c_apply_object tinyint not null default 0;
alter table [_]action change c_generic c_generic tinyint not null default 0;
alter table [_]address change c_primary c_primary tinyint not null default 0;
alter table [_]address change c_hidden c_hidden tinyint not null default 0;
alter table [_]adventure change c_waitlist_only c_waitlist_only tinyint not null default 0;
alter table [_]adventure_comment change c_anonymous c_anonymous tinyint not null default 0;
alter table [_]chat change c_primary c_primary tinyint not null default 0;
alter table [_]chat change c_hidden c_hidden tinyint not null default 0;
alter table [_]expense change c_reimbursable c_reimbursable tinyint not null default 0;
alter table [_]member change c_is_student c_is_student tinyint not null default 0;
alter table [_]member change c_receive_email c_receive_email tinyint not null default 0;
alter table [_]member change c_hidden c_hidden tinyint not null default 0;
alter table [_]member change c_email_hidden c_email_hidden tinyint not null default 0;
alter table [_]membership_type change c_flexible c_flexible tinyint not null default 0;
alter table [_]membership_type change c_hidden c_hidden tinyint not null default 0;
alter table [_]mutex change c_mutex c_mutex tinyint not null default 0;
alter table [_]phone_number change c_primary c_primary tinyint not null default 0;
alter table [_]phone_number change c_hidden c_hidden tinyint not null default 0;
alter table [_]transaction change c_reimbursable c_reimbursable tinyint not null default 0;

create index c_signup_date on [_]adventure (c_signup_date);

create table [_]email (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         tinyint         not null default 0,
    c_subject         varchar(100)    not null,
    c_message         text            not null,
    c_what_relates_to varchar(30),
    c_related_uid     int unsigned,
    primary key  (c_uid)
) type=MyISAM;

create table [_]email_recipient (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         tinyint         not null default 0,
    c_email           int unsigned    not null, -- > [_]email
    c_recipient       int unsigned    not null, -- > [_]member
    primary key  (c_uid)
) type=MyISAM;

insert into [_]foreign_key
    (c_parent_table, c_child_table, c_parent_col, c_child_col)
    values
    ("[_]email",                "[_]email_recipient",       "c_uid",    "c_email"),
    ("[_]member",               "[_]email_recipient",       "c_uid",    "c_recipient");

-- These actions apply to ALL tables.  These are the defaults.
insert into [_]implemented_action (c_table, c_action)
    select c_name, c_title
    from [_]action, [_]table
    where c_title in ("read", "write", "delete", "list_all", "list_owned_by",
        "create", "stat", "chmod", "chgrp", "chown", "chmeta", "view_acl",
        "copy", "add_privilege")
    and c_name in ("[_]email", "[_]email_recipient");
