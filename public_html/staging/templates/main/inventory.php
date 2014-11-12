<table class="compact elbowroom cleanHeaders" style="float:right">
  <caption>Inventory Summary</caption>
  <tr>
    <th>Type</th>
    <th>Qty</th>
    <th>Qty Out</th>
  </tr>
  <tr>{row:}
    <td>
      <a href="members/item/list_all?category={ic_uid}&type={ty_uid}&form-name=1">{ic_title} &raquo; {ty_title}</a>
    </td>
    <td align="right">{existing}</td>
    <td align="right">{items_out}</td>
  </tr>{:row}
</table>

<h1>{CLUB_NAME} Inventory</h1>

<p>This section of the website handles equipment inventory.</p>

<p>You may do the following:</p>

<ul>

{item_list_all:}
<li><a href="members/item/list_all">Browse the inventory online</a></li>
{:item_list_all}
{item_create:}
<li><a href="members/item/create">Add items to the inventory</a></li>
{:item_create}
{checkout_create:}
<li><a href="members/checkout/create">Check equipment out to a member</a></li>
{:checkout_create}
{checkout_list_all:}
<li><a href="members/checkout/list_all">View equipment that's checked out</a></li>
<li><a href="members/checkout/list_all?form-name=1&status=1">View unfinished checkout sheets</a></li>
{:checkout_list_all}
{manage:}
<li><a href="members/item_category">Manage item categories</a></li>
<li><a href="members/item_type">Manage item types</a></li>
{:manage}

</ul>
