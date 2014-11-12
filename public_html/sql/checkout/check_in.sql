update [_]checkout as co
    left outer join [_]checkout_item as ci on ci.c_checkout = co.c_uid
        and ci.c_status = {checked_out,int}
    left outer join [_]checkout_gear as cg on cg.c_checkout = co.c_uid
        and cg.c_status = {checked_out,int}
set co.c_status = {checked_in,int}
where co.c_uid = {checkout,int}
    and ci.c_uid is null
    and cg.c_uid is null
