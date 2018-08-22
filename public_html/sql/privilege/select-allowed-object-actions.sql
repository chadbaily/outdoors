-- Selects a list of all actions that the member is allowed to do on the object.
-- Takes into account both the UNIX style bitmasked privileges and the ACL
-- privileges that are stored in [_]privilege.
-- Parameters:
--      member: The member's UID
--      groups: The member's c_group_memberships bit field
--      object: The UID of the object
--      table:  The table the object lives in.  This parameter appears in both
--              char and none data types.
--      root_group: The UID of the root group
--      All of the various bitmasks from [_]unixperm.
select
    ac.c_title,
    ac.c_summary,
    case when (ia.c_label <> '') then ia.c_label else ac.c_label end as c_label,
    ac.c_row,
    ac.c_description,
    ac.c_generic
    -- You could add columns to the select statement to show what granted the
    -- privilege, for instance group_owner.c_uid would show if the member is in
    -- the group that owns the object.
from
    [_]action as ac
    -- join onto the object itself
    inner join {table,none} as obj on obj.c_uid = {object,int}
    -- Filter out actions that do not apply to this object type
    inner join [_]implemented_action as ia
        on ia.c_action = ac.c_title
            and ia.c_table = {table,char}
            and ((ia.c_status = 0) or (ia.c_status & obj.c_status <> 0))
    -- Privileges that apply to the object (or every object in the table)
    -- and grant the given action
    left outer join [_]privilege as pr
        on pr.c_related_table = {table,char}
            and pr.c_deleted <> 1
            and pr.c_action = ac.c_title
            and (
                (pr.c_what_relates_to = 'object' and pr.c_related_uid = {object,int})
                or pr.c_what_relates_to = 'global'
                or (pr.c_what_relates_to = 'self' and {object,int} = {member,int} and {table,char} = '[_]member'))
where
    -- The action must apply to objects
    ac.c_apply_object
    and obj.c_deleted <> 1
    and (
        -- Members of the 'root' group are always allowed to do everything
        ({groups,int} & {root_group,int} <> 0)
        -- UNIX style read permissions in the bit field
        or (ac.c_title = 'read' and (
            -- The other_read permission bit is on
            (obj.c_unixperms & {other_read,int} <> 0)
            -- The owner_read permission bit is on, and the member is the owner
            or ((obj.c_unixperms & {owner_read,int} <> 0) and obj.c_owner = {member,int})
            -- The group_read permission bit is on, and the member is in the group
            or ((obj.c_unixperms & {group_read,int} <> 0) and ({groups,int} & obj.c_group <> 0))))
        -- UNIX style write permissions in the bit field
        or (ac.c_title = 'write' and (
            -- The other_write permission bit is on
            (obj.c_unixperms & {other_write,int} <> 0)
            -- The owner_write permission bit is on, and the member is the owner
            or ((obj.c_unixperms & {owner_write,int} <> 0) and obj.c_owner = {member,int})
            -- The group_write permission bit is on, and the member is in the group
            or ((obj.c_unixperms & {group_write,int} <> 0) and ({groups,int} & obj.c_group <> 0))))
        -- UNIX style delete permissions in the bit field
        or (ac.c_title = 'delete' and (
            -- The other_delete permission bit is on
            (obj.c_unixperms & {other_delete,int} <> 0)
            -- The owner_delete permission bit is on, and the member is the owner
            or ((obj.c_unixperms & {owner_delete,int} <> 0) and obj.c_owner = {member,int})
            -- The group_delete permission bit is on, and the member is in the group
            or ((obj.c_unixperms & {group_delete,int} <> 0) and ({groups,int} & obj.c_group <> 0))))
        -- user privileges
        or (pr.c_what_granted_to = 'user' and pr.c_who_granted_to = {member,int})
        -- owner privileges
        or (pr.c_what_granted_to = 'owner' and obj.c_owner = {member,int})
        -- owner_group privileges
        or (pr.c_what_granted_to = 'owner_group' and (obj.c_group & {groups,int} <> 0))
        -- group privileges
        or (pr.c_what_granted_to = 'group' and (pr.c_who_granted_to & {groups,int} <> 0)))
order by ac.c_summary
