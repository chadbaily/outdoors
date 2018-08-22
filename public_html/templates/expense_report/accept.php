<h1>Accept Expense Report</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>The following are all expenses for this report.  Check the checkbox next to
any that are reimbursable by Student Council and submit the form.  <b>Note: by
accepting this expense report, you are recording transactions in the database 
saying that you have paid for these expenses.  If you haven't written a check
for all of these expenses, please correct the expense report before accepting
it.</b></p>

<script src="assets/tinymenu.js"></script>

<form method="POST" action="members/expense_report/accept/{OBJECT}">

<table class="cleanHeaders compact top">
  <tr>
    <th colspan="2">Description</th>
    <th>Date</th>
    <th>Category</th>
    <th>Adventure</th>
    <th>Merchant</th>
    <th>Amount</th>
  </tr>{expense:}
  <tr>
    <td>
      <input type="checkbox" name="expense[]" value="{c_uid}" id="expense{c_uid}">
    </td>
    <td>
      {actions,expense,{c_uid},tinymenu,1}
      <label for="expense{c_uid}">{c_description}</label>
    </td>
    <td nowrap>{c_expense_date}</td>
    <td>{cat_title}</td>
    <td>{c_adventure}</td>
    <td>{c_merchant}</td>
    <td align="right">${c_amount}</td>
  </tr>{:expense}
</table>

<input type="submit" value="Accept this Report" name="submitted" >

</form>

</div>
