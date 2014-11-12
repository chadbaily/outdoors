insert into [_]item_note
    (c_owner, c_creator, c_created_date, c_item, c_status, c_condition, c_note)
select
    co.c_owner, co.c_creator, now(), it.c_uid, it.c_status, it.c_condition,
        concat('Checked out to ', me.c_full_name)
from [_]checkout as co
    inner join [_]checkout_item as ci on ci.c_checkout = co.c_uid
    inner join [_]item as it on ci.c_item = it.c_uid
    inner join [_]member as me on co.c_member = me.c_uid
where co.c_uid = {checkout,int}
