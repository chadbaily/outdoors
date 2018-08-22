delete [_]item_feature
from [_]item_feature as ia
    inner join [_]item as it on it.c_uid = ia.c_item
where it.c_type = {item_type,int}
    and ia.c_name = {attr_name,char}
