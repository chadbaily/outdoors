-- Selects membership types that the current user does not already have.  These
-- are then valid options for renewing.  Flexible membership types are not
-- included, since a member can have many memberships of a flexible type.
-- Parameters: private, flexible, member
select
    mt.c_uid, mt.c_title, mt.c_description,
    case when mt.c_flexible = 0
        then mt.c_begin_date
        else 'Activation'
    end as c_begin_date,
    case when mt.c_flexible = 0
        then mt.c_expiration_date
        else concat(mt.c_units_granted, ' ', mt.c_unit,
            case when mt.c_units_granted > 1 then 's' else '' end,
            ' later')
    end as c_expiration_date,
    mt.c_total_cost
from [_]membership_type as mt
    -- This exclusion join will prevent certain membership types from
    -- being shown as choices.  Anything that DOES match the join criterion
    -- will not be shown as an option for renewing.  We want to show:
    -- * Any non-flexible membership the member does not have
    -- * Any flexible membership, unless the member already has one that is
    --   active or pending.
    left outer join [_]membership as ms
        -- Primary join criteria, which should be in any join btw these tables
        on mt.c_uid = ms.c_type and ms.c_member = {member,int}
        -- Necessary to put here instead of in the where clause
        and ms.c_deleted <> 1
        and (
            -- Exclude non-flexible memberships, period.
            (mt.c_flexible = 0)
            -- Exclude flexible memberships that are not "used up".  Flexible
            -- memberships that are inactive will have a NULL c_expiration_date,
            -- so we can treat them as though their expiration date is in the
            -- future, which if you think about it is actually true.
            --   * Exp. date is either null or at least a month in the future.
            or ifnull(ms.c_expiration_date,
                    date_add(current_date, interval 3 month))
                >= date_add(current_date, interval 3 month)
        )
where (mt.c_hidden = 0)
    and current_date between mt.c_show_date and mt.c_hide_date
    and mt.c_deleted <> 1
    and ms.c_uid is null
