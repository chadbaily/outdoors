update [_]checkout_item as ci
    inner join [_]item as it on it.c_uid = ci.c_item
set it.c_status = {status,int},
    ci.c_status = {status,int}
where ci.c_uid = {checkout_item,int}
