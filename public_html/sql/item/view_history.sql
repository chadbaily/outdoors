select
    ci.c_checkout,
    ci.c_created_date,
    ci.c_status,
    me.c_uid,
    me.c_full_name
from [_]checkout_item as ci
    inner join [_]checkout as co on ci.c_checkout = co.c_uid
    inner join [_]member as me on me.c_uid = co.c_member
where ci.c_item = {item,int}
    and ci.c_deleted = 0
    and co.c_deleted = 0
    and me.c_deleted = 0
