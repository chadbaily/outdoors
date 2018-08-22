<h1>Delete Unwanted Memberships</h1>

<p>If you signed up but never followed through, and want to delete your old
membership so you can start again, you can use this page to clear out the old
memberships that are getting in your way.  Just use the form below to delete
them.  When you are done, you may return to the <a
href="members/join/final-instructions">final instructions</a>
page.</p>

{some:}
<form action="members/join/delete-old" method="POST">

{membership:}
<input type="checkbox" name="delete[]" value="{c_uid}" id="ms{c_uid}">
<label for="ms{c_uid}">{c_title}</label>
<br>{:membership}

<p><input type="submit" value="Delete Selected"></p>

</form>
{:some}

{none:}
<p class="notice">There are no old memberships to delete.</p>
{:none}
