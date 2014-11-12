select
    ad.c_title,
    ad.c_uid,
    ad.c_start_date
from [_]adventure as ad
where c_destination = {destination,int,,,0,c_destination}
    and (ad.c_status & {active,int} = {active,int})
    and ad.c_deleted <> 1
order by ad.c_start_date desc
