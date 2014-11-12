select
    count(*) as num_attendees,
    ad.c_max_attendees,
    sum(if(at.c_status & {waitlisted,int} = {waitlisted,int}, 1, 0)) as num_waitlisted,
    sum(
        case
        when at.c_uid <> {member,int,,,0}
            and (at.c_status & {waitlisted,int} = {waitlisted,int})
            and at.c_joined_date < {joined,date,,,0}
        then 1
        else 0
        end
    ) as ahead_of_me
from [_]attendee as at
    inner join [_]adventure as ad on at.c_adventure = ad.c_uid 
where at.c_adventure = {adventure,int,,,0}
    and at.c_deleted <> 1
    and ad.c_deleted <> 1
group by ad.c_uid
