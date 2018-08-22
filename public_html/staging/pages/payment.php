<?

include_once("paypal_ipn.php");
include_once("membership.php");
include_once("member.php");
include_once("payment.php");
// Adapted from a script by robin kohli (robin@19.5degs.com) for 19.5 Degrees (http://www.19.5degs.com)

// email header
$em_headers  = "From: " . $cfg['paypal_email_name'] . "\n";	
$em_headers .= "Reply-To: " . $cfg['paypal_email'] . "\n";
$em_headers .= "Return-Path: " . $cfg['paypal_email'] . "\n";
$em_headers .= "Organization: " . $cfg['club_name'] . "\n";
$em_headers .= "X-Priority: 3\n";


$paypal_info = $HTTP_POST_VARS;
$paypal_ipn = new paypal_ipn($paypal_info,$cfg['paypal_url']);

# Reply to paypal.  We should get a status back
$paypal_ipn->error_email = 'payments@outdoorsatuva.org';
$paypal_ipn->email_headers = $em_headers;
$paypal_ipn->send_response($em_headers);

if (!$paypal_ipn->is_verified()) {
	$paypal_ipn->error_out("Bad order (PayPal says it's invalid)" . $paypal_ipn->paypal_response);
	die();
}

switch( $paypal_ipn->get_payment_status() )
{
	case 'Pending':
		
		$pending_reason=$paypal_ipn->paypal_post_vars['pending_reason'];
					
		if ($pending_reason!="intl") {
			$paypal_ipn->error_out("Pending Payment - $pending_reason");
			break;
		}


	case 'Completed':
		$membership = new membership();
		$membership->select($paypal_ipn->paypal_post_vars['item_number']);
	
		if ($paypal_ipn->paypal_post_vars['txn_type']=="reversal") {
			$reason_code=$paypal_ipn->paypal_post_vars['reason_code'];
			$paypal_ipn->error_out("PayPal reversed an earlier transaction.");
			// you should mark the payment as disputed now
		} else {
					
			if (
				(strtolower(trim($paypal_ipn->paypal_post_vars['receiver_email'])) == $cfg['paypal_email']) && 
				(trim($paypal_ipn->paypal_post_vars['mc_currency'])=="USD") && 
				(((float) trim($paypal_ipn->paypal_post_vars['payment_gross'])) == $membership->getTotalCost() + $cfg['paypal_handling_cost']) 
				) {
				$payment = new payment();
				$payment->initialize($paypal_ipn->paypal_post_vars);
				$payment_id = $payment->insert();
				
				if ( $payment_id ) {
					#Mark the membershp as paid
		    		$membership->setStatus($cfg['status_id']['paid']);
                    $membership->setAmountPaid($paypal_ipn->paypal_post_vars['payment_gross']);
		    		$membership->update();
		    		#Add a note to the member that they paid
		    		$member = new member();
					$member->select($membership->getMember());
					$member->addNote("Paid via paypal for membership " . $membership->getUID() . " ($" .  number_format($paypal_ipn->paypal_post_vars['payment_gross'], 2, ".", ",") . ")");
					
					$paypal_ipn->error_out("This was a successful transaction", "SUCCESS");

				} else {
					$paypal_ipn->error_out("This was a duplicate transaction");
				} 
			} else {
				$paypal_ipn->error_out("The data for the amount paid did not match our records");
			}
		}
		break;
		
	case 'Failed':
		// this will only happen in case of echeck.
		$paypal_ipn->error_out("Failed Payment");
	break;

	case 'Denied':
		// denied payment by us
		$paypal_ipn->error_out("Denied Payment");
	break;

	case 'Refunded':
		// payment refunded by us
		$paypal_ipn->error_out("Refunded Payment");
	break;

	case 'Canceled':
		// reversal cancelled
		// mark the payment as dispute cancelled		
		$paypal_ipn->error_out("Cancelled reversal");
	break;

	default:
		// order is not good
		$paypal_ipn->error_out("Unknown Payment Status - " . $paypal_ipn->get_payment_status());
	break;

} 

?>
