<?php Include("../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");
$callDate = date('Y-m-d');
error_reporting(0);
$page= $_GET['page_no'];

$custom_name= $_GET['custom_name'];
$m_person= $_GET['m_person'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-30');
}
$from_date = date('Y-m-d',strtotime($f_date));
$to_date = date('Y-m-d',strtotime($t_date));

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}


if($custom_name != ""){
    $custom_nameSql= " AND customer_name LIKE '%".$custom_name."%'";
}
else{
    $custom_nameSql ="";
}
if($m_person != ""){
    $meet_personSql= " AND meet_person LIKE '%".$m_person."%'";
}
else{
    $meet_personSql ="";
}
$added = $_COOKIE['user_id'];
if($added == ''){
    $addBy='Super Admin';
}
else{
    $addBy = $added;
}

$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin' || $_COOKIE['role'] == 'Admin'){
    $addedBy = "";
}
else{
    $addedBy = " AND added_by='$added_by'";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Service Planner Calender-View</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://erp.aecindia.net/includes/AEC.png">
    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../vendor/chartist/css/chartist.min.css">
    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../../vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="../../vendor/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="../../vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="../../vendor/pickadate/themes/default.date.css">
    <link href="../../vendor/summernote/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<style>
    html {
        font-family: sans-serif;
        font-size: 15px;
        line-height: 1.4;
        color: #444;
    }

    body {
        margin: 0;
        font-size: 1em;
    }

    .wrapper {
        margin: 15px auto;
        max-width: 1100px;
    }

    .container-calendar {
        background: #ffffff;
        padding: 15px;
        margin: 0 auto;
        overflow: auto;
    }

    .button-container-calendar button {
        cursor: pointer;
        display: inline-block;
        background: #00a2b7;
        color: #fff;
        border: 1px solid #0aa2b5;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .table-calendar {
        border-collapse: collapse;
        width: 100%;
    }

    .table-calendar td, .table-calendar th {
        padding: 5px;
        border: 1px solid #080000;
        text-align: center;
        vertical-align: top;
        width: 14.2857%; /* Fixed width for all cells to ensure uniformity */
    }

    .date-picker.selected {
        font-weight: bold;
        outline: 1px dashed #00BCD4;
    }

    .date-picker.selected span {
        border-bottom: 2px solid #fff;
    }

    /* Sunday */
    .date-picker:nth-child(1) {
        color: #080000;
    }

    /* Friday */
    .date-picker:nth-child(6) {
        color: #080000;
    }

    #monthAndYear {
        text-align: center;
        margin-top: 0;
    }

    .button-container-calendar {
        position: relative;
        margin-bottom: 1em;
        overflow: hidden;
        clear: both;
    }

    #previous {
        float: left;
    }

    #next {
        float: right;
    }

    .footer-container-calendar {
        margin-top: 1em;
        border-top: 1px solid #080000;
        padding: 10px 0;
    }

    .footer-container-calendar select {
        cursor: pointer;
        display: inline-block;
        background: #ffffff;
        color: #080000;
        border: 1px solid #080000;
        border-radius: 3px;
        padding: 5px 1em;
    }

    /* Add this to ensure the calendar cells have a consistent height */
    .table-calendar td {
        height: 100px; /* Adjust height as needed */
    }
</style>
<body>

<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>

<div id="main-wrapper">

    <?php
    $header_name ="Service-Planner Calender-View";
    Include ('../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Service Planner Calender-View</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Calender-View</h4>

                </div>
                <div class="card-body">
                    <div class="wrapper">

                        <div class="container-calendar">
                            <h3 id="monthAndYear"></h3>

                            <div class="button-container-calendar">
                                <button id="previous" onclick="previous()">&#8249;</button>
                                <button id="next" onclick="next()">&#8250;</button>
                            </div>

                            <table class="table-calendar" id="calendar" data-lang="en">
                                <thead id="thead-month"></thead>
                                <tbody id="calendar-body"></tbody>
                            </table>

                            <div class="footer-container-calendar">
                                <label for="month">Jump To: </label>
                                <select id="month" onchange="jump()">
                                    <option value=0>Jan</option>
                                    <option value=1>Feb</option>
                                    <option value=2>Mar</option>
                                    <option value=3>Apr</option>
                                    <option value=4>May</option>
                                    <option value=5>Jun</option>
                                    <option value=6>Jul</option>
                                    <option value=7>Aug</option>
                                    <option value=8>Sep</option>
                                    <option value=9>Oct</option>
                                    <option value=10>Nov</option>
                                    <option value=11>Dec</option>
                                </select>
                                <select id="year" onchange="jump()"></select>
                            </div>

                        </div>

                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>


    </div>

    <?php Include ('../../includes/footer.php') ?>
</div>

<script src="../../vendor/global/global.min.js"></script>
<script src="../../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../../js/custom.min.js"></script>
<script src="../../js/dlabnav-init.js"></script>
<script src="../../vendor/owl-carousel/owl.carousel.js"></script>
<script src="../../vendor/peity/jquery.peity.min.js"></script>
<!--<script src="../../vendor/apexchart/apexchart.js"></script>-->
<script src="../../js/dashboard/dashboard-1.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../../js/plugins-init/jquery.validate-init.js"></script>
<script src="../../vendor/moment/moment.min.js"></script>
<script src="../../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../vendor/summernote/js/summernote.min.js"></script>
<script src="../../js/plugins-init/summernote-init.js"></script>

<script>
    function generate_year_range(start, end) {
        var years = "";
        for (var year = start; year <= end; year++) {
            years += "<option value='" + year + "'>" + year + "</option>";
        }
        return years;
    }

    today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    selectYear = document.getElementById("year");
    selectMonth = document.getElementById("month");


    createYear = generate_year_range(1970, 2050);
    /** or
     * createYear = generate_year_range( 1970, currentYear );
     */

    document.getElementById("year").innerHTML = createYear;

    var calendar = document.getElementById("calendar");
    var lang = calendar.getAttribute('data-lang');

    var months = "";
    var days = "";

    var monthDefault = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var dayDefault = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    if (lang == "en") {
        months = monthDefault;
        days = dayDefault;
    } else if (lang == "id") {
        months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        days = ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    } else if (lang == "fr") {
        months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        days = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
    } else {
        months = monthDefault;
        days = dayDefault;
    }


    var $dataHead = "<tr>";
    for (dhead in days) {
        $dataHead += "<th data-days='" + days[dhead] + "'>" + days[dhead] + "</th>";
    }
    $dataHead += "</tr>";

    //alert($dataHead);
    document.getElementById("thead-month").innerHTML = $dataHead;


    monthAndYear = document.getElementById("monthAndYear");
    showCalendar(currentMonth, currentYear);



    function next() {
        currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
        currentMonth = (currentMonth + 1) % 12;
        showCalendar(currentMonth, currentYear);
    }

    function previous() {
        currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
        currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
        showCalendar(currentMonth, currentYear);
    }

    function jump() {
        currentYear = parseInt(selectYear.value);
        currentMonth = parseInt(selectMonth.value);
        showCalendar(currentMonth, currentYear);
    }

    // $(document).ready(function () {
    //     showCalendar(currentMonth, currentYear);
    // });

    function showCalendar(month, year) {
        var firstDay = (new Date(year, month)).getDay();
        var tbl = document.getElementById("calendar-body");
        tbl.innerHTML = "";
        monthAndYear.innerHTML = months[month] + " " + year;
        selectYear.value = year;
        selectMonth.value = month;
        var date = 1;
        var daysInMonth = 32 - new Date(year, month, 32).getDate();

        for (var i = 0; i < 6; i++) {
            var row = document.createElement("tr");
            for (var j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    cell = document.createElement("td");
                    cellText = document.createTextNode("");
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                } else if (date > daysInMonth) {
                    break;
                } else {
                    cell = document.createElement("td");
                    cell.setAttribute("data-date", date);
                    cell.setAttribute("data-month", month + 1);
                    cell.setAttribute("data-year", year);
                    cell.setAttribute("data-month_name", months[month]);
                    cell.className = "date-picker";
                    cell.innerHTML = "<span>" + date + "</span>";
                    row.appendChild(cell);
                    date++;
                }
            }
            tbl.appendChild(row);
        }

        fetchMarketingData(year, month + 1);
    }

    function fetchMarketingData(year, month) {
        var startDate = year + '-' + (month < 10 ? '0' + month : month) + '-01';
        var endDate = year + '-' + (month < 10 ? '0' + month : month) + '-' + daysInMonth(month - 1, year);

        $.ajax({
            url: 'calender_api.php',
            method: 'GET',
            data: { start_date: startDate, end_date: endDate },
            success: function (response) {
                var data = JSON.parse(response);
                for (var i = 0; i < data.length; i++) {
                    var nextDate = new Date(data[i].next_date);
                    var customerName = data[i].customer_name;
                    var cell = document.querySelector("[data-date='" + nextDate.getDate() + "'][data-month='" + (nextDate.getMonth() + 1) + "'][data-year='" + nextDate.getFullYear() + "']");
                    if (cell) {
                        // cell.innerHTML += "<br><span>" + customerName + "</span>";
                        cell.innerHTML += "<br><span style='display:block; word-break:break-word;color:#627bef;font-weight: bold'>" + customerName + "</span>";

                    }
                }
            }
        });
    }

    function daysInMonth(iMonth, iYear) {
        return 32 - new Date(iYear, iMonth, 32).getDate();
    }
</script>

</body>
</html>
