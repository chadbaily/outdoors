select
    ph.*,
    pt.c_abbreviation
from [_]phone_number as ph
    inner join [_]phone_number_type as pt on ph.c_type = pt.c_uid
where ph.c_owner = {owner,int,,,0}
    and ph.c_deleted <> 1
    and pt.c_deleted <> 1
