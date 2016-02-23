<?php
	//include 'dataRetrieval.php';
	//========================================================
	function getSessionID()
	{
		$data = array("email" => "zhouhongji@live.cn", "password" => "zhouhongji");
		$data_string = json_encode($data);
	
		$ch = curl_init('https://dsp-acer-believe.cloud.dreamfactory.com/rest/user/session');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-DreamFactory-Application-Name: digisoft'
		));
	
		$result = curl_exec($ch);
		$response = json_decode($result);
		curl_close($ch);
		// var_dump($response->session_id);
		//echo $response->session_id;
		return $response->session_id;
	
	}
	
	//========================================================
	function jsonHourlyConstructor($record){
		$temp1 = str_replace(array('[',']'), '', $record);
		$dbString = str_replace(array('{','}'), '', $temp1);
	
		$myArr = explode(',', $dbString);
		$newArr = array();
		foreach($myArr as $element){
			$part = explode(':', $element);
			$record = '{"time":'.$part[0].','.'"volume"'.':'.$part[1].'}';
			array_push($newArr, $record);
		}
		/*foreach($newArr as $element){
			echo $element."\n";
		}*/
		$newString = '[';
		for($i = 0; $i < sizeof($newArr); $i++){
			if ($i == 0){
				$newString .= $newArr[0].',';
			}else{
				if($i != sizeof($newArr)-1){
					$newString .= $newArr[$i].',';
				}else{
					$newString .= $newArr[$i].']'; 
				}
			}	
		}
		return $newString;	
	}
	//=====================================================										
	//get Dreamfactory sessionID
		$sessionID = getSessionID();
	
			//$curl_hourly_data="https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/hourly_data?limit=1";
		$curl_hourly_data="https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/hourly_data?filter=HMGNS_ID%3D27581%20and%20PERIOD_TYPE%3D'SCHOOL%20TERM'&fields=DOW%2CTotalTraffic";
		
		//echo "session is $sessionID\n";
	
		$ch = curl_init($curl_hourly_data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-DreamFactory-Application-Name: digisoft",
			"X-DreamFactory-Session-Token: $sessionID"
		));
	
		$result = curl_exec($ch);										
		curl_close($ch);
		$response = json_decode($result);
		$record = json_encode($response->record);
//echo $record;
//$test = json_encode($response);
//var_dump($record);
//var_dump($test);
		//echo $record;
		//$objects = json_decode($record, true);
		//var_dump($objects[0]["TotalTraffic"]);
		//var_dump($record);



		echo "
			 <script>
			    var json  = $record;
				var chartjsData = [];
				var factor = Math.round((Math.random()*10) + 1);
				for (var i = 0; i < json.length; i++) {
					chartjsData.push(Math.ceil(json[i].TotalTraffic * factor/10000));
				}

				var barData = {
					labels : [\"MON\",\"TUE\",\"WED\",\"THU\",\"FRI\",\"SAT\",\"SUN\"],
					datasets : [
							{
							//for bar:fillColor : \"#48A497\",
							//for bar:strokeColor : \"#48A4D1\",
							fillColor: \"rgba(220,220,220,0.2)\",
              				strokeColor: \"rgba(220,220,220,1)\",
              				pointColor: \"rgba(220,220,220,1)\",
              				pointStrokeColor:\"#fff\",
							data : chartjsData
							}
							]
						}
						var income = document.getElementById(\"line\").getContext(\"2d\");
						var lineChart = new Chart(income).Line(barData, {scaleShowGridLines:false});

				setInterval(function(){
				  // Get a random index point
				  var indexToUpdate = Math.round(Math.random() * 7);

				  // Update one of the points in the second dataset
				  lineChart.datasets[0].points[indexToUpdate].value = Math.random() * 100;

				  lineChart.update();
				}, 5000);

			 </script>
		";

	?>