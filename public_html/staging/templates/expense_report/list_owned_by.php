<h1>Your Expense Reports</h1>

{SOME:}
<table class="borders collapsed">
  <tr>
    <th>Date</th>
    <th>Total</th>
    <th>Status</th>
  </tr>{report:}
  <tr>
    <td>
      <a href="members/expense_report/read/{c_uid}">
        {c_created_date|_date_format,'m/d/Y'}</a>
    </td>
    <td>{total}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>{:report}
</table>
{:SOME}

{NONE:}
<p class="notice">No matching records found.</p>
{:NONE}
