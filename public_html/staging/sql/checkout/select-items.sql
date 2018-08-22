select
    ci.c_uid,
    it.c_uid as it_uid,
    it.c_condition,
    co.c_title,
    ci.c_status,
    coalesce(iat1.c_value, "[null]") as c_primary,
    coalesce(iat2.c_value, "[null]") as c_secondary,
    ty.c_title as ty_title
from [_]checkout_item as ci
    inner join [_]item as it on it.c_uid = ci.c_item
    inner join [_]condition as co on it.c_condition = co.c_uid
    inner join [_]item_type as ty on ty.c_uid = it.c_type
    inner join [_]item_category as ic on ic.c_uid = ty.c_category
    left outer join [_]item_feature as iat1 on iat1.c_item = it.c_uid
        and iat1.c_name = ty.c_primary_feature
        and iat1.c_deleted <> 1
    left outer join [_]item_feature as iat2 on iat2.c_item = it.c_uid
        and iat2.c_name = ty.c_secondary_feature
        and iat2.c_deleted <> 1
where ci.c_checkout = {checkout,int}
    and ci.c_deleted <> 1
    and it.c_deleted <> 1
    and ic.c_deleted <> 1
    and ({status,int} is null or (ci.c_status & {status,int} <> 0))
