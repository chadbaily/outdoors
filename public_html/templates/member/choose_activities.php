<h1>Interests for {C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to choose activities that you're interested in.  This will
enable the website to customize itself for you by showing you things you care
about, and also lets the leaders know who's interested in what kinds of
adventures.  Check the checkboxes next to the activities you're interested in,
and then click Save Changes at the bottom of the form.</p>

{SUCCESS:}
<p class="notice">The interests were updated.</p>
{:SUCCESS}

<form action="members/member/choose_activities/{OBJECT}" method="POST">
<input type="hidden" name="posted" value="1">

<p>
{INTEREST:}
  <input type="checkbox" name="activities[]" value="{C_UID}" id="activity{C_UID}" {CHECKED}>
  <label for="activity{C_UID}">{C_TITLE}</label><br>
{:INTEREST}
</p>

<p>
  <input type="reset" value="Reset">
  <input type="submit" value="Save Changes">
</p>

</form>

</div>
