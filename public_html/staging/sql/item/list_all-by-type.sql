select{select:}
    coalesce({c_name}_table.c_value, "[null]") as {c_name}_table,{:select}
    it.c_uid as ID_table,
    it.c_qty as qty_table,
    it.c_status,
    co.c_title as condition_table
from [_]item as it
    inner join [_]condition as co on co.c_uid = it.c_condition
    inner join [_]item_type as ty on ty.c_uid = it.c_type{join:}
    left outer join [_]item_feature as {c_name}_table on {c_name}_table.c_item = it.c_uid
        and {c_name}_table.c_name = '{c_name}'
        and {c_name}_table.c_deleted <> 1{:join}
where it.c_type = {itemtype,int}
    and it.c_deleted <> 1
    and co.c_deleted <> 1
    and ty.c_deleted <> 1
    and ({status,int} is null or (it.c_status & {status,int} <> 0))
order by {orderby,none,,,,it.c_uid}
limit {offset,int},{limit,int}
