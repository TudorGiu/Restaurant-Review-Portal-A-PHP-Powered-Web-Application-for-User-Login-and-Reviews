
<!DOCTYPE html>

<?php

	session_start();
	
	if(isset($_POST["logoutButton"]))
	{
		unset($_SESSION);
		session_destroy();
		echo "<script>window.location.href='http://localhost/home.php'</script>";
	}
	
	if(isset($_SESSION['sessionusername']))
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
	?>
        
        <h1 style="text-align:center; font-size:300%; margin: 12.3px;"><b>Restaurants</b></h1>
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
	
	
	<form class="gridUnderMenu" action="" method="post">
		<div>
			<h1 class="sortbytext">Filter column:</h1>
			<select class="selecttag" name="selecttagvalueFilter" id="selecttagvalueFilter">
				<option value="Name">Name</option>
				<option value="Type">Type</option>
			</select>
		
			<h1 class="sortbytext">Sort by:</h1>
			<select class="selecttag" name="selecttagvalueSort" id="selecttagvalueSort">
				<option value="Id">Id</option>
				<option value="Name">Name</option>
				<option value="Type">Type</option>
			</select>
		</div>
		
		<div>
			<h1 class="sortbytext">Filter after:</h1>
			<input class="selecttag" type="text" id="wordsToFilterWith" name="wordsToFilterWith">
			<br>
			<input class="submit" type="submit" name="submit" value="APPLY" />
		</div>
	</form>
	
	<br> <br>
		<table>
			<tr style="background-color: #131921; color: white; text-align: left">
				<th style="padding-left: 5px">Id</th>
				<th style="padding-left: 10px">Name</th>
				<th style="padding-left: 5px">Type</th>
			</tr>
		<?php
			$conn = new PDO("pgsql:host=localhost;dbname=restaurant_reviews", "postgres", "2828");
			$sql = "SELECT restaurant_id,restaurant_name,RT.type_name FROM \"Restaurants_being_reviewed\"
					JOIN \"Restaurant_Types\" RT on RT.type_code = \"Restaurants_being_reviewed\".type_code";
			
			if(isset($_POST['submit']))
			{
				echo "<script>document.getElementById('wordsToFilterWith').value = '" . $_POST['wordsToFilterWith'] . "'</script>";
				echo '<script>document.getElementById("selecttagvalueFilter").value = "'. $_POST['selecttagvalueFilter'] . '";</script>';
				if($_POST['wordsToFilterWith'] != '')
				{
					switch($_POST['selecttagvalueFilter'])
					{
						case 'Name':
							$sql = $sql . " WHERE restaurant_name LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
							break;
						case 'Type':
							$sql = $sql . " WHERE type_name LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
							break;
						default:
							break;
					}
				}
				
				echo '<script>document.getElementById("selecttagvalueSort").value = "'. $_POST['selecttagvalueSort'] . '";</script>';
				switch($_POST['selecttagvalueSort'])
				{
					case 'Id':
						$sql = $sql . " ORDER BY restaurant_id";
						break;
					case 'Name':
						$sql = $sql . " ORDER BY restaurant_name";
						break;
					case 'Type':
						$sql = $sql . " ORDER BY type_name";
						break;
					default:
						break;
				}
			}
			else{
				$sql = $sql . " ORDER BY restaurant_id";
			}
			
			
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			
			if(count($result) > 0)
			{
				foreach($result as $row){
					echo "<tr><td style=\"background-color: #edb770\">". $row["restaurant_id"] ."</td><td>".$row["restaurant_name"]."</td><td>".$row["type_name"]."</td></tr>";
				}
				echo "</table>";
			}
		?>
		</table>
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
	
	.selecttag{
		font-size:20px;
		width: 200px; 
	  height: 45px; 
	  background-color: white;
	  border: 3px solid #ddd;
	  color: #333; 
	  padding: 5px; 
	}
	
	.sortbytext{
		margin: 5px 5px 5px 0px; 
		font-size: 30px;
	}
	
	.submit{
		margin-left: 50px;
		margin-top: 45px;
		width: 100px;
		height: 40px;
		background-color: orange;
	}
	
	.submit:hover{
		background-color: orangered;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
		cursor: pointer;
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
	
	table {
		border: 3px solid #ddd;
		border-collapse: collapse; 
		width: 100%;
		font-size: 20px;
	}

	tr {
	  background-color: antiquewhite; 
	}

	td {
	  border: 3px solid #ddd;
	  padding: 8px;
	}
	
	.gridUnderMenu{
		display: grid;
		grid-template-columns: 33% 33% auto;
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
	
	
</script>