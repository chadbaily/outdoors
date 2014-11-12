<h1>Manage Your Profile</h1>

<p>Use the links below to manage your profile.</p>

<ul>
  <li><a href="members/member/optout/{OBJECT}">Opt out of emails</a></li>
  <li><a href="members/member/choose_activities/{OBJECT}">Choose activities you are interested in</a></li>
  <li><a href="members/profile/update">Update your profile</a></li>
  <li><a href="members/member/change_password/{OBJECT}">Change your password</a></li>
  <li><a href="members/subscription">Manage email subscriptions</a></li>
</ul>

<h2>Addresses, Phone Numbers, Chat Identities</h2>

<p>The following tables display your addresses, phone numbers, and chat
identities.  When an item is &ldquo;primary,&rdquo; it means that it's the item
that appears in the member directory.  When an item is &ldquo;private,&rdquo; it
won't appear in the member directory (club officers can see it though).  If you
want to change your preferences on any of these items, use the links under the
&ldquo;Primary&rdquo; and &ldquo;Private&rdquo; headings.</p>

<h3 id="address">Addresses</h3>

<table class="cleanHeaders" width="100%">
    <tr>
        <th width="60%">Name</th>
        <th width="20%">Primary?</th>
        <th width="20%">Private?</th>
    </tr>{ADDRESS:}
    <tr>
        <td><a href="members/address/read/{T_ADDRESS}">{C_TITLE}</a></td>
        <td>
            <a href="members/profile/default?primaryAddress={T_ADDRESS}#address">{PRIMARY}</a>
        </td>
        <td>
            <a href="members/profile/default?privateAddress={T_ADDRESS}#address">{PRIVATE}</a>
        </td>
    </tr>{:ADDRESS}
</table>

<p class="indented">&raquo; <a href="members/address/create">Create a new address</a>.</p>

<h3 id="phone">Phone Numbers</h3>

<table class="cleanHeaders" width="100%">
    <tr>
        <th width="60%">Name</th>
        <th width="20%">Primary?</th>
        <th width="20%">Private?</th>
    </tr>{PHONE:}
    <tr>
        <td>
            <a href="members/phone_number/read/{T_PHONE_NUMBER}">{C_TITLE}</a></td>
        <td>
            <a href="members/profile/default?primaryPhone={T_PHONE_NUMBER}#phone">{PRIMARY}</a>
        </td>
        <td>
            <a href="members/profile/default?privatePhone={T_PHONE_NUMBER}#phone">{PRIVATE}</a>
        </td>
    </tr>{:PHONE}
</table>

<p class="indented">&raquo; <a href="members/phone_number/create">Create a new phone number</a>.</p>

<h3 id="chat">Chat Identities</h3>

<table class="cleanHeaders" width="100%">
    <tr>
        <th width="60%">Screen Name</th>
        <th width="20%">Primary?</th>
        <th width="20%">Private?</th>
    </tr>{CHAT:}
    <tr>
        <td><a href="members/chat/read/{T_CHAT}">{C_SCREENNAME}</a></td>
        <td>
            <a href="members/profile/default?primaryChat={T_CHAT}#chat">{PRIMARY}</a>
        </td>
        <td>
            <a href="members/profile/default?privateChat={T_CHAT}#chat">{PRIVATE}</a>
        </td>
    </tr>{:CHAT}
</table>

<p class="indented">&raquo; <a href="members/chat/create">Create a new chat
identity</a>.</p>

<h2 id="privacy">Privacy Preferences</h2>


<p>Do you want your email address to show up in the member directory?  We
encourage you to let your email address be visible (it is only visible to
members).  Your current choice is {EMAIL_PRIVATE}.  You can change that choice
by clicking on 
<a href="members/profile/default?hideEmail=1#privacy">hide
my email address</a> or 
<a href="members/profile/default?hideEmail=0#privacy">show
my email address</a>.  If you hide your email address, it will just appear as
the text [private] in the member directory.</p>

<p>If you don't even want to appear in the member directory at all, you can set
yourself as &ldquo;hidden&rdquo; (we hope you don't feel the need to do this).
You are currently {HIDDEN} hidden.  You can change that by clicking on <a
href="members/profile/default?meHidden=1#privacy">hide me totally</a> or
<a href="members/profile/default?meHidden=0#privacy">don't hide
me</a>.  Please note that you can't lead adventures if you are hidden!  Also,
this doesn't change anything else about the website, such as keeping you
from receiving emails; it just keeps people from seeing your contact
information.</p>
