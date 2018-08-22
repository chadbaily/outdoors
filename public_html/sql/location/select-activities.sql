select
    ac.*
from [_]location_activity as la
inner join [_]activity as ac on la.c_activity = ac.c_uid
where la.c_location = {location,int,,,0}
    and la.c_deleted <> 1
    and ac.c_deleted <> 1
