<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 06/09/15
 * Time: 13:11
 */
include 'getAccidentController.php';
$accResult = getAllAccident();
echo "
			 <script>
                var lineDataArray = $accResult;
                var dowValue = [1,0,0,0,0,0,0];
                  // var dowValue = [0,0,0,0,0,0,0];
                   //to see the actual number of accidents happend in days of the week
                   console.log(lineDataArray);
                   for (var i = 0; i < lineDataArray.length; i++) {
                       switch (lineDataArray[i].DAY_OF_WEEK) {
                           case \"Sunday\":
                               var temp = dowValue[i];
                               dowValue[6] = temp + 1;
                               break;
                           case \"Monday\":
                               dowValue[0] += 1;
                               break;
                           case \"Tuesday\":
                               dowValue[1] += 1;
                               break;
                           case \"Wednesday\":
                               dowValue[2] += 1;
                               break;
                           case \"Thursday\":
                               dowValue[3] += 1;
                               break;
                           case \"Friday\":
                               dowValue[4] += 1;
                               break;
                           case \"Saturday\":
                               dowValue[5] += 1;
                               break;
                           default:
                               break;
                       }
                   }

                //var dowValue = [5,7,6,9,10,20,56];
				var graphData = {
					labels : [\"MON\",\"TUE\",\"WED\",\"THU\",\"FRI\",\"SAT\",\"SUN\"],
					datasets : [
							{
							//for bar:fillColor : \"#48A497\",
							//for bar:strokeColor : \"#48A4D1\",
							fillColor: \"rgba(220,220,220,0.2)\",
              				strokeColor: \"rgba(220,220,220,1)\",
              				pointColor: \"rgba(220,220,220,1)\",
              				pointStrokeColor:\"#fff\",
							data : dowValue
							}
							]
						}
						var lineGraph = document.getElementById(\"line\").getContext(\"2d\");
						var lineChart = new Chart(lineGraph).Line(graphData, {scaleShowGridLines:false});

				setInterval(function(){
				  // Get a random index point
				  var indexToUpdate = Math.round(Math.random() * 7);

				  // Update one of the points in the second dataset
				  lineChart.datasets[0].points[indexToUpdate].value = Math.random() * 10;

				  lineChart.update();
				}, 5000);

			 </script>
		";