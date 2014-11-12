select distinct ia.c_value
from [_]item_feature as ia
    inner join [_]item as it on it.c_uid = ia.c_item
where ia.c_name = {name,char}
    and it.c_type = {item_type,int}
    and ia.c_value <> {value,char}
    and ia.c_deleted <> 1
    and it.c_deleted <> 1
order by ia.c_value
