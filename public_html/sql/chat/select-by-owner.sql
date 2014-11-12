select *
from [_]chat
where c_owner = {owner,int,,,0}
    and c_deleted <> 1
