update [_]item as it
    inner join [_]condition as co on co.c_title = {condition,char}
    inner join [_]checkout_item as ci on ci.c_item = it.c_uid
set it.c_condition = co.c_uid
where ci.c_uid = {checkout_item,int}
