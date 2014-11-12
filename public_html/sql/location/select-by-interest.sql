-- Select the locations with the most adventures going to them, which are marked
-- as something the member is interested in.
select
    lo.c_uid as loc_uid,
    lo.c_title as loc_title,
    ac.c_title as ac_title,
    count(aa.c_uid) as num
from [_]location as lo
    inner join [_]adventure as ad on ad.c_destination = lo.c_uid
    inner join [_]adventure_activity as aa on aa.c_adventure = ad.c_uid
    inner join [_]interest as ir on ir.c_activity = aa.c_activity
    inner join [_]activity as ac on ac.c_uid = aa.c_activity
where
    ir.c_member = {member,int}
    and (ad.c_status & {active,int} = {active,int})
    and lo.c_deleted <> 1
    and ad.c_deleted <> 1
    and aa.c_deleted <> 1
    and ir.c_deleted <> 1
    and ac.c_deleted <> 1
group by ac.c_uid, lo.c_uid
order by num desc
