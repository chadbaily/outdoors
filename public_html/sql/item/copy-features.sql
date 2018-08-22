insert into [_]item_feature (c_owner, c_creator, c_created_date, c_item, c_name, c_value)
    select {owner,int}, {owner,int}, now(), {to,int}, c_name, c_value
    from [_]item_feature
    where c_item = {from,int}
        and c_deleted <> 1
