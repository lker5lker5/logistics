<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="../js/Chart.min.js"></script>
    <script src="../js/homepageJS.js"></script>
    <link href="../css/layout.css" rel="stylesheet" type="text/css" media="all">
</head>
<body onLoad="onloadFunctions()">

    <div id="search" class="firstLayer">
        <div class="search">
            <strong style="font-size:14px;color:#fff;font-family:sans-serif;">Start:</strong>
            <input id="start" name="startPoint" class="controls" type="text" placeholder="Start Location" />
            <strong style="font-size:14px;color:#fff;font-family:sans-serif;">End:</strong>
            <input id="end" name="endPoint" class="controls" type="text" placeholder="End Location" />
            <strong style="font-size:14px;color:#fff;font-family:sans-serif;">Mode of Travel: </strong>
            <select id="mode" class="selectpicker show-menu-arrow form-control">
                <option value="DRIVING">Driving</option>
                <option value="WALKING">Walking</option>
                <option value="BICYCLING">Bicycling</option>
            </select>
            <input id="map_search" type="submit" class="button" value="Search" onClick="searchClickEvents()">
            <br>
<!--            <form id="searchRadio">-->
            <input type="radio" name="searchOption" id="shortest" checked value="shortest">Shortest</input>
            <input type="radio" name="searchOption" id="quickest" value="quickest">Quickest</input>
            <input type="radio" name="searchOption" id="safest" value="safest">Safest</input>
<!--            </form>-->
        </div>
        <div id="panel" class="panel"></div>
    </div>

    <div id="map" class="map">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&language=en-AU"></script>
        <script type="text/javascript" src="../js/googleMap.js"></script>
    </div>
    <div id="loading">
        <img src="http://bradsknutson.com/wp-content/uploads/2013/04/page-loader.gif" height="30"
             width="30" style="margin-top:18%;margin-left:48%">
    </div>

    <div id="table" class="table_layer" style="display: none;">
        <table id="pricetable">
            <thead>
            <tr>
                <th class="side">&nbsp;</th>
                <th id="r1_header" class="choicea">Route 1</th>
                <th id="r2_header" class="choiceb">Route 2</th>
                <th id="r3_header" class="choicec">Route 3</th>
            </tr>
            </thead>
            <tfoot>
                <tr>
                    <td class="side">&nbsp;</td>
                    <td id="r1_yes" class="choicea"><a href="#" onclick="activateThisColumn('choicea');return false;"><img src="i/check.png" alt="yes" /></a></td>
                    <td id="r2_yes" class="choiceb"><a href="#" onclick="activateThisColumn('choiceb');return false;"><img src="i/check.png" alt="yes" /></a></td>
                    <td id="r3_yes" class="choicec"><a href="#" onclick="activateThisColumn('choicec');return false;"><img src="i/check.png" alt="yes" /></a></td>
                </tr>
            </tfoot>
            <tbody>
            <tr>
                <td class="side">Number of accidents</td>
                <td id="r1_acc" class="choicea">
                    <p style="color:#31b0d5" id="accident_no1">Not Specified</p>
                </td>
                <td id="r2_acc" class="choiceb">
                    <p style="color:#31b0d5" id="accident_no2">Not Specified</p>
                </td>
                <td id="r3_acc" class="choicec">
                    <p style="color:#31b0d5" id="accident_no3">Not Specified</p>
                </td>
            </tr>
            <tr>
                <td class="side">Traffic Volume</td>
                <td id="r1_tff" class="choicea">
                    <p style="color: #d58512;" id="traffic_volume1">Not Specified</p>
                </td>
                <td id="r2_tff" class="choiceb">
                    <p style="color: #d58512;" id="traffic_volume2">Not Specified</p>
                </td>
                <td id="r3_tff" class="choicec">
                    <p style="color: #d58512;" id="traffic_volume3">Not Specified</p>
                </td>
            </tr>
            <tr>
                <td class="side">Weather influence</td>
                <td id="r1_wth" class="choicea">
                    <p style="color:darkblue" class="weatherInfo"></p>
                </td>
                <td id="r2_wth" class="choiceb">
                    <p style="color:darkblue" class="weatherInfo"></p>
                </td>
                <td id="r3_wth" class="choicec">
                    <p style="color:darkblue" class="weatherInfo"></p>
                </td>
            </tr>
            <tr>
                <td class="side">Safety Ratio</td>
                <td id="r1_safety" class="choicea">
                    <p id="safety1" style="color:darkblue"></p>
                </td>
                <td id="r2_safety" class="choiceb">
                    <p id="safety2" style="color:darkblue" ></p>
                </td>
                <td id="r3_safety" class="choicec">
                    <p id="safety3" style="color:darkblue"></p>
                </td>
            </tr>
            </tbody>
        </table>
        <p style="background-color: #c7c7c7">*These data are based on previous 5 years' statistics from VicRoads</p>
    </div>

    <div id="graph" class="graph" style="display: none">
        <!--Line chart canvas element-->
        <div style="float: left; width: 50%; height: 400px">
            <canvas id="line" style="width:100%;height: 100%;"></canvas>
        </div>

        <!-- bar chart canvas element -->
        <div style="float: left; width: 50%; height: 400px">
            <canvas id="bar" style="width:100%;height: 100%;"></canvas>
        </div>
    </div>
</body>
</html>