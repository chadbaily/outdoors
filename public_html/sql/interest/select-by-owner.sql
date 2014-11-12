select
    nt.*,
    ac.c_title as ac_title
from [_]interest as nt
    inner join [_]activity as ac on nt.c_activity = ac.c_uid
where nt.c_owner = {owner,int,,,0}
    and nt.c_deleted <> 1
    and ac.c_deleted <> 1
order by ac.c_title
