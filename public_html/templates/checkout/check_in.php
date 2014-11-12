<h1>Check in Inventory</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Mark below the equipment that you are checking back in.</p>

<form action="members/checkout/check_in/{OBJECT}" method="POST">

{someitems:}

<p><b>Items</b> that are checked out:</p>

<table class="cleanHeaders compact top">
  <tr>
    <th colspan="2">Item #</th>
    <th>Type</th>
    <th>Details 1</th>
    <th>Details 2</th>
    <th>Condition</th>
    <th>Comment</th>
  </tr>{item:}
  <tr>
    <td>
      <input type="checkbox" name="item[]" value="{c_uid}" id="item{c_uid}" />
    </td>
    <td>
      <label for="item{c_uid}">{it_uid}</label>
    </td>
    <td>{ty_title}</td>
    <td>{c_primary}</td>
    <td>{c_secondary}</td>
    <td>
      <input type="hidden" name="item{c_uid}oldcond" value="{c_title}">
      <select name="item{c_uid}condition" class="compact">
        <option value="">{c_title}</option>
        <option value="brand_new">brand_new</option>
        <option value="dirty">dirty</option>
        <option value="excellent">excellent</option>
        <option value="fair">fair</option>
        <option value="good">good</option>
        <option value="mint">mint</option>
        <option value="poor">poor</option>
        <option value="unknown">unknown</option>
        <option value="unsafe">unsafe</option>
        <option value="unusable">unusable</option>
      </select>
    </td>
    <td><input type="text" name="item{c_uid}comment" class="squeeze"></td>
  </tr>{:item}
</table>
{:someitems}

{somegear:}
<p><b>Gear</b> that is checked out:</p>

<table class="cleanHeaders compact top">
  <tr>
    <th colspan="2">Type</th>
    <th>Qty</th>
    <th>Description</th>
  </tr>{gear:}
  <tr>
    <td>
      <input type="checkbox" name="gear[]" value="{c_uid}" id="gear{c_uid}" />
    </td>
    <td>
      <label for="gear{c_uid}">{ic_title} &raquo; {it_title}</label>
    </td>
    <td>{c_qty}</td>
    <td>{c_description}</td>
  </tr>{:gear}
</table>
{:somegear}

<p><input type="submit" name="submitted" value="Check in Selected Equipment"></p>

</form>

</div>
