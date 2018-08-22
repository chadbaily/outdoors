select
    ac.c_title as activity,
    ad.c_uid,
    ad.c_title,
    ad.c_start_date
from [_]interest as ir 
    inner join [_]adventure_activity as aa on aa.c_activity = ir.c_activity
    inner join [_]adventure as ad on ad.c_uid = aa.c_adventure
    inner join [_]activity as ac on ac.c_uid = ir.c_activity
where (ad.c_status & {active,int} = {active,int})
    and ad.c_start_date > current_date
    and ir.c_member = {member,int}
    and ir.c_deleted <> 1
    and aa.c_deleted <> 1
    and ad.c_deleted <> 1
    and ac.c_deleted <> 1
order by ad.c_start_date
