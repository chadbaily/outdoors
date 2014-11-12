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
# Another possibility: my $mailer = "/var/qmail/bin/qmail-inject";
my $mailer    = '/usr/sbin/sendmail -i -t';
# How long to keep emails before purging them.
my $keep_days = 30;
# ===========================================================================
# STOP CONFIGURING HERE
# ===========================================================================

# Make sure nobody else is running
open my $FILE, ">>", "lock.txt" or die "Can't create lock.txt: $OS_ERROR";
flock($FILE, 2) or die "Can't get lock on lock.txt: $OS_ERROR";

print "Here\n\n";

sub send_mail {
    my ($from, $to, $subject, $message) = @_;
    $message =~ s/^\./../g;

    print "Message $message\n\n";
	print "To $to\n\n";
	sleep(2);
}

my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host", $user, $pass) or die("Can't connect to DB: $OS_ERROR");
my $prep = $dbh->prepare("select e.c_uid, m.c_email, m.c_full_name,e.c_subject,"
    . " e.c_message "
    . "from ${prefix}email as e "
    . "inner join ${prefix}email_recipient as r on r.c_email = e.c_uid "
    . "inner join ${prefix}member as m on e.c_owner = m.c_uid "
    . "where r.c_deleted = 0 "
    . "group by e.c_uid limit 1");
$prep->execute();

while ($prep->rows) {
    # Get this email's info
    my $email = $prep->fetchrow_hashref();
    # Get the recipients for the email
    my $recips = $dbh->prepare("select er.c_uid, me.c_email "
        . "from ${prefix}member as me "
        . "inner join ${prefix}email_recipient as er on me.c_uid = er.c_recipient "
        . "where er.c_email = " . $email->{'c_uid'});
    $recips->execute();
    my $recip;
    # Build the string that will be the from field
    my $from = "\"$email->{'c_full_name'}\" <$email->{'c_email'}>";

    while ($recip = $recips->fetchrow_hashref()) {
        # Send the message and update it in the database.
        send_mail($from,
                $recip->{'c_email'},
                $email->{'c_subject'},
                $email->{'c_message'});
#        $dbh->do("update ${prefix}email_recipient set c_deleted = 1 where c_uid = "
#            . $recip->{'c_uid'});
    }
 
    # Get the next email to be sent
    $prep->execute();
}

# Purge emails that have been sent.
#$dbh->do("delete from ${prefix}email where c_created_date "
#    . "< date_add(current_date, interval -$keep_days day);");
#$dbh->do("delete from ${prefix}email_recipient where c_created_date "
#    . "< date_add(current_date, interval -$keep_days day) and c_deleted = 1;");

flock($FILE, 8);
close($FILE);
