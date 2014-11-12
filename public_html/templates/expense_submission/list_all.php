<h1>Expense Submissions</h1>

{FORM}
<br>

{SOME:}
<table class="borders collapsed">
  <tr>
    <th>Owner &amp; Date</th>
    <th>Total</th>
    <th>Status</th>
  </tr>{report:}
  <tr>
    <td>
      <a href="members/expense_submission/read/{c_uid}">
        {c_created_date|_date_format,'m/d/Y'} {c_full_name}</a>
    </td>
    <td>{total}</td>
    <td>{status_title}</td>
  </tr>{:report}
</table>
{:SOME}

{NONE:}
<p class="notice">No matching records found.</p>
{:NONE}
