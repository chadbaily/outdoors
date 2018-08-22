<h1>Edit Expense Report</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to add expenses to your report.  When you are done, click the
"Submit" tab above.  You can use the &raquo; link next to each expense to delete
or edit an expense.</p>

{some:}
<p>The following are all expenses for this report:</p>

<script src="assets/tinymenu.js"></script>

<table class="borders collapsed compact top">
  <tr>
    <th>Description</th>
    <th>Date</th>
    <th>Category</th>
    <th>Adventure</th>
    <th>Merchant</th>
    <th>Amount</th>
  </tr>{expense:}
  <tr>
    <td>
      {actions,expense,{c_uid},tinymenu,1}
      {c_description}
    </td>
    <td nowrap>{c_expense_date}</td>
    <td>{cat_title}</td>
    <td>{c_adventure}</td>
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
<p class="notice">This report has no expenses.</p>
{:none}

<fieldset>
<legend>Add Expenses</legend>

<p>Use this form to add an expense to your report.</p>

{FORM}

</fieldset>

</div>
