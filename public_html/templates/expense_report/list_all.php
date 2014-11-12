<h1>Expense Reports</h1>

{FORM}
<br>

{SOME:}
<table class="borders collapsed">
  <tr>
    <th>Leader &amp; Date</th>
    <th>Total</th>
    <th>Status</th>
  </tr>{report:}
  <tr>
    <td>
      <a href="members/expense_report/read/{c_uid}">
        {c_created_date|_date_format,'m/d/Y'} {c_full_name}</a>
    </td>
    <td>{total}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>{:report}
</table>
{:SOME}

{NONE:}
<p class="notice">No matching records found.</p>
{:NONE}
