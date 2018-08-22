select
    app.c_uid,
    app.c_created_date,
    me.c_full_name,
    coalesce((select count(*)
     from m_member as me
         inner join m_adventure as ad on ad.c_owner = me.c_uid
         where ad.c_status = 4 
         and ad.c_start_date <= now() and me.c_uid = app.c_member
         group by me.c_uid),0) num_led_all_time,
    coalesce((select count(*)
     from m_member as me
     inner join m_attendee as att on me.c_uid = att.c_member
     inner join m_adventure as ad on att.c_adventure = ad.c_uid
     where ad.c_start_date <= now() and att.c_status & 1 = 1 and ad.c_status = 4 and me.c_uid = app.c_member
     group by me.c_uid),0) num_attended_all_time,
    coalesce((select count(*)
     from m_member as me
     inner join m_adventure as ad on ad.c_owner = me.c_uid
     where ad.c_status = 4 
         and (ad.c_start_date >= DATE_SUB(now(), interval 1 year)) and
         me.c_uid = app.c_member
     group by me.c_uid),0) num_led_last_year,
    coalesce((select count(*)
     from m_member as me
     inner join m_attendee as att on me.c_uid = att.c_member
     inner join m_adventure as ad on att.c_adventure = ad.c_uid
     where (ad.c_start_date >= DATE_SUB(now(), interval 1 year)) and att.c_status & 1 = 1 and ad.c_status = 4
       and me.c_uid = app.c_member
     group by me.c_uid),0) num_attended_last_year
from [_]application as app
    inner join [_]member as me on me.c_uid = app.c_member
where
    ({begin,date} is null or app.c_created_date >= {begin,date})
    and ({end,date} is null or app.c_created_date <= {end,date})
    and ({member,int} is null or app.c_member = {member,int})
    and app.c_deleted <> 1
    and app.c_deleted <> 1
order by app.c_created_date DESC
