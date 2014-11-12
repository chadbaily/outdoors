<h1>Inactive Membership</h1>

<p>As soon as {CLUB_NAME} receives your signed <a
href="members/join/print-waiver" target="_blank">Liability
Waiver</a> and payment, we will activate your membership and send you a welcome
email.  Please, <b>follow the instructions on the liability waiver</b> so there
are no delays getting your membership activated.</p>

<table class="compact collapsed elbowroom" width="100%">
  <tr>
    <td>
      <form action="https://{PAYPAL_URL}/cgi-bin/webscr" method="post">{PAYPAL:}
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
        <img alt="" border="0" width="1" height="1" src="https://{PAYPAL_URL}/en_US/i/scr/pixel.gif" />{:PAYPAL}
      </form> 
    </td>
    <td>
      You can pay your membership dues with PayPal.  There is a ${PAYPAL_FEE|number_format,2} handling fee.<br/>
      <b>Note:</b> You will still need to send us your signed liability waiver if you pay with PayPal.
    </td>
  </tr>
</table>

<p>If an unwanted, inactive membership is keeping you from signing up with a new
membership, you may <a href="members/join/delete-old">delete old
memberships</a>.</p>
