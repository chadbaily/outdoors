<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
  <base href="{BASE}">
  <title>{TITLE}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" href="assets/stylesheet.css" type="text/css">
  <script type="text/javascript" src="assets/combo-box.js" language="text/javascript"></script>
  <script type="text/javascript" src="assets/datechooser.js" language="javascript"></script>
  <script type="text/javascript" src="assets/script.js" language="javascript"></script>
{JQUERY:}
  <link rel="stylesheet" href="assets/jquery/jquery-ui.css" type="text/css">
  <script type="text/javascript" src="assets/jquery/jquery.js" language="javascript"></script>
  <script type="text/javascript" src="assets/jquery/jquery-ui.js" language="javascript"></script>
{:JQUERY}
{RSSFEED:}
  <link rel="alternate" type="application/rss+xml" title="Outdoors at UVA Upcoming Trips" href="/extras/rss.xml">
{:RSSFEED}
</head>

<body>

<div id="wrapper">

<div id="header">
<img src="images/header01.jpg" alt="{CLUB_NAME}" width="799" height="100" />
</div>

<div id="navbar" class="navbar">
{NAVBAR}
</div>

<div id="content">
{HELP}
{CONTENT}
</div>

<!-- Make sure pages without much content don't cause the navbar to overflow the
    containing box -->
<div style="clear:both">&nbsp;</div>
</div>

<p class="copyright">
{LOGOUT:}
[{C_FULL_NAME}]
<a href="members/main/logout">Log out</a> |
{:LOGOUT}
<a href="http://sourceforge.net/projects/socialclub">Powered by SocialClub</a> |
<a href="about/copyright-terms-privacy.shtml">Copyright &copy; Outdoors at
UVa</a> |
<a href="about/copyright-terms-privacy.shtml">Terms of Use</a> |
<a href="about/copyright-terms-privacy.shtml">Privacy Policy</a> |
<a href="about/contact.shtml">Contact Us</a> |

</p>

</body>
</html>
