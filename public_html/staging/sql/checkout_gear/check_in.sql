update [_]checkout_gear
set c_status = {status,int}, c_checkin_member = {member,int}
where c_uid = {checkout_gear,int}
