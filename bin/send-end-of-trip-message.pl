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
# ===========================================================================
# STOP CONFIGURING HERE
# ===========================================================================

sub get_trip_email() {
  my ($adventure_name, $adventure_id, $leader_id, $leader_name, $leader_email) = @_;
  my $subject = "${adventure_name} follow-up";
  my $message = <<EOF;
I hope that you had fun on your recent trip, "${adventure_name}!"

By following the links below, you can leave comments about your trip and see who was on your trip, in case you forgot people's names.

To comment on this trip, follow this link:
http://www.outdoorsatuva.org/members/adventure/comment/${adventure_id}

To get the names of who was on your trip, or if you'd like to email them, follow this link:
http://www.outdoorsatuva.org/members/adventure/comment/${adventure_id}

Thanks,
${leader_name}
EOF

  return ($sender, $subject, $message);
}

sub get_attendee_id_query {
  my ($adventure_id) = @_;
  my $select_attendees = <<EOF;
select me.c_uid
    from [_]member as me
    inner join [_]attendee as att on att.c_member = me.c_uid
    inner join [_]adventure as ad on att.c_adventure = ad.c_uid
    left outer join [_]optout as oo on oo.c_member = me.c_uid and oo.category = 1
    where me.c_deleted = 0
      and att.c_deleted = 0
      and ad.c_deleted = 0
      and att.c_status & 1 = 1
      and ad.c_uid = ${adventure_id}
      and coalesce(oo.c_deleted,0) = 0
      and isnull(oo.c_category)
EOF
  return $select_attendees;
}

my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host", $user, $pass) or
die("Can't connect to DB: $OS_ERROR");

#Get a list of adventures that ended today
my $select_adventures = <<EOF;
select ad.c_uid, ad.c_title, ad.c_owner, m.c_full_name, m.c_email from 
  ${prefix}adventure ad inner join ${prefix}member m on m.c_uid = ad.c_owner
where c_deleted = 0 AND 
  c_end_date = current_date
EOF

my $adventures = $dbh->prepare($select_adventures);
my $adventure;
$adventures->execute();

while ($adventure = $adventures->fetchrow_hashref()) {
  my ($sender, $subject, $message) = get_trip_email($adventure->{'c_title'},
                                                    $adventure->{'c_uid'},
                                                    $adventure->{'c_owner'},
                                                    $adventure->{'c_full_name'},
                                                    $adventure->{'c_email'});
  my $email_insert = "insert into ${prefix}email (c_owner, c_subject, c_message, c_created_date) "
      . "values (${sender},\"${subject}\", \"${message}\", current_date)";
  my $insert_query = $dbh->prepare($email_insert);
  my $insert;
  my $insert_statement;
  my $email_id = 0;
  my ($select_attendees) = get_attendee_id_query($adventure->'c_uid');
  my $attendees = $dbh->prepare($select_attendees);
  my $attendee;
  $attendees->execute();
  while ($attendee = $attendees->fetchrow_hashref()) {
    if ($email_id == 0) {
      $insert_query->execute();
      $email_id = $insert_query->{'mysql_insertid'};
      if ($email_id <= 0) {
        print("error: email_id was invalid");
        exit(1);
      }
    }
    $insert = "insert into ${prefix}email_recipient (c_email, c_recipient) "
        . "values (${email_id}," . $attendee{'c_uid'} . ")\n";
    $insert_statement = $dbh->prepare($insert);
    $insert_statement->execute();
  }
}

