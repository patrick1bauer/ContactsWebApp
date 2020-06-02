<?php
	$inData = getRequestInfo();

	$ContactID = $inData["ContactID"];
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$PhoneNumber = $inData["PhoneNumber"];
	$Email = $inData["Email"];

	$conn = new mysqli("localhost", "cop4331u_contactsuser", "cop4331group16", "cop4331u_contacts");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}

	else
	{
		$sql = "update Contact set FirstName= '" . $FirstName . "', LastName= '" . $LastName . "', PhoneNumber= '" . $PhoneNumber . "', Email= '" . $Email . "' where ID= " . $ContactID;

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
