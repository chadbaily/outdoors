select
    at.c_uid,
    at.c_joined_date,
    at.c_amount_paid,
    at.c_status,
    ad.c_uid as ad_uid,
    ad.c_title,
    me.c_uid as me_uid,
    me.c_full_name
from [_]attendee as at
    inner join [_]adventure as ad on at.c_adventure = ad.c_uid
    inner join [_]member as me on at.c_member = me.c_uid
where at.c_uid = {attendee,int}
    and at.c_deleted <> 1
    and ad.c_deleted <> 1
    and me.c_deleted <> 1
