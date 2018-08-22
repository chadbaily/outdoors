-- Select the number of days remaining until the member needs to renew.
select
    to_days(coalesce(max(
            -- Flexible memberships with null c_expiration_date can be counted
            -- as expiring sometime in the future.
            ifnull(c_expiration_date, date_add(current_date, interval 1 year))
        ), current_date)) - to_days(current_date)
    as days_left
from [_]membership as ms
where ms.c_member = {member,int}
    and ms.c_deleted <> 1
