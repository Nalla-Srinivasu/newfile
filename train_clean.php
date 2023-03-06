<?php

set_time_limit(3600);
$con = mysqli_connect("localhost","root","","train");
if(mysqli_connect_error()){
	echo "Database Error:".mysqli_connect_error();
	exit();
}

if( 1==2 	){
mysqli_query($con, "truncate table stations");
mysqli_query($con, "truncate table trains");

$start = time();

$stations = [];
$res = mysqli_query($con, "select distinct station_code, station_name from train_data");
while( $row = mysqli_fetch_assoc($res) ){
	$stations[ $row['station_code'] ] = $row['station_name']; 
}

echo "<div>Stations: " . ( time()-$start ) . " seconds</div>";

foreach( $stations as $station_code=>$station_name ){
	$q ="insert into stations set 
		code = '" . $station_code . "',
		name = '" . mysqli_escape_string($con,$station_name) . "' ";
		echo "<div>" . $q . "</div>"; 
	mysqli_query($con, $q);
	if( mysqli_error($con) ){
		echo "<div>Error inserting stations: " . mysqli_error($con) . "</div>";exit;
	}
	$station_id = mysqli_insert_id($con);
	$q = "update train_data 
		set station_id = " . $station_id . "
		where station_code = '" . $station_code . "' ";
		echo "<div>" . $q . "</div>"; 
	mysqli_query( $con, $q);
	if( mysqli_error($con) ){
		echo "<div>Error updating stations: " . mysqli_error($con) . "</div>";exit;
	}
	$q = "update train_data 
		set source_station_id = " . $station_id . "
		where source_station = '" . $station_code . "' ";
		echo "<div>" . $q . "</div>"; 
	mysqli_query( $con, $q);
	if( mysqli_error($con) ){
		echo "<div>Error updating source stations: " . mysqli_error($con) . "</div>";exit;
	}
	echo "<div>Affected Rows: " .mysqli_affected_rows($con) . "</div>";
	$q = "update train_data 
		set dest_station_id = " . $station_id . "
		where destination_station = '" . $station_code . "' ";
		echo "<div>" . $q . "</div>"; 
	mysqli_query( $con, $q);
	if( mysqli_error($con) ){
		echo "<div>Error updating dest_stations: " . mysqli_error($con) . "</div>";exit;
	}
	echo "<div>Affected Rows: " .mysqli_affected_rows($con) . "</div>";
}

echo "<div>updating station ids: " . ( time()-$start ) . " seconds</div>";

$trains = [];
$res = mysqli_query($con, "select distinct train_no, train_name from train_data");
while( $row = mysqli_fetch_assoc($res) ){
	$trains[ $row['train_no'] ] = $row['train_name']; 
}

echo "<div>trains: " . ( time()-$start ) . " seconds</div>";

foreach( $trains as $train_code=>$train_name ){
	$q = "insert into trains set 
		code = '" . $train_code . "',
		name = '" . mysqli_escape_string($con,$train_name) . "' ";
	echo "<div>" . $q . "</div>"; 
	mysqli_query($con, $q);
	if( mysqli_error($con) ){
		echo "<div>Error inserting trains: " . mysqli_error($con) . "</div>";exit;
	}

	$train_id = mysqli_insert_id($con);
	$q = "update train_data 
		set train_id = " . $train_id . "
		where train_no = '" . $train_code . "' ";
	echo "<div>" . $q . "</div>"; 
	mysqli_query( $con, $q);
	if( mysqli_error($con) ){
		echo "<div>Error updating trains: " . mysqli_error($con) . "</div>";exit;
	}
	echo "<div>Affected Rows: " .mysqli_affected_rows($con) . "</div>";
}

echo "<div>updating trains: " . ( time()-$start ) . " seconds</div>";


}

if( 1==2 ){

	$query = "select distinct train_id, source_station_id, dest_station_id from train_data " ;
	echo "<div>". $query . "</div>";
	$res = mysqli_query($con, $query );
	echo mysqli_error($con);
	while( $row = mysqli_fetch_assoc($res) ){


		$query ="update trains set source_station_id = '" . $row['source_station_id'] . "',
		dest_station_id = '" . $row['dest_station_id'] . "' 
		where id = " . $row['train_id'];
		echo "<div>". $query . "</div>";
		mysqli_query($con, $query);
		echo mysqli_error($con);


	}


}