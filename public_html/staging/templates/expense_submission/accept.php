<h1>Mark Expenses as Reimbursed</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{some:}

<p>Once expenses have been reimbursed, you need to mark them as reimbursed.
This will create transactions in the database, so you should be sure you only
mark the expenses for which you actually got a reimbursement.</p>

<form action="members/expense_submission/accept/{OBJECT}" method="POST">

<table class="top borders collapsed compact">
  <tr>
    <th>Category</th>
    <th>Date</th>
    <th>Adventure</th>
    <th>Merchant</th>
    <th>Items</th>
    <th>Total</th>
    <th>Trip Leader</th>
  </tr>{expense:}
  <tr>
    <td nowrap>
      <input type="checkbox" name="expense[]" value="{c_uid}" id="expense_{c_uid}" />
      <label for="expense_{c_uid}">{cat_title}</label>
    </td>
    <td nowrap>{c_expense_date}</td>
    <td>{c_adventure}</td>
    <td>{c_merchant}</td>
    <td>{c_description}</td>
    <td align="right">${c_amount}</td>
    <td>{c_full_name}</td>
  </tr>{:expense}
  <tr>
    <th colspan="5">Total</th>
    <td align="right"><b>${total}</b></td>
    <td>&nbsp;</td>
  </tr>
</table>

<p><input type="submit" name="submitted" value="Mark Expenses as Reimbursed"></p>

</form>
{:some}

{none:}
<p class="notice">There are no expenses on this submission.</p>
{:none}

</div>
