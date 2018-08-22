select count(*) as num
from [_]item as it
    inner join [_]item_type as ty on ty.c_uid = it.c_type
    inner join [_]item_category as ic on ic.c_uid = ty.c_category
where ({cat,int} is null or ic.c_uid = {cat,int})
    and ({status,int} is null or it.c_status = {status,int})
    and ({type,int} is null or ty.c_uid = {type,int})
    and it.c_deleted <> 1
    and ty.c_deleted <> 1
    and ic.c_deleted <> 1
