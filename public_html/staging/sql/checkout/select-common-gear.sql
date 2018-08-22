select
    ic.c_title as ic_title,
    ty.c_uid,
    ty.c_title as ty_title,
    it.existing
        - sum(coalesce(it.it_out, 0) + coalesce(cg.g_out, 0)) as available,
    cg.total as num
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
        select cg.c_checkout, cg.c_type, 
            sum(c_qty) as total, 
            sum(case when cg.c_status={checked_out,int} then c_qty else 0 end) as g_out 
        from [_]checkout_gear as cg 
        where cg.c_deleted = 0
        group by cg.c_type
    ) as cg on ty.c_uid = cg.c_type
    left outer join [_]checkout as co on co.c_uid = cg.c_checkout
        and co.c_deleted = 0
        and co.c_activity = {activity,int}
where ty.c_deleted = 0
    and ic.c_deleted = 0
group by ic.c_uid, ic.c_title, ty.c_uid, ty.c_title
having count(co.c_uid) > 0
order by c_uid
limit 10
