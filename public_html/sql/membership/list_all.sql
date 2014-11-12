select
    me.c_uid,
    me.c_first_name,
    me.c_last_name,
    if(date_add(me.c_birth_date, interval 18 year) > ms.c_created_date, 1, 0)
        as c_underage,
    ms.c_uid as membership_uid,
    ms.c_type,
    mt.c_title,
    ms.c_total_cost,
    -- Flexible memberships begin when the last active membership expires,
    -- or today, whichever is later.
    case when mt.c_flexible
        then coalesce(max(om.c_expiration_date), current_date)
        else ms.c_begin_date
    end as c_begin_date,
    -- Flexible memberships end a certain time after the begin date.
    case when mt.c_flexible
        then
            case when ms.c_unit = 'day' then
                date_add(coalesce(max(om.c_expiration_date), current_date),
                    interval ms.c_units_granted day)
            when ms.c_unit = 'month' then
                date_add(coalesce(max(om.c_expiration_date), current_date),
                    interval ms.c_units_granted month)
            when ms.c_unit = 'year' then
                date_add(coalesce(max(om.c_expiration_date), current_date),
                    interval ms.c_units_granted year)
            end
        else ms.c_expiration_date
    end as c_expiration_date
from [_]member as me
    inner join [_]membership as ms on ms.c_member = me.c_uid
    inner join [_]membership_type as mt on ms.c_type = mt.c_uid
    -- Use this join to find the begin/expire dates for flexible types.
    -- Only join on memberships that end > today, to avoid having to do
    -- complex case statements above.
    left outer join [_]membership as om on om.c_member = me.c_uid
        and om.c_deleted <> 1
        and om.c_status & {active,int} = {active,int}
        and om.c_expiration_date >= current_date
where (ms.c_status & {inactive,int} = {inactive,int})
    and me.c_deleted <> 1
    and ms.c_deleted <> 1
    and mt.c_deleted <> 1
    and ifnull(ms.c_expiration_date, current_date) >= current_date
    and ({start,date} is null or ms.c_created_date >= {start,date})
group by me.c_uid, me.c_first_name, me.c_last_name, me.c_birth_date,
    ms.c_created_date, ms.c_uid, ms.c_type, mt.c_title, ms.c_total_cost,
    mt.c_flexible, ms.c_begin_date, ms.c_expiration_date
order by me.c_last_name, me.c_first_name
