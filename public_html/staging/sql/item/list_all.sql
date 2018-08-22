select
    it.c_uid as ID,
    it.c_type,
    it.c_qty as qty,
    coalesce(iat1.c_value, "[null]") as details1,
    coalesce(iat2.c_value, "[null]") as details2,
    co.c_title as "condition",
    ty.c_title as type,
    it.c_status
from [_]item as it
    inner join [_]condition as co on co.c_uid = it.c_condition
    inner join [_]item_type as ty on ty.c_uid = it.c_type
    inner join [_]item_category as ic on ic.c_uid = ty.c_category
    left outer join [_]item_feature as iat1 on iat1.c_item = it.c_uid
        and iat1.c_name = ty.c_primary_feature
        and iat1.c_deleted <> 1
    left outer join [_]item_feature as iat2 on iat2.c_item = it.c_uid
        and iat2.c_name = ty.c_secondary_feature
        and iat2.c_deleted <> 1
where ({cat,int} is null or ic.c_uid = {cat,int})
    and ({status,int} is null or it.c_status & {status,int} <> 0)
    and it.c_deleted <> 1
    and co.c_deleted <> 1
    and ty.c_deleted <> 1
    and ic.c_deleted <> 1
order by {orderby,none,,,,it.c_type}
limit {offset,int},{limit,int}
