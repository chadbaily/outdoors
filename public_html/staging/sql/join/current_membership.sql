select
  ms.c_uid as MEMBERSHIP_ID, 
  (ms.c_total_cost + {paypal_charge,float}) as MEMBERSHIP_COST, 
  mt.c_title as MEMBERSHIP_NAME,
  m.c_first_name as FIRST_NAME,
  m.c_last_name as LAST_NAME,
  a.c_street as ADDRESS,
  a.c_city as CITY,
  a.c_state as STATE,
  a.c_zip as ZIP
from
  [_]membership as ms
  inner join [_]membership_type as mt on mt.c_uid = ms.c_type
  inner join [_]member as m on m.c_uid = ms.c_member
  left outer join [_]address as a on a.c_owner = ms.c_member
where
  ms.c_member = {member,int} and
  ms.c_status & {inactive,int} = {inactive,int} and
  (
    ms.c_begin_date is null or
    ms.c_begin_date > current_date or
    mt.c_flexible
  ) and
  a.c_primary = 1
order by ms.c_last_modified DESC
limit 1
