<h1>Edit Features for Item Type &ldquo;{C_TITLE}&rdquo;</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<h2>Add a New Feature</h2>

<p>Use this form to add a new feature to this item type.  Every item of this
type will now be expected to have this feature, too.  When you're done, use the
"Edit" tab to choose which feature should show up in the list of items.</p>

<p class="{reserved}">The following words are reserved and cannot be used: id,
qty, condition, status.</p>

{FORM}

<p>This table shows the existing features for every item of this type, which
defines sort of a "template" for the items themselves.  Deleting any features
from the type will also delete the features from the items themselves.</p>

<form method="POST" action="members/item_type/edit_features/{OBJECT}">

<table>
  <tr>{ATTR:}
    <td>
      <input type="radio" name="delete" id="delete{C_UID}" value="{C_UID}">
    </td>
    <td{CLASS}><label for="delete{C_UID}">{C_NAME}</label></td>
  </tr>{:ATTR}
  <tr>
    <td colspan="3" align="right">
      <input type="submit" value="Delete Selected">
    </td>
  </tr>
</table>

</form>

</div>
