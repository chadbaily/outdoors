<h1>Add Items to Check Out</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to add equipment that {name} is borrowing.  You can add
equipment two ways.  The first is by item number and is preferred so we can
track exactly which item is checked out.  When this is not possible, you can
check out a quantity of a certain number of items, which is referred to as
&ldquo;gear&rdquo; below.  If you do this, please be as specific as possible
with your description fields, so we can track the inventory better.</p>

{good:}

{itemForm}

<fieldset>
<legend>Add Gear by Category</legend>

{activityForm}

{someFreq:}
{multiForm}
{:someFreq}
{noFreq:}
<p class="notice">There is no frequently-checked-out gear for this activity.</p>
{:noFreq}

{gearForm}

</fieldset>

{someitems:}
<p>You are checking out the following &ldquo;items&rdquo; to {name}:</p>
<table class="borders collapsed compact top">
  <tr>
    <th>Item #</th>
    <th>Type</th>
    <th>Details 1</th>
    <th>Details 2</th>
  </tr>{item:}
  <tr>
    <td>
      {actions,checkout_item,{c_uid},tinymenu,1}
      {it_uid}
    </td>
    <td>{ty_title}</td>
    <td>{c_primary}</td>
    <td>{c_secondary}</td>
  </tr>{:item}
</table>
{:someitems}

{noitems:}
<p class="notice">You haven't selected any items to check out.</p>
{:noitems}

{somegear:}
<p>You are checking out the following &ldquo;gear&rdquo; to {name}:</p>

<script src="assets/tinymenu.js">
</script>

<table class="borders collapsed compact top">
  <tr>
    <th>Type</th>
    <th>Qty</th>
    <th>Description</th>
  </tr>{gear:}
  <tr>
    <td>
      {actions,checkout_gear,{c_uid},tinymenu,1}
      {ic_title} &raquo; {it_title}
    </td>
    <td>{c_qty}</td>
    <td>{c_description}</td>
  </tr>{:gear}
</table>
{:somegear}

{nogear:}
<p class="notice">You haven't selected any gear to check out.</p>
{:nogear}

<p>When you are done adding equipment, click the "Accept" tab at the top.  You can
also <a href="members/checkout/delete/{OBJECT}">cancel
checking out</a> (deletes this checkout permanently).</p>
{:good}

{bad:}
<p class="error">Sorry, this equipment has already been checked out and you can't
edit this checkout further.</p>
{:bad}

</div>
