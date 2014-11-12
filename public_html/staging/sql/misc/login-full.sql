select 
    me.c_uid as c_uid,

    -- Whether the person entered the correct password
    if(me.c_password = {password,char,30}, 1, 0) as password_correct,

    -- Total number of memberships for the member
    count(ms.c_uid) as total,
    -- Total number of activated memberships that have begun and not yet expired
    -- for this member.  These are memberships that will allow the member to log
    -- in.
    sum(if(
            ifnull(ms.c_status, 0) & {active,int} = {active,int}
            and ms.c_begin_date <= current_date
            and ms.c_expiration_date >= current_date,
        1, 0)
    ) as valid,

    -- Total number of expired memberships for the member.  These are
    -- memberships that have been activated but the expiration date has
    -- passed.
    sum(if(ms.c_status & {active,int} = {active,int}
            and ms.c_expiration_date < current_date, 1, 0)) as expired,

    -- Total number of inactive memberships for the member.  These are
    -- memberships that are either inactive or the begin date has not yet
    -- arrived.
    sum(if(
            (
                ms.c_status & {inactive,int} = {inactive,int}
                or ms.c_status & {paid,int} = {paid,int}
            )
            and (
                ms.c_begin_date is null
                or ms.c_begin_date > current_date
                or mt.c_flexible
            ), 1, 0)
    ) as pending,

    current_timestamp

from [_]member as me
    left outer join [_]membership as ms on me.c_uid = ms.c_member
        and ms.c_deleted <> 1
    left outer join [_]membership_type as mt on mt.c_uid = ms.c_type
where me.c_email = {email,char,60}
    and me.c_deleted <> 1
group by me.c_uid
