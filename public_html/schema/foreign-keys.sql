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
 * $Id: foreign-keys.sql,v 1.2 2005/06/05 18:07:47 bps7j Exp $
 *
 */

delete from [_]foreign_key;

--------------------------------------------------------------------------------
-- Define foreign keys.  Insert rows for properties that every table has: owner,
-- creator, status
--------------------------------------------------------------------------------

insert into [_]foreign_key
    (c_parent_table, c_child_table, c_parent_col, c_child_col)
    select "[_]member", c_name, "c_uid", "c_owner"
    from [_]table;

insert into [_]foreign_key
    (c_parent_table, c_child_table, c_parent_col, c_child_col)
    select "[_]member", c_name, "c_uid", "c_creator"
    from [_]table;

--------------------------------------------------------------------------------
-- Insert rows for other foreign keys in the database.
--------------------------------------------------------------------------------

insert into [_]foreign_key
    (c_parent_table, c_child_table, c_parent_col, c_child_col)
    values
    ("[_]activity",             "[_]adventure_activity",    "c_uid",    "c_activity"),
    ("[_]activity",             "[_]interest",              "c_uid",    "c_activity"),
    ("[_]activity",             "[_]location_activity",     "c_uid",    "c_activity"),
    ("[_]activity_category",    "[_]optout",                "c_uid",    "c_category"),
    ("[_]activity_category",    "[_]activity",              "c_uid",    "c_category"),
    ("[_]adventure",            "[_]adventure_comment",     "c_uid",    "c_adventure"),
    ("[_]adventure",            "[_]adventure_activity",    "c_uid",    "c_adventure"),
    ("[_]adventure",            "[_]attendee",              "c_uid",    "c_adventure"),
    ("[_]adventure",            "[_]question",              "c_uid",    "c_adventure"),
    ("[_]attendee",             "[_]absence",               "c_uid",    "c_attendee"),
    ("[_]attendee",             "[_]answer",                "c_uid",    "c_attendee"),
    ("[_]chat_type",            "[_]chat",                  "c_uid",    "c_type"),
    ("[_]condition",            "[_]item",                  "c_uid",    "c_condition"),
    ("[_]condition",            "[_]item_note",             "c_uid",    "c_condition"),
    ("[_]email_list",           "[_]subscription",          "c_uid",    "c_list"),
    ("[_]expense_report",       "[_]expense",               "c_uid",    "c_report"),
    ("[_]expense_report",       "[_]expense_report_note",   "c_uid",    "c_report"),
    ("[_]expense_category",     "[_]expense",               "c_uid",    "c_category"),
    ("[_]member",               "[_]expense_report",        "c_uid",    "c_member"),
    ("[_]item",                 "[_]item_note",             "c_uid",    "c_item"),
    ("[_]item",                 "[_]item_feature",          "c_uid",    "c_item"),
    ("[_]item_category",        "[_]item_type",             "c_uid",    "c_category"),
    ("[_]item_type",            "[_]item",                  "c_uid",    "c_type"),
    ("[_]item_type",            "[_]item_type_feature",     "c_uid",    "c_type"),
    ("[_]location",             "[_]adventure",             "c_uid",    "c_departure"),
    ("[_]location",             "[_]adventure",             "c_uid",    "c_destination"),
    ("[_]location",             "[_]location_activity",     "c_uid",    "c_location"),
    ("[_]member",               "[_]attendee",              "c_uid",    "c_member"),
    ("[_]member",               "[_]interest",              "c_uid",    "c_member"),
    ("[_]member",               "[_]member_note",           "c_uid",    "c_member"),
    ("[_]member",               "[_]membership",            "c_uid",    "c_member"),
    ("[_]member",               "[_]optout",                "c_uid",    "c_member"),
    ("[_]membership_type",      "[_]membership",            "c_uid",    "c_type"),
    ("[_]phone_number_type",    "[_]phone_number",          "c_uid",    "c_type"),
    ("[_]question",             "[_]answer",                "c_uid",    "c_question"),
    ("[_]rating",               "[_]adventure_comment",     "c_uid",    "c_rating"),
    ("[_]expense_submission",   "[_]expense_submission_expense", "c_uid", "c_submission"),
    ("[_]expense",              "[_]expense_submission_expense", "c_uid", "c_expense"),
    ("[_]expense_category",     "[_]transaction",           "c_uid",    "c_category"),
    ("[_]member",               "[_]transaction",           "c_uid",    "c_from"),
    ("[_]member",               "[_]transaction",           "c_uid",    "c_to"),
    ("[_]member",               "[_]checkout",              "c_uid",    "c_member"),
    ("[_]activity_category",    "[_]checkout",              "c_uid",    "c_activity"),
    ("[_]checkout",             "[_]checkout_gear",         "c_uid",    "c_checkout"),
    ("[_]item_type",            "[_]checkout_gear",         "c_uid",    "c_type"),
    ("[_]member",               "[_]checkout_gear",         "c_uid",    "c_checkin_member"),
    ("[_]checkout",             "[_]checkout_item",         "c_uid",    "c_checkout"),
    ("[_]item",                 "[_]checkout_item",         "c_uid",    "c_item");

-------------------------------------------------------------------------------
-- Done inserting foreign keys.
-------------------------------------------------------------------------------
