select
    ita.c_name,
    ia.c_uid,
    coalesce(ia.c_value, "") as c_value
from [_]item as it
    inner join [_]item_type_feature as ita on it.c_type = ita.c_type 
    left outer join [_]item_feature as ia on ia.c_item = it.c_uid
        and ia.c_name = ita.c_name
        and ia.c_deleted <> 1
where it.c_uid = {item,int}
    and it.c_deleted <> 1
    and ita.c_deleted <> 1
order by ita.c_uid
