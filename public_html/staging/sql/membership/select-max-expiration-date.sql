-- Find the max date of the member's currently active memberships. If
-- there are none, use today's date.
select
    coalesce(max(c_expiration_date), current_date) as max_date
from [_]membership as ms
where ms.c_member = {member,int}
    and (ms.c_status & {active,int} = {active,int})
    and ms.c_deleted <> 1
