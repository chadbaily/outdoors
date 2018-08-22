<h1>Administrative Tasks</h1>

<p>Use this page to manage the website.  You should only see links that you have
permission to execute, so if you don't see a link it just means you're not
allowed to run it.</p>

<p>The following links will help you manage various things in the database:</p>

<ul>
    <li><a href="members/activity">Manage activities</a></li>
    <li><a href="members/membership_type">Manage membership types</a></li>
</ul>

{ACTIVATE:}
<p>Use the following links to manage memberships:</p>

<ul>
  <li><a href="members/membership/list_all">Activate Memberships</a></li>
  {UNSUBSCRIBE:}
  <li><a href="members/admin/auto-unsubscribe-expired-members">Unsubscribe
  expired members</a></li>
  {:UNSUBSCRIBE}
</ul>
{:ACTIVATE}

{CONFIG:}
<p>You can configure various settings with the following link:</p>

<ul>
    <li><a href="members/admin/configuration">Manage website configuration</a></li>
</ul>
{:CONFIG}

{DBCOMMON:}

<p>Use the following links to manage the database itself:</p>

<p class="notice">NOTE: These pages iteratively query the database; some of the
queries require table scans.  Don't run them casually.</p>

<ul>
  <li><a href="members/admin/check-foreign-keys">
      Check the integrity of foreign keys in the database
    </a></li>
  <li><a href="members/admin/?action=check-orphaned-rows">
      Check the database for orphaned rows
    </a></li>
  <li><a href="members/admin/dedupe-questions">
      De-dupe questions in the database
    </a></li>
</ul>
{:DBCOMMON}

{SU:}
<ul>
  <li><a href="members/member/su/{root_uid}">Become the administrator</a></li>
</li>
{:SU}
