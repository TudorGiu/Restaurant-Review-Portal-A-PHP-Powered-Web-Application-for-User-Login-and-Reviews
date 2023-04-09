<!DOCTYPE html>

<?php

	session_start();
	
	$message = '';
	$login_successful = false;
	
	$conn = new PDO("pgsql:host=localhost;dbname=restaurant_reviews", "postgres", "2828");
	

	if(isset($_POST["logInButton"]))
	{
		$last_name = $_POST["last_name"];
		$first_name = $_POST["first_name"];
		$password1 = $_POST["password"];
		
		$sql = "SELECT * FROM \"Users\" where last_name='$last_name' and first_name='$first_name' and password ='$password1'";
		$result  = $conn->prepare($sql);
		$result->execute();
		
		$user = $result->fetchAll();
		
		if($user)
		{
			if(!empty($_POST["checkbox"]))
			{
				setcookie("last_name", $last_name, time()+ (1800));
				//setcookie("first_name", $first_name, time()+ (1800));
				setcookie("password1", $password1, time()+ (1800));
			}
			else
			{
				if(isset($_COOKIE["last_name"]))
				{
					setcookie("last_name", '');
				}
				if(isset($_COOKIE["first_name"]))
				{
					setcookie("first_name", '');
				}
				if(isset($_COOKIE["password1"]))
				{
					setcookie("password1", '');
				}
			}
			
			$login_successful = true;
		}
		else
		{	
			if(isset($_COOKIE["last_name"]))
			{
				setcookie("last_name", "");
			}
			if(isset($_COOKIE["first_name"]))
			{
				setcookie("first_name", "");
			}
			if(isset($_COOKIE["password1"]))
			{
				setcookie("password1", "");
			}
			$message = "Invalid login";			
		}
	}
	else if(isset($_POST["signInButton"]))
		{
			$last_name = $_POST["last_name"];
			$first_name = $_POST["first_name"];
			$password1 = $_POST["password"];
			
			if(!empty($last_name) && !empty($first_name) && !empty($password1))
			{
				$sql = "INSERT INTO \"Users\" (last_name, first_name, password) VALUES ('$last_name', '$first_name', '$password1');";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				
				if(!empty($_POST["checkbox"]))
				{
					setcookie("last_name", $last_name, time()+ (1800));
					setcookie("first_name", $first_name, time()+ (1800));
					setcookie("password1", $password1, time()+ (1800));
				}
				else
				{
					if(isset($_COOKIE["last_name"]))
					{
						setcookie("last_name", "");
					}
					if(isset($_COOKIE["first_name"]))
					{
						setcookie("first_name", "");
					}
					if(isset($_COOKIE["password1"]))
					{
						setcookie("password1", "");
					}
				}
				
				$login_successful = true;
			}
			else	
			{	
				echo '<script>document.querySelector(".popup").classList.add("active");</script>';
				$message = "One of the fields is empty";			
			}				
		}
	
	
	if($login_successful == true)
	{
		$_SESSION['last_name'] = $last_name;
		$_SESSION['first_name'] = $first_name;
	}
	
	if(isset($_POST["logoutButton"]))
	{
		unset($_SESSION);
		
		session_destroy();
	}
	
	if(isset($_SESSION['last_name']))
	{
		//echo "You are logged in";
	}
	else
	{
		//echo "You are not logged in.";
	}	
?>

<html style="padding-block: 0"lang="en">
	
<head>
    <link rel="icon" href="\assets\icon.png"/>
    <title>
        ILikeFood
    </title>
</head>
	
<body class="bodyOfThePage">

    <div class="header">
	<?php
		if(isset($_SESSION['last_name']))
		{
			echo '
				<div style="display: grid; float: right ">
					<div style="font-size: 40px; float:right; grid-row: 1; grid-column: 1;">Hello ' . $_SESSION["first_name"] . '!</div>
					<form style="grid-row: 2; grid-column: 1;" action="" method="post">
						<button name="logoutButton" class="loginbutton">
							<img src="\assets\logedin_icon.png" alt="login_logo" width="30" height="30" />
								Log out
						</button>
					</form>
				</div>';
		}
		else
		{
			echo '<button id="show-login" class="loginbutton" type="button">
            <img src="\assets\login_icon.png" alt="login_logo" width="30" height="30" />
				Log in
        </button>';
		}
	
	?>
        
        <img src="\assets\icon.png" alt="logo" width="100" height="100" class="logostyle" />
        <h1>I Like Food</h1>
    </div>

    <div id="menubar">
            <rectangle id="rectangleMenuBar" class="menubar">
                <button class="menubutton" id="firstMenuButton" onclick="window.location.href='http://localhost/home.php'"><b>Home</b></button>
                <button class="menubutton" id="secondMenuButton" onclick="window.location.href='http://localhost/restaurants_page.php'"><b>Restaurants</b></button>
                <button class="menubutton" id="thirdMenubutton" onclick="window.location.href='http://localhost/reviews_page.php'"><b>Reviews</b></button>
            </rectangle>
    </div>

	<div class="columnOfColumns">
		<div class="columnleft">
			<h1>Importance of reviews</h1>
			<p>
			<b>Reviews</b> can be an important factor for customers when deciding where to dine out. They provide valuable insights and opinions from people who have already tried the restaurant's food and service, and can help customers make informed decisions about where to eat.
			</p>
			<p>
			One of the main benefits of reviews is that they can provide a detailed overview of the restaurant's atmosphere and overall experience. Customers can read about the decor, ambiance, and even the noise level, which can all contribute to the overall enjoyment of the meal. Reviews can also give customers an idea of the restaurant's specialty dishes and the quality of their food, which can be especially helpful for those with dietary restrictions or preferences.
			</p>
			<p>
			In addition to helping customers make informed decisions, reviews can also be beneficial for the restaurant itself. Positive reviews can attract new customers and help build the restaurant's reputation, while negative reviews can provide valuable feedback for the restaurant to improve upon. This can be especially important in a competitive market where customers have many dining options to choose from.
			</p>
			<p>
			Furthermore, reviews can help restaurants stay accountable and ensure that they are consistently providing high-quality food and service. Customers who take the time to write a review are usually passionate about their experience, and their feedback can help restaurants identify areas for improvement and make necessary changes.
			</p>
			<p>
			Overall, reviews play a crucial role in the restaurant industry. They provide valuable information for customers and can help restaurants improve and stay competitive. Whether you are a customer looking for a new dining experience or a restaurant owner seeking to improve your business, reviews can be an invaluable resource.
			</p>
			<br>
		</div>

		<div class="columnright">
				<a href="https://stackify.com/how-to-host-php-on-windows-with-iis/" class="column_pictures" title="Best guide that helped me build this">
				<img src="\assets\stackify_logo.png" alt="Stackify_logo" width="70%" height="70%" style="display:block; margin-top: 40px">
				</a>
				
				<a href="https://carturesti.ro/carte/oda-shaormei-2025070333" class="column_pictures" title="Oda shaormei">
				<img src="\assets\oda_shaormei.png" alt="Stackify_logo" width="70%" height="70%" style="display:block; margin-top: 100px">
				</a>
		</div>
	</div>

    <footer class="footer">
        <p>contact: tudorgiu@gmail.com</p>
    </footer>

</body>

<div class="popup">

    <div class="close_btn">&times;</div>
    <div class="form" >
        <h2>Authentication</h2>
			<form id="myform" action="" method="post">
				<div class="form_element">
					<label for="last_name">Last name</label>
					<input type="text" id="last_name" name="last_name" placeholder="Enter last name" value="<?php if(isset($_COOKIE["last_name"])) {echo $_COOKIE["last_name"];}?>">
				</div>
				<div class="form_element">
					<label for="first_name">First name</label>
					<input type="text" id="first_name" name="first_name" placeholder="Enter first name" value="<?php if(isset($_COOKIE["first_name"])) {echo $_COOKIE["first_name"];}?>">
				</div>
				<div class="form_element">
					<label for="password">Passsword</label>
					<input type="password" id="password" name="password" placeholder="Enter password" value="<?php if(isset($_COOKIE["password1"])) {echo $_COOKIE["password1"];}?>">
				</div>
			</form>
            <div class="form_element">
                <input form="myform" type="checkbox"name= "checkbox" id="remember_me_checkbox" <?php if(isset($_COOKIE["last_name"])) { ?> checked <?php } ?> />
                <button class="remember_me" id="remember_me_text">Remember me</button>
            </div>
			<?php
				//if($message == "One of the fields is empty" || $message == "Invalid login")
				//	echo '<script>document.querySelector(".popup").classList.add("active");</script>';
				//else
				//	echo'<script>document.querySelector(".popup").classList.remove("active");</script>';
				
				
				if(isset($message))
				{
					echo "<p id=\"error_text\" style=\"background: none;
													border: none;
													color: red;
													padding: 0;
													margin: 0;
													width: 60%;
													text-align: left;\">$message</p>";
				}
				
			?>
            <div class ="form_element">
                <button form="myform" name="signInButton" >Sign in</button>
				<br><br>
				<button form="myform" name="logInButton" >Log in</button>
            </div>
    </div>
</div>

</html>

<style>
    * {
        box-sizing: border-box;
        font-family: sans-serif;
    }

    .footer {
        background-color: antiquewhite;
        padding: 10px;
        text-align: center;
    }
	
	.columnOfColumns{
		display: grid;	
		grid-template-columns: 80% auto;
	}
		
    .columnleft {
        position: relative;
        background-color: white;
        width: 100%;
        height: 100%;
        padding: 10px;
        float: left;
    }

    .columnright {
        position: relative;
        background-color: #131921;
        width: 100%;
        height: 100%;
        padding: 10px;
        float: left;
    }
	
	.column_pictures{
		display:flex;
		align-items: center; 
		justify-content:center;
	}

    .logostyle {
        display: block;
        border-radius: 50px;
        float: left;
        margin-left: 20px;
        margin-right: 20px;
        margin-bottom: 0px;
        position: relative;
    }

    .header {
        padding: 30px;
    }

    .bodyOfThePage {
        flex-direction: column;
		display: flex;
        height: auto;
        margin: 0;
    }

    .menubar {
        width: 100%;
        height: 12%;
    }

    .menubutton {
        background-color: orange;
        text-align: center;
        font-size: 20px;
        padding: 20px 50px;
		cursor: pointer;
    }

    .menubutton:hover {
        background-color: orangered;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
    }

    #firstMenuButton {
        position: relative;
        left: -10%;
    }

    #secondMenuButton {
        position:relative;
    }

    #thirdMenubutton {
        position: relative;
        right: -10%;
    }

    .loginbutton{
        border-radius: 30px;
        padding: 10px 40px;
        float: right;
        border: 0px;
        font-size: 40px;
        background-color:white;
		cursor: pointer;
    }

    .loginbutton:hover{
        background-color:antiquewhite;
    }

    rectangle {
        background-image: linear-gradient(to right, #666, #b1c0c4);
        background-color: #666;
        padding: 30px;
        text-align: center;
        font-size: 35px;
        color: white;
        float: left;
        border-style: solid;
        border-left: 0;
        border-right: 0;
        border-color: black;
    }

    .sticky {
        position: sticky;
        top: 0px;
        width: 100%;
        z-index: 3;
    }

    .popup {
        position: absolute;
        top: -150%;
        left: 50%;
        opacity: 0;
        transform: translate(-50%, -50%);
        width: 380px;
		height: 450px;
        padding: 20px 30px;
        background: antiquewhite;
        border-radius: 50px;
        border-color: black;
        transition: top 0ms ease-in-out 200ms, 
                    opacity 200ms ease-in-out 0ms, 
                    transform 20ms ease-in-out 0ms;
    }

    .popup.active {
        top: 50%;
        opacity: 1;
        transform: translate(-50%, -50%);
        transition: top 0ms ease-in-out 0ms,
                    opacity 200ms ease-in-out 200ms,
                    transform 20ms ease-in-out 0ms;
    }

    .popup .close_btn {
        position: absolute;
        top: 20px;
        right: 30px;
            width: 20px;
            height: 20px;
            background: #808080;
            text-align: center;
            line-height: 15px;
            border-radius: 15px;
            cursor: pointer;
    }

    .popup .form h2 {
        text-align: center;
        margin: 10px 0px 20px;
        font-size: 25px;
        color: #131921;
    }
    
    .popup .form .form_element{
        margin: 5px 0px;
    }

    .popup .form .form_element label{
        font-size: 14px;
    }

    .popup .form .form_element input[type="text"],
    .popup .form .form_element input[type="password"]{
        margin-top: 5px;
        display: block;
        width: 100%;
        padding: 10px;
        outline: none;
        border: 1px solid;
        border-radius: 5px;
    }
    .popup .form .form_element input[type = "checkbox"]{
        margin-right: 5px;
    }

    .popup .form .form_element button {
        width: 100%;
        height: 40px;
        border: none;
        outline: none;
        font-size: 16px;
        background: #131921;
        color: white;
        border-radius: 10px;
        cursor: pointer;
    }

    .popup .form .form_element button.remember_me {
        background: none;
        border: none;
        color: black;
        padding: 0;
        margin: 0;
        width: 60%;
        text-align: left;
    }

</style>

<script>
    window.onscroll = function () { myFunction() };

    var menubar = document.getElementById("menubar");
    var sticky = menubar.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            menubar.classList.add("sticky")
        } else {
            menubar.classList.remove("sticky");
        }
    }

    document.querySelector("#show-login").addEventListener("click", function () {
        document.querySelector(".popup").classList.add("active");
    });

    document.querySelector(".popup .close_btn").addEventListener("click", function () {
		
        document.querySelector(".popup").classList.remove("active");
		
		document.getElementById("error_text").innerHTML = '';
    });

    document.getElementById("remember_me_text").addEventListener("click", function handleClick() {
        if (document.getElementById("remember_me_checkbox").checked == true)
            document.getElementById("remember_me_checkbox").checked = false;
        else
            document.getElementById("remember_me_checkbox").checked = true;
    });

	

</script>