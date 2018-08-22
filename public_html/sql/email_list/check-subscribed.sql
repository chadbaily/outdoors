select
    count(*)
from [_]subscription as sub
where c_owner = {owner,int,,,0}
    and c_list = {list,int,,,0}
    and c_email = {email,char,60,,0}
    and c_deleted <> 1
