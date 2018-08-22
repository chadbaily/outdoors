<?php
$cfg['login_mode'] = 'partial';
include_once("includes/authorize.php");

include_once("EasyPdf.php");

# Create some objects...
$address = $obj['user']->getPrimaryAddress();
$phone = $obj['user']->getPrimaryPhoneNumber();

# If the member has no address or phone number, we can't continue.
if (!is_object($address) || !is_object($phone)) {
    trigger_error("Can't find address or phone number for member $cfg[user]",
        E_USER_ERROR);
    $res['content'] = file_get_contents("templates/join/cant-print-waiver.php");
    $res['title'] = "Error Creating Waiver";
    return;
}

# Set up the PDF and add some common stuff to it
$pdf =& new EasyPdf();
$pdf->selectFont("utility/fonts/Times-Roman.afm");
$pdf->ezSetMargins(40, 40, 40, 40);
# Put a line on the top and bottom of all the pages
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(40,40,552,40);
$pdf->line(40,800,552,800);
$pdf->addText(185,28,9, "Outdoors at UVa, P.O. Box 400444, Charlottesville, VA 22904");
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all,'all');

# Print out the header of the page
# $pdf->ezText(strtoupper("$cfg[club_name]\n"), 12,
$pdf->ezText(strtoupper("OUTDOORS AT UVA, LTD. \n"), 14,
    array('justification' => 'center'));
$pdf->ezText("<b><u> INDEMNIFICATION AGREEMENT </u></b>\n", 14,
    array('justification' => 'center'));

# Add the text of the liability waiver
$waiverText = $obj['user']->insertIntoTemplate(
    file_get_contents("templates/text/liability-waiver.txt"));
$pdf->ezText($waiverText, 11, array('justification' => 'full'));

# Add the member's information in a table
$memberInfo = array(
        array('name' => 'Name:', 'val' => $obj['user']->getFullName()),
        array('name' => 'Birthdate:', 'val' => date("F j, Y",
                strtotime($obj['user']->getBirthDate()))),
        array(
            'name' => 'Local Address:', 
            'val' => $address->getLine1() . "\n" . $address->getLine2()),
        array('name' => 'Email:', 'val' => $obj['user']->getEmail()),
        array('name' => 'Local Phone:', 'val' => $phone->toString()));
$pdf->ezTable(
    $memberInfo,
    array('name' => 'Name', 'val' => 'Value'),
    '',
    array('showHeadings' => 0));

$now = date("Y-m-d");
$birth = new DateTime($obj['user']->getBirthDate());
$plus18 = $birth->addYears(18);
# Print out the signature lines
$pdf->ezText("\n\n\nSignature:______________________________________ Date: _____________");
if($now < $plus18->toString("Y-m-d")){
   $pdf->ezText("\nName and signature of parent or legal guardian (<b>required</b> to become a member before your 18th birthday):");
   $pdf->ezText("\n\nPrinted Name:__________________________________ Signature:______________________________________ Date: _____________");
}

# Add the instructions
$pdf->ezNewPage();
$pdf->ezText("<b>INSTRUCTIONS</b>", 12, array('justification' => 'center'));
$pdf->ezText(
    file_get_contents("templates/text/liability-instructions.txt"),
    12, array('justification' => 'full'));

# Add a table describing the member's inactive memberships
$total = 0;
$memberships = array();
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership/select-for-waiver.sql");
$cmd->addParameter("member", $cfg['user']);
$cmd->addParameter("inactive", $cfg['status_id']['inactive']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $memberDebts[] = array(
        'description' => $row['c_title'],
        'amount' => number_format($row['c_total_cost'], 2));
    $total += $row['c_total_cost'];
}
# Add a total row...
$memberDebts[] = array(
    'description' => "<b>Total:</b>",
    'amount' => '$<b>' . number_format($total, 2) . "</b>");
# Create the table
$pdf->ezTable($memberDebts, 
    array(
        'description' => "Description", 
        'amount' => "Amount"), 
    '<b>Amounts Owed</b>', 
    array('showHeadings' => 1)); 


# Send to the browser
$pdf->ezStream();
exit;
?>

