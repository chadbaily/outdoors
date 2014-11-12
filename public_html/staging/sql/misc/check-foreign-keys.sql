select child.c_uid, child.{foreign,none}
from {child,none} as child
    left outer join {parent,none} as parent
        on child.{foreign,none} = parent.{primary,none}
        and parent.c_deleted <> 1
where parent.{primary,none} is null
    and child.c_deleted <> 1
