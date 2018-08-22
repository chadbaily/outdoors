/*
 * Gets rows from a given table.
 */
select * from {table,none}
where c_owner = {owner,int,,,0,c_owner}
    and c_deleted <> 1
order by {orderby,none,,,0,c_uid}
