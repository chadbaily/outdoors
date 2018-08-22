select
    me.c_full_name,
    me.c_email,
    ad.c_uid,
    ad.c_owner,
    ad.c_created_date,
    ad.c_title,
    ad.c_text
from [_]classified_ad as ad
    inner join [_]member as me on me.c_uid = ad.c_owner
where (ad.c_status & 9 <> 0)
    and ad.c_deleted <> 1
    and me.c_deleted <> 1
order by ad.c_created_date desc
