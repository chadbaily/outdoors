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
 * $Id: create.sql,v 1.7 2005/08/31 00:56:22 bps7j Exp $
 *
 * NOTE you must not have an unmatched quote in your comments, or MySQL will
 * barf.  The same goes for semicolons, parentheses etc.
 *
 * If you change this file, you are responsible for updating sql/initialize.sql
 * and sql/upgrade.sql as well.
 *
 * Explanation of Table Structure and Naming Conventions
 *
 * These tables, with some exceptions, have a common set of columns that mean as
 * follows:
 *
 *  c_uid
 *      is the primary key.
 *  c_owner
 *      is the user that owns the row, and relates to [_]member
 *  c_creator
 *      is the user that created the row, and relates to [_]member
 *  c_group
 *      is the group that owns the row.  This is NOT the group to which the
 *      member belongs (in the case of [_]member, this confuses many people).
 *  c_unixperms
 *      is a bitmask field that defines read, write, and delete permissions on
 *      the row for user, group, and other.  This is inspired by unix
 *      permissions.  Read the 'ls' man page for a good explanation.  The
 *      default value of 500 corresponds to 111110100.  If you want to know what
 *      permissions are set on an object, run the following:
 *      select c_title from [_]unixperm where c_bitmask & c_unixpermns
 *  c_created_date
 *      obvious
 *  c_last_modified
 *      timestamp columns will auto-update in MySql.  Do not assign a value to
 *      this row, and it will take care of itself whenever you update the row.
 *  c_status
 *      is a bitmasked combination of values defined in includes/setup.php.
 *  c_deleted
 *      is a tinyint that is 1 when the record is deleted.
 *
 *  Naming Conventions
 *  *   Any value of type date or datetime must have 'date' in its name
 *      somewhere.
 *  *   When pairing up begin/end columns, use the following pairs:
 *      - begin/expire
 *      - start/end
 *      What this means is, do NOT use begin/end as a pair.
 */

create table [_]absence (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    -- Members of the group are allowed to read and write, but ordinary users
    -- are not allowed to even read the absence.
    c_unixperms       int unsigned    not null default 496, -- 111110000
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_attendee        int unsigned    not null, -- > [_]attendee
    c_comment         varchar(100),             -- Short comment on absence
    c_severity        varchar(20),              -- Severity of the incident
    index (c_attendee),
    primary key  (c_uid)
) type=MyISAM;

/*
 * For coding simplicity, titles should be all one word, only alphanumeric and _
 * characters.  The title is used in code, but the short and long descriptions
 * are for people to see.  The long description is really only for admin users
 * to understand what is going on.  The summary is for use in a drop-down menu
 * of actions, so it should be something useful, like "Change Permissions."  The
 * label is for user interfaces, and may be overridden by the values in
 * [_]implemented_action.
 */

create table [_]action (
    c_title           varchar(100),   -- unique string, lowercase with _
    c_summary         varchar(25),    -- a few words
    c_label           varchar(25),    -- for UI.  & creates an access key
    c_row             tinyint         not null default 0,   -- for UI
    c_description     varchar(255),   -- a full description of the action
    c_apply_object    bit             not null default 0,
    c_generic         bit             not null default 0,
    primary key (c_title)
) type=MyISAM;

create table [_]activity (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_category        int unsigned    not null default 1, -- > [_]activity_category
    primary key  (c_uid)
) type=MyISAM;

-- Each activity has a category.
create table [_]activity_category (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root 
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    primary key  (c_uid)
) type=MyISAM;

create table [_]address (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(60),
    c_street          varchar(60),
    c_city            varchar(60),
    c_state           varchar(60),
    c_zip             varchar(20),
    c_country         varchar(60)     not null default 'US',
    c_primary         bit             not null default 0,
    c_hidden          bit             not null default 0,
    index (c_owner),
    primary key  (c_uid)
) type=MyISAM;

create table [_]adventure (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_fee             decimal(6,2)    not null,
    c_max_attendees   int unsigned    not null,
    c_signup_date     datetime        not null,
    c_title           varchar(255),
    c_description     text,
    c_start_date      datetime,
    c_end_date        datetime,
    c_departure       int unsigned    not null, -- > [_]location
    c_destination     int unsigned    not null, -- > [_]location
    c_average_rating  float           not null default 0,
    c_num_ratings     int unsigned    not null default 0,
    index (c_start_date),
    index (c_signup_date),
    index (c_end_date),
    index (c_owner),
    primary key  (c_uid)
) type=MyISAM;

create table [_]adventure_comment (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_adventure       int unsigned    not null, -- > [_]adventure
    c_rating          int unsigned    not null, -- > [_]rating
    c_subject         varchar(255),
    c_text            text,
    c_anonymous       bit             not null default 0,
    unique index(c_owner, c_adventure),
    primary key  (c_uid)
) type=MyISAM;

create table [_]adventure_activity (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_adventure       int unsigned    not null, -- > [_]adventure
    c_activity        int unsigned    not null, -- > [_]activity
    unique index (c_adventure, c_activity),
    primary key  (c_uid)
) type=MyISAM;

create table [_]answer (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_question        int unsigned    not null, -- > [_]question
    c_attendee        int unsigned    not null, -- > [_]attendee
    c_answer_text     text,
    index (c_question),
    index (c_owner),
    primary key  (c_uid)
) type=MyISAM;

create table [_]attendee (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 8, -- leader
    -- Members of the group are allowed the extra privilege of deleting an
    -- attendee that someone else has created.
    c_unixperms       int unsigned    not null default 508, -- 111111100
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_adventure       int unsigned    not null, -- > [_]adventure
    c_member          int unsigned    not null, -- > [_]member
    -- Just for record-keeping purposes, so we know how much the adventure fee was
    -- at the time the member joined it.
    c_amount_paid     decimal(2,2)    not null default 0,
    -- Again, for record-keeping purposes
    c_joined_date     datetime        not null,
    index (c_adventure, c_member),
    index (c_joined_date),
    primary key  (c_uid)
) type=MyISAM;

create table [_]chat (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_screenname      varchar(100)    not null,
    c_type            int unsigned    not null default 1, -- > [_]chat_type
    c_primary         bit             not null default 0,
    c_hidden          bit             not null default 0,
    primary key  (c_uid)
) type=MyISAM;

create table [_]chat_type (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_abbreviation    varchar(5),
    c_description     varchar(255),
    primary key  (c_uid)
) type=MyISAM;

create table [_]checkout (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    -- The owner checks out the items.  The member is who they are checked out TO.
    c_member          int unsigned    not null default 0, -- > [_]member
    -- Generally speaking, what type of checkout is this?
    c_activity        int unsigned    not null default 0, -- > [_]activity_category
    primary key  (c_uid)
) type=MyISAM;

create table [_]checkout_gear (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_checkout        int unsigned    not null default 0, -- > [_]checkout
    c_type            int unsigned    not null default 0, -- > [_]item_type
    c_qty             int unsigned    not null default 0,
    c_description     varchar(100)    not null default '',
    c_checkin_member  int unsigned    not null default 0, -- > [_]member
    primary key  (c_uid),
    index(c_checkout)
) type=MyISAM;

create table [_]checkout_item (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_checkout        int unsigned    not null default 0, -- > [_]checkout
    c_item            int unsigned    not null default 0, -- > [_]item
    primary key  (c_uid),
    unique index(c_checkout, c_item)
) type=MyISAM;

create table [_]classified_ad (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100)    not null,
    c_text            text            not null,
    primary key  (c_uid)
) type=MyISAM;

-- Defines possible values for the condition of an item.
create table [_]condition (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100)    not null,
    c_rank            int unsigned    not null default 0,
    c_description     varchar(255)    not null,
    primary key  (c_uid),
    unique index (c_title)
) type=MyISAM;

create table [_]configuration (
    c_name            varchar(50)     not null,
    c_value           varchar(255)    not null,
    -- Value is one of bit  integer string number datetime date email
    c_type            varchar(10)     not null,
    c_description     varchar(255)    not null,
    primary key(c_name)
) type=MyISAM;

create table [_]email_list (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_description     varchar(255),
    c_name            varchar(100),   -- name that the list software uses
                                      -- e.g. socialclub
    c_password        varchar(30),    -- list password
    c_owner_address   varchar(50),    -- email addy of list owner
    c_mgmt_address    varchar(50),    -- send automated requests here
    c_list_address    varchar(40),    -- send to the whole list
    c_type            varchar(30),    -- eg MailmanList, MajordomoList
    c_subject_prefix  varchar(30),    -- a prefix to all subjects sent
    primary key  (c_uid)
) type=MyISAM;

create table [_]expense (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 4, -- treasurer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_report          int unsigned    not null, -- > [_]expense_report
    c_category        int unsigned    not null, -- > [_]expense_category
    c_expense_date    date            not null,
    c_adventure       varchar(100)    not null,
    c_merchant        varchar(30)     not null,
    c_description     varchar(60)     not null,
    c_amount          decimal(6,2)    not null,
    c_reimbursable    bit             not null default 0,
    primary key  (c_uid)
) type=MyISAM;

create table [_]expense_category (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 4, -- treasurer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(30)     not null,
    primary key  (c_uid)
) type=MyISAM;

-- Expense reports initially belong to the member that creates them.  But after
-- the member accepts the expense report, the owner becomes root, and the member
-- cannot edit or delete it anymore.
create table [_]expense_report (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 4, -- treasurer
    c_unixperms       int unsigned    not null default 508, -- nonstandard!
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_member          int unsigned    not null, -- > [_]member
    primary key  (c_uid)
) type=MyISAM;

create table [_]expense_report_note (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 4, -- treasurer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_report          int unsigned    not null, -- > [_]expense_report
    c_new_status      int unsigned    not null, -- > status
    primary key  (c_uid)
) type=MyISAM;

create table [_]expense_submission (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    primary key  (c_uid)
) type=MyISAM;

create table [_]expense_submission_expense (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_submission      int unsigned    not null, -- > [_]expense_submission
    c_expense         int unsigned    not null, -- > [_]expense
    primary key  (c_uid),
    index (c_submission),
    unique index(c_expense)
) type=MyISAM;

-- Defines relationships between data in the database.  There may be multiple
-- rows per relationship.
create table [_]foreign_key (
    c_parent_table    varchar(50) not null,
    c_child_table     varchar(50) not null,
    c_parent_col      varchar(50) not null,
    c_child_col       varchar(50) not null,
    primary key (c_parent_table, c_child_table, c_parent_col, c_child_col)
) type=MyISAM;

/* This table maintains an association between actions and data types, to
 * specify which actions apply to which data types.  The columns mean as
 * follows:
 *  c_table 
 *      is the name of the table a piece of data lives in.  If it is empty, the
 *      action applies to ALL tables.
 *  c_action
 *      is an action that applies to (is implemented by) objects in c_table.
 *  c_label
 *      When this field contains something other than the empty string, it will
 *      override the value in [_]action.
 *  c_status
 *      is a bit field that specifies which statuses the action is valid for.
 *      If this field is 0, then the action is valid for every status.
 */
create table [_]implemented_action (
    c_table           varchar(100)    not null,
    c_action          varchar(100)    not null, -- > [_]action
    c_label           varchar(25)     not null,
    c_status          int unsigned    not null default 0,
    primary key (c_table, c_action)
) type=MyISAM;

create table [_]interest (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_member          int unsigned    not null, -- > [_]member
    c_activity        int unsigned    not null, -- > [_]activity
    unique index (c_member, c_activity),
    primary key (c_uid)
) type=MyISAM;

-- Defines an item of inventory.
create table [_]item (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_condition       int unsigned    not null, -- > condition
    c_type            int unsigned    not null, -- > type
    c_description     text            not null,
    c_purchase_date   date,
    c_qty             int unsigned    not null,
    primary key  (c_uid),
    index (c_type, c_condition)
) type=MyISAM;

-- Records a note on an item, along with the new condition.
create table [_]item_note (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_item            int unsigned    not null, -- > item
    c_condition       int unsigned    not null, -- > condition
    c_note            varchar(255)    not null,
    primary key  (c_uid),
    index (c_item, c_creator)
) type=MyISAM;

-- Records a feature on an item, which is a named value
create table [_]item_feature (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_item            int unsigned    not null, -- > item
    c_name            varchar(10)     not null,
    c_value           varchar(100)    not null,
    primary key  (c_uid),
    unique index (c_item, c_name)
) type=MyISAM;

-- Each item type has a category.
create table [_]item_category (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    primary key  (c_uid)
) type=MyISAM;

-- Records a feature that every item of a certain type should have.  Taken
-- all together, the set of item_type_features for a given item_type define
-- the features that every item of that type is expected to have.
create table [_]item_type_feature (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_type            int unsigned    not null, -- > item_type
    c_name            varchar(10)     not null,
    primary key  (c_uid),
    unique index (c_type, c_name)
) type=MyISAM;

-- Defines a type of item.  Each type of item has its own set of features,
-- which are defined in item_type_feature.  Thus a type of item is a sort
-- of template for the named values that belong to this class of item.
create table [_]item_type (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100)    not null,
    c_category        int unsigned    not null default 1, -- > [_]item_category
    c_primary_feature varchar(10)     not null,
    c_secondary_feature varchar(10)   not null,
    unique index (c_category, c_title),
    primary key  (c_uid)
) type=MyISAM;

create table [_]location (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 8, -- leader
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_description     text,
    c_zip_code        varchar(20),
    primary key  (c_uid)
) type=MyISAM;

create table [_]location_activity (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 8, -- leader
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_location        int unsigned    not null, -- > [_]location
    c_activity        int unsigned    not null, -- > [_]activity
    primary key  (c_uid),
    unique index(c_location, c_activity)
) type=MyISAM;

-- This table is somewhat different as regards group ownership and privileges.
-- This is so that members can view other member records, but a user who is a
-- member of the guest group cannot view member records.
create table [_]member (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 32, -- member
    -- Members of the 'member' group can take the 'read' action, but no privileges
    -- are granted to 'other' at all.  Thus the guest user cannot view member
    -- info.
    c_unixperms       int unsigned    not null default 480, -- nonstandard 111100000
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1, -- default
    c_deleted         bit             not null default 0,
    c_email           varchar(60),
    c_password        varchar(30) binary,
    c_first_name      varchar(30),
    c_last_name       varchar(30),
    -- This is redundant, but stored here to ease coding, especially automatic
    -- insertion into text
    c_full_name       varchar(60),
    c_birth_date      date,
    c_gender          enum('m','f'),
    -- A bitmask of which groups the member belongs to.
    c_group_memberships int unsigned  not null default 0,
    c_is_student      bit             not null default 0,
    c_receive_email   bit             not null default 0,
    c_hidden          bit             not null default 0,
    c_email_hidden    bit             not null default 0,
    unique index (c_email),
    primary key  (c_uid)
) type=MyISAM;

create table [_]member_note (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_member          int unsigned    not null, -- > [_]member
    c_note            text,
    primary key(c_uid)
) type=MyISAM;

create table [_]membership (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 2, -- inactive
    c_deleted         bit             not null default 0,
    c_member          int unsigned    not null, -- > [_]member
    c_type            int unsigned    not null, -- > [_]membership_type
    -- These two are allowed to be null because they are not set until
    -- the membership is activated, or, in the case the membership type
    -- has its own start and expiration dates, they are copied from the
    -- membership type.
    c_begin_date      date,
    c_expiration_date date,
    -- The following are set when the membership is created.  This is for
    -- record-keeping (what if someone signs up, then the membership_type
    -- gets changed and now you do not know what the member saw at signup?).
    c_units_granted   int             not null default 0,
    c_unit            varchar(50),    -- day, month, week, year
    -- The total cost for the membership type when the member signed up.  This
    -- is never changed (that would change history).
    c_total_cost      decimal(6,2)    not null default 0,
    -- The amount the member actually paid for the membership when it was
    -- activated.
    c_amount_paid     decimal(6,2)    not null default 0,
    index (c_member),
    primary key (c_uid)
) type=MyISAM;

create table [_]membership_type (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_description     varchar(255),
    -- The following properties only apply if this is not a flexible plan, and describe when
    -- the membership is valid and when it expires
    c_begin_date      date,
    c_expiration_date date,
    -- When to start and stop showing this plan on the signup form
    c_show_date       date,
    c_hide_date       date,
    -- The following property only applies if this is a flexible plan.  It describes
    -- how many units of time to add onto a membership expiration date
    c_units_granted   int             not null default 0,
    -- Unit of measure for cost, and unit cost of the membership plan
    c_unit            varchar(50),    -- day, month, week, year
    c_unit_cost       decimal(6,2)    not null default 0,
    -- Total cost of the membership plan
    c_total_cost      decimal(6,2)    not null default 0,
    c_flexible        bit             not null default 0,
    c_hidden          bit             not null default 0,
    primary key  (c_uid)
) type=MyISAM;

-- Used for bridging queries so unions and subselects can be done as joins.
create table [_]mutex (
    c_mutex           tinyint unsigned not null primary key
) type=MyISAM;

-- Members are opted in to every category of email unless they opt out.
create table [_]optout (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_member          int unsigned    not null, -- > [_]member
    c_category        int unsigned    not null, -- > [_]activity_category
    unique index (c_member, c_category),
    primary key  (c_uid)
) type=MyISAM;

create table [_]phone_number (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_type            int unsigned    not null default 1, -- > [_]phone_number_type
    c_title           varchar(100),
    c_country_code    varchar(5)      not null default "",
    c_area_code       varchar(5),
    c_exchange        varchar(5),
    c_number          varchar(5),
    c_extension       varchar(8)      not null default "",
    -- This is redundant, but stored here to ease coding, especially automatic
    -- insertion into text
    c_phone_number    varchar(100),
    c_primary         bit             not null default 0,
    c_hidden          bit             not null default 0,
    index (c_owner),
    primary key  (c_uid)
) type=MyISAM;

create table [_]phone_number_type (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_description     varchar(255),
    c_abbreviation    varchar(10),
    primary key  (c_uid)
) type=MyISAM;

create table [_]privilege (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    -- Whether granted to user, group, owner, owner_group
    c_what_granted_to varchar(30)     not null default 'nothing',
    -- Which user or group it applies to (only needed if c_what_granted_to is
    -- "user" or "group")
    c_who_granted_to  int unsigned    not null default 0,
    -- The action that the privilege grants.
    c_action          varchar(100)    not null,
    -- What the privilege applies to: a table, an object in a table, all of the
    -- objects in the specified table, or oneself.  The values should hence be
    -- 'table', 'object', 'global' or 'self'.
    c_what_relates_to varchar(30)     not null default 'nothing',
    -- The table to which the privilege applies (never optional), though it
    -- doesn't matter for a 'self' privilege which only applies to objects in the
    -- [_]member table.
    c_related_table   varchar(100)    not null default '',
    -- The object to which the privilege applies (only needed if the privilege is
    -- an object privilege; global and table privileges don't care about this
    -- value)
    c_related_uid     int unsigned    not null default 0,
    primary key  (c_uid),
    unique index(
        c_what_granted_to, c_who_granted_to, c_action,
        c_what_relates_to, c_related_table, c_related_uid)
) type=MyISAM;

create table [_]question (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 8, -- leader
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_adventure       int unsigned    not null, -- > [_]adventure
    c_type            enum('bool','text'),
    c_text            text,
    index (c_adventure),
    primary key  (c_uid)
) type=MyISAM;

create table [_]rating (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(10),
    primary key  (c_uid)
) type=MyISAM;

create table [_]report (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    -- Nonstandard permissions are granted on reports: members of the group are
    -- allowed to read but not alter it.  Only the owner can alter a report.
    c_unixperms       int unsigned    not null default 480, -- 111100000
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_title           varchar(100),
    c_description     varchar(255),
    c_query           text,
    primary key  (c_uid)
) type=MyISAM;

create table [_]subscription (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 2, -- officer
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_list            int unsigned    not null, -- > [_]email_list
    c_email           varchar(60),    -- The address that is subscribed
    c_password        varchar(255),   -- The password used to subscribe
    primary key  (c_uid)
) type=MyISAM;

create table [_]table (
    c_name            varchar(100),
    primary key (c_name)
) type=MyISAM;

create table [_]transaction (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 16, -- quartermaster
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         bit             not null default 0,
    c_category        int unsigned    not null default 0, -- > [_]expense_category
    c_amount          decimal(6,2)    not null,
    c_from            int unsigned    not null, -- > [_]member
    c_to              int unsigned    not null, -- > [_]member
    c_description     varchar(100)    not null default '',
    c_expense         int unsigned    not null default 0, -- > [_]expense (optional)
    c_reimbursable    bit             not null default 0,
    primary key  (c_uid),
    index (c_from, c_to)
) type=MyISAM;
