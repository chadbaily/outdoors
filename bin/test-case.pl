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
my $pass      = "At_12_pass";
my $prefix    = "m_";
#set this to when the email should be sent
my $days_remaining = 14;
#sender of the email (their id)
my $sender = 1;
# ===========================================================================
# STOP CONFIGURING HERE
# ===========================================================================

my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host", $user, $pass) or
die("Can't connect to DB: $OS_ERROR");

#this is the body of the select statement. First we'll see if there
#are going to be any results then we'll insert in an email
#then we'll insert in recipients.  Obviously we don't want to insert
#an email if there are no recipients
my $select_users = <<EOF;
select UserID, Member from
(
select
  case when ((date_format(now(), '%j') - date_format(me.c_birth_date, '%j')) < 0) then (date_format(now(), '%Y') - date_format(me.c_birth_date, '%Y')) -1 else (date_format(now(), '%Y') - date_format(me.c_birth_date, '%Y')) end as Age,
  case when ((date_format(ms.c_begin_date, '%j') - date_format(me.c_birth_date, '%j')) < 0) then (date_format(ms.c_begin_date, '%Y') - date_format(me.c_birth_date, '%Y')) -1 else (date_format(ms.c_begin_date, '%Y') - date_format(me.c_birth_date, '%Y')) end as BeginAge,
  case when ((date_format(date_add(now(),interval 14 day), '%j') - date_format(me.c_birth_date, '%j')) < 0) then (date_format(date_add(now(),interval 14 day), '%Y') - date_format(me.c_birth_date, '%Y')) -1 else (date_format(date_add(now(),interval 14 day), '%Y') - date_format(me.c_birth_date, '%Y')) end as NearAge,
  me.c_full_name as Member,
  me.c_uid as UserID,
  me.c_birth_date as BirthDate,
  ms.c_expiration_date as ExpirationDate
from m_member as me
inner join m_membership as ms on ms.c_member = me.c_uid
where ms.c_expiration_date >= now() and ms.c_status = 4
) as inner_table
where
  Age < 18 and NearAge >= 18
EOF

#form the insert statement to insert the email into the database
my $subject = "Test Email";
my $message = <<EOF; 

This is a test email. The intendended recipient is one of the officers. If you are receiving this email, please disregard.

Thanks,
The Officers
outdoors-officers\@virginia.edu
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

while ($recip = $recips->fetchrow_hashref()) {
    #this way, we're only inserting if there are emails to send
   my $email_insert = "insert into ${prefix}email (c_owner,c_subject, c_message,
                   c_created_date) "
                   . "values (${sender},\"${subject}\", \"Dear " . $recip->{'Member'}
                   . ", ${message}\",current_date)";

  my $insert_query  = $dbh->prepare($email_insert);
  my $insert;
  my $insert_statement;

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
 
#    $insert = "insert into ${prefix}email_recipient (c_email, c_recipient) "
#                 . "values (${email_id}," . $recip->{'UserID'} . ")\n";
#    $insert_statement = $dbh->prepare($insert);
#    $insert_statement->execute();
   $insert = "insert into ${prefix}email_recipient (c_email, c_recipient) "
            . "values (${email_id}, 5627)\n";
    print($insert);
    $insert_statement = $dbh->prepare($insert);
    $insert_statement->execute();
#  my $hashref = $insert_statement->fetchrow_hashref();
#  my @column_names = keys %$hashref;
#   @sth->{NAME};
#   print(@sth);
}

print("Finished script successfully");
        

