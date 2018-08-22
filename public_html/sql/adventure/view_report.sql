select
    at.c_uid,
    case when(at.c_status = {waitlisted,int}) then 'W' else 'J' end as c_status,
    at.c_joined_date,
    at.c_member,
    me.c_full_name,
    me.c_birth_date,
    me.c_email,
    me.c_gender,
    ifnull(c_absences, 0) as c_absences,
    ifnull(c_waitlists, 0) as c_waitlists
from [_]attendee as at
    inner join [_]member as me on at.c_member = me.c_uid
    -- join in attendees marked absent
    left outer join (
        select abat.c_member, count(*) as c_absences
        from [_]attendee as abat
            inner join [_]absence as ab on ab.c_attendee = abat.c_uid
        where abat.c_created_date >= date_add(current_date, interval -6 month)
            and ab.c_deleted <> 1
            and abat.c_deleted <> 1
        group by abat.c_member
    ) as abat on abat.c_member = me.c_uid
    -- join in attendees that are waitlisted
    left outer join (
        select wa.c_member, count(*) as c_waitlists
        from [_]attendee as wa 
        where wa.c_status = {waitlisted,int}
            and wa.c_created_date >= date_add(current_date, interval -6 month)
            and wa.c_deleted <> 1
        group by wa.c_member
    ) as wa on wa.c_member = at.c_member
where
    at.c_adventure = {adventure,int}
    and at.c_deleted <> 1
    and me.c_deleted <> 1
    and ({status,int} is null or at.c_status = {status,none})
order by at.c_joined_date
