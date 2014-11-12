select
    ad.c_uid,
    ad.c_title,
    ad.c_description,
    ad.c_owner,
    ad.c_max_attendees,
    ad.c_destination,
    ad.c_departure,
    ad.c_start_date,
    ad.c_end_date,
    ad.c_signup_date,
    ad.c_fee,
    ifnull(at.c_uid, 0) as at_uid,
    ifnull(co.c_uid, 0) as co_uid,
    me.c_full_name,
    ds.c_title as destination_title,
    ifnull(ds.c_zip_code, '') as c_zip_code,
    dp.c_title as departure_title,
    if(current_timestamp < ad.c_start_date, 1, 0) as before_deadline,
    if(current_timestamp > ad.c_end_date, 1, 0) as trip_over,
    count(other_atts.c_uid) as num_atts
from [_]adventure as ad
    inner join [_]member as me on me.c_uid = ad.c_owner
    inner join [_]location as ds on ds.c_uid = ad.c_destination
    inner join [_]location as dp on dp.c_uid = ad.c_departure
    left outer join [_]attendee as at on at.c_member = {member,int}
        and at.c_adventure = ad.c_uid
    left outer join [_]adventure_comment as co on co.c_owner = {member,int}
        and co.c_adventure = ad.c_uid
    left outer join [_]attendee as other_atts
        on other_atts.c_adventure = ad.c_uid
        and other_atts.c_status & {default,int} = {default,int}
        and other_atts.c_deleted = 0
where ad.c_uid = {adventure,int}
group by
    ad.c_uid, ad.c_title, ad.c_description, ad.c_owner, ad.c_max_attendees,
    ad.c_destination, ad.c_departure, ad.c_start_date, ad.c_end_date, ad.c_fee,
    ad.c_signup_date, at.c_uid, co.c_uid, me.c_full_name, ds.c_title,
    ds.c_zip_code, dp.c_title
