<h1>Item {T_ITEM}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Notes</th>
    <td>{C_DESCRIPTION}</td>
  </tr>
  <tr>
    <th>Type</th>
    <td>
      <a href="members/item_type/read/{C_TYPE}">{TYPE_TITLE}</a>
    </td>
  </tr>
  <tr>
    <th>Purchase Date</th>
    <td>{C_PURCHASE_DATE}</td>
  </tr>
  <tr>
    <th>Condition</th>
    <td>{CONDITION_TITLE}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{C_STATUS|bitmaskString,'status_id'}</td>
  </tr>
  <tr>
    <th>Quantity</th>
    <td>{C_QTY}</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px 0">This item has the following features:</td>
  </tr>{ATTR:}
  <tr>
    <th>{C_NAME}</th>
    <td>{C_VALUE}</td>
  </tr>{:ATTR}
</table>

<p style="text-align:center">
{LAST:}
<a href="members/item/read/{LAST_ID}">&laquo; prev</a>
{:LAST}
<a href="members/item/list_all?category=&form-name=1&type={C_TYPE}">item list</a>
{NEXT:}
<a href="members/item/read/{NEXT_ID}">next &raquo;</a>
{:NEXT}
</p>

{actions,{PAGE},{OBJECT},default}

</div>
