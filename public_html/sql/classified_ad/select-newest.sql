select
    ad.c_uid,
    ad.c_title,
    ad.c_created_date
from [_]classified_ad as ad
where (ad.c_status & {default,int} = {default,int})
    and ad.c_deleted <> 1
order by ad.c_created_date desc
limit {limit,int}
