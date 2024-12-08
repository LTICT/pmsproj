/*                            ~~ START JS 4 ALL APPLICATIONS ~~
Calendar.2 is a javascript Program that is designed to implement Ethiopian Calendar
Designed and implemented by SINISHAW KASSA
FEB-2011
INSA
*****************************************************************************/
var initialDay = 1, initialMonth = 1, initialYear = 1899, totalDaysForMonth,
dayListValue, localId, language = "Amh";
/******************************************************************************
FUNCTION THAT ADD 2 VALUES TO LANGUAGE OPTION BOX DISPLAYED ON CALENDAR, HOLDS
EITHER AMHARIC OR ENGLISH LANGUAGE
-----------------------------------------------------------------------------*/
function display() {
    alert("in");
}
function setLanguage(sId) {
    var changer = document.getElementById(szId).value;

    if ((changer * 1) == 2)
        language = "Eng";
    else
        language = "Amh";
}
/*****************************************************************************
/*A FUNCTION THAT CAN ADD OPTIONS TO LANGUAGE OPTION BOX @ RUN TIME*/
/*----------------------------------------------------------------------------*/
function addChoice(selectBox, text, value) {
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    selectBox.options.add(optn);
}
function addValueChoice(selectBox) {
    addOption(document.getElementById(selectBox), "Amh", "1");
    //addOption(document.getElementById(selectBox), "Eng", "2");
}
/*****************************************************************************
FUNCTION THAT ADD 2 VALUES TO CALENDAR OPTION BOX, BUT NOT WORK FOR THIS
VERSION, RATHER THE VALUES'LL B FUNCTIONAL 4 Z NEXT VERSION
-----------------------------------------------------------------------------*/
function addOption(selectBox, text, value) {
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    selectBox.options.add(optn);
}
function addValue(selectBox) {
    addOption(document.getElementById(selectBox), "EC", "1");
    addOption(document.getElementById(selectBox), "GC", "2");
}
/*****************************************************************************
FUNCTIONS THAT ADD VALUES TO MONTH OPTION BOX @ RUN TIME
-----------------------------------------------------------------------------*/
function addOptionMonth(selectBox, text, value) {
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    selectBox.options.add(optn);
}
function addValueMonth(selectBox) {
    var months;
    if (language == "Amh")
        months = new Array("መስከረም", "ጥቅምት", "ሕዳር", "ታህሳስ", "ጥር", "የካቲት", "መጋቢት", "ሚያዝያ", "ግንቦት", "ሰኔ", "ሐምሌ", "ነሐሴ", "ጳጉሜ");
    else
        months = new Array("Meskrem", "Tikimt", "Hidar", "Tahisas", "Thir", "Yekatit", "Megabit", "Miyaziya", "Ginbot", "Sene", "Hamle", "Nehasse", "Puagme");
    for (var i = 0; i <= 12; i++) {
        addOptionMonth(document.getElementById(selectBox), months[i], i);
    }
}
/*****************************************************************************
FUNCTIONS THAT ADD VALUES TO YEAR OPTION BOX @ RUN TIME
-----------------------------------------------------------------------------*/
function addOptionYear(selectBox, text, value) {
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    selectBox.options.add(optn);
}
function addValueYear(selectBox) {
    for (var i = 1900; i <= 2050; i++) {
        addOptionYear(document.getElementById(selectBox), i, i);
    }
}
/*****************************************************************************
THIS FUNCTION GET AND RETURN THE SELECTED DAY AS A STRING, BASED ON CALLER
FUNCTION REQUIREMENTS
-----------------------------------------------------------------------------*/
function whatIsToday(totalDays) {
    totalDays = (totalDays * 1);
    var dayValue = 0;
    if (totalDays <= 7)
        dayValue = totalDays;
    dayValue = totalDays % 7;
    switch (dayValue) {
        case 0: return "ማክሰኞ";
        case 1: return "ረቡዕ";
        case 2: return "ሐሙስ";
        case 3: return "አርብ";
        case 4: return "ቅዳሜ";
        case 5: return "እሁድ";
        default: return "ሰኞ";
    }
}

//*****************************************************************************
/*THIS FUNCTION COUNT NUMBER OF LEAP YEARS TO GET THE EXACT NUMBER OF PASSED DAYS*/
/*-----------------------------------------------------------------------------*/
function numberOfLeapYears(yearToBeChecked) {
    yearToBeChecked = (yearToBeChecked * 1);
    var counter = 0;
    for (var year = initialYear; year < yearToBeChecked; year += 4) {
        counter++;
    }
    return counter;
}
//*****************************************************************************
/*A FUNCTION THAT CHECK WHETHER THE YEAR IS LEAP OR NOT*/
/*-----------------------------------------------------------------------------*/
function isLeapYear(yearToBeChecked) {
    yearToBeChecked = (yearToBeChecked * 1);
    for (var year = initialYear; year <= yearToBeChecked; year += 4) {
        if (yearToBeChecked == year)
            return 1;
    }
    return 0;
}
//*****************************************************************************
/*THIS IS ALSO THE BASIC AND ONE OF THE MOST IMPORTANT FUNCTION INSIDE THE SCRIPT, IT
CALCULATED NUMBER OF DAYS B/N SELECTED DATE AND THE DATE I STARTED AS AN
INITIAL DATE AND RETURN THE COUNTED.*/
/*-----------------------------------------------------------------------------*/
function numberOfDays(day, month, year) {
    day = (day * 1); month = (month * 1); year = (year * 1);
    var resultDays, resultMonths, resultYears, leapYears, totalDays = 0;
    resultDays = ((day - initialDay) * 1);
    resultMonths = ((month - initialMonth) * 1);
    resultYears = ((year - initialYear) * 1);
    if (resultYears == 0) {
        totalDays = resultDays + (resultMonths * 30);
    } else {
        leapYears = numberOfLeapYears(year);
        leapYears = (leapYears * 1);
        totalDays = resultDays + (resultMonths * 30) + (resultYears * 365) + (leapYears * 1);
    }
    return totalDays;
}
//******************************************************************************
/*THIS FUNCTIN RETURNS THE FULL DATE IN FORMATTED MANNER. U CAN CHANGE THIS
FUNCTION BASED ON THE FORMAT YOU CHOOSE FOR YOUR APPLICATION THAT MEANS I
CHOOSE MY DATE FORMATTING DD/MM/YYYY BUT U CAN MODIFY THIS JUST BY CHANGING
5th LINE OF THIS FUNCTION BODY. */
/*-----------------------------------------------------------------------------*/
function displayToday(selectedValue) {
    var day = selectedValue;
    if(day<10)day="0"+day;

    var month = document.getElementById("month_list").value;
    month = ((month * 1) + 1);
    if(month<10)month="0"+month
    var year = document.getElementById("year_list").value;
    /*5th line*/
    var fullYear = day + "/" + month + "/" + year;
    document.getElementById(localId).value = fullYear;
}
//******************************************************************************
/*THIS FUNCTION HELP CHANGEMONTHSTRUCTURE() FUNCTION TO GET STARTING DAY OF THE
SELECTED MONTH FROM SCRIPT, AND RETURNS TO CALLER FUNCTION.*/
/*-----------------------------------------------------------------------------*/
function displayToday2() {
    var month = document.getElementById("month_list").value;
    month = ((month * 1) + 1);
    var year = document.getElementById("year_list").value;
    //alert(year);
    return whatIsToday(numberOfDays(1, month, year));
}

//******************************************************************************
/*THERE ARE FEW VERY SERIOUS AND ESSENCIAL FUNCTIONS IN THIS SCRIPT, AND THIS
FUNCTION IS THE TRIGGER WHERE ANY APPLICATION CALL FOR IT FIRST B4 ACCESSING
ANYOTHER FUNCTION, SO THIS FUNCTION CAN BE CONSIDERD AS A MAIN or GATEWAY TO
OTHER FUNCTIONS IN THE WHOLE SCRIPT*/
/*------------------------------------------------------------------------------*/
var tableConstructor;

function showCal() {
    if(document.getElementById("div1")!=null)
        return;
    createToolTip();
    var divTag = document.createElement("div");
    divTag.id = "div1";
    divTag.className = "dynamicDiv";
    var dayList, monthList, yearList, days, close, selectedDate, choice, toolTip;
    close = "<div class='close' style='width:10px;'><a  \n\
        style='text-decoration:none;color:white;padding:1px;' \n\
        onclick='ice_hide()'>X</a></div>  ";
    selectedDate = "<div class='divheader' id='divHead' text-align:right;'>Header</div>";

    monthList = "<select id='month_list' class='lists' \n\
        onchange='changeMonthStructure(displayToday2()),changeHeader()' >\n\
        <option value=''></option> </select>";
    yearList = "<select id='year_list' class='lists' \n\
        onchange='changeMonthStructure(displayToday2()),\n\
        changeHeader()'><option value='' ></option></select>";
    choice = "<select id='choice_list' class='lists' \n\
        onchange='clearMonth(),\n\
        changeMonthStructure(displayToday2()),changeHeader()'>\n\
       </select></div>";
    var y = "<center>" + monthList + yearList + choice + "</center>";
    divTag.innerHTML = /*close + toolTip + */selectedDate + y;
    document.body.appendChild(divTag);
    document.getElementById("div1").style.display="none";
    var divtable = document.createElement("div");
    divtable.id = "divtable";
    divtable.className = "divtable";

    var hold = 1;
    for (var i = 1; i <= 5; i++) {
        tableConstructor += "<tr>";
        for (var j = hold; j <= 30; j++) {
            tableConstructor += "<td class='tt'><a  style='text-decoration:none' \n\
                class='btns' id='btn" + j + "' type='button' value='" + j + "' \n\
                onclick='displayToday(this.value),convertEcToGc(this.value)'></td>";
            if (j % 7 == 0) {
                hold = j + 1; break;
            }
        }
        tableConstructor += "</tr>";
    }

    divtable.innerHTML = "<table style='border-color:yellow;' id='tbl'>" + tableConstructor + "</table>";
    document.getElementById("div1").appendChild(divtable);
    var divbutton = document.createElement("div");
    divbutton.id = "div3";
    divbutton.className = "divbutton";
    document.getElementById("div1").appendChild(divbutton);
    addValueChoice('choice_list');
    clearMonth();
    clearYear();
    addValueYear('year_list');
    getToday();
    changeHeader();
    ice_hide();

}
//******************************************************************************
/*SOMETIMES WE MAY NEED TO CLEAR THE LIST BOXES WE FILL, THESE FUNCTIONS CLEAR
AND FEEL AGAIN MONTH DROP DOWN LIST ITEMS WHEN NEEDED*/
/*----------------------------------------------------------------------------*/
function clearMonth() {

    var theDropDown = document.getElementById("month_list");
    var numberOfOptions = theDropDown.options.length;
    for (i = 0; i < numberOfOptions; i++) {
        theDropDown.remove(0)
    }
    addValueMonth('month_list');
    currentDate();
}
function clearYear() {

    var theDropDown = document.getElementById("year_list");
    var numberOfOptions = theDropDown.options.length;
    for (i = 0; i < numberOfOptions; i++) {
        theDropDown.remove(0)
    }
}

/* ----------------------- TOOL TIP --------------------------*/

//******************************************************************************
/*THERE ARE 13 OFFICIALS HOLYDAYS IN ETHIOPIA, THIS FUNCTION DUTIES IS TO RECIEV DAYS
FROM THE CALLER FUNCTION, PLUS OBTAIN THE SELECTED MONTH AND YEAR, THEN TELL IF THE
DAY ON EACH SELECTED YEAR & MONTH IS A HOLYDAY OR NOT AND TELL WHAT HOLYDAY IS IT*/
/*----------------------------------------------------------------------------*/
function whatHolydayIsTodyay(day) {
    var selectedMonth = document.getElementById('month_list').value;
    var selectedYear = document.getElementById('year_list').value;
    day = parseInt(day);
    //---------------- to Mark Ester and Good Friday-----------------------//
    var holyDays = etEasterCalculation();
    var elem = holyDays.split('/');
    var sikletDay = elem[0];
    var sikletMonth = elem[1];
    var fasikaDay = elem[2];
    var fasikaMonth = elem[3];
    //---------------- to Mark X-Mass-----------------------//
    var leapEffect = isLeapYear(parseInt(document.getElementById('year_list').value) - 1);
    var genna;
    if (leapEffect == 0)
        genna = 29;
    else
        genna = 28;
    //---------------- to Mark Mewlid-----------------------//
    var mewlidDay, mewlidMonth, holyday, mewlid, holyday1;
    holyday1 = lunarHolyDays('M'); //Ask for Mewlid
    mewlid = holyday1.split('/');
    mewlidDay = parseInt(mewlid[0]);
    mewlidMonth = parseInt(mewlid[1]);
    //---------------- to Mark EdAlFetir-----------------------//
    var FeturDay, FeturMonth, holyday2, edAlFetir;
    holyday2 = lunarHolyDays('F'); //Ask for edAlFetir
    edAlFetir = holyday2.split('/');
    FeturDay = parseInt(edAlFetir[0]);
    FeturMonth = parseInt(edAlFetir[1]);
    //---------------- to Mark EdAlAdha(Arefa)-----------------------//
    var AdhaDay, AdhaMonth, holyday3, edAlAdha;
    holyday3 = lunarHolyDays('A'); //Ask for edAlAdha
    edAlAdha = holyday3.split('/');
    AdhaDay = parseInt(edAlAdha[0]);
    AdhaMonth = parseInt(edAlAdha[1]);

    var hollyDayName;
    if (selectedMonth == 0 && day == 1)
        hollyDayName = "&nbsp;&nbsp;&nbsp; የዘመን መለወጫ &nbsp;";
    else if (selectedMonth == 0 && day == 17)
        hollyDayName = "&nbsp;&nbsp;&nbsp; የመስቀል በዓል &nbsp;";
    else if (selectedMonth == 3 && day == genna)
        hollyDayName = "&nbsp;&nbsp;&nbsp; ገና (የእየሱስ ክርስቶስ ልደት በዓል) &nbsp;";
    else if (selectedMonth == 4 && day == 11)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  የጥምቀት በዓል &nbsp;";
    else if (selectedMonth == 5 && day == 23)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  የአድዋ ድል በዓል &nbsp;";
    else if (selectedMonth == 7 && day == 23)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  አለም አቀፍ የላባደሮች ቀን &nbsp;";
    else if (selectedMonth == 7 && day == 27)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  የአርበኞች መታሰቢያ &nbsp;";
    else if (selectedMonth == sikletMonth && day == sikletDay)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ስቅለት &nbsp;";
    else if (selectedMonth == fasikaMonth && day == fasikaDay)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ትንሳኤ &nbsp;";
    else if (selectedMonth == 8 && day == 20)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ደርግ የወደቀበት &nbsp;";
    else if (selectedMonth == (mewlidMonth - 1) && day == mewlidDay)
        hollyDayName = "&nbsp;&nbsp;&nbsp; መውሊድ (የነብዩ ሙሀመድ ልደት) &nbsp;";
    else if (selectedMonth == (FeturMonth - 1) && day == FeturDay)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ኢድ-አል-ፈጥር (የጾም ፍቺ) &nbsp;";
    else if (selectedMonth == (AdhaMonth - 1) && day == AdhaDay)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ኢድ-አል-አድሃ (አረፋ) &nbsp;";
    //--------------------manual highlighting for 3 hijira holydays that double appearance at the same year
    else if (selectedMonth == 0 && day == 9 && selectedYear == 2002)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ኢድ-አል-ፈጥር (የጾም ፍቺ) &nbsp;";
    else if (selectedMonth == 0 && day == 2 && selectedYear == 2009)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  ኢድ-አል-አድሃ (አረፋ) &nbsp;";
    else if (selectedMonth == 0 && day == 5 && selectedYear == 2017)
        hollyDayName = "&nbsp;&nbsp;&nbsp;  መውሊድ (የነብዩ ሙሀመድ ልደት) &nbsp;";

    return hollyDayName;
}

//******************************************************************************
/*IF IT IS NEEDED TO MARK UP HOLY DAYS, THEN IT IS MORE MEANINGFUL IF THE USER
EASILY SEE WHAT HOLYDAY IS THE MARKED UP DAY AS A TOOL TIP. THE FOLLOWING FUN
SHOW WHAT HOLYDAY IS THE MARKED UP DAY ON MOUSE OVERE EVENT NEAR TO THE HOVERED
DAY */
/*----------------------------------------------------------------------------*/
function showtip(e, num) {
    var selectedMonth = parseInt(document.getElementById('month_list').value);
    var selectedYear = parseInt(document.getElementById('year_list').value);
    num = parseInt(num);
    if (!e) e = window.event;

    if (e.pageX == null && e.clientX != null) {
        var doc = document.documentElement, body = document.body;

        e.pageX = e.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc.clientLeft || 0);
        e.pageY = e.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc.clientTop || 0);
    }
    var holyDays = etEasterCalculation();
    var elem = holyDays.split('/');
    var sikletDay = elem[0];
    var sikletMonth = elem[1];

    var fasikaDay = elem[2];
    var fasikaMonth = elem[3];
    var leapEffect = isLeapYear(parseInt(document.getElementById('year_list').value) - 1);
    var genna;
    if (leapEffect == 0)
        genna = 29;
    else
        genna = 28;
    //---------------- to Mark Mewlid-----------------------//
    var mewlidDay, mewlidMonth, holyday, mewlid, holyday1;
    holyday1 = lunarHolyDays('M'); //Ask for Mewlid
    mewlid = holyday1.split('/');
    mewlidDay = parseInt(mewlid[0]);
    mewlidMonth = parseInt(mewlid[1]);
    //---------------- to Mark EdAlFetir-----------------------//
    var FeturDay, FeturMonth, holyday2, edAlFetir;
    holyday2 = lunarHolyDays('F'); //Ask for edAlFetir
    edAlFetir = holyday2.split('/');
    FeturDay = parseInt(edAlFetir[0]);
    FeturMonth = parseInt(edAlFetir[1]);
    //---------------- to Mark EdAlAdha(Arefa)-----------------------//
    var AdhaDay, AdhaMonth, holyday3, edAlAdha;
    holyday3 = lunarHolyDays('A'); //Ask for edAlAdha
    edAlAdha = holyday3.split('/');
    AdhaDay = parseInt(edAlAdha[0]);
    AdhaMonth = parseInt(edAlAdha[1]);

    if ((selectedMonth == 0 && num == 1) || (selectedMonth == 0 && num == 17)
        || (selectedMonth == 3 && num == genna) || (selectedMonth == 4 && num == 11) ||
        (selectedMonth == 5 && num == 23) || (selectedMonth == 7 && num == 23)
        || (selectedMonth == 7 && num == 27) ||
        (selectedMonth == sikletMonth && num == sikletDay)
        || (selectedMonth == fasikaMonth && num == fasikaDay)
        || (selectedMonth == 8 && num == 20) ||
        (selectedMonth == (mewlidMonth - 1) && num == mewlidDay)
        || (selectedMonth == (FeturMonth - 1) && num == FeturDay)
        || (selectedMonth == (AdhaMonth - 1) && num == AdhaDay) ||
        (selectedYear == 2002 && (selectedMonth == 0 && num == 9))
        || (selectedYear == 2009 && (selectedMonth == 0 && num == 2))
        || (selectedYear == 2017 && (selectedMonth == 0 && num == 5))) {
        document.getElementById("tooltip").innerHTML = whatHolydayIsTodyay(num);
        document.getElementById("tooltip").style.visibility = "visible";
        document.getElementById("tooltip").style.left = (parseInt(e.pageX) + 10) + "px";
        document.getElementById("tooltip").style.top = e.pageY + "px";
    }
    else
        document.getElementById("tooltip").style.visibility = "hidden";
}

function hidetip() {
    document.getElementById("tooltip").style.visibility = "hidden";
}

/* ----------------------END OF TOOL TIP --------------------------*/
//******************************************************************************
/*THIS FUNCTION READ THE CURRENT SYSTEM DATE AND CONVERT IT IN ETHIOPIAN DATE, THEN
SEARCH FOR THE ETHIOPIAN DATE AND MARK IT IN THE CALENDAR. WHENEVER USER CLICK THE
CALENDAR BUTTON THE CALENDAR APPEARS WITH TODAY SELECTED.*/
/*----------------------------------------------------------------------------*/
function highlightToday(dayValue) {
    //the following 4 lines js code feach system day, month and year from the local computer that executed the script
    var today = new Date();
    var currentGcDay = parseInt(today.getDate());
    var currentGcMonth = parseInt(today.getMonth());
    var currentGcYear = parseInt(today.getFullYear());

    //the following 1 line js code pass the feached current date to EC convertor function and put the returned date value in a
    //string format to convertedDate variable which holds the Ethiopian equivalent of system current GC date.
    var convertedDate = GCtoEC_Converter(currentGcDay, currentGcMonth, currentGcYear);

    //the following 4 lines code get only the number value of day,month and year of the converted ethiopian date without special
    // caracter '/' that is returned from the GCtoEC_Converter(,,) function
    var elem = convertedDate.split('/');
    currentEtDay = elem[0];
    currentEtMonth = elem[1];
    currentEtYear = elem[2];

    var yearIndex = getYearIndex(currentEtYear); // this function gets the ethiopian year index inside a listbox
    //next 2 lines get the EC selected month & year when the calendar is load @ 1st time
    var selectedEtMonth = parseInt(document.getElementById("month_list").value);
    //alert(selectedEtMonth);
    var selectedEtYear = parseInt(document.getElementById("year_list").value);
    //next 2 lines code get the current date location inside a table cell from 30 occupied cells/excluding empty cells
    var currentEtDayId = parseInt(currentEtDay) + (dayValue - 1);
    currentEtDayId = "btn" + currentEtDayId;

    //the following 1 line code display the currentEtDate in the textbox provided to the js on page load time.
    //document.getElementById(localId).value = currentEtDay + "/" + currentEtMonth + "/" + currentEtYear;
    //the next 1 line code add 1 to selectedEtMonth inside the option box since it starts from 0 unlike to the returned currentEtMonth
    //from the converter function, which returns the exact month. so that the if statment will achieve to work acurately.
    selectedEtMonth = parseInt(selectedEtMonth + 1);
    if (currentEtDayId == "btn36") {//this if statment check special case of id generation, if the 30th day of ethiopian month
        currentEtDayId = "btn30+"; //appears at the first cell of the table, it assigns btn36, which is not exist so it gives btn30+/special case/
    }
    //this if statment helps user to see the current date on the calendar by changing the style appearance of it. and only works if
    //and only if the user is on the current month and year other wise it skip it.
    //alert(document.getElementById('month_list').value);
    if (currentEtMonth == selectedEtMonth && currentEtYear == selectedEtYear) {
        //alert(selectedEtYear + selectedEtMonth);
        document.getElementById(currentEtDayId).style.color = "white";
        document.getElementById(currentEtDayId).style.backgroundColor = "#669933";
    }
}
//******************************************************************************
/*THE OTHER ESSENCIAL FUNCTION FOR THIS SCRIPT IS THIS FUNCTION, ITS USAGE IS TO
CHANGE INNER CONTENT OF THE MONTHS STRUCTURE WHEN USER OF CALENDAR CHANGE THE
MONTH OR YEAR,LET'S TAKE SENE 1977 STARTS FROM SATURDAY AND ENDS WITH SUNDAY,
WHEN USER OF CALENDAR CHANGE THE SELECTED MONTH OR YEAR THE STARTING OR ENDING
OF THE SELECTED MONTH STRUCTURE SHOULD REFLECT AGAINST THE CHANGE MADE*/
/*------------------------------------------------------------------------------*/
function changeMonthStructure(startingDayOfMonth) {
    var selectedMonth = document.getElementById("month_list").value;
    var selectedYear = document.getElementById("year_list").value;
    if (selectedMonth != 13) {
        var dayvalue, cellposition;
        switch (startingDayOfMonth) {
            case "ሰኞ": {
                dayvalue = "1"; break;
            }
            case "ማክሰኞ": {
                dayvalue = "2"; break;
            }
            case "ረቡዕ": {
                dayvalue = "3"; break;
            }
            case "ሐሙስ": {
                dayvalue = "4"; break;
            }
            case "አርብ": {
                dayvalue = "5"; break;
            }
            case "ቅዳሜ": {
                dayvalue = 6; break;
            }
            default: {
                dayvalue = "7"; break;
            }
        }
        var isThisYearLeap = isLeapYear(document.getElementById("year_list").value);
        var isLastYearLeap = isLeapYear(parseInt(document.getElementById("year_list").value) - 1);

        // var breakHolder = 0;
        //var tableConstructor = "<tr id='toprow'><td>ሰኞ</td><td>ማክሰ</td><td>ረቡዕ</td><td>ሐሙስ</td><td>አርብ</td><td>ቅዳሜ</td><td>እሁድ</td></tr>";
        if (language == "Amh")
            tableConstructor = "<tr id='toprow'><td>ሰኞ</td><td>ማክሰ</td><td>ረቡዕ</td><td>ሐሙስ</td><td>አርብ</td><td>ቅዳሜ</td><td>እሁድ</td></tr>";
        else
            tableConstructor = "<tr id='toprow'><td>Mon</td><td>Tus</td><td>Wed</td><td>Thu</td><td>Fri</td><td>Sat</td><td>Sun</td></tr>";
        var hold = 1, cellPosition = 1;

        for (var rowPosition = 1; rowPosition <= 5; rowPosition++) {
            tableConstructor += "<tr>";
            for (/*var*/cellPosition = hold; cellPosition <= (30 + dayvalue); cellPosition++) {
                if ((cellPosition == 1) && dayvalue == 7 && selectedMonth != 12) {
                    tableConstructor += "<td class='tt'><a  style='text-decoration:none' class='btns' id='btn30+'  value='30' onclick='displayToday(30),ice_hide()' onmouseover='convertEcToGc(30)'>30</a></td>";
                    continue;
                }
                if (selectedMonth == 12 && (cellPosition - dayvalue) >= (5 + isThisYearLeap)) {
                    document.getElementById("divtable").innerHTML = "<table>" + tableConstructor + "</table>";
                    highlightToday(dayvalue);
                    if (selectedYear == 2002)
                        document.getElementById("btn4").style.color = "#FF82AB";
                    return;
                }

                if (cellPosition >= dayvalue) {
                    if ((cellPosition - dayvalue) >= 30)
                        continue;
                    if (cellPosition % 7 == 0) {
                        tableConstructor += "<td class='tt'><a href='Javascript:void(0)'  style='text-decoration:none' class='btns' id='btn" + cellPosition + "'  value='" + (cellPosition - (dayvalue - 1)) + "' onclick='displayToday(" + (cellPosition - (dayvalue - 1)) + "),ice_hide()' onmouseover='showtip(event," + (cellPosition - (dayvalue - 1)) + "),convertEcToGc(" + (cellPosition - (dayvalue - 1)) + ")' onmouseout='hidetip()'>" + (cellPosition - (dayvalue - 1)) + "</a></td>";
                        hold = cellPosition + 1;
                        break;
                    }
                    tableConstructor += "<td class='tt'><a href='Javascript:void(0)' style='text-decoration:none' class='btns' id='btn" + cellPosition + "'  value='" + (cellPosition - (dayvalue - 1)) + "' onclick='displayToday(" + (cellPosition - (dayvalue - 1)) + "),ice_hide()' onmouseover='showtip(event," + (cellPosition - (dayvalue - 1)) + "),convertEcToGc(" + (cellPosition - (dayvalue - 1)) + ")' onmouseout='hidetip()' >" + (cellPosition - (dayvalue - 1)) + "</a></td>";
                }
                else {
                    tableConstructor += "<td class='emptydays'><input class='btnempty' id='btn" + cellPosition + "' type='button' value='X'></td>";
                }
            }
            tableConstructor += "</tr>";
        }
        document.getElementById("divtable").innerHTML = "<table id='tbl'>" + tableConstructor + "</table>";
    }


    highlightToday(dayvalue);


    //next 2 lines get the EC selected month & year when the calendar is load @ 1st time
    var selectedEtMonth = parseInt(document.getElementById("month_list").value);
    //alert(selectedEtMonth);
    var selectedEtYear = parseInt(document.getElementById("year_list").value);
    //next 2 lines code get the current date location inside a table cell from 30 occupied cells/excluding empty cells
    var currentEtDayId = parseInt(currentEtDay) + (dayvalue - 1);
    currentEtDayId = "btn" + currentEtDayId;
    //the next 1 line code add 1 to selectedEtMonth inside the option box since it starts from 0 unlike to the returned currentEtMonth
    //from the converter function, which returns the exact month. so that the if statment will achieve to work acurately.
    selectedEtMonth = parseInt(selectedEtMonth + 1);
    //HOLY DAYS MARKING UPS.
    //The following blocks of if determines and mark holydays and special days of ethiopia for each year accordingly
    var bottomListInnerHTML = document.getElementById("div3").innerHTML;
    //alert(bottomListInnerHTML);
    if (selectedEtYear >= 1900) {
        dayvalue = parseInt(dayvalue);
        if (selectedEtMonth == 1) {
            document.getElementById("btn" + dayvalue).style.color = "#FF82AB";
            //document.getElementById("btn" + dayvalue).style.backgroundColor = "#ff9999";
            document.getElementById("btn" + (dayvalue + 16)).style.color = "#FF82AB";
        //document.getElementById("btn" + (dayvalue + 16)).style.backgroundColor = "#ff9999";
        //document.getElementById("div3").innerHTML = bottomListInnerHTML + "'1' አዲስ ዓመት '17' መስቀል";
        }

        else if (selectedEtMonth == 4) {
            if (isLastYearLeap == 1)
                document.getElementById("btn" + (dayvalue + 27)).style.color = "#FF82AB";
            else
                document.getElementById("btn" + (dayvalue + 28)).style.color = "#FF82AB";
        }
        else if (selectedEtMonth == 5) {
            document.getElementById("btn" + (dayvalue + 10)).style.color = "#FF82AB";
        }
        else if (selectedEtMonth == 6) {
            document.getElementById("btn" + (dayvalue + 22)).style.color = "#FF82AB";
        }
        else if (selectedEtMonth == 7) {
            var holydays = etEasterCalculation();
            var sikletAndFasica = holydays.split('/');
            var sikletDay = sikletAndFasica[0];
            var sikletMonth = sikletAndFasica[1];
            var fasikaDay = sikletAndFasica[2];
            var fasikaMonth = sikletAndFasica[3];
            if (sikletMonth == (selectedEtMonth - 1)) {
                document.getElementById("btn" + (dayvalue + (sikletDay - 1))).style.color = "#FF82AB";
            }
            if (fasikaMonth == (selectedEtMonth - 1)) {
                document.getElementById("btn" + (dayvalue + (fasikaDay - 1))).style.color = "#FF82AB";
            }
        }
        else if (selectedEtMonth == 8) {

            holydays = etEasterCalculation();
            sikletAndFasica = holydays.split('/');
            sikletDay = sikletAndFasica[0];
            sikletMonth = sikletAndFasica[1];
            fasikaDay = sikletAndFasica[2];
            fasikaMonth = sikletAndFasica[3];

            if (sikletMonth == (selectedEtMonth - 1)) {
                document.getElementById("btn" + (dayvalue + (sikletDay - 1))).style.color = "#FF82AB";
            }
            if (fasikaMonth == (selectedEtMonth - 1)) {
                document.getElementById("btn" + (dayvalue + (fasikaDay - 1))).style.color = "#FF82AB";
            }

            document.getElementById("btn" + (dayvalue + 22)).style.color = "#FF82AB";
            //document.getElementById("btn" + (dayvalue + 22)).style.backgroundColor = "#ff9999";
            document.getElementById("btn" + (dayvalue + 26)).style.color = "#FF82AB";
        //document.getElementById("btn" + (dayvalue + 26)).style.backgroundColor = "#ff9999";
        }
        else if (selectedEtMonth == 9) {
            document.getElementById("btn" + (dayvalue + 19)).style.color = "#FF82AB";
        //document.getElementById("btn" + (dayvalue + 19)).style.backgroundColor = "#ff9999";
        }

        if (selectedEtYear >= 1988) {
            var mewlidDay, mewlidMonth, FeturDay, FeturMonth, AdhaDay, AdhaMonth;
            var holyday1, holyday2, holyday3;
            var mewlid, edAlFetir, edAlAdha;

            holyday1 = lunarHolyDays('M'); //Ask for Mewlid
            holyday2 = lunarHolyDays('F'); //Ask for edAlFetir
            holyday3 = lunarHolyDays('A'); //Ask for edAlAdha

            mewlid = holyday1.split('/');
            mewlidDay = parseInt(mewlid[0]);
            mewlidMonth = parseInt(mewlid[1]);
            // mewlidYear = mewlid[2];

            edAlFetir = holyday2.split('/');
            FeturDay = parseInt(edAlFetir[0]);
            FeturMonth = parseInt(edAlFetir[1]);
            //mewlidYear = edAlFetir[2];

            edAlAdha = holyday3.split('/');
            AdhaDay = parseInt(edAlAdha[0]);
            AdhaMonth = parseInt(edAlAdha[1]);

            if (selectedEtMonth == mewlidMonth) {
                //alert("mewlid month");
                document.getElementById("btn" + (dayvalue + (mewlidDay - 1))).style.color = "#FF82AB";
            }
            if (selectedEtMonth == FeturMonth) {
                //alert("fetur month " + FeturDay);
                document.getElementById("btn" + (dayvalue + (FeturDay - 1))).style.color = "#FF82AB";
            }
            if (selectedEtMonth == AdhaMonth) {
                //alert("adha month");
                document.getElementById("btn" + (dayvalue + (AdhaDay - 1))).style.color = "#FF82AB";
            }
            /*the next 3 if statements manually mark the 3 holydays of muslims manually, it is because there are 2 same holydays on
            each selected year in between meskerem 1-8 and nehase 25 and above at the same year because of 1 year in hijira is 354
            and the function lunarHolyDays() will always skip the 1st one and return z last holyday only*/

            if (selectedEtYear == 2002 && selectedEtMonth == 1) {//in 2002 there are 2 edalfetirs so the 1st appearance'll be the next line
                document.getElementById("btn" + (dayvalue + 8)).style.color = "#FF82AB";
            }

            if (selectedEtYear == 2009 && selectedEtMonth == 1) {//in 2009 there are 2 edaladhas so the 1st appearance'll be the next line
                document.getElementById("btn" + (dayvalue + 1)).style.color = "#FF82AB";
            }

            if (selectedEtYear == 2017 && selectedEtMonth == 1) {//in 2017 there are 2 mewlids so the 1st appearance'll be the next line
                document.getElementById("btn" + (dayvalue + 4)).style.color = "#FF82AB";
            }
        }
    }
}
//***********************************************************************
/*SIMPLE BUT POWERFUL ON GIVING INFO TO USER, THIS FUNCTION MAJOR DUTIES IS TO
GIVE/SHOW WHAT MONTH AND YEAR IS CURRENTLY SELECTED BY THE USER, FOR EXAMPLE
IF USER CHOOSE MESKEREM 2002 THEN THIS FUNCTION DISPLAY THE SELECTED DATE @ THE
HEADER, SO THAT USER SIMPLY GET WHAT THEY CURRENTLY SELECTED.*/
/*------------------------------------------------------------------------------*/

function changeHeader() {
    var selectedMonth = document.getElementById("month_list").value;
    var selectedYear = document.getElementById("year_list").value;
    var month;
    if (language == "Amh") {
        switch (selectedMonth * 1) {
            case 0: {
                month = "መስከረም"; break;
            }
            case 1: {
                month = "ጥቅምት"; break;
            }
            case 2: {
                month = "ሕዳር"; break;
            }
            case 3: {
                month = "ታህሳስ"; break;
            }
            case 4: {
                month = "ጥር"; break;
            }
            case 5: {
                month = "የካቲት"; break;
            }
            case 6: {
                month = "መጋቢት"; break;
            }
            case 7: {
                month = "ሚያዝያ"; break;
            }
            case 8: {
                month = "ግንቦት"; break;
            }
            case 9: {
                month = "ሰኔ"; break;
            }
            case 10: {
                month = "ሐምሌ"; break;
            }
            case 11: {
                month = "ነሐሴ"; break;
            }
            default: {
                month = "ጳጉሜ"; break;
            }

        }
    }
    else {
        switch (selectedMonth * 1) {
            case 0: {
                month = "Meskerem"; break;
            }
            case 1: {
                month = "Tikimt"; break;
            }
            case 2: {
                month = "Hidar"; break;
            }
            case 3: {
                month = "Tahissas"; break;
            }
            case 4: {
                month = "Thir"; break;
            }
            case 5: {
                month = "Yekatit"; break;
            }
            case 6: {
                month = "Megabit"; break;
            }
            case 7: {
                month = "Miaziya"; break;
            }
            case 8: {
                month = "Ginbot"; break;
            }
            case 9: {
                month = "Sene"; break;
            }
            case 10: {
                month = "Hamle"; break;
            }
            case 11: {
                month = "Nehasse"; break;
            }
            default: {
                month = "Puagme"; break;
            }

        }
    }

    document.getElementById("divHead").innerHTML = month + ", " + selectedYear;

}
//*****************************************************************************
/*USE ONE OF THESE FUNCTIONS TO DISABLE THE CSS VISIBILITY OF CALENDAR IN ANY APPLICATION
I PREFFERED TO USE THE 2ND ONE, WHICH WORKS EVERYWHERE*/
/*------------------------------------------------------------------------------*/
function hide() {
    if (document.getElementById("div1")) {
        document.getElementById("div1").style.display = "none";
    }
}
function ice_hide() {
    hide(); //hides the calendar
    hidetip(); //hides if there is holydays tip on the screen
}
//******************************************************************************
/*USE THIS FUNCTION TO ENABLE THE CSS VISIBILITY OF CALENDAR IN ANY APPLICATION */
/*------------------------------------------------------------------------------*/
function show() {
    if (document.getElementById("div1")) {
        document.getElementById("div1").style.display = "block";
    }
}
//******************************************************************************
/*MOST JS FACE THE DIFFICULTIES TO BE EXECUTED IN MOST OF THE BROWSERS, IT RARELY
DIFFICULT FOR THIS SCRIPT TOO, THIS FUNCTION TRIES TO SHOW/DISPLAY THIS CALENDAY
UNDER THE BUTTON YOU USE TO DISPLAY/CALL THE CALENDAR IN THE APPLICATION. AS ALTERNATIVE,
OTHER FUNCTION CALLED GETVISUAL() DO SIMILAR TASK.*/
/*------------------------------------------------------------------------------*/

function getPosition(elementId) {

    var element = document.getElementById(elementId);
    var left = 0;
    var top = 0;
    var elementHeight = document.getElementById(elementId).style.height;
    elementHeight = parseInt(elementHeight);
    if (element != null) {
        try {
            while (element.offsetParent) {
                left += element.offsetLeft + 0.5;
                if (element.offsetParent.scrollLeft) {
                    left -= element.offsetParent.scrollLeft;
                }
                top += element.offsetTop;
                if (element.offsetParent.scrollTop) {
                    top -= element.offsetParent.scrollTop-25;
                }
                element = element.offsetParent;
            }
        }
        catch (e) {
        // Do nothing
        }
        left += element.offsetLeft + document.body.scrollLeft - document.body.clientLeft;
        top += element.offsetTop + document.body.scrollTop - document.body.clientTop;
    }
    document.getElementById("div1").style.left = left + "px";
    document.getElementById("div1").style.top = top + 23 + "px";
    //document.getElementById("div1").style.display = "block";
    changeButtonStatus();
    getToday();
}
//******************************************************************************
/*MOST JS FACE THE DIFFICULTIES TO BE EXECUTED IN MOST OF THE BROWSERS, IT RARELY
DIFFICULT FOR THIS SCRIPT TOO, THIS FUNCTION TRIES TO SHOW/DISPLAY THIS CALENDAY
UNDER THE BUTTON YOU USE TO DISPLAY/CALL THE CALENDAR IN THE APPLICATION. AS ALTERNATIVE,
OTHER FUNCTION CALLED GETPOSITION() DO SIMILAR TASK.*/
/*------------------------------------------------------------------------------*/
function getVisual(e) {
    if (!e) e = window.event;

    if (e.pageX == null && e.clientX != null) {
        var doc = document.documentElement, body = document.body;

        e.pageX = e.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc.clientLeft || 0);
        e.pageY = e.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc.clientTop || 0);
    }
    document.getElementById("div1").style.left = e.pageX + "px";
    document.getElementById("div1").style.top = e.pageY + "px";
    document.getElementById("div1").style.display = "block";

}
//*****************************************************************************
/*IF YOU WANT TO DISPLAY THE RESULT DATE(THE DATE YOU SELECTED) IN A SPECIFIC
TEXT LOCATION, THEN SEND THE ID OF THE TEXT OR ANY OTHER CONTROL AT THE ONCLICK
EVENT OF CALENDAR BUTTON TO THIS FUNCTION.*/
/*------------------------------------------------------------------------------*/
function getId(sentIdFromForm) {
    localId = sentIdFromForm;
}

//REMEMBER TO INCLUDE THE CSS PART OF THIS SCRIPT TO DISPLAY THE CALENDAR WITH THE RIGHT LAYOUT
//----------------------------------------END OF SCRIPT--------------------------------------------------------------



//ADDITIONAL FEATURS

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// EC TO GC
//*****************************************************************************
/*THIS FUNCTION ACCEPTS EC DAY,MONTH AND YEAR, THEN IT CONVERTED TO GC AND RETURN
TO CALLER FUNCTION ON GC DD/MM/YYYY FORMAT, THE CALLER FUNCTION MAY NEED TO SPLIT
THE STRING TO GET DAY,MONTH AND YEAR SEPARATELY*/
/*------------------------------------------------------------------------------*/
function ECtoGC_Converter(day, month, year) {
    day = parseInt(day);
    month = parseInt(month);
    year = parseInt(year);
    var cday, cmonth, cyear;
    var leapEffect = isLeapYear(year - 1);
    var gcLeapEffect;
    var convertedDate;

    if (month == 1) //if Meskerem
    {
        cyear = year + 7;
        if (day <= (20 - leapEffect)) {
            cmonth = 9; //sep
            cday = day + 10 + leapEffect;
        }
        else {
            cmonth = 10; //oct
            if (leapEffect == 1)
                cday = day - 19;
            else
                cday = day - 20;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 2) //if Tikimt
    {
        cyear = year + 7;
        if (day <= (21 - leapEffect)) {
            cmonth = month + 8; //oct
            cday = day + 10 + leapEffect;
        }
        else {
            cmonth = month + 9; //nov
            if (leapEffect == 1)
                cday = day - 20;
            else
                cday = day - 21;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 3) //if Hidar
    {
        cyear = year + 7;
        if (day <= (21 - leapEffect)) {
            cmonth = month + 8; //nov
            cday = day + 9 + leapEffect;
        }
        else {
            cmonth = month + 9; //dec
            if (leapEffect == 1)
                cday = day - 20;
            else
                cday = day - 21;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 4) //if Tahissas
    {
        //cyear = year + 7;
        if (day <= (22 - leapEffect)) {
            cyear = year + 7; //year is ready to switch
            cmonth = month + 8; //dec
            cday = day + 9 + leapEffect;
        }
        else {
            cyear = year + 8; //year is switched
            cmonth = month - 3;  //JAN /*HAPPY NEW YEAR*/
            if (leapEffect == 1)
                cday = day - 21;
            else
                cday = day - 22;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 5) //if Thir
    {
        cyear = year + 8;
        if (day <= (23 - leapEffect)) {
            cmonth = month - 4; //jan
            cday = day + 8 + leapEffect;
        }
        else {
            cmonth = month - 3; //feb /*April the fool*/
            if (leapEffect == 1)
                cday = day - 22;
            else
                cday = day - 23;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 6) //if Yekatit
    {
        cyear = year + 8;
        gcLeapEffect = isLeapYearGC(cyear);
        if (day <= ((21 + gcLeapEffect) - leapEffect)) {
            cmonth = month - 4; //feb
            cday = day + 7 + leapEffect;
        }
        else {
            cmonth = month - 3; //mar
            if (leapEffect == 1)
                cday = day - (20 + gcLeapEffect);
            else
                cday = day - (21 + gcLeapEffect);
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 7) //if Megabit
    {
        cyear = year + 8;
        if (day <= 22) {
            cmonth = month - 4;  //mar
            cday = day + 9;
        }
        else {
            cmonth = month - 3;  //apr
            cday = day - 22;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 8) //if Miyazia
    {
        cyear = year + 8;
        if (day <= 22) {
            cmonth = month - 4;  //apr
            cday = day + 8;
        }
        else {
            cmonth = month - 3;  //may
            cday = day - 22;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 9) //if Ginbot
    {
        cyear = year + 8;
        if (day <= 23) {
            cmonth = month - 4;  //may
            cday = day + 8;
        }
        else {
            cmonth = month - 3;  //jun
            cday = day - 23;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 10) //if Sene
    {
        cyear = year + 8;
        if (day <= 23) {
            cmonth = month - 4;  //jun
            cday = day + 7;
        }
        else {
            cmonth = month - 3;  //jul
            cday = day - 23;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 11) //if Hamle
    {
        cyear = year + 8;
        if (day <= 24) {
            cmonth = month - 4;  //jul
            cday = day + 7;
        }
        else {
            cmonth = month - 3;  //aug
            cday = day - 24;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 12) //if Nehasse
    {
        cyear = year + 8;
        if (day <= 25) {

            cmonth = month - 4;  //aug
            cday = day + 6;
        }
        else {
            //cyear = year + 7;
            cmonth = month - 3;  //sep
            cday = day - 25;
        }
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    if (month == 13) //if Puagme /*Ethiopian's alone month*/
    {
        cyear = year + 8;
        cmonth = month - 4;  //sep
        cday = 10;
        //alert(cday + "/" + cmonth + "/" + cyear);
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
} //end of function

//*****************************************************************************
/*YOU CAN CALL THE ECTOGC_CONVERTER(,,) FUNCTION INDIRECTLY BY THIS FUNCTION, THIS
HELPS BECAUSE WE ONLY NEED TO PASS ONE ARGUMENT TO THIS, RATHER TO PASS DIRECTLY
3 PARAMETERS...USER SEE THE CONVERTED DATE AT THE BUTTOM BY THIS FUNCTION WHEN
SHE/HE TRIGGER THE ONMOUSE OVER EVENT ON EACH DAY*/
/*------------------------------------------------------------------------------*/
function convertEcToGc(day) {
    day = parseInt(day);
    var month = parseInt(document.getElementById('month_list').value);
    var year = parseInt(document.getElementById('year_list').value);
    //document.getElementById("TextBox1").value = ECtoGC_Converter(day, month, year);
    //document.getElementById("div3").innerHTML = "<center><b>GC Equivalent: " + ECtoGC_Converter(day, month, year) + "</b></center>";
    document.getElementById("div3").innerHTML = "<table style='color:white;' ><tr><td>GC Equivalent: </td><td width='85'>" + ECtoGC_Converter(day, month, year) + "</td><td><a style='cursor:pointer;text-decoration:none;color:#fff;padding:1px;' onclick='ice_hide()'>Hide</a></td></tr></table>";

//return ECtoGC_Converter(day, month, year)
}
//*****************************************************************************
/*CHECKS WHETHER A YEAR IS LEAP OR NOT (FEB 29 OR 28) FOR GREGORIAN CALENDAR*/
/*------------------------------------------------------------------------------*/
function isLeapYearGC(yearToBeChecked) {
    yearToBeChecked = parseInt(yearToBeChecked);
    if (yearToBeChecked % 4 == 0) {
        if (yearToBeChecked % 100 != 0)
            return 1;
        else if (yearToBeChecked % 400 == 0)
            return 1;
        else
            return 0;
    }
    return 0;
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// GC TO EC
//*****************************************************************************
/*THIS FUNCTION ACCEPTS GC DAY,MONTH AND YEAR, THEN IT CONVERTED TO EC AND RETURN
TO CALLER FUNCTION ON ET DD/MM/YYYY FORMAT, THE CALLER FUNCTION MAY NEED TO SPLIT
THE STRING TO GET DAY,MONTH AND YEAR SEPARATELY*/
/*------------------------------------------------------------------------------*/
function GCtoEC_Converter(day, month, year) {
    /*alert(day + "/" + month + "/" + year);*/
    //alert("in function" + month);
    day = parseInt(day);
    month = parseInt(month);
    year = parseInt(year);
    var cday, cmonth, cyear;
    var ecLeapEffect, gcLeapEffect;
    var convertedDate;
    ecLeapEffect = isLeapYear(year - 9);
    gcLeapEffect = isLeapYearGC(year);
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 1)//jan
    {
        cyear = year - 8;
        //alert(ecLeapEffect);
        if (day <= (8 + ecLeapEffect)) {
            cmonth = month + 3; //tahissas
            cday = (day + 22 - ecLeapEffect);
        }
        else {
            cmonth = month + 4; //thir
            if (ecLeapEffect == 1)
                cday = day - 9;
            else
                cday = day - 8;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    else if (month == 2)//feb
    {
        cyear = year - 8;
        if (day <= (7 + ecLeapEffect)) {
            cmonth = month + 3; //thir
            cday = (day + 23 - ecLeapEffect);
        }
        else {//this else statment doesn't need to consider GC Leap year, since it doesn't make any diffrence on conversion
            cmonth = month + 4; //yekatit
            if (ecLeapEffect == 1)
                cday = day - 8;
            else                    //1ce in 4 year feb leap it self and be 29 rather 28 in this else statment
                cday = day - 7;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 3)//mar
    {
        //both ec and gc leapeffects returns one here so either feb 29 or 28 it ends with ec 21 and march starts from 22
        //so in this case the ec leap effect affects no more month before this end of year, since it is rejected by gc leap effect
        //alert("ec leap = " + ecLeapEffect + " gc leap = " + gcLeapEffect);
        //alert(gcLeapEffect);
        cyear = year - 8;
        if (day <= 9) {
            cmonth = month + 3; //yekatit
            cday = (day + 21);
        }
        else {
            cmonth = month + 4; //megabit
            cday = day - 9;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 4)//apr
    {
        cyear = year - 8;
        if (day <= 8) {
            cmonth = month + 3; //megabit
            cday = (day + 22);
        }
        else {
            cmonth = month + 4; //miyaziya
            cday = day - 8;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 5)//may
    {
        cyear = year - 8;
        if (day <= 8) {
            cmonth = month + 3; //miyaziya
            cday = (day + 22);
        }
        else {
            cmonth = month + 4; //ginbot
            cday = day - 8;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 6)//jun
    {
        cyear = year - 8;
        if (day <= 7) {
            cmonth = month + 3; //ginbot
            cday = (day + 23);
        }
        else {
            cmonth = month + 4; //sene
            cday = day - 7;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 7)//jul
    {
        cyear = year - 8;
        if (day <= 7) {
            cmonth = month + 3; //sene
            cday = (day + 23);
        }
        else {
            cmonth = month + 4; //hamle
            cday = day - 7;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 8)//aug
    {
        cyear = year - 8;
        if (day <= 6) {
            cmonth = month + 3; //hamle
            cday = (day + 24);
        }
        else {
            cmonth = month + 4; //nehasse
            cday = day - 6;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 9)//sep
    {
        ecLeapEffect2 = isLeapYear(year - 8); // this is not the same leap check as the global peer, rather it checks if the current
        if (day <= 5) {                         //year is leap or not, the global checks if the current-1 is leap or not.
            cyear = year - 8;
            cmonth = month + 3; //nehasse
            cday = (day + 25);
        }
        else if (day >= 6 && day <= (10 + ecLeapEffect2)) {
            cyear = year - 8;
            cmonth = month + 4; //Puagme
            cday = day - 5;
        }
        else {
            cyear = year - 7;
            cmonth = month - 8; //Meskerem
            if (ecLeapEffect2 == 1)
                cday = day - 11;
            else
                cday = day - 10;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 10)//oct
    {
        ecLeapEffect2 = isLeapYear(year - 8); // check if last ethiopian year is leap or not, bc it affects months after puagme 5 or 6
        cyear = year - 7;                       // and consider that there is no gc leap arround this month so it will continue until it gets it.
        if (day <= (10 + ecLeapEffect2)) {
            cmonth = month - 9;  //meskerem
            if (ecLeapEffect2 == 1)
                cday = day + 19;
            else
                cday = day + 20;
        }
        else {
            cmonth = month - 8;  //tikimt
            if (ecLeapEffect2 == 1)
                cday = day - 11;
            else
                cday = day - 10;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;

    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 11)//nov
    {
        ecLeapEffect2 = isLeapYear(year - 8); // check if last ethiopian year is leap or not, bc it affects months after puagme 5 or 6
        cyear = year - 7;                       // and consider that there is no gc leap arround this month so it will continue until it gets it.
        if (day <= (9 + ecLeapEffect2)) {
            cmonth = month - 9;  //tikimt
            if (ecLeapEffect2 == 1)
                cday = day + 20;
            else
                cday = day + 21;
        }
        else {
            cmonth = month - 8;  //hidar
            if (ecLeapEffect2 == 1)
                cday = day - 10;
            else
                cday = day - 9;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        return convertedDate;

    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if (month == 12)//dec
    {
        //alert(day+" " + month + " " + year);
        ecLeapEffect2 = isLeapYear(year - 8); // check if last ethiopian year is leap or not, bc it affects months after puagme 5 or 6
        cyear = year - 7;                       // and consider that there is no gc leap arround this month so it will continue until it gets it.
        if (day <= (9 + ecLeapEffect2)) {
            cmonth = month - 9;  //hidar
            if (ecLeapEffect2 == 1)
                cday = day + 20;
            else
                cday = day + 21;
        }
        else {
            cmonth = month - 8;  //tahissas
            if (ecLeapEffect2 == 1)
                cday = day - 10;
            else
                cday = day - 9;
        }
        convertedDate = cday + "/" + cmonth + "/" + cyear;
        //alert(convertedDate);
        return convertedDate;

    }
//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
} //end of function
//*****************************************************************************
/*THIS FUNCTION RETURN AT WHAT INDEX EACH ETHIOPIAN YEARS APPEAR IN A DROP DOWN
YEAR LIST BOX, FOR EXAMPLE 1900 @ INDEX 0, 1901 @ INDEX 1....BLA BLA*/
/*------------------------------------------------------------------------------*/
function getYearIndex(year) {
    year = parseInt(year);
    //alert("getyear");
    var i = 0, years = 1900;
    while (years <= 2050) {
        if (year == years) {
            //alert(i + " " + years);
            return i;
        }
        else {
            years++;
            i++;
        }
    }
    return 0;
}
//*****************************************************************************
/*THIS FUNCTION CALCULATE AND TELL THE CURENT ETHIOPIAN MONTH AND YEAR TO
ENABLE A START FROM THESE MONTH WHEN NEEDED*/
/*------------------------------------------------------------------------------*/
function currentDate() {
    var today = new Date();
    var currentGcDay = today.getDate();
    var currentGcMonth = today.getMonth();
    var currentGcYear = parseInt(today.getFullYear());
    var convertedDate = GCtoEC_Converter(currentGcDay, currentGcMonth, currentGcYear); //this function returns the EC equivalent of GC

    var elem = convertedDate.split('/');
    currentEtDay = elem[0];
    currentEtMonth = elem[1];
    currentEtYear = elem[2];

    var yearIndex = getYearIndex(currentEtYear); // this function gets the ethiopian year index inside a listbox
    document.getElementById("month_list").selectedIndex = (currentEtMonth - 1);
    document.getElementById("year_list").selectedIndex = yearIndex;
}
//*****************************************************************************
/*THIS FUNCTION CALCULATE AND TELL THE CURENT ETHIOPIAN MONTH AND YEAR TO
ENABLE A START FROM THESE MONTH WHEN NEEDED*/
/*------------------------------------------------------------------------------*/
function getToday() {

    //the following 4 lines js code feach system day, month and year from the local computer that executed the script
    var today = new Date();
    var currentGcDay = parseInt(today.getDate());
    var currentGcMonth = parseInt(today.getMonth());
    var currentGcYear = parseInt(today.getFullYear());
    document.getElementById("div3").innerHTML = "<table style='color:white;'><tr><td>GC Equivalent: </td><td width='85'>" + currentGcDay + "/" + (currentGcMonth + 1) + "/" + currentGcYear + "</td><td><a  style='text-decoration:none;color:#fff;padding:1px; text-align:right;' onclick='ice_hide()'>Hide</a></td></tr></table>";
    //the following 1 line js code pass the feached current date to EC convertor function and put the returned date value in a
    //string format to convertedDate variable which holds the Ethiopian equivalent of system current GC date.
    var convertedDate = GCtoEC_Converter(currentGcDay, currentGcMonth, currentGcYear);

    //the following 4 lines code get only the number value of day,month and year of the converted ethiopian date without special
    // caracter '/' that is returned from the GCtoEC_Converter(,,) function
    var elem = convertedDate.split('/');
    currentEtDay = elem[0];
    currentEtMonth = elem[1];
    currentEtYear = elem[2];

    var yearIndex = getYearIndex(currentEtYear); // this function gets the ethiopian year index inside a listbox
    document.getElementById("month_list").selectedIndex = (currentEtMonth - 1);
    document.getElementById("year_list").selectedIndex = yearIndex;
    changeMonthStructure(displayToday2());
    changeHeader();
}
//*****************************************************************************
/*A FUNCTION THAT CREATES A TOOLTIP WHEN THE CALENDAR IS UNDER CONSTRUCTION, AND MAKE
IT READY WHEN A TOOL TIP IS REQUIRED*/
/*------------------------------------------------------------------------------*/
function createToolTip() {
    var toolTip = document.createElement("div");
    toolTip.id = "tooltip";
    toolTip.Name = "tooltip";
    toolTip.style.visibility = "hidden";
    document.body.appendChild(toolTip);
}
//*****************************************************************************
/*A FUNCTION THAT IS USED TO HIDE THE TOOL TIP WHEN IT IS NOT HIDDEN*/
/*------------------------------------------------------------------------------*/
function hide_toolTip() {
    document.getElementById("toolTip").style.display = "none";
}
//*****************************************************************************
/*A FUNCTION THAT IS USED TO SHOW THE TOOL TIP WHEN IT IS HIDDEN */
/*------------------------------------------------------------------------------*/
function show_toolTip(event) {
    //document.getElementById("toolTip").innerHTML=innerText;
    document.getElementById("toolTip").style.display = "block";

    getToolTipVisual(event);
}
//*****************************************************************************
/*THIS FUNCTION DISPLAY THE TOOL TIP AT THE RIGHT LOCATION */
/*------------------------------------------------------------------------------*/
function getToolTipVisual(e) {
    if (!e) e = window.event;

    if (e.pageX == null && e.clientX != null) {
        var doc = document.documentElement, body = document.body;

        e.pageX = e.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc.clientLeft || 0);
        e.pageY = e.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc.clientTop || 0);
    }
    document.getElementById("toolTip").style.left = e.pageX + "px";
    document.getElementById("toolTip").style.top = e.pageY + "px";
    document.getElementById("toolTip").style.display = "block";
}
//*****************************************************************************
/*THE ETHIOPIAN ORTHODOX RELEGION HAS ITS OWN CALENDAR TO CALCULATE DATES, SPECIALLY
HOLYDAYS LIKE ETHIOPIAN EASTER AND GOOD FRIDAY, THE FOLLOWING FUNCTION
IS USED TO CALCULATE THE EXACT APPEARANCE OF BOTH EASTER AND GOOD FRIDAY ON EACH YEAR, TO
MARK THEM ON THE CALENDAR*/
/*------------------------------------------------------------------------------*/
function etEasterCalculation() {

    var year, x, ameteAlem, wenber, mitkDay, tewsak, bealeMitkMonth, neneweStartsAt, mebajaHammer;
    year = parseInt(document.getElementById('year_list').value);
    var siklet, tinsae;
    ameteAlem = (year + 5500);
    wenber = ameteAlem % 19;

    if (wenber == 0)
        wenber = 19;

    x = wenber - 1;
    mitkDay = (x * 19) % 30;
    if (mitkDay > 14)
        bealeMitkMonth = 1; //meskerem
    else
        bealeMitkMonth = 2; //tikimt
    var eleteMitk = whatIsToday(numberOfDays(mitkDay, bealeMitkMonth, year));
    switch (eleteMitk) {
        case "ቅዳሜ": {
            tewsak = 8; break;
        }
        case "እሁድ": {
            tewsak = 7; break;
        }
        case "ሰኞ": {
            tewsak = 6; break;
        }
        case "ማክሰኞ": {
            tewsak = 5; break;
        }
        case "ረቡዕ": {
            tewsak = 4; break;
        }
        case "ሐሙስ": {
            tewsak = 3; break;
        }
        default: {
            tewsak = 2; break;
        }
    }
    mebajaHammer = tewsak + mitkDay; // nenewe starting day
    //alert("mh = " + mebajaHammer + "tewsak = " +tewsak);

    if (bealeMitkMonth == 1) {
        if (mebajaHammer <= 30) {
            neneweStartsAt = 4; //tir
        }
        else {
            mebajaHammer = mebajaHammer - 30;
            neneweStartsAt = 5; //yekatit
        }
    }
    else {
        neneweStartsAt = 5;  //yekatit
    }
    var goodFridayMonth = neneweStartsAt + 2;
    var esterMonth = neneweStartsAt + 2;

    siklet = mebajaHammer + 7;
    tinsae = mebajaHammer + 9;
    if (siklet > 30) {
        siklet = siklet - 30;
        goodFridayMonth = goodFridayMonth + 1;
    }
    if (tinsae > 30) {
        tinsae = tinsae - 30;
        esterMonth = esterMonth + 1;
    }
    var result = siklet + "/" + goodFridayMonth + "/" + tinsae + "/" + esterMonth;
    //document.getElementById('DateDisplayText').value = result;
    return result;
}
//*****************************************************************************
/*THIS FUNCTION USE TO CHANGE THE BUTTON STATUS WHICH DISPLAYS THE CALENDAR, FOR
EXAMPLE IF THE CALENDAR IS DISPLAYED WHEN USER CLICK ON THE BUTTON, THEN THE NEXT
CLICK EVENT ON THE SAME BUTTON SHOULD HIDE THE BUTTON AND RESPECTIVELY*/
/*------------------------------------------------------------------------------*/
function changeButtonStatus() {
    //alert("button");
    if (document.getElementById("div1").style.display == "none") {
        document.getElementById("div1").style.display = "block";
    }
    else {
        ice_hide();
    }
}
//----------------  --------------- Lunar Calculation ------------------  ----------------//
//*****************************************************************************
/*THE FOLOWING FUNCTION IS USED TO GET 3 HOLYDAYS THAT FOUND ON MUSLIM CALENDARS TO
HIGHLIGHT THEM, MEWLID, EDALFETIR AND EDALADHA. SINCE MOST MUSLIM CALENDARS WORKS
BASED ON LUNAR(CALLED HIJIRA) WHICH IS 354 DAYS APPROXIMATELY, WE NEED THIS FUNCTION TO GET THE
EXACT HOLYDAYS APPEARANCE IN SOLAR BASED CALENDARS*/
/*------------------------------------------------------------------------------*/
function lunarHolyDays(signature) {
    var selectedMonth = parseInt(document.getElementById('month_list').value);
    var selectedYear = parseInt(document.getElementById('year_list').value);
    var daysInAMonth, dayCount = 0, puagmeValue, holyDay = "", lunarLeap = 0;

    var dayDiff, initialYear;
    if (signature == "M") {
        initialYear = 1988;
        dayDiff = 321;
    }
    else if (signature == "F") {
        initialYear = 1989;
        dayDiff = 151;
    }
    else {
        initialYear = 1991;
        dayDiff = 199;
    }
    for (var year = initialYear; year <= 2050; year++) {
        puagmeValue = parseInt(isLeapYear(year)) + 5;
        for (var month = 0; month <= 12; month++) {
            if (month == 12)
                daysInAMonth = puagmeValue;
            else
                daysInAMonth = 30;

            for (day = 1; day <= daysInAMonth; day++) {
                dayCount++;

                if ((dayCount - dayDiff) % 354 == 0) {
                    if (Math.floor(lunarLeap + 0.37) > lunarLeap)
                        day++;
                    lunarLeap = lunarLeap + 0.37;
                    //holyDay = holyDay + "***" + day + "/" + month + "/" + year;
                    if (year == selectedYear) {
                        //alert("yes" + " month = " + month + " year = " + year + " day = " + day);
                        holyDay = day + "/" + (month + 1) + "/" + year;
                    }
                }
            }
        }
    }
    //document.getElementById("DateDisplayText").value = holyDay;
    return holyDay;
}


function constructCalendar()
{
    
}
 $(document).ready(function() {
function displayCalendar()
{
     getId('date');
    showCal();
    //var date= $(this).closest("tr").find("td").find("#date"); 
   // alert(date.val());
    getPosition('date');

   
}
 });