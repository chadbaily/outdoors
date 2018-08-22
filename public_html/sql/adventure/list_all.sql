select
    ad.c_uid,
    ad.c_title,
    ad.c_start_date, 
    lo.c_title as loc_title,
    lo.c_uid as loc_uid,
    me.c_uid as me_uid,
    me.c_full_name
from [_]adventure as ad
    inner join [_]location as lo on ad.c_destination = lo.c_uid
    inner join [_]member as me on ad.c_owner = me.c_uid
where (ad.c_status & {active,int} = {active,int})
    and ad.c_deleted <> 1
    and lo.c_deleted <> 1
    and ({title,char} is null or ad.c_title like {title,char})
    and ({location,char} is null or lo.c_title like {location,char})
    and ({leader,char} is null or me.c_full_name like {leader,char})
    and ({start,date} is null or ad.c_start_date >= {start,date})
    and ({end,date} is null or ad.c_start_date <= {end,date})
order by ad.c_start_date desc
