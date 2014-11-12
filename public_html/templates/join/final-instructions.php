<h1>Final Steps</h1>

<p>Your inactive membership has been saved in the database.  All you need to do
at this point is save, print, and sign the <a
href="http://www.outdoorsatuva.org/pub-files/OUVA_Liability_Waiver.pdf">liability waiver</a>, and send it in with your
dues. Due to the limited number of officers in town during school breaks, 
we do not check our mailbox as regularly as during the school year.  If 
you submit your waiver during summer or winter break, please email the 
officers at outdoors-officers@virginia.edu so that we know to check for 
your waiver and dues.  Thanks!

<!-- Old waiver: href="members/join/print-waiver" -->
<b>Please follow the instructions on the 
waiver</b>.</p>

<p>You will need <a
href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a> or
similar software to view and print the liability waiver.  To save and print your
liability waiver, <a href="pub-files/OUVA_Liability_Waiver.pdf">click here</a>. We
strongly encourage that you save an electronic copy. You may also sign the waiver
<a href="https://www.hellosign.com/s/c101b9bb">electronically</a>.</p> 


<!-- Old waiver:  <a href="members/join/print-waiver">click here</a>. -->

<p>We'll activate your membership and send you a welcome email as soon as we
receive everything. Please ensure that your payment matches the membership
choice you have made!</p>

<h2>Amount Due</h2>

<p>Please send payment (instructions are on the waiver) for your membership.
At the bottom of the page is a list of your inactive memberships saved in the database.  If
you see an old membership here, you can <a
href="members/join/delete-old">delete unwanted memberships</a>.</p>

<p>If you are looking to pay via PayPal, you may do so here:</p>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="HHH86H6PDH4ZS">
<table>
<tr><td><input type="hidden" name="on0" value="Types">Types</td></tr><tr><td><select name="os0">
    <option value="5 month (semester)">5 month (semester) $30.00 USD</option>
		<option value="12 month (year)">12 month (year) $50.00 USD</option>
		</select> </td></tr>
		</table>
		<input type="hidden" name="currency_code" value="USD">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<br>

<table align="center" class="cleanHeaders" width="400">
  <tr>
    <th>Membership</th>
    <th>Amount Due</th>
  </tr>{MEMBERSHIPS:}
  <tr>
    <td>{C_TITLE}</td>
    <td align="right">${C_TOTAL_COST}</td>
  </tr>{:MEMBERSHIPS}
  <tr>
    <td colspan="2"><hr noshade="true" size="1"></td>
  </tr>
  <tr>
    <td><b>Total Amount Due</b></td>
    <td align="right"><b>${TOTAL|number_format,2}</b></td>
  </tr>
</table>
