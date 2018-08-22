select count(*) as num_recent
from [_]application
where ({member,int} is null or c_member = {member,int})
  and c_created_date >= DATE_SUB(now(), interval {days,int} day)
