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
 * $Id: drop.sql,v 1.5 2006/03/27 03:46:25 bps7j Exp $
 *
 * NOTE you must not have an unmatched quote in your comments, or MySQL will
 * barf.  The same goes for semicolons, parentheses etc.
 *
 * If you change this file, you are responsible for updating sql/initialize.sql
 * and sql/upgrade.sql as well.  If you change this file, please also update the
 * admin pages that check the database for bad data.
 */

drop table if exists [_]absence;
drop table if exists [_]action;
drop table if exists [_]activity;
drop table if exists [_]activity_category;
drop table if exists [_]address;
drop table if exists [_]adventure;
drop table if exists [_]adventure_activity;
drop table if exists [_]adventure_comment;
drop table if exists [_]answer;
drop table if exists [_]attendee;
drop table if exists [_]chat;
drop table if exists [_]chat_type;
drop table if exists [_]checkout;
drop table if exists [_]checkout_gear;
drop table if exists [_]checkout_item;
drop table if exists [_]classified_ad;
drop table if exists [_]condition;
drop table if exists [_]configuration;
drop table if exists [_]email;
drop table if exists [_]email_recipient;
drop table if exists [_]email_list;
drop table if exists [_]expense;
drop table if exists [_]expense_category;
drop table if exists [_]expense_report;
drop table if exists [_]expense_report_note;
drop table if exists [_]expense_submission;
drop table if exists [_]expense_submission_expense;
drop table if exists [_]foreign_key;
drop table if exists [_]implemented_action;
drop table if exists [_]interest;
drop table if exists [_]item;
drop table if exists [_]item_category;
drop table if exists [_]item_feature;
drop table if exists [_]item_note;
drop table if exists [_]item_type;
drop table if exists [_]item_type_feature;
drop table if exists [_]location;
drop table if exists [_]location_activity;
drop table if exists [_]member;
drop table if exists [_]member_note;
drop table if exists [_]membership;
drop table if exists [_]membership_type;
drop table if exists [_]mutex;
drop table if exists [_]optout;
drop table if exists [_]phone_number;
drop table if exists [_]phone_number_type;
drop table if exists [_]privilege;
drop table if exists [_]question;
drop table if exists [_]rating;
drop table if exists [_]report;
drop table if exists [_]subscription;
drop table if exists [_]table;
drop table if exists [_]transaction;
drop table if exists [_]unixperm;

