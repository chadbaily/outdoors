select
    pr.c_uid,
    pr.c_what_granted_to as c_who_type,
    pr.c_who_granted_to as c_who_uid,
    case
        when (pr.c_what_granted_to = 'user') then coalesce(me.c_full_name, '--DNE--')
        when (pr.c_what_granted_to = 'group') then ''
        when (pr.c_what_granted_to = 'owner_group') then ''
        else 'none'
    end as c_who,
    pr.c_action,
    ac.c_title as c_action_title,
    pr.c_what_relates_to as c_granted_on,
    pr.c_related_table as c_table,
    pr.c_related_uid,
    ia.c_status
from [_]privilege as pr
    inner join [_]action as ac on ac.c_title = pr.c_action
    inner join {table,none} as ob on ob.c_uid = {object,int}
    inner join [_]implemented_action as ia on ia.c_table = {table,varchar}
        and ia.c_action = ac.c_title
    left outer join [_]member as me
        on pr.c_what_granted_to = 'user'
        and pr.c_who_granted_to = me.c_uid
        and me.c_deleted <> 1
where (
        (pr.c_what_relates_to = 'object' and pr.c_related_uid = {object,int})
        or (pr.c_what_relates_to in ('table', 'global'))
        or (pr.c_what_relates_to = 'self' and pr.c_related_table = '[_]member'))
    and pr.c_related_table = {table,varchar}
    and pr.c_deleted <> 1
    and ob.c_deleted <> 1
