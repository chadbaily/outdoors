#!/bin/bash
#

# Personal info / preferences
# Input your settings

user='outdoors-officers@virginia.edu'
password=
userdir=/usr/home/outdoorsatuva/bash_scripts
# The next 6 files are for parsing web info
emailfile="$userdir/shellemaillist.txt"
blackfile="$userdir/shellblacklist.txt"
gearfile="$userdir/shellgearfile.txt"
greatfile="$userdir/shellgreatfile.txt"
crapfile="$userdir/shellcrapfile.txt"
officerfile="$userdir/shellofficerfile.txt"
phonefile="$userdir/shellphonefile.txt"
notefile="$userdir/shellnotefile.txt"
duefile="$userdir/shellduefile.txt"
tshootfile="$userdir/shelltroubleshoot.txt"
# The files below are for the content of the email message
emailtemplate="$userdir/emailmessage.txt"
personalemail="$userdir/personalmessage.txt"
# And a signature as to how you'd like the emails to be signed
signature="\nHappy Trails,\nThe Officers"
# Subject of the email
subject="Outdoors at UVA: Please Return Your Overdue Gear!"

###########################################################
# Below here shouldn't _need_ to be edited, but you might
# want to change $coday, $comonth, and $coyear, which are set
# to avoid coming up with people/items that are long gone or
# have some bug associated with them

coday="1"     # Check-Out day
comonth="Jan" # Check-Out month
coyear="2012" # Check-Out year

toignore=( quintin.brubaker@gmail.com jlb4te@virginia.edu ) 
# An another approach to keep from emailing people that are
# mistakenly on the "overdue" list is to add them to the 
# above array (just seperate emails by spaces)

###########################################################
# OK, really, no need to edit below here.

datearr=(`date`)
month=${datearr[1]}
myday=${datearr[2]};day=$((myday-1))
year=${datearr[5]}
dayofyear=(`date +%j`)
delinq=7 # this is the number of days gear must be overdue before people are red-flagged

echo -e "This list was compiled on: " > $officerfile
echo `date` >> $officerfile
echo -e "\n#############################\n" >> $officerfile


wget --post-data="user=$user&pass=$password&login-form-name=1" --save-cookies=cookies.txt --keep-session-cookies -O $blackfile http://www.outdoorsatuva.org/members/main/member-home

url="http://www.outdoorsatuva.org/members/checkout/list_all?form-name=1&member=&status=128&item=&type=&begin=$comonth+$coday+$coyear&end=$month+$day+$year&due=$month+$day+$year"

wget --load-cookies=cookies.txt -O $blackfile $url

#http://www.outdoorsatuva.org/members/checkout/list_all?form-name=1&member=&status=128&item=&type=&begin=$comonth+$coday+$coyear&end=$month+$day+$year&due=$month+$day+$year

cat $blackfile | grep -B 1 mailto | sed 's/\"/ /g'> $emailfile
cat $blackfile | grep -B 1 '<td>checked_out<' | sed 's/<\([\/]*\)td>/ /g' > $duefile

gearurl=(`grep "checkout/read" $emailfile | awk '{print $3}'`)
emails=(`grep "mailto" $emailfile | awk '{print $3}' | sed 's/mailto\://'`)
surnames=(`grep "members/checkout/read/" $emailfile | awk '{print $4}' | sed 's/>//' | sed 's/,//'`)
firstnames=(`grep "members/checkout/read/" $emailfile | awk '{print $5}' | sed 's/<\/a><\/td>//'`)
duedates=(`grep / $duefile`)
dueyears=(`grep / $duefile | sed 's/\// /g' | awk '{print $3}'`)
noverdue=${#gearurl[*]}

for ((member=0;member<noverdue;++member )); do 

    dayslastyear=(`date -d "12/31/${dueyears[$member]}" +%j`)
    adddays=(`echo "($year-${dueyears[$member]})*$dayslastyear" | bc`)
    dueday=(`date -d ${duedates[$member]} +%j`)
    daysoverdue=(`echo "$dayofyear-$dueday + $adddays" | bc`)
	echo "$adddays $dueday $daysoverdue $dayslastyear" >> $tshootfile
	echo "###############################################"
    echo "Gear has been overdue for $daysoverdue days"
    echo "###############################################"

    thisurl="http://www.outdoorsatuva.org/${gearurl[$member]}"
    wget --load-cookies=cookies.txt -O $gearfile $thisurl
    cat $gearfile | grep -A 5 '\="checked_out"' | sed 's/\"/ /g'> $greatfile
    itemnums=(`grep "href" $greatfile | sed 's/\// /g' | awk '{print $6}'`)
    grep -A 2 "href" $greatfile | sed 's/<\([\/]*\)td>/ /g'> $crapfile
    items=(`grep -B 1 '\-\-' $crapfile | sed 's/\-\-//g' | sed 's/ /_/g' | tr '\n' ' '`)
    items=( ${items[*]} `cat $crapfile | tail -1 | sed 's/ /_/g' | tr '\n' ' '`)
    #items=(`grep "td" $crapfile | sed 's/^td/ /' | sed 's/[/?]td$/ /'`)
    # Done finding "Items": gear that the club owns that is numbered

    grep "raquo" $greatfile | sed 's/ //g' | sed 's/;/ /' | awk '{print $2}'> $crapfile
    geartypes=(`grep "td" $crapfile | sed 's/<\/td>/ /g' | awk '{print $0}'`)
    gearnum=(`grep -A 1 "raquo" $greatfile | grep '[0-9]' | sed 's/<\([\/]*\)td>/ /g' | awk '{print $0}'`)
    grep -A 2 "raquo" $greatfile | sed 's/ //' | sed 's/<\([\/]*\)td>/ /g' > $crapfile
    bar=(`grep -B 1 '\-\-' $crapfile | sed 's/\-\-//g' | sed 's/ /_/g' | tr '\n' ' '`)
    bar=( ${bar[*]} `cat $crapfile | tail -1 | sed 's/ /_/g' | tr '\n' ' '`)
    # Done finding "Gear": gear the club owns that is NOT numbered
    # (Some small bit of parsing / string editing is left to be done)

    # Let's start "writing" our message to the member with overdue gear
    nitems=${#itemnums[@]}; ngear=${#gearnum[*]} # This line may not work in all shell (versions)

	echo -e "Dear ${firstnames[$member]} \n \n" > $personalemail

    cat $emailtemplate >> $personalemail

    echo -e "The gear you have out has been overdue for $daysoverdue days.\n" >> $personalemail
    if [ $daysoverdue -gt $delinq ] ; then
        echo -e "${firstnames[$member]} ${surnames[$member]} has the following gear out that is $daysoverdue days overdue." >> $officerfile
	phoneurl="http://www.outdoorsatuva.org/members/member/list_all?go=1&name=${firstnames[$member]}+${surnames[$member]}&email=&limit=30&offset=1&sort=last_name"
	wget --load-cookies=cookies.txt -O $phonefile $phoneurl
	cat $phonefile | grep -A 1 ${emails[$member]} | sed 's/\"/ /g'> $crapfile
	cat $crapfile | grep '([0-9]\{3\})' | sed 's/<td>//g' | sed 's/<\/td>//g' > $crapfile
	foo=(`cat $crapfile`); numberexists=${#foo[@]};
	if [ $numberexists -lt 2 ]; then
	    phoneurl="http://www.outdoorsatuva.org/members/member/list_all?go=1&name=${firstnames[$member]}+${surnames[$member]}&email=&limit=30&offset=1&sort=last_name&view_inactive=1"
	    wget --load-cookies=cookies.txt -O $phonefile $phoneurl
	    cat $phonefile | grep -A 1 ${emails[$member]} | sed 's/\"/ /g'> $crapfile
	    cat $crapfile | grep '([0-9]\{3\})' | sed 's/<td>//g' | sed 's/<\/td>//g' > $crapfile
	fi
	cat $crapfile  >> $officerfile
	echo -e "${emails[$member]} \n" >> $officerfile
    fi

    if [ $nitems -gt 0 ] ; then
	echo "You have the following gear out:" >> $personalemail
    fi

    for ((index=0;index<nitems;++index )); do
	itemname[index]=`echo ${items[$index]} | sed 's/[\_]*/ /' | sed 's/\_/ /g' | sed 's/^ //' | sed 's/ $//'`
	if [ ${#itemname[index]} -eq 1 ] ; then
	    itemname[index]='<no description available>'
	fi
	echo "Item number ${itemnums[$index]}, which is a ${itemname[$index]}." >> $personalemail
	if [ $daysoverdue -gt $delinq ] ; then
            echo -e "Item number ${itemnums[$index]}, which is a ${itemname[$index]}." >> $officerfile
	fi
    done
    if [ $ngear -gt 0 ]; then
	echo "You have the following un-numbered gear out:" >> $personalemail
    fi
    for ((index=0;index<ngear;++index )); do
	geardesc[index]=`echo ${bar[$index]} | sed 's/[\_]*/ /' | sed 's/\_/ /g' | sed 's/^ //' | sed 's/ $//'`
	if [ ${#fool[index]} -eq 1 ] ; then
	    geardesc[index]='<no description available>'
	fi
	echo "${gearnum[$index]} of ${geartypes[$index]} with description: ${geardesc[$index]}." >> $personalemail
	if [ $daysoverdue -gt $delinq ] ; then
	    echo "${gearnum[$index]} of ${geartypes[$index]} with description: ${geardesc[$index]}." >> $officerfile
	fi
    done

    echo -e $signature >> $personalemail

	if [ $daysoverdue -gt $delinq ] ; then
	    echo -e "\n ######################### \n" >> $officerfile
	fi

    proceed=1
    for ignore in ${toignore[*]}; do
	if [ $address == $ignore ]; then 
	    proceed=0
	fi
    done
    if [ $proceed -eq 1 ] ; then
	echo "hi"
#	mail -s "$subject" ${emails[$member]} -c "outdoors-officers@virginia.edu" -- -r outdoors-officers@virginia.edu < $personalemail
#	mail -s "$subject" "${emails[$member]}" -- -r charles.romero@gmail.com < $personalemail
    fi

done

#mail -s "Gear greater than $delinq days overdue" outdoors-officers@virginia.edu -- -r charles.romero@gmail.com < $officerfile
