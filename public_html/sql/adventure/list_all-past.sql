select
    ad.c_uid,
    ad.c_title,
    ad.c_start_date, 
    lo.c_title as loc_title,
    lo.c_uid as loc_uid
from [_]adventure as ad
    inner join [_]location as lo on ad.c_destination = lo.c_uid
where ad.c_start_date < now()
    and (ad.c_status & {active,int} = {active,int})
    and ad.c_start_date > {start,date,,,0,date_sub(current_date, interval 1 year)}
    and ad.c_deleted <> 1
    and lo.c_deleted <> 1
order by ad.c_start_date desc
