<div>
{SOME:}
<form action="#" method="GET" onsubmit="return onSubmit(this)">
  <input type="hidden" name="page" value="adventure"/>
  <input type="hidden" name="object" value="{OBJECT}"/>
  <input type="hidden" name="action" value="move_attendees"/>
  <input type="hidden" name="response_type" value="JSON"/>
<table class="compact cleanHeaders top">
  <tr>
    <td>&nbsp;</td>
    <th>J/W</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Sex</th>
    <th>Birthdate</th>
    <th>Abs/Wtlst</th>
  </tr>{ROW:}
  <tr>
    <td>
      <input type="checkbox" class="compact" name="attendee[]" value="{C_UID}" onClick="enableForm(this.form)">
    </td>
    <td>{C_STATUS}</td>
    <td>
      <a href="members/member/read/{C_MEMBER}" title="View details for this member">{C_FULL_NAME}</a>
    </td>
    <td><a href="mailto:{C_EMAIL}">{C_EMAIL}</a></td>
    <td nowrap>{NUMBERS}</td>
    <td>{C_GENDER}</td>
    <td>{C_BIRTH_DATE|_date_format,'n/j/y'}</td>
    <td>
      <a href="members/member/view_absences/{C_MEMBER}"
        title="View absences for {C_FULL_NAME}">{C_ABSENCES}</a>/<a
        href="members/member/view_waitlist/{C_MEMBER}">{C_WAITLISTS}</a>
    </td>
  </tr>
{:ROW}
  <tr><td colspan="8" style="border-top:1px solid #000; text-align:right">
  <input type="checkbox" name="check_all" id="{DIV_PREFIX}check_all" value="check_all" onClick="onCheckAllClicked()"/>
  <label for="{DIV_PREFIX}check_all">Select all</label>
  <input type="radio" name="attendee_action" value="withdraw" id="{DIV_PREFIX}action1" onClick="enableForm(this.form)">
  <label for="{DIV_PREFIX}action1">Withdraw</label>
  <input type="radio" name="attendee_action" value="waitlist" id="{DIV_PREFIX}action2" onClick="enableForm(this.form)">
  <label for="{DIV_PREFIX}action2">Waitlist</label>
  <input type="radio" name="attendee_action" value="join" id="{DIV_PREFIX}action3" onClick="enableForm(this.form)">
  <label for="{DIV_PREFIX}action3">Join from Waitlist</label>
  <input type="radio" name="attendee_action" value="mark_absent" id="{DIV_PREFIX}action4" onClick="enableForm(this.form)">
  <label for="{DIV_PREFIX}action4">Mark Absent</label>
  <input type="submit" name="submit" value="Go" disabled="true">
  </td></tr>
</table>
</form>

{SHOW_QUESTIONS:}
<p>Show responses: <a onclick="$('#{DIV_PREFIX}question').load('{LINK}&question=_V'); return false;" href="#">Vertically</a> or <a href="#" onclick="$('#{QUESTION_DIV}').load('{LINK}&question=_H'); return false;">Horizontally</a></p>
{:SHOW_QUESTIONS}
<div id="{DIV_PREFIX}question"></div>
{:SOME}

{NONE:}
<p class="notice">There is nothing to display.</p>
{:NONE}
<p style="display:none;" title="num_joined">{NUM_JOINED}</p>
<p style="display:none;" title="num_waitlisted">{NUM_WAITLISTED}</p>
</div>
