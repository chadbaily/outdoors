<h1>View Expense Report</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Member</th>
    <td>{member}</td>
  </tr>
  <tr>
    <th>Created</th>
    <td>{c_created_date|_date_format,'M j, Y'}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{status}</td>
  </tr>
</table>

{some:}
<p>The following are all expenses for this report.  Light-blue rows are expenses
for which we can seek Student Council reimbursement.</p>

<style type="text/css">
tr.expense1 td {
    background: #dfe4ee;
}
</style>

<table class="borders collapsed compact top">
  <tr>
    <th>Category</th>
    <th>Date</th>
    <th>Adventure</th>
    <th>Description</th>
    <th>Merchant</th>
    <th>Amount</th>
  </tr>{expense:}
  <tr class="expense{c_reimbursable}">
    <td>{cat_title}</td>
    <td nowrap>{c_expense_date}</td>
    <td>{c_adventure}</td>
    <td>{c_description}</td>
    <td>{c_merchant}</td>
    <td align="right">${c_amount}</td>
  </tr>{:expense}
  <tr>
    <th colspan="5">Total</th>
    <td align="right"><b>${total}</b></td>
  </tr>
</table>

{:some}

{none:}
<p class="notice">There are no expenses on this report.</p>
{:none}

{actions,{PAGE},{OBJECT},default}

</div>
