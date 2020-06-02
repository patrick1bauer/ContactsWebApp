<?php
	$inData = getRequestInfo();

	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Login = $inData["Login"];
	$Password = $inData["Password"];

	$conn = new mysqli("localhost", "cop4331u_contactsuser", "cop4331group16", "cop4331u_contacts");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}

	else
	{
		$sql = "select Login from User where Login='" . $Login . "'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			die("Login already exists");
		}

		$sql = "insert into User (FirstName,LastName,Login,Password) VALUES ('" . $FirstName . "','" . $LastName . "','" . $Login . "','" . $Password . "')";
		//$sql = "insert into User (FirstName,LastName,Login,Password) VALUES (" . $FirstName . ",'" . $LastName . ",'" . $Login . ",'" . $Password . "')";

		if( $result = $conn->query($sql) != TRUE )
		{
			returnWithError( $conn->error );
		}
		$conn->close();
	}

	returnWithError("");

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
