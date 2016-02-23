<?php	
		//get an hour array holding all hours
		function getHourArray(){
			$hourArray = array("am12"=>"12am","am1"=>"1am","am2"=>"2am","am3"=>"3am","am4"=>"4am","am5"=>"5am","am6"=>"6am","am7"=>"7am","am8"=>"8am","am9"=>"9am","am10"=>"10am","am11"=>"11am","pm1"=>"1pm","pm2"=>"2pm","pm3"=>"3pm","pm4"=>"4pm","pm5"=>"5pm","pm6"=>"6pm","pm7"=>"7pm","pm8"=>"8pm","pm9"=>"9pm","pm10"=>"10pm","pm11"=>"11pm","pm12"=>"12pm");	
			
			return $hourArray;
		}
		
		function getRoadObjectsArray(){
			$jsonStr = file_get_contents('resultData.json');
			//$jsonStr = getJSON();
			//var_dump($jsonStr);
			//an array contains all roads in one route
			$roadObjects = json_decode($jsonStr);
			return $roadObjects;
		}
			
		//get total traffic of a day in a specific road
		function getOneDayVolumeOfARoad($roadIndex, $dayIndex){
			$roadObjects = getRoadObjectsArray();
			//var_dump($roadObjects);
			//get one road object, e.g. the first road in a route
			$ObjJsonStr = json_encode($roadObjects[$roadIndex - 1]->dayVolumeArray);
			//parse the specific road object to retrieve data
			$objArray = json_decode($ObjJsonStr,true);
			$dowVolArray = array();
			for($i = 0; $i < count($objArray); $i++){
				$volume = $objArray[$i]['totalVolume'];
				$dowVolArray[$i+1] = $volume;
			}
			//get a specific day traffic volume
			return $dowVolArray[$dayIndex];
		}
		
		//get total traffic of a hour in a specific road
		/*function getOneHourVolumeOfARoad($roadIndex, $hourIndex){
					
			}*/
		
		//get an array holding daily total traffic volume of a route
		function getDailyTotalTrafficVolumeArray(){
			$roadObjects = getRoadObjectsArray();
			$roadNo = count($roadObjects);
			$totalTrafficVolume = array();
			for($day = 1; $day <= 7; $day++){
				$traffic_volume = 0;
				for($road = 0; $road < $roadNo; $road++){
					$traffic_volume += getOneDayVolumeOfARoad($road, $day);
				}
				$totalTrafficVolume[$day] = $traffic_volume;
				$traffic_volume = 0;
			}
			
			return $totalTrafficVolume;
		}
		
		//get an array holding hourly traffic volume of a route
		/*function getHourlyTotalTrafficVolumeArray(){
			
		}*/
		
		//get one road's geo data 
		function getRoadGeoData($roadIndex){
			$roadObjects = getRoadObjectsArray();
			$objJsonStr = json_encode($roadObjects[$roadIndex - 1]);
			//var_dump($ObjJsonStr);
			$objArray = json_decode($objJsonStr, true);
			//var_dump($objArray);
			$roadLat = $objArray['minpnt_lat'];
			$roadLng = $objArray['minpnt_lng'];
			//parse the specific road object to retrieve data
			/*$objArray = json_decode($ObjJsonStr,true);
			$dowVolArray = array();
			for($i = 0; $i < count($objArray); $i++){
				$volume = $objArray[$i]['totalVolume'];
				$dowVolArray[$i+1] = $volume;
			}
			//get a specific day traffic volume
			return $dowVolArray[$dayIndex];*/
			
			return $roadLat.",".$roadLng;
			//return $objArray;
		}
		
		//get latitude of a road
		function getRoadLat($roadIndex){
			$geoInfo = getRoadGeoData($roadIndex);
			$geo_arr = explode(",", $geoInfo);
			return floatval($geo_arr[0]);
		}
		
		//get longitude of a road
		function getRoadLng($roadIndex){
			$geoInfo = getRoadGeoData($roadIndex);
			$geo_arr = explode(",", $geoInfo);
			return floatval($geo_arr[1]);
		}
				
		function getArrayLength($roadIndex){
			return count(getRoadGeoData($roadIndex));
		}
		
		//get an array holds geo info of each road in a specific route 	
		function getRoadsGeoArray(){
			$roadsGeoInfoArray = array();
			$roadObjects = getRoadObjectsArray();
			$roadNo = count($roadObjects);
			for($i = 1; $i <= $roadNo; $i++){
				$roadsGeoInfoArray[$i] = getRoadGeoData($i);				
			}	
			return $roadsGeoInfoArray;
		}
		
		//get an array holds accident details of each road in a specific route
		function getRoadsAccidentArray(){
			$roadAccidentsArray = array();
			$accidentJSON = file_get_contents('../testFiles/TestAccident.json');
			$accidentObjects = json_decode($accidentJSON);
			$accidentNo = count($accidentObjects);
			for($i = 0; $i < $accidentNo; $i++){
				$geoInfo = $accidentObjects[$i];
				$roadAccidentsArray[$i] = $geoInfo;		
			}	
			return $roadAccidentsArray;
		}
		//$result = getRoadsAccidentArray();
		//var_dump($result);
		//echo $result;



		//===========SEP 6,2015, parse weather==============
		//function parseWeather($weatherJSON){
		function parseWeather(){
			$weatherJSON = '{"coord":{"lon":144.96,"lat":-37.81},"weather":[{"id":503,"main":"Rain","description":"very heavy rain","icon":"10d"}],"base":"cmc stations","main":{"temp":286.71,"pressure":1019,"humidity":66,"temp_min":284.15,"temp_max":288.15},"wind":{"speed":6.7,"deg":10},"rain":{"1h":87.38},"clouds":{"all":90},"dt":1441502946,"sys":{"type":1,"id":8201,"message":0.005,"country":"AU","sunrise":1441485322,"sunset":1441526560},"id":2158177,"name":"Melbourne","cod":200}';
			$weatherObj = json_decode($weatherJSON);
			//var_dump($weatherObj.weather);
			//var_dump($weatherObj);
			$weatherDetailJSON = json_encode($weatherObj->weather);
			var_dump($weatherDetailJSON);
			$weatherType_a = json_decode($weatherDetailJSON, ture);
			//var_dump($weatherType_a);
			$weatherType = $weatherType_a[0]["main"];
			//var_dump($weatherType);
			return $weatherType;
		}

		//$result = parseWeather();
		//var_dump($result);
		
?>
