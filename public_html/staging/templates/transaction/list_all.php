<h1>Transactions</h1>

{FORM}

{NONE:}
<p class="notice">There were no results.</p>
{:NONE}

{GENERIC:}

<p>The following is a summary of transactions for the date range you selected.  You
can click through to see a detailed view of the transactions in a category.</p>

<table class="borders collapsed compact top">
  <tr>
    <th>Category</th>
    <th>Income</th>
    <th>Expenditure</th>
  </tr>{row:}
  <tr>
    <td>
      <a href="members/transaction/list_all?form-name=1&category={c_uid}">{c_title}</a>
    </td>
    <td align="right">${income}</td>
    <td align="right">${expenditures}</td>
  </tr>{:row}
</table>
{:GENERIC}

{BY_TYPE:}

<p>The following transactions matched your query.</p>

<table class="borders collapsed compact top">
  <tr>
    <th>Category</th>
    <th>Date</th>
    <th>Amount</th>
    <th>From</th>
    <th>To</th>
    <th>Description</th>
  </tr>{row:}
  <tr>
    <td>{c_title}</td>
    <td>{c_created_date|_date_format,'n/j/y'}</td>
    <td align="right">${c_amount}</td>
    <td>{from_name}</td>
    <td>{to_name}</td>
    <td>{description}</td>
  </tr>{:row}
</table>

{:BY_TYPE}
