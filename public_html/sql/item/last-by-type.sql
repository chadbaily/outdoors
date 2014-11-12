select c_uid
from [_]item
where c_type = {type,int}
    and c_uid < {item,int}
    and c_deleted <> 1
order by c_uid desc
limit 1
