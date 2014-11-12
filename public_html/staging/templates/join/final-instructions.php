<h1>Final Steps</h1>

<p>Your inactive membership has been saved in the database.  All you need to do
at this point is print and sign the <a
href="http://www.outdoorsatuva.org/pub-files/OUVA_Liability_Waiver.pdf">liability waiver</a>, and send it in with your
dues.  <b>Please follow the instructions on the waiver</b>.</p>

<p>You will need <a
href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a> or
similar software to view and print the liability waiver.  To print your
liability waiver, <a href="www.outdoorsatuva.org/pub-files/OUVA_Liability_Waiver.pdf">click here</a>.</p>

## PHP waiver: href="members/join/print-waiver"

<p>We'll activate your membership and send you a welcome email as soon as we
receive everything.</p>

<h2>Amount Due</h2>

<p>Please send payment (instructions are on the waiver) for your membership.
The following is a list of your inactive memberships saved in the database.  If
you see an old membership here, you can <a
href="members/join/delete-old">delete unwanted memberships</a>.</p>

<p>
You can pay your membership dues with PayPal.  There is a ${PAYPAL_FEE|number_format,2} handling fee.<br/>
<b>Note:</b> You will still need to send us your signed liability waiver if you pay with PayPal.
</p>

<table align="center" class="cleanHeaders" width="600">
  <tr>
    <th>Membership</th>
    <th>Amount Due</th>
    <th>&nbsp;</th>
  </tr>{MEMBERSHIPS:}
  <tr>
    <td>{MEMBERSHIP_NAME}</td>
    <td align="right">${TOTAL_COST}</td>
    <td>
      <form action="https://{PAYPAL_URL}/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="{PAYPAL_EMAIL}"/>
        <input type="hidden" name="cmd" value="_xclick"/>
        <input type="hidden" name="item_name" value="{MEMBERSHIP_NAME}"/>
        <input type="hidden" name="amount" value="{MEMBERSHIP_COST}"/>
        <input type="hidden" name="item_number" value="{MEMBERSHIP_ID}"/>
        <input type="hidden" name="currency_code" value="USD"/>
        <input type="hidden" name="first_name" value="{FIRST_NAME}"/>
        <input type="hidden" name="last_name" value="{LAST_NAME}"/>
        <input type="hidden" name="address1" value="{ADDRESS}"/>
        <input type="hidden" name="city" value="{CITY}"/>
        <input type="hidden" name="state" value="{STATE}"/>
        <input type="hidden" name="zip" value="{ZIP}"/>
        <input type="image" name="submit" border="0" src="https://{PAYPAL_URL}/en_US/i/btn/btn_paynowCC_LG.gif" alt="PayPal - The safer, easier way to pay online"/>
        <img alt="" border="0" width="1" height="1" src="https://{PAYPAL_URL}/en_US/i/scr/pixel.gif" />
      </form>
    </td>
  </tr>{:MEMBERSHIPS}
  <tr>
    <td colspan="3"><hr noshade="true" size="1"></td>
  </tr>
  <tr>
    <td><b>Total Amount Due</b></td>
    <td align="right"><b>${TOTAL|number_format,2}</b></td>
  </tr>
</table>
