<h1>Opt-Outs for {C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to opt out of various types of email.  If the checkbox is
checked, you will get that type of email.  Any emails that don't have a
particular category will be sent under the "Everything Else" category.  Click
"Save Changes" at the bottom when you are done.</p>

<form action="members/member/optout/{OBJECT}" method="POST">
<input type="hidden" name="posted" value="1">

<p><b>I want to receive emails about...</b></p>

{optout:}
<input type="checkbox" name="cats[]" value="{c_uid}" id="cat{c_uid}" {CHECKED}>
<label for="cat{c_uid}">{c_title}</label>
<br>
{:optout}

{SUCCESS:}
<p class="notice"><b>{C_FULL_NAME}'s</b> opt-outs were updated.</p>
{:SUCCESS}

<p>
  <input type="reset" value="Reset">
  <input type="submit" value="Save Changes">
</p>

</form>

</div>
