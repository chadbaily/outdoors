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
my $pass      = "mR9jfSA8";
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
select c_uid 
from ${prefix}member inner join 
(select c_member, ifnull(max(c_expiration_date),current_date) latest_exp
 from ${prefix}membership where c_deleted = 0  group by c_member) memberships 
on ${prefix}member.c_uid = memberships.c_member 
where date_sub(latest_exp, interval ${days_remaining} day) = current_date 
and c_deleted = 0 and c_receive_email = 1
EOF

#form the insert statement to insert the email into the database
my $subject = "Your membership is expiring soon!";
my $message = <<EOF; 
Your Outdoors at UVA membership is expiring soon! 
To renew your membership, just go to http://www.outdoorsatuva.org/members/join/renew. You'll be able to pick from a 12-month membership for \$50 and a 5-month one for \$30. Just follow the instructions for dropping off or mailing your dues and a new waiver.  Again this year, you'll get your dues back if you lead 4 or more club trips (one of which must be an intro trip). If you've led 4 trips in the past year, you still need to renew your membership above and hand in a waiver - we will waive the dues. Really active members also have the chance to become club officers.

Remember that as a member, you can borrow club gear, sign up for trips, and receive discounts from local merchants. On all official trips, the club will again cover gas and lodging costs. For overnight trips, the club will pay for most food expenses.
 
Please email the officers if you have any questions!

Thanks,
The Officers
outdoors-officers\@virginia.edu
EOF

my $email_insert = "insert into ${prefix}email (c_owner,c_subject, c_message,
   c_created_date) "
                   . "values (${sender},\"${subject}\", \"${message}\",current_date)";

my $insert_query  = $dbh->prepare($email_insert);
my $insert;
my $insert_statement;
my $email_id = 0;

print($select_users);
my $recips = $dbh->prepare($select_users);
my $recip;
$recips->execute();

while ($recip = $recips->fetchrow_hashref()) {
    #this way, we're only inserting if there are emails to send
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
                 . "values (${email_id}," . $recip->{'c_uid'} . ")\n";
    print($insert);
    $insert_statement = $dbh->prepare($insert);
    $insert_statement->execute();
}

print("Finished script successfully");
        

