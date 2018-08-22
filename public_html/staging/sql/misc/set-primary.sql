-- Updates a given row in a table to have c_primary set, and all others to have it
-- unset.
-- Parameters:
-- {table,none}  The table to update
-- {object,int}  The object to make primary
-- {member,int}  The member for which to update the objects
update {table,none}
    set c_primary = if(c_uid = {object,int}, 1, 0)
where c_owner = {member,int}
