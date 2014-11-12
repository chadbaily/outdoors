update [_]checkout as co
    left join [_]checkout_item as ci on ci.c_checkout = co.c_uid
    inner join [_]item as it on ci.c_item = it.c_uid
set
    co.c_status = {checked_out,int},
    ci.c_status = {checked_out,int},
    it.c_status = {checked_out,int}
where co.c_uid = {checkout,int}
