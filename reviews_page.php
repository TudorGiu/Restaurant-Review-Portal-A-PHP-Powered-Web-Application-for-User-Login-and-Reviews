
<!DOCTYPE html>

<?php

	session_start();
	
	if(isset($_POST["logoutButton"]))
	{
		unset($_SESSION);
		session_destroy();
		echo "<script>window.location.href='http://localhost/home.php'</script>";
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
        
        <h1 style="text-align:center; font-size:300%; margin: 12.3px;"><b>Reviews</b></h1>
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
		
		<div class="gridHelper">
			<form class="gridUnderMenu" action="" method="post">
				<div>
					<h1 class="sortbytext">Filter column:</h1>
					<select class="selecttag" name="selecttagvalueFilter" id="selecttagvalueFilter">
						<option value="Publication_name">Publication Name</option>
							<option value="Restaurant_name">Restaurant Name</option>
							<option value="last_name">Last Name</option>
							<option value="first_name">First Name</option>
							<option value="review_title">Review Title</option>
							<option value="review_details">Review Details</option>
					</select>
				
					<h1 class="sortbytext">Sort by:</h1>
					
					<div>
						<select class="selecttag" name="selecttagvalueSort" id="selecttagvalueSort">
							<option value="Id">Id</option>
							<option value="Publication_name">Publication Name</option>
							<option value="Restaurant_name">Restaurant Name</option>
							<option value="last_name">Last Name</option>
							<option value="first_name">First Name</option>
						</select>
					</div>
				</div>
				
				<div>
					<h1 class="sortbytext">Filter after:</h1>
					<input class="selecttag" type="text" id="wordsToFilterWith" name="wordsToFilterWith">
					<br>
					<input class="submit" type="submit" name="submit" value="APPLY" />
				</div>
			</form>
			
			<div>
				<?php
					if(isset($_SESSION['last_name']))
					{
						echo "
							<h1 style=\"margin: 5px 5px 5px 0px; font-size: 30px;\">Leave a review:</h1>
							<button class=\"submit\" style=\"margin-left: 0px; width:200px; height: 45px; \" onclick=\"window.location.href='http://localhost/leave_review_page.php'\">GO</b></button>
						";
					}
					else
					{	
						echo "<br><h1 style=\" margin: 40px 5px 5px 0px; font-size: 30px;\">You have to be logged in to leave a review</h1>";
					}
				?>
			</div>
		</div>
		
		<br> <br>
			<table>
				<tr style="background-color: #131921; color: white; text-align: left">
					<th style="padding-left: 5px">Id</th>
					<th style="padding-left: 10px">Publication Name</th>
					<th style="padding-left: 5px">Restaurant Name</th>
					<th style="padding-left: 5px">Last Name</th>
					<th style="padding-left: 5px">First Name</th>
					<th style="padding-left: 5px">Review title</th>
					<th style="padding-left: 5px">Review details</th>
				</tr>
			<?php
				$conn = new PDO("pgsql:host=localhost;dbname=restaurant_reviews", "postgres", "2828");
				$sql = "SELECT review_id, RP.publication_name, RBR.restaurant_name, R.last_name, R.first_name, review_title, review_details FROM \"Reviews\"
						JOIN \"Review_Publications\" RP on \"Reviews\".publication_code = RP.publication_code
						JOIN \"Restaurants_being_reviewed\" RBR on \"Reviews\".restaurant_id = RBR.restaurant_id
						JOIN \"Reviewers\" R on \"Reviews\".reviewer_id = R.reviewer_id";
				
				
				if(isset($_POST['submit']))
				{
					echo "<script>document.getElementById('wordsToFilterWith').value = '" . $_POST['wordsToFilterWith'] . "'</script>";
					echo '<script>document.getElementById("selecttagvalueFilter").value = "'. $_POST['selecttagvalueFilter'] . '";</script>';
					if($_POST['wordsToFilterWith'] != '')
					{
						switch($_POST['selecttagvalueFilter'])
						{
							case 'Publication_name':
								$sql = $sql . " WHERE publication_name LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
								break;
							case 'Restaurant_name':
								$sql = $sql . " WHERE restaurant_name LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
								break;
							case 'last_name':
								$sql = $sql . " WHERE last_name LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
								break;
							case 'first_name':
								$sql = $sql . " WHERE first_name LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
								break;
							case 'review_title':
								$sql = $sql . " WHERE review_title LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
								break;
							case 'review_details':
								$sql = $sql . " WHERE review_details LIKE '%" . $_POST['wordsToFilterWith'] . "%'";
								break;
							default:
								break;
						}
					}
					
					echo '<script>document.getElementById("selecttagvalueSort").value = "'. $_POST['selecttagvalueSort'] . '";</script>';
					switch($_POST['selecttagvalueSort']){
						case 'Id':
							$sql = $sql . " ORDER BY review_id";
							break;
						case 'Publication_name':
							$sql = $sql . " ORDER BY RP.publication_name";
							break;
						case 'Restaurant_name':
							$sql = $sql . " ORDER BY RBR.restaurant_name";
							break;
						case 'last_name':
							$sql = $sql . " ORDER BY R.last_name";
							break;
						case 'first_name':
							$sql = $sql . " ORDER BY R.first_name";
							break;
						default:
							break;
					}
				}
				else{
					$sql = $sql . " ORDER BY review_id";
				}
				
				
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				
				$result = $stmt->fetchAll();
				
				if(count($result) > 0)
				{
					foreach($result as $row){
						echo "<tr ><td style=\"background-color: #edb770\">". $row["review_id"] ."</td><td>".$row["publication_name"]."</td><td>"
						.$row["restaurant_name"]."</td><td>".$row["last_name"]."</td><td>".$row["first_name"]."</td>
						<td>".$row["review_title"]."</td><td>".$row["review_details"]."</td></tr>";
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

	.bodyOfThePage {
		flex-direction: column;
		display: flex;
        height: auto;
        margin: 0;
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
	
	.gridHelper{
		display: grid;
		grid-template-columns: 66% auto;
	}
	
	.gridUnderMenu{
		display: grid;
		grid-template-columns: 50% 50%;
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

    .menubar {
        width: 100%;
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