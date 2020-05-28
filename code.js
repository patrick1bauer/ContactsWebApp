var urlBase = 'http://covidcontacts.net/LAMP_API';
var extension = 'php';


var userId = 0;
var firstName = "";
var lastName = "";

function login()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	var login = document.getElementById("username").value;
	var password = document.getElementById("password").value;
//	var hash = SHA256(password);
	
	document.getElementById("loginResult").innerHTML = "";

//	var jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '"}';
	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Login.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);
		
		var jsonObject = JSON.parse( xhr.responseText );
		
		userId = jsonObject.id;
		
		if( userId < 1 )
		{
			document.getElementById("loginResult").innerHTML = "Username/Password is incorrect";
			return;
		}
		
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;

		saveCookie();

		window.location.href = "contacts.html";
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

function saveCookie()
{
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	var data = document.cookie;
	var splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function addUser()
{
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("lastName").value;
	var login = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var confirmPassword = document.getElementById("confirmPassword").value;
	var hash = SHA256(password);
	
	document.getElementById("registerResult").innerHTML = "";

	var jsonPayload = '{"FirstName" : "' + firstName + '", "LastName" : "' + lastName + '", "Login" : "' + login + '", "Password" : "' + hash + '"}';
	var url = urlBase + '/AddUser.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{	
		if (!firstName)
		{
			document.getElementById("registerResult").innerHTML = "Please enter the first name.";
			return;
		}

		if (!lastName)
		{
			document.getElementById("registerResult").innerHTML = "Please enter the last name.";
			return;
		}

		if (!login)
		{
			document.getElementById("registerResult").innerHTML = "Please enter the user name.";
			return;
		}

		if (!password)
		{
			document.getElementById("registerResult").innerHTML = "Please enter the password.";
			return;
		}

		if (!confirmPassword)
		{
			document.getElementById("registerResult").innerHTML = "Please confirm your password.";
			return;
		}

		if (password != confirmPassword)
		{
			document.getElementById("registerResult").innerHTML = "Password doesn't match.";
			return;
		}
		
		xhr.send(jsonPayload);

		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("registerResult").innerHTML = this.responseText;
			}
		};
	}
	catch(err)
	{
		document.getElementById("registerResult").innerHTML = err.message;
	}
}