select
    it.c_status,
    ci.c_uid,
    ci.c_checkout,
    coalesce(ci2.c_uid, -1) as already
from [_]item as it
    left outer join [_]checkout_item as ci on ci.c_item = it.c_uid
        and (ci.c_status & {checked_out,int} = {checked_out,int})
    left outer join [_]checkout_item as ci2 on ci2.c_item = it.c_uid
        and ci2.c_checkout = {checkout,int}
where it.c_uid = {item,int}
    and it.c_deleted = 0
