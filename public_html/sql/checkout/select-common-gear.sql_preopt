select
    ic.c_title as ic_title,
    ty.c_uid,
    ty.c_title as ty_title,
    sum(case when (it.c_status = {missing,int}) then 0 else it.c_qty end)
        - sum(
            case when (it.c_status & {checked_out,int}) then it.c_qty
            when (cg.c_status = {checked_out,int}) then cg.c_qty
            else 0 end
        ) as available,
    sum(cg.c_qty) as num
from [_]item_category as ic 
    inner join [_]item_type as ty on ty.c_category = ic.c_uid
    inner join [_]mutex as mu on mu.c_mutex in (0, 1)
    left outer join [_]item as it on it.c_type = ty.c_uid
        and mu.c_mutex = 0
        and it.c_deleted = 0
    left outer join [_]checkout_gear as cg on ty.c_uid = cg.c_type
        and mu.c_mutex = 1
        and cg.c_deleted = 0
    left outer join [_]checkout as co on co.c_uid = cg.c_checkout
        and co.c_deleted = 0
        and co.c_activity = {activity,int}
where ty.c_deleted = 0
    and ic.c_deleted = 0
group by ic.c_uid, ic.c_title, ty.c_uid, ty.c_title
having count(co.c_uid) > 0
order by num desc
limit 10
