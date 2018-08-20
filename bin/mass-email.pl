#!/usr/bin/perl
#use strict;
#use warnings;

use English qw(-no_match_vars);

# ===========================================================================
# CONFIGURE THESE VARIABLES
# ===========================================================================
# Another possibility: my $mailer = "/var/qmail/bin/qmail-inject";
my $mailer    = '/usr/sbin/sendmail -i -t';
# ===========================================================================
# STOP CONFIGURING HERE
# ===========================================================================


sub send_mail {
    my ($from, $to, $subject, $message) = @_;
    $message =~ s/^\./../g;

    open(MAIL, "|$mailer") or die("Could not open $mailer: $OS_ERROR");
    print MAIL <<"%%%";
From: $from
Subject: $subject
To: $to

$message
%%%

    close(MAIL);
}
open (EMAILLIST, "massemail.txt");
my $recip = "";
my $frm = "Outdoors at UVA Officers <outdoors-officers\@virginia.edu>";
my $subj = "Outdoors at UVA Info Session";
my $msg = "Thanks for your interest in Outdoors at UVA!  We're excited about a fun new year with the club!\n\nWe will hold two info sessions this week. Both presentations will be the same, so you only need to come to one. If you're interested in joining, remember to bring $30 for membership dues so we can activate you right away. Below are the dates and times of the info sessions:\n\nThursday, August 24th: 7-8:30, Cabell 311\nMonday, August 28th: 7-8:30, Minor 125\n\nAlso, if you can't make either of the meetings, remember that you can learn more about the club and sign up online at http://www.outdoorsatuva.org !\n\nWe look forward to seeing you soon,\n\nThe Officers\noutdoors-officers\@virginia.edu\nQuintin, Dave, Meghan, Andrew, Katrina, Baron, Aarash, Eric, and Lila\n"; @raw_data = <EMAILLIST>;
foreach $recip (@raw_data) {
        # Send the message
	print $recip;
	chop($recip);
        send_mail($frm,
                $recip,
                $subj,
                $msg);
}

close(EMAILLIST);
