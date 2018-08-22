select c_child_col from [_]foreign_key
where c_parent_table = {parent,char}
    and c_child_table = {child,char}
    and ({key,char} is null or {key,char} = c_child_col)
