#!/usr/bin/perl
use strict;
use warnings;

use DBI;
use English qw(-no_match_vars);

# ===========================================================================
# CONFIGURE THESE VARIABLES
# ===========================================================================
my $db        = "at_main1";
my $host      = "db57b.pair.com";
my $user      = "at";
my $pass      = "wzPq8AtV1234";
my $prefix    = "m_";
#set this to when the email should be sent
my $days_remaining = 14;
#sender of the email (their id)
my $sender = 1;
# ===========================================================================
# STOP CONFIGURING HERE
# ===========================================================================

# This was copied from send-reminder.pl on May 20, 2014
# A cronjob was started the same week for it.
# -- Charles Romero

my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host", $user, $pass) or
die("Can't connect to DB: $OS_ERROR");

#this is the body of the select statement. First we'll see if there
#are going to be any results then we'll insert in an email
#then we'll insert in recipients.  Obviously we don't want to insert
#an email if there are no recipients
my $select_users = <<EOF;
select UserID, Member, UserEmail from
(
select
  case when ((date_format(now(), '%j') - date_format(me.c_birth_date, '%j')) < 0) then (date_format(now(), '%Y') - date_format(me.c_birth_date, '%Y')) -1 else (date_format(now(), '%Y') - date_format(me.c_birth_date, '%Y')) end as Age,
  case when ((date_format(ms.c_begin_date, '%j') - date_format(me.c_birth_date, '%j')) < 0) then (date_format(ms.c_begin_date, '%Y') - date_format(me.c_birth_date, '%Y')) -1 else (date_format(ms.c_begin_date, '%Y') - date_format(me.c_birth_date, '%Y')) end as BeginAge,
  case when ((date_format(date_add(now(),interval 14 day), '%j') - date_format(me.c_birth_date, '%j')) < 0) then (date_format(date_add(now(),interval ${days_remaining} day), '%Y') - date_format(me.c_birth_date, '%Y')) -1 else (date_format(date_add(now(),interval 14 day), '%Y') - date_format(me.c_birth_date, '%Y')) end as NearAge,
  me.c_full_name as Member,
  me.c_uid as UserID,
  me.c_birth_date as BirthDate,
  ms.c_expiration_date as ExpirationDate,
  me.c_email as UserEmail
from m_member as me
inner join m_membership as ms on ms.c_member = me.c_uid
where ms.c_expiration_date >= now() and ms.c_status = 4 and me.c_deleted = 0 and me.c_receive_email = 1
) as inner_table
where
  Age < 18 and NearAge >= 18
EOF

#form the insert statement to insert the email into the database
my $subject = "You're turning 18!";
my $message = <<EOF; 


According to our records, you are currently a minor, but will soon turn 18. Given this, you will need to sign an indemnification AFTER you have turned 18. Please turn in your signed indemnification within 7 days of turning 18. You may sign the indemnification agreement electronically at https://www.hellosign.com/s/c101b9bb or you may print out a copy (available at http://www.outdoorsatuva.org/pub-files/OUVA_Liability_Waiver.pdf), sign it, and return it to the officers

Please email the officers if you have any questions!

Thanks,
The Officers
outdoors-officers\@virginia.edu
EOF

my $footsubject = "Copy of email to minor turning 18 in the next 2 weeks";
my $footnote = <<EOF; 


This is a copy for the officers so that they know to expect an updated waiver from this member. The above has been sent to the appropriate recipient at 
EOF

#my $email_insert = "insert into ${prefix}email (c_owner,c_subject, c_message,
#   c_created_date) "
#                   . "values (${sender},\"${subject}\", $name->{'Member'} \"${message}\",current_date)";
#my $insert_query  = $dbh->prepare($email_insert);
#my $insert;
#my $insert_statement;
my $email_id = 0;

#print($select_users);
my $recips = $dbh->prepare($select_users);
my $recip;
$recips->execute();

my $email_insert;
my $insert_query;
my $insert;
my $insert_statement;

while ($recip = $recips->fetchrow_hashref()) {
    #this way, we're only inserting if there are emails to send
   $email_insert = "insert into ${prefix}email (c_owner,c_subject, c_message,
                   c_created_date) "
                   . "values (${sender},\"${subject}\", \"Dear " . $recip->{'Member'}
                   . ", ${message}\",current_date)";

  $insert_query  = $dbh->prepare($email_insert);

    if ($email_id == 0)
    {
      print($email_insert);
      $insert_query->execute();
      $email_id = $insert_query->{'mysql_insertid'};
      if ($email_id <= 0)
      {
        print("error: email_id was 0");
        exit(1);
      }
    }
    # Form the insert statements to insert each member into the recipients table
 
    $insert = "insert into ${prefix}email_recipient (c_email, c_recipient) "
                 . "values (${email_id}," . $recip->{'UserID'} . ")\n";
#   $insert = "insert into ${prefix}email_recipient (c_email, c_recipient) "
#            . "values (${email_id}, 5627)\n";
   print($insert);
    $insert_statement = $dbh->prepare($insert);
    $insert_statement->execute();

   $email_insert = "insert into ${prefix}email (c_owner,c_subject, c_message,
                   c_created_date) "
                   . "values (${sender},\"${footsubject}\", \"Dear " . $recip->{'Member'}
                   . ", ${message} ${footnote} " . $recip->{'UserEmail'} . " \",current_date)";
  $insert_query  = $dbh->prepare($email_insert);
  $insert_query->execute();
  $email_id = $insert_query->{'mysql_insertid'};

   $insert = "insert into ${prefix}email_recipient (c_email, c_recipient) "
            . "values (${email_id}, 1)\n";
# UserID for outdoors-officers is 1, so we send an email to them!
#    print($insert);
    $insert_statement = $dbh->prepare($insert);
    $insert_statement->execute();
}

print("Finished script successfully");
        
