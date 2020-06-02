<?php

	$inData = getRequestInfo();

	$search = $inData["search"];
	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("localhost", "cop4331u_contactsuser", "cop4331group16", "cop4331u_contacts");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{

		$sql = "select FirstName,LastName,PhoneNumber,Email,ID,DateCreated from Contact where (FirstName like '%" . $search . "%' or LastName like '%" . $search . "%') and UserID=" . $inData["UserID"];
		//$sql = "select FirstName,LastName,PhoneNumber,Email,ID from Contact where (FirstName like '%" . $search . "%' or LastName like '%" . $search . "%') and UserID=" . $inData["UserID"];

		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
				}
				$searchCount++;
				$searchResults .= '"' . $row["FirstName"] . ' ' . $row["LastName"] . '"';
				//$searchResults .= '"' . $row["FirstName"] . ' ' . $row["LastName"] . ' ' .  $row["PhoneNumber"] . ' ' . $row["Email"] . ' ' . $row["DateCreated"] . ' ' . $row["ID"] . '"';
			}
		}
		else
		{
			returnWithError( "No Records Found" );
		}
		$conn->close();
	}

	returnWithInfo( $searchResults );

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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
