<h1>View Expense Submission</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>An expense submission is just a container to track which expenses you
submitted together for reimbursal.</p>

<table class="verticalHeaders collapsed elbowroom">
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

<p>The following are all expenses for this submission.  <b>Expenses are not
marked as submitted until you explicitly mark them.</b>  To mark expenses as
submitted, use the "Submit" tab.  To mark expenses as reimbursed, use the
"Accept" tab.</p>

<script src="assets/tinymenu.js"></script>

<table class="borders collapsed compact top">
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
      {actions,expense_submission_expense,{ese_uid},tinymenu,1}
      {cat_title}
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

{:some}

{none:}
<p class="notice">There are no expenses on this submission.</p>
{:none}

{actions,{PAGE},{OBJECT},default}

</div>
