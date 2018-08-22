<style type="text/css">
td.overdue { color:red; }
</style>
<h1>Checked-Out Equipment</h1>

{FORM}
<br>

{SOME:}
<table class="borders collapsed compact">
  <tr>
    <th>Member</th>
    <th>Email</th>
    <th>Date Out</th>
    <th>Due Date</th>
    <th>Status</th>
    <th>Total/Out</th>
    <th>Overdue</th>
  </tr>{checkout:}
  <tr>
    <td><a href="members/checkout/read/{c_uid}">{c_last_name}, {c_first_name}</a></td>
    <td><a href="mailto:{c_email}">{c_email}</a></td>
    <td>{c_created_date|_date_format,'n/j/Y'}</td>
    <td>{c_due_date|_date_format,'n/j/Y'}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
    <td>{qty}/{qty_out}</td>
    <td class="{overdue}">{overdue}</td>
  </tr>{:checkout}
</table>
{:SOME}

{NONE:}
<p class="notice">No matching records found.</p>
{:NONE}
