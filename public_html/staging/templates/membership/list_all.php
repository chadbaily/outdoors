<h1>Activate Members</h1>

{SUCCESS:}

<p class="notice">The selected memberships were activated.  Below is a count of
how many memberships you activated:</p>

<table class="cleanHeaders">
  <tr>
    <th>Membership Type</th>
    <th>Number</th>
  </tr>{RESULTS:}
  <tr>
    <td>{MEMBERSHIP_TITLE}</td>
    <td>{NUM}</td>
  </tr>{:RESULTS}
</table>
{:SUCCESS}

{SOME:}

{form}

<p>The following memberships need to be activated.  Check the checkbox next to a
member to activate that membership.</p>
<table class="top cleanHeaders">
  <tr><th>Member Alert Legend</th></tr>
  <tr class="underage"><td>Member is underage and has not paid</td></tr>
  <tr class="underage_paid"><td>Member is underage and has already paid with PayPal</td></tr>
  <tr><td>Member has not paid</td></tr>
  <tr class="paid"><td>Member has already paid with PayPal</td></tr>
</table>
<p>If you have a signed waiver for a membership but don't see that member below,
    try searching for the member and then clicking on the "History" tab.  You
    can see all the memberships, active and inactive, from that page; you can
    click on a membership and activate it.</p>

<form method="post" action="members/membership/list_all">
<input type="hidden" name="submitted" value="1">

<table class="top cleanHeaders">
  <tr>
    <th colspan="2">Member</th>
    <th>Membership</th>
    <th>Dates</th>
  </tr>{row:}
  <tr{status}>
    <td>
      <input type="checkbox" name="membership[]" id="check{membership_uid}" value="{membership_uid}">
    </td>
    <td>
      <label for="check{membership_uid}">{c_last_name}, {c_first_name}</label>
    </td>
    <td>{c_title}</td>
    <td>
      &nbsp;{c_begin_date|_date_format,'n/j/y'}
      to {c_expiration_date|_date_format,'n/j/y'}
    </td>
  </tr>{:row}
</table>

<input type="submit" value="Activate Selected Members">

{:SOME}

</form>

