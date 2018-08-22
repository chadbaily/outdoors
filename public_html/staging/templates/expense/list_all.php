<h1>Expenses</h1>

{FORM}

{NONE:}
<p class="notice">There were no results.</p>
{:NONE}

{GENERIC:}

<p>The following is a summary of expenses for the date range you selected.  You
can click through to see a detailed view of the expenses in a category.</p>

<table class="borders collapsed compact top">
  <tr>
    <th>Category</th>
    <th>Expenses</th>
    <th>Receipts</th>
  </tr>{row:}
  <tr>
    <td>
      <a href="members/expense/list_all?form-name=1&category={c_uid}">{c_title}</a>
    </td>
    <td align="right">${expenses}</td>
    <td align="right">${receipts}</td>
  </tr>{:row}
</table>
{:GENERIC}

{BY_TYPE:}

<p>The following expenses matched your query.  To submit expenses for
reimbursement, select the checkboxes at left and use the button at the bottom of
the page.</p>

<script src="assets/tinymenu.js"></script>

<form action="members/expense_submission/create" method="POST">

<table class="borders collapsed compact top">
  <tr>
    <th>Category</th>
    <th>Date</th>
    <th>Adventure</th>
    <th>Merchant</th>
    <th>Items</th>
    <th>Total Cost</th>
    <th>Trip Leader</th>
  </tr>{row:}
  <tr>
    <td nowrap>
      <input type="checkbox" name="expense[]" value="{c_uid}" id="expense_{c_uid}" />
      <label for="expense_{c_uid}">{c_title}</label>
      {actions,expense,{c_uid},tinymenu,1}
    </td>
    <td>{c_expense_date|_date_format,'n/j/y'}</td>
    <td>{c_adventure}</td>
    <td>{c_merchant}</td>
    <td>
      <a href="members/expense_report/read/{c_report}">{c_description}</a>
    </td>
    <td align="right">${c_amount}</td>
    <td>{c_full_name}</td>
  </tr>{:row}
</table>

<p><input type="submit" value="Submit for Reimbursement"></p>

</form>

{:BY_TYPE}
