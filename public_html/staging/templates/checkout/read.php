<h1>Items Checked Out to {name}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>The following inventory is checked out to {name}:</p>

<h2>Items</h2>

<table class="borders collapsed compact top">
  <tr>
    <th>Item #</th>
    <th>Type</th>
    <th>Details 1</th>
    <th>Details 2</th>
    <th>Status</th>
  </tr>{item:}
  <tr class="{c_status|bitmaskString,'status_id'}">
    <td>
      <a href="members/item/read/{it_uid}">{it_uid}</a>
    </td>
    <td>{ty_title}</td>
    <td>{c_primary}</td>
    <td>{c_secondary}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>{:item}
</table>

<h2>Gear</h2>

<table class="borders collapsed compact top">
  <tr>
    <th>Type</th>
    <th>Qty</th>
    <th>Description</th>
    <th>Status</th>
  </tr>{gear:}
  <tr class="{c_status|bitmaskString,'status_id'}">
    <td>{ic_title} &raquo; {it_title}</td>
    <td>{c_qty}</td>
    <td>{c_description}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>{:gear}
</table>

<p class="notice">This equipment is due on {due|_date_format,'M j, Y'}.</p>

{actions,{PAGE},{OBJECT},default}

</div>
