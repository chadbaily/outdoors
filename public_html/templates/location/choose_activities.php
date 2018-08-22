<h1>Choose Activity Types</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to choose activities that you could do at <b>{C_TITLE}</b>.
This helps us tailor the website to user needs.  Check the checkboxes next to
the activities you'd do at this location, then click Save Changes at the bottom
of the form.</p>

{SUCCESS:}
<p class="notice">The location's activity types were updated.</p>
{:SUCCESS}

<form action="members/location/choose_activities/{OBJECT}" method="POST">
<input type="hidden" name="posted" value="1">

<p>
{ACTIVITY:}
  <input type="checkbox" name="activities[]" value="{C_UID}" id="activity{C_UID}" {CHECKED}>
  <label for="activity{C_UID}">{C_TITLE}</label><br>
{:ACTIVITY}
</p>

<p>
  <input type="reset" value="Reset">
  <input type="submit" value="Save Changes">
</p>

</form>

</div>
