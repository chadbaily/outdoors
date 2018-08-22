<h1>Edit Item {OBJECT}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{SUCCESS:}
<p class="notice">The item was successfully updated.</p>
{:SUCCESS}

<p><b>Please note</b> that the quantity field below should not be used to
indicate when we get another item.  We need to track items individually, and
create new items for each item when we get new ones or lose old ones.  Use the
quantity field only when the item is not trackable individually, as in the case
of AA batteries or similar.</p>

{FORM}

<p style="text-align:center">
{LAST:}
<a href="members/item/write/{LAST_ID}">&laquo; prev</a>
{:LAST}
<a href="members/item/list_all?category=&form-name=1&type={C_TYPE}">item list</a>
{NEXT:}
<a href="members/item/read/{NEXT_ID}">next &raquo;</a>
{:NEXT}
</p>

</div>
