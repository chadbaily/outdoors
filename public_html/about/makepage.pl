#!/usr/bin/perl

use strict;
use CGI;
use CGI ':standard';
use CGI::Carp qw(fatalsToBrowser);

my $query = CGI->new();
my $name = $query->param("name") || "no name";
my $email = $query->param("email") || "not entered";
my $year = $query->param("year");
my $timeleft = $query->param("timeleft");
my $clubs = $query->param("clubs");
my $leadership = $query->param("leadership");
my $climbing = $query->param("climbing");
my $kayaking = $query->param("kayaking");
my $mtnbiking = $query->param("mtnbiking");
my $hiking = $query->param("backpacking");
my $snowsports = $query->param("snowsports");
my $caving = $query->param("caving");
my $other = $query->param("other");
my $whyofficer = $query->param("whyofficer");
my $gear = $query->param("gear") || "not";
my $quartermaster = $query->param("quartermaster") || "not";
my $tripadvisor = $query->param("tripadvisor") || "not";
my $treasurer = $query->param("treasurer") || "not";

print header;
print <<HTMLFORPAGE;
<html>
<head>
  <Title>Success</title>
  <meta http-equiv="Refresh" content="2;url=/members/main/member-home">
</head>
<body>
<h1>Application Successfully Completed!</h1>
<p>You will now be redirected to the member home page.  Thanks for your interest! (Click <a href"/members/main/member-home/">here</a> if nothing 
happens after a few seconds.)</p>
HTMLFORPAGE
print end_html;

my $time = time();

my $filename = "./application/$name$time.html";
open(OUTFILE, ">$filename") || die("Cannot open file for writing");

print OUTFILE start_html("$name");
print OUTFILE <<ALLHTML;

<h1><a href="http://www.outdoorsatuva.org/members/report/execute/13?arg=$name">$name</a></h1>
<p><b>Note:</b>The formatting may be a bit off from what they actually 
typed in.. this is my fault, not theirs!</p>
<hr>
<table width="100\%" cellpadding=4 border=1>
  <col width="25\%">
  <col width="75\%">
  <tr>
    <td>Email</td>
    <td><a href="mailto:$email">$email</a></td>
  <tr>
    <td>Year</td>
    <td>$year</td>
  </tr>

  <tr>
    <td>Time left at UVA or C-Ville</td>
    <td>$timeleft</td>
  </tr>

  <tr>
    <td>Outdoors club(s) experience</td>
    <td>$clubs</td>
  </tr>

  <tr>
    <td>Leadership experience</td>
    <td>$leadership</td>
  </tr>

  <tr>
    <td>Climbing experience</td>
    <td>$climbing</td>
  </tr>

  <tr>
    <td>Kayaking experience</td>
    <td>$kayaking</td>
  </tr>

  <tr>
    <td>Mountain biking experience</td>
    <td>$mtnbiking</td>
  </tr>

  <tr>
    <td>Hiking/Backpacking experience</td>
    <td>$hiking</td>
  </tr>

  <tr>
    <td>Caving experience</td>
    <td>$caving</td>
  </tr>

  <tr>
    <td>Snowsports experience</td>
    <td>$snowsports</td>
  </tr>

  <tr>
    <td>Other experience</td>
    <td>$other</td>
  </tr>

  <tr>
    <td>Why do you want to be an officer?</td>
    <td>$whyofficer</td>
  </tr>

</table>
<br>
ALLHTML


if ($quartermaster eq "on" || $quartermaster eq "checked"){
	print OUTFILE 'I want to be a quartermaster!<br>';
}

if ($tripadvisor eq "on" || $tripadvisor eq "checked"){
  print OUTFILE 'I want to be a trip advisor!<br>';
}

if ($treasurer eq "on" || $treasurer eq "checked"){
  print OUTFILE 'I want to be a treasurer!<br>';
}

if ($gear eq "on" || $gear eq "checked"){
  print OUTFILE 'I want to be in charge of buying gear!<br>';
}

my $now = localtime(time);

print OUTFILE "<p>Application submitted on $now</p>";

print OUTFILE end_html;

close(OUTFILE);

#write the index file
my $indexfilename = "./application/index.html";
open(INDEXFILE, ">$indexfilename") || die("Cannot open index file");

print INDEXFILE start_html("List of Applicants");
print INDEXFILE h1("List of Applicants");
print INDEXFILE "<p>If a name appears multiple times, it means they are 
different people or they submitted multiple times</p>\n";

opendir APPDIR, "./application";
my @contents = readdir APPDIR;
closedir APPDIR;

print INDEXFILE "<ul>\n";
my $i;
foreach $i(@contents){
  if ($i ne "index.html" && $i ne "makepage.pl.save"){
    my $html = rindex($i,'.');
    if ($html >= 1){
      my $name = substr($i,0,($html-10));
      print INDEXFILE '<li><a href="',"$i",'">',"$name</a></li>\n";
    }
  }
}
print INDEXFILE "</ul>\n";

print INDEXFILE end_html;

close(INDEXFILE);
