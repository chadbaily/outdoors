#!/usr/bin/perl
use strict;
use warnings;

use DBI;
use English qw(-no_match_vars);

# ===========================================================================
# CONFIGURE THESE VARIABLES
# ===========================================================================
my $dumpfile         = "/usr/home/at/stagingdump.sql";

my $master_db        = "at_main1";
my $master_host      = "db57b.pair.com";
my $master_user      = "at";
my $master_pass      = "At_12_pass";

my $staging_db       = "at_staging";
my $staging_host     = "db30.pair.com";
my $staging_user     = "at_2";
my $staging_pass     = "Password2";

#Dump the database
`mysqldump --user=$master_user --host=$master_host --password=$master_pass --no-create-db --add-drop-table $master_db > $dumpfile`;

#Bring the database into staging
`mysql --user=$staging_user --host=$staging_host --password=$staging_pass --database $staging_db < $dumpfile`;

#Change all emails to end in .out
my $dbh = DBI->connect("DBI:mysql:database=$staging_db;host=$staging_host", $staging_user, $staging_pass) or die("Can't connect to DB: $OS_ERROR");
$dbh->do("update m_member set c_email = CONCAT(c_email, '.out')");

#delete the dump
unlink $dumpfile;

chdir "/usr/home/at/public_html/staging/schema";
print "Hit Enter\n";
my $results = `/usr/home/at/public_html/staging/schema/upgrade m_ $staging_host $staging_db $staging_user $staging_pass 2006-03-27 2009-03-24`;
print "Printing results\n";
print $results;
