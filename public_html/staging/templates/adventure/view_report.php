<h1>View Report</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

Adventure: <b>{C_TITLE}</b>

<p>Attendees are shown in the order they joined<sup>[1]</sup>.  The first column shows whether
the member is joined or waitlisted.  The last shows the number of absences and
times the member has been waitlisted over the past 6 months<sup>[2]</sup>.  Use the radio button in the left column,
and the buttons at the bottom of the table, to manage attendees.</p>

<script type="text/javascript" language="javascript">
//Create the tab structure
$(document).ready(function(){
    $("#report").tabs({ load: onTabLoaded});
    $('#waitlist-dialog').dialog({autoOpen: false,buttons: { "Ok": function() { $(this).dialog("close"); } }, minWidth: 500});
});

function onTabLoaded(event, ui) {
    console.log(ui.panel);
    updateFields(currentCounts("#" + ui.panel.id)); 
}

function onSubmit(form) {
    var curDiv = getCurrentDiv();
    console.log($(curDiv + " input[type=radio]:checked").attr('value'));
    if ( $(curDiv + " input[type=radio]:checked").attr('value') == 'waitlist' ) {
        $('#waitlist-dialog').dialog('option','buttons',{"OK": function(event, ui) {
            $(this).dialog("close");
            var get_str = $('#waitlist-dialog form').serialize();
            get_str += "&" + $(form).serialize();
            jQuery.get('index.php',get_str,onAttendeesMoved,'json');
        }});
        $('#waitlist-dialog').dialog("open");
    }
    else {
        jQuery.get('index.php',$(form).serialize(),onAttendeesMoved,'json');
    }
    return false;
}

function onCheckAllClicked() {
    var curDiv = getCurrentDiv();
    var checkboxes = $(curDiv + " input[type=checkbox]");
    if ( $(curDiv  + ' input[name=check_all]:checked').length == 1 ) {
        checkboxes.attr('checked','true');
    } else {
        checkboxes.removeAttr('checked');
    }
}   

function getCurrentDiv() {
    var divregex = /#.*/;
    return divregex.exec($("#report li a")[$("#report").tabs('option','selected')].href);
}

function onAttendeesMoved(data, status) {
    
    var cur_div = getCurrentDiv();
    for ( var index in data.attendees ) {
        attendee = data.attendees[index];
        if (attendee.response == "success") {
           removeLine(cur_div, attendee.attendee); 
           if (attendee.action == 'join') {
           }
           if (attendee.action == 'waitlist') {
           }
           if (attendee.action == 'withdraw') {
           }
        }
    }
    var newCounts = {num_waitlisted:data.num_waitlisted, num_joined:data.num_joined, num_both:data.num_waitlisted+data.num_joined};
    updateFields(newCounts);
    currentCounts(cur_div, newCounts);
}

function currentCounts(cur_div, newCounts) {
    var num_joined = 0, num_waitlisted = 0, num_both;
    var element =  $(cur_div + " p[title=num_joined]");
    if ( element.length != 0 ) {
        if ( newCounts ) element.text(newCounts.num_joined);
        num_joined = parseInt(element.text());
    }
    element =  $(cur_div + " p[title=num_waitlisted]")
    if ( element.length != 0 ) {
        if ( newCounts ) element.text(newCounts.num_waitlisted);
        num_waitlisted = parseInt(element.text());
    }
    num_both = num_joined + num_waitlisted;
    
    return {num_joined:num_joined,num_waitlisted:num_waitlisted,num_both:num_both};
}

function updateFields(curCounts) {
    $("#joined_tab").text("Joined (" + curCounts.num_joined + "/" + curCounts.num_both + ")");
    $("#waitlisted_tab").text("Waitlisted (" + curCounts.num_waitlisted + "/" + curCounts.num_both + ")");
    $("#both_tab").text("Both (" + curCounts.num_both + ")");

}

function removeLine(div, attendee_id) {
    $(div + " tr td input[value=" + attendee_id + "]").parent().parent().addClass("highlight").fadeOut("normal");//remove();
}

function formEnabled(form) {
    var left = false;
    var bottom = false;
    if (form.elements["attendee[]"].checked) {
        left = true;
    }
    else {
        for (var i = 0; i < form.elements["attendee[]"].length; ++i) {
            if (form.elements["attendee[]"][i].checked) {
                left = true;
            }
        }
    }
    for (var i = 0; i < form.elements["attendee_action"].length; ++i) {
        if (form.elements["attendee_action"][i].checked) {
            bottom = true;
        }
    }
    return (left && bottom);
}

function enableForm(form) {
    form.elements['submit'].disabled = !formEnabled(form);
}
</script>

<div id="report">
  <ul>
    <li><a id="joined_tab" href="members/adventure/view_report/{T_ADVENTURE}?form-name=1&piece=report&status=default&question=0"><span>Joined</span></a></li>
    <li><a id="waitlisted_tab" href="members/adventure/view_report/{T_ADVENTURE}?form-name=1&piece=report&status=waitlisted&question=0"><span>Waitlisted</span></a></li>
    <li><a id="both_tab" href="members/adventure/view_report/{T_ADVENTURE}?form-name=1&piece=report&status=&question=0"><span>Both</span></a></li>
  </ul>
</div>

<div id="waitlist-dialog" title="Waitlist Action">
<p>Where do you want to move the attendees?</p>
<p>
    <form>
        <input type="radio" value="front" name="where" id="where1">
        <label for="where1">Move the attendee(s) to the <b>front</b> of the waitlist</label><br/>
        <input type="radio" value="back" name="where" id="where2">
        <label for="where2">Move the attendee(s) to the <b>back</b> of the waitlist</label>
    </form>
</p>
</div>


<p><sup>[1]</sup><span class="tiny">After a member is automatically removed from
the waitlist his/her joined date is reset, so s/he appears at the end of the
roster.  This is correct behavior.  It doesn't mean that people who joined later
are getting off the waitlist first.<br>
<sup>[2]</sup>Clicking on the number of waitlists or absenses a person has will show
their history for all time.</span></p>

</div>
