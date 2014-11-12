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

-- FROM: 2006-03-26
-- TO:   2009-03-24

alter table [_]optout add c_category_type varchar(10) not null default 'activity';
drop index c_member on [_]optout;
create unique index c_member on [_]optout (c_member, c_category, c_category_type(1));

drop table if exists [_]email_category;
-- These are categories for emails that aren't related to specific activities
create table [_]email_category (
    c_uid             int unsigned    not null,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root 
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         tinyint         not null default 0,
    c_title           varchar(100),
    primary key  (c_uid)
) type=MyISAM;

insert into [_]email_category (c_uid, c_title) values (1,'Post-trip Emails');

drop table if exists [_]application;

-- Add an application table
create table [_]application (
    c_uid             int unsigned    not null AUTO_INCREMENT,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         tinyint         not null default 0,
    c_title           varchar(50)     not null default '',
    c_yearsleft       float           not null,
    c_yearinschool    varchar(50)     not null,
    c_club            text            null,
    c_leadership      text            null,
    c_climbing        text            null,
    c_kayaking        text            null,
    c_biking          text            null,
    c_hiking          text            null,
    c_caving          text            null,
    c_snowsports      text            null,
    c_other           text            null,
    c_whyofficer      text            null,
    c_purchasing      tinyint         not null default 0,
    c_quartermaster   tinyint         not null default 0,
    c_treasurer       tinyint         not null default 0,
    c_advisor         tinyint         not null default 0,
    c_member          int unsigned    not null,
    primary key  (c_uid)
) type=MyISAM;

-- Add the permissions for this table
drop table if exists temp_privileges;

create table temp_privileges (
    c_what varchar(20),
    c_groups varchar(20),
    c_action_title varchar(50),
    c_granted_on varchar(50),
    c_table varchar(50),
    primary key (c_what, c_groups, c_action_title, c_granted_on, c_table)
) type=MyISAM;

insert into temp_privileges
    (c_what, c_groups, c_action_title, c_granted_on, c_table)
values
    ('group',       2,       'list_all',          'table',        '[_]application'),
    ('group',       32,      'create',            'table',        '[_]application'),
    ('group',       32,      'list_owned_by',     'table',        '[_]application');

insert into [_]privilege
    (c_owner, c_created_date, c_what_granted_to, c_who_granted_to, c_action,
     c_what_relates_to, c_related_table, c_related_uid)
    select
        1,
        now(),
        c_what,
        c_groups,
        c_action_title,
        c_granted_on,
        c_table,
        0 -- These are not object-level privileges so this argument is ignored.
    from temp_privileges;

drop table temp_privileges;

insert into [_]table 
    (c_name)
    values
    ("[_]application"),
    ("[_]email_category");

-- These actions apply to ALL tables.  These are the defaults.
insert into [_]implemented_action (c_table, c_action)
    select c_name, c_title
    from [_]action, [_]table
    where c_title in ("read", "write", "delete", "list_all", "list_owned_by",
        "create", "stat", "chmod", "chgrp", "chown", "chmeta", "view_acl",
        "copy", "add_privilege") and c_name in ("[_]application","[_]email_category");

insert into [_]configuration (c_name, c_value, c_type, c_description)
    values
    ("taking_applications","true","bool", "Whether users should see the officer application link"),
    ("paypal_handling_cost","1.00","number","Extra cost to add to membership if they are paying by PayPal"),
    ("paypal_email_name","Outdoors at UVA Payments <payments@outdoorsatuva.org>","string","Email address that will show when members receive a PayPal confirmation"),
    ("paypal_email","payments@outdoorsatuva.org","string","Email address of PayPal account");

drop table if exists [_]payment;

create table [_]payment (
    c_uid             int unsigned    not null auto_increment,
    c_owner           int unsigned    not null default 1, -- root
    c_creator         int unsigned    not null default 1, -- root
    c_group           int unsigned    not null default 1, -- root 
    c_unixperms       int unsigned    not null default 500,
    c_created_date    datetime        not null,
    c_last_modified   timestamp       not null,
    c_status          int unsigned    not null default 1,
    c_deleted         tinyint         not null default 0,
    c_payer_id varchar(60) default NULL,
    c_payment_date varchar(50) default NULL,
    c_txn_id varchar(50) default NULL,
    c_first_name varchar(50) default NULL,
    c_last_name varchar(50) default NULL,
    c_payer_email varchar(75) default NULL,
    c_payer_status varchar(50) default NULL,
    c_payment_type varchar(50) default NULL,
    c_memo tinytext,
    c_item_name varchar(127) default NULL,
    c_item_number varchar(127) default NULL,
    c_quantity int(11) NOT NULL default '0',
    c_mc_gross decimal(9,2) default NULL,
    c_mc_currency char(3) default NULL,
    c_address_name varchar(255) NOT NULL default '',
    c_address_street varchar(255) NOT NULL default '',
    c_address_city varchar(255) NOT NULL default '',
    c_address_state varchar(255) NOT NULL default '',
    c_address_zip varchar(255) NOT NULL default '',
    c_address_country varchar(255) NOT NULL default '',
    c_address_status varchar(255) NOT NULL default '',
    c_payer_business_name varchar(255) NOT NULL default '',
    c_payment_status varchar(255) NOT NULL default '',
    c_pending_reason varchar(255) NOT NULL default '',
    c_reason_code varchar(255) NOT NULL default '',
    c_txn_type varchar(255) NOT NULL default '',
    PRIMARY KEY  (c_uid),
    UNIQUE KEY txn_id (c_txn_id),
    KEY txn_id_2 (c_txn_id)
) TYPE=MyISAM;
