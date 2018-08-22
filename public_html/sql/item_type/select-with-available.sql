select
    ic.c_uid as cat_uid,
    ic.c_title as cat_title,
    ty.c_uid,
    ty.c_title,
    it.existing
        - sum(coalesce(it.it_out, 0) + coalesce(cg.g_out, 0)) as available
from [_]item_category as ic 
    inner join [_]item_type as ty on ty.c_category = ic.c_uid
    left outer join (
        select it.c_type,
            sum(if(it.c_status = {missing,int},0,it.c_qty)) as existing,
            sum(if(it.c_status & {checked_out,int}, it.c_qty,0)) as it_out
        from [_]item as it 
        where it.c_deleted = 0
        group by it.c_type
    ) as it on it.c_type = ty.c_uid
    left outer join (
        select cg.c_type, sum(c_qty) as g_out 
        from [_]checkout_gear as cg 
        where cg.c_status = {checked_out,int} and cg.c_deleted = 0
        group by cg.c_type
    ) as cg on ty.c_uid = cg.c_type
where ({cat,int} is null or ic.c_uid = {cat,int})
    and ty.c_deleted = 0
    and ic.c_deleted = 0
group by ic.c_uid, ic.c_title, ty.c_uid, ty.c_title
order by ic.c_title, ty.c_title 
