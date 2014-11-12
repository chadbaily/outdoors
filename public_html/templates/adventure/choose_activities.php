<h1>Choose Activities</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to choose activities that are related to this adventure.  This will
help classify adventures and make them easier to search.</p>

<p>You are choosing activity types for adventure <b>{C_TITLE}</b>
({C_DESCRIPTION|substr,0,250}...).</p>

{SUCCESS:}
<p class="notice">This adventure's activities were updated.</p>
{:SUCCESS}

<form action="members/adventure/choose_activities/{OBJECT}" method="POST">
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
