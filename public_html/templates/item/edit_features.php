<h1>Edit Features for Item {OBJECT}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use the form below to edit this item's features.  You can either
choose an existing value, or just type a new one in the menu (this might only
work in newer browsers).</p>

{SUCCESS:}
<p class="notice">Your changes were saved.</p>
{:SUCCESS}

<form action="members/item/edit_features/{OBJECT}" method="POST">

<table>{ATTRS}
  <tr>
    <td colspan="2" align="right">
      <input type="submit" value="Update Features">
    </td>
  </tr>
</table>

<input type="hidden" value="1" name="edit_features">
</form>

<script>
window.onload = function() {
    document.forms[0].elements[0].focus();
}
</script>

<p style="text-align:center">
{LAST:}
<a href="members/item/edit_features/{LAST_ID}">&laquo; prev</a>
{:LAST}
<a href="members/item/list_all?category=&form-name=1&type={C_TYPE}">item list</a>
{NEXT:}
<a href="members/item/edit_features/{NEXT_ID}">next &raquo;</a>
{:NEXT}
</p>

</div> 
{ATTRIBUTE:}
  <tr>
    <td>{C_NAME}</td>
    <td>
      <select class="comboBox" name="{C_NAME}" onKeyPress="edit(event)" onBlur="this.editing = false;">{OPTION:}
         <option>{C_VALUE}</option>{:OPTION}
         {OPTIONS}
      </select>
    </td>
  </tr>{:ATTRIBUTE}
