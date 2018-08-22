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
 * $Id: initialize.sql,v 1.10 2009/03/12 03:15:59 pctainto Exp $
 *
 */

insert into [_]configuration
    (c_name, c_value, c_type, c_description)
    values
    ('club_name', 'SocialClub', 'string', 'Organization name'),
    ('club_admin_email', 'admin@domain.org', 'email', 'Club Administrator'),
    ('club_admin_email_name', 'SocialClub Administrator <admin@domain.org>',
        'string', 'Club Administrator, full email with name'),
    ('treasurer_email', 'treasurer@domain.org', 'email',
        'The club treasurer, comma-separated'),
    ('send_emails', 'true', 'bool', 'Whether the website should send any email'),
    ('root_uid', '1', 'integer', 'The "root" user for the system'),
    ('expense_from_uid', '2', 'integer', 'The user from whom expenses come'),
    ('short_trip_length', '0', 'integer', 'The length of what is considered a ''short'' trip (in nigths).'),
    ('short_trip_advance_notice', '24', 'integer', 'How much advance notice must be given for a short trip to be funded (in hours).'),
    ('short_trip_message', 'You have not given enough advance notice for this trip to be funded.  You must post the trip 24 hours prior to the trip leaving.','string','Message displayed when someone creates a short trip on short notice'),
    ('med_trip_length', '1', 'integer', 'The length of what is considered a ''medium'' trip (in nights)'),
    ('med_trip_advance_notice', '48', 'integer', 'How much advance notice must be given for a short trip to be funded (in hours).'),
    ('med_trip_message', 'You have not given enough advance notice for this trip to be funded.  Overnight trips must be posted the 48 hours prior to the trip leaving.','string','Message displayed when someone creates a short trip on short notice'),
    ('long_trip_length', '3', 'integer', 'The length of what is considered a ''long'' trip (in nights)'),
    ('long_trip_advance_notice', '168', 'integer', 'How much advance notice must be given for a short trip to be funded (in hours).'),
    ('long_trip_message', 'You have not given enough advance notice for this trip to be funded.  Trips that are 3 nights or longer must be posted a week prior to the trip leaving.','string','Message displayed when someone creates a short trip on short notice');

insert into [_]table
    (c_name)
    values
    ("[_]absence"),
    ("[_]activity"),
    ("[_]activity_category"),
    ("[_]address"),
    ("[_]adventure"),
    ("[_]adventure_activity"),
    ("[_]adventure_comment"),
    ("[_]answer"),
    ("[_]attendee"),
    ("[_]chat"),
    ("[_]chat_type"),
    ("[_]checkout"),
    ("[_]checkout_gear"),
    ("[_]checkout_item"),
    ("[_]classified_ad"),
    ("[_]condition"),
    ("[_]email"),
    ("[_]email_list"),
    ("[_]email_recipient"),
    ("[_]expense"),
    ("[_]expense_category"),
    ("[_]expense_report"),
    ("[_]expense_report_note"),
    ("[_]expense_submission"),
    ("[_]expense_submission_expense"),
    ("[_]interest"),
    ("[_]item"),
    ("[_]item_note"),
    ("[_]item_feature"),
    ("[_]item_category"),
    ("[_]item_type_feature"),
    ("[_]item_type"),
    ("[_]location"),
    ("[_]location_activity"),
    ("[_]member"),
    ("[_]member_note"),
    ("[_]membership"),
    ("[_]membership_type"),
    ("[_]optout"),
    ("[_]phone_number"),
    ("[_]phone_number_type"),
    ("[_]privilege"),
    ("[_]question"),
    ("[_]rating"),
    ("[_]report"),
    ("[_]subscription"),
    ("[_]transaction");

insert into [_]action (c_title, c_summary, c_label, c_description) values
      ('read', "View Details", '&Details', 'Read and display the contents of an object'),
      ('write', "Edit or Update", 'Edit', 'Edit (modify or update) the object'),
      ('delete', "Delete", 'De&lete', 'Delete the object or mark it as deleted'),
      ('list_all', 'List All Objects', "List All Objects", 'List all objects of the given type'),
      ('list_owned_by', 'List Objects I Own', "List Objects I Own", 'List all objects of the given type that the member owns'),
      ('view_history', "View History", 'Histor&y', 'View a history of the object'),
      ('create', "Create", 'Create', 'Create an object of the given type'),
      ('activate', "Activate", 'A&ctivate', 'Activate the object'),
      ('deactivate', "Deactivate", "Deactiv&ate", 'Deactivate the object'),
      ('join', "Join", "&Join", 'Join the adventure'),
      ('copy', 'Copy Object', 'Cop&y', "Make another object with identical data"),
      ('withdraw', "Withdraw", '&Withdraw', 'The opposite of join'),
      ('edit_questions', "Edit Questions", 'Edit &Questions', 'Create or edit questions for an adventure'),
      ('choose_activities', "Choose Activities", 'C&hoose Activities', 'Choose which type(s) of activity applies to this adventure'),
      ('view_report', "View Report", "View Re&port", 'View a report on attendees'),
      ('announce', "Announce", "A&nnounce", 'Announce the adventure to the main email list'),
      ('stat', "View Properties", 'P&roperties', "View meta-data about an object, such as its owner and date of creation"),
      ('chmod', "Change Permissions", "&Change Permissions", 'Change Unix-style permissions for an object'),
      ('chgrp', "Change Group", "Change &Group", "Change the object's primary group"),
      ("chown", "Change Owner", "Cha&nge Owner", "Change the object's owner"),
      ("chmeta", "Change Meta-Data", "Edit &Properties", "Change the object's attributes, such as status, owner, and so forth"),
      ("view_acl", "View ACL", "View &ACL", "View the object's Access Control List (Privileges)"),
      ('chgrp_secondary', "Change Group Memberships", "Change Group Memberships", "Change the member's secondary groups"),
      ("change_password", "Change Password", "Change &Password", "Change a member's password"),
      ("view_waitlist", "View Waitlist", "View Wait&list", "View the adventure's waitlist"),
      ("execute", "Execute", "E&xecute", "Execute the object (mostly used for reports)"),
      ("email_attendees", "Email Attendees", "Emai&l Attendees", "Email all attendees for an adventure"),
      ("waitlist", "Waitlist", "W&aitlist", "Move an attendee onto an adventure's waitlist"),
      ("mark_absent", "Mark Absent", "Mar&k Absent", "Mark an attendee as absent from an adventure"),
      ("view_absences", "View Absences", "View A&bsences", "View all times this member has been absent from an adventure"),
      ("view_members", "View Members", "View &Members", "View members that belong to this group"),
      ("view_notes", "View Notes", "View &Notes", "View notes on the object"),
      ("view_answers", "View Answers", "View An&swers", "View answers to the questions for this adventure"),
      ("comment", "Comment", "Commen&t", "Comment on this item"),
      ("cancel", "Cancel", "Cancel", "Cancel"),
      ("add_privilege", "Add Privilege", "Add Pri&vilege", "Add Privilege"),
      ("subscribe", "Subscribe", "Subscribe", "Subscribe"),
      ("su", "Switch User", "&Switch User", "Become another member"),
      ("edit_features", "Edit Features", "Edit Feat&ures", "Edit the object's features"),
      ("unsubscribe", "Unsubscribe", "&Unsubscribe", "Unsubscribe"),
      ('optout', "Opt Out", '&Opt Out', 'Opt out of emails'),
      ('submit', "Submit", '&Submit', 'Submit an Expense Report'),
      ('accept', "Accept", '&Accept', 'Accept an Expense Report'),
      ('check_in', "Check In", 'Chec&k In', 'Check in Inventory');

-- Set object actions to apply to objects.
update [_]action
    set c_apply_object = 1
    where c_title not in ('list_all', 'list_owned_by', 'create');

-- Specify which actions should appear on the 'generic' set of tabs, and which
-- should appear on the tabs specific to the object:
update [_]action set c_generic = 1
where c_title in ("stat", "chmod", "chmeta", "delete",
        "view_acl", "chgrp", "chown", "add_privilege");

update [_]action set c_row = 1 where c_title in ("chmod", "chgrp", "chown",
        "view_acl", "add_privilege");

insert into [_]expense_category (c_title)
    values
    ("Food"),
    ("Gas"),
    ("Lodging"),
    ("Other"),
    ("Registration Fee"),
    ("Equipment"),
    ("Membership Dues"),
    ("Rental Fee"),
    ("Postage"),
    ("Participation Fee"),
    ("Equipment Repair");

-- Adventure ratings

insert into [_]rating
    (c_title)
    values
    ("Poor"), ("Fair"), ("Average"), ("Good"), ("Excellent");

insert into [_]address
    (c_title, c_street, c_city, c_state, c_zip, c_country, c_primary)
values
    ("Main Club Address", "SocialClub", "SomeTown", "VA", 12345, "US", 1);

insert into [_]phone_number_type
    (c_owner, c_created_date, c_title, c_description, c_abbreviation)
    values
    (1, now(), "Default", "Default phone number type", ""),
    (1, now(), "Cell", "Cellular phone", "(c)"),
    (1, now(), "Work", "Work phone", "(w)"),
    (1, now(), "School", "School phone", "(sch)"),
    (1, now(), "Home", "Home phone", "(h)");

insert into [_]phone_number
    (c_title, c_country_code, c_area_code, c_exchange, c_number,
    c_phone_number, c_primary)
values (
    "Main Club Phone Number", "1", "123", "456", "7890",
    "(123) 456-7890", 1);

insert into [_]activity_category
    (c_created_date, c_title)
    values
    (now(), "Everything Else"),
    (now(), "Biking"),
    (now(), "Camping and Backpacking"),
    (now(), "Climbing"),
    (now(), "Day Hiking and Running"),
    (now(), "Service"),
    (now(), "Winter Sports"),
    (now(), "Water Sports");

insert into [_]condition
    (c_owner, c_created_date, c_title, c_rank, c_description)
    values
    (1, now(), "unknown", 0, "Unknown"),
    (1, now(), "unsafe", 1, "Unsafe"),
    (1, now(), "unusable", 2, "Unusable"),
    (1, now(), "poor", 3, "Extremely poor condition"),
    (1, now(), "dirty", 4, "Dirty condition"),
    (1, now(), "fair", 5, "Fair condition"),
    (1, now(), "good", 6, "Good condition"),
    (1, now(), "excellent", 7, "Almost mint, but not quite"),
    (1, now(), "mint", 8, "Absolutely factory new, except not brand new"),
    (1, now(), "brand_new", 9, "Brand new");

insert into [_]item_category
    (c_created_date, c_title)
    values
    (now(), "No Category");

insert into [_]member
    (c_created_date, c_first_name, c_last_name, c_email, c_password,
    c_gender, c_birth_date, c_full_name, c_group_memberships, c_hidden)
values
    (now(), "Club", "Manager", "admin@domain.org", "root",
      "m", '2000-01-01', "Club Manager", 1, 1),
    (now(), "Guest", "User", "guest@domain.org", "guest",
      "m", '2000-01-01', "Guest User", 64, 1);

insert into [_]chat_type
    (c_owner, c_created_date, c_title, c_abbreviation, c_description)
    values
    (1, now(), "AIM", "AIM", "AOL Instant Messenger"),
    (1, now(), "Yahoo! Messenger", "YM", "Yahoo! Messenger"),
    (1, now(), "MSN Messenger", "MSN", "MSN Messenger"),
    (1, now(), "ICQ", "ICQ", "ICQ");

insert into [_]membership_type
    (c_owner, c_created_date, c_title, c_description,
    c_begin_date, c_expiration_date, c_show_date, c_hide_date,
    c_units_granted, c_unit, c_unit_cost, c_total_cost, c_hidden)
    values
    ( -- Lifetime membership for system accounts
      1,
      now(),
      "Lifetime membership",
      "Membership for system accounts such as root",
      '2000-01-01',
      '2020-01-01',
      '0000-00-00',
      '0000-00-00',
      0,
      'month',
      0.00,
      0.00,
      1);

insert into [_]membership
    (c_created_date, c_status, c_member, c_type, c_begin_date,
    c_expiration_date, c_units_granted, c_unit, c_total_cost, c_amount_paid)
select now(), 4, me.c_uid, mt.c_uid, mt.c_begin_date,
    mt.c_expiration_date, c_units_granted, c_unit, c_total_cost, 0
    from [_]member as me, [_]membership_type as mt
    where me.c_email in ("admin@domain.org", "guest@domain.org")
        and mt.c_title = "Lifetime Membership";

insert into [_]activity
    (c_created_date, c_title)
    values
    (now(), "Biking (Mountain)"),
    (now(), "Biking (Road)"),
    (now(), "Camping"),
    (now(), "Canoeing"),
    (now(), "Caving"),
    (now(), "Climbing (Bouldering)"),
    (now(), "Climbing (Indoor Bouldering)"),
    (now(), "Climbing (Indoor Sport)"),
    (now(), "Climbing (Indoor Top Rope)"),
    (now(), "Climbing (Indoor)"),
    (now(), "Climbing (Sport)"),
    (now(), "Climbing (Top Rope)"),
    (now(), "Climbing (Trad)"),
    (now(), "Disc (Frisbee) Golf"),
    (now(), "Fishing"),
    (now(), "Hang gliding"),
    (now(), "High ropes and challenge courses"),
    (now(), "Highway Cleanup"),
    (now(), "Hiking (Backpacking)"),
    (now(), "Hiking (Day Hikes)"),
    (now(), "Horseback Riding"),
    (now(), "Hot air ballooning"),
    (now(), "Kayaking (Instruction Only)"),
    (now(), "Kayaking (River)"),
    (now(), "Kayaking (Sea)"),
    (now(), "Kayaking (Whitewater)"),
    (now(), "Picnics"),
    (now(), "River Tubing"),
    (now(), "Sailing"),
    (now(), "Scuba diving"),
    (now(), "Service Projects"),
    (now(), "Skiing (Cross-Country)"),
    (now(), "Skiing (Downhill)"),
    (now(), "Skydiving"),
    (now(), "Snow Tubing"),
    (now(), "Snowboarding"),
    (now(), "Snowshoeing"),
    (now(), "Social Events, Potlucks, etc."),
    (now(), "Stargazing "),
    (now(), "Sunbathing"),
    (now(), "Swimming"),
    (now(), "Whitewater rafting");

insert into [_]mutex values (0), (1), (2), (3), (4), (5), (6), (7), (8), (9);
