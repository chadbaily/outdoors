select
    ad.c_uid,
    ad.c_created_date,
    ad.c_title,
    ad.c_status
from [_]classified_ad as ad
where ad.c_owner = {member,int}
    and ad.c_deleted <> 1
