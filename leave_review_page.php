<!DOCTYPE html>

<?php

	function fill_Review_Feature_table($feature_array, $review_id){
		
		$conn = new PDO("pgsql:host=localhost;dbname=restaurant_reviews", "postgres", "2828");
		if($conn)
		{
			for ($i = 0; $i < count($feature_array); $i++) {
				if($feature_array[$i] != false)
				{
					$sql = "INSERT INTO \"Review_Features\" (review_id, feature_id) VALUES ($review_id, " . $feature_array[$i] .")";
					$result = $conn->prepare($sql);
					$result->execute();
				}
			}
		}
	}

	session_start();
	
	
	$invalid_form = false;
	
	if(isset($_POST["submit"]))
	{
	
		
		if(isset($_POST["gender"]))
		{
			$gender = $_POST["gender"];
		}
		else
		{
			$gender = null;
			$invalid_form = true;
		}
			
		$restaurant = $_POST["restaurant"];
		$type = $_POST["type"];
		$review_title = $_POST["review_title"];
		$review = $_POST["review"];
		
		if(isset($_POST["cleanliness"]))
		{
			$cleanliness = 1;
		}
		else
		{
			$cleanliness = false;
		}
		
		if(isset($_POST["serving"]))
		{
			$serving = 2;
		}
		else
		{
			$serving = false;
		}
		
		if(isset($_POST["food"]))
		{
			$food = 3;
		}
		else
		{
			$food = false;
		}
		
		if(isset($_POST["hospitality"]))
		{
			$hospitality = 4;
		}
		else
		{
			$hospitality = false;
		}
		
		if(isset($_POST["serving_speed"]))
		{
			$serving_speed = 5;
		}
		else
		{
			$serving_speed = false;
		}
		
		if(isset($_POST["prices"]))
		{
			$prices = 6;
		}
		else
		{
			$prices = false;
		}
		
		if(isset($_POST["vegan_options"]))
		{
			$vegan_options = 7;
		}
		else
		{
			$vegan_options = false;
		}
		
		if(isset($_POST["menu"]))
		{
			$menu = 8;
		}
		else
		{
			$menu = false;
		}
		
		if(isset($_POST["takeaway"]))
		{
			$takeaway = 10;
		}
		else
		{
			$takeaway = false;
		}
		
		if(isset($_POST["location"]))
		{
			$location = 9;
		}
		else
		{
			$location = false;
		}
		$array_of_features = [$cleanliness, $serving, $food, $hospitality, $serving_speed, $prices, $vegan_options, $menu, $takeaway, $location];
		
		if($restaurant && $review_title && $review && $gender)
		{
			$host = "localhost";
			$dbname = "restaurant_reviews";
			$username = "postgres";
			$password = "2828";
			$conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
			
			if($conn)
			{
				$sql = "SELECT restaurant_name FROM \"Restaurants_being_reviewed\" where restaurant_name = '$restaurant'";
				$result  = $conn->prepare($sql);
				$result->execute();
				
				// if restaurant in not already in database
				if($result->rowCount() == 0) // add restaurant to 'Restaurants_being_reviewed' table
				{
					// in $findcodeoftype we have the code of the restaurant type introduced in the selection tag
					$sqlquery = "SELECT type_code FROM \"Restaurant_Types\" WHERE type_name = '" . $type ."'";
					$res = $conn->prepare($sqlquery);
					$res->execute();
				
					$findcodeoftype = $res->fetch(); 
				
					$sqlquery = "INSERT INTO \"Restaurants_being_reviewed\" (type_code, restaurant_name) VALUES (" . $findcodeoftype["type_code"] . ",'". $restaurant ."')";
					$result = $conn->prepare($sqlquery);
					$result->execute();
					
					// new restaurant added
				}
				
				//if user is not already in 'Reviewers' table
				
				$sql = "SELECT first_name, last_name FROM \"Reviewers\" WHERE last_name = '" . $_SESSION['last_name'] . "' and first_name = '" . $_SESSION['first_name'] ."' and gender='". $gender . "'";
				$result  = $conn->prepare($sql);
				$result->execute();
				
				
				if($result->rowCount() == 0) // add the new user to the 'Reviewers' table
				{
					$current_date = date('Y-m-d');
					$sqlquery = "INSERT INTO \"Reviewers\" (last_name, first_name, gender,total_review_count, date_of_first_review, date_of_latest_review)
								VALUES ('" . $_SESSION['last_name'] . "', '" . $_SESSION['first_name'] ."', '". $gender . "', 1, '$current_date', '$current_date')";
					$result = $conn->prepare($sqlquery);
					$result->execute();
				}
				
				// insert the review
				
				$sql_to_get_rest_id = "SELECT restaurant_id FROM \"Restaurants_being_reviewed\" WHERE restaurant_name = '".$restaurant."'";
				$result  = $conn->prepare($sql_to_get_rest_id);
				$result->execute();
				$restaurant_id = $result->fetch();
				
				// in $restaurant_id["restaurant_id"] we have the restaurant id
				
				$sql_to_get_reviewer_id = "SELECT reviewer_id FROM \"Reviewers\"
										   WHERE last_name = '" . $_SESSION['last_name'] . "' and first_name = '" . $_SESSION['first_name'] ."' and gender = '". $gender . "'";
				$result  = $conn->prepare($sql_to_get_reviewer_id);
				$result->execute();
				$reviewer_id = $result->fetch();
				
				// in $reviewer_id["reviewer_id"] we have the reviewer's id
				
				$current_date = date('Y-m-d');
				
				// insert review in table finally
				$sql = "INSERT INTO \"Reviews\" (publication_code, restaurant_id, reviewer_id, date_of_review, review_title, review_details)
						VALUES (10, " . $restaurant_id["restaurant_id"] . ", " . $reviewer_id["reviewer_id"].", '$current_date', '" . $review_title . "', '" . $review . "')";
				$result  = $conn->prepare($sql);
				$result->execute();
				
				// add entries to the Review_Features table
				
				
				// find now-made review id
				$sql = "SELECT review_id FROM \"Reviews\"
						ORDER BY review_id DESC
						LIMIT 1;";
				$result  = $conn->prepare($sql);
				$result->execute();
				$temp = $result->fetch();
				
				fill_Review_Feature_table($array_of_features, $temp["review_id"]);
				
				echo "<script>window.location.href='http://localhost/reviews_page.php'</script>";
				
			}
		}
		else
		{
			$invalid_form = true;
		}
			
		if($invalid_form == true)
		{
		}
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
	<div class="middleFrame">
		<h1 style="text-align: center; margin: 0; margin-top: 5px">Review form</h1>
		<div class="gridInFrame">
			<form class="form" id="review_form" action="" method="post">
			
				
				<label >Gender:</label><br>
				<input type="radio" id="male" name="gender" value="M"> <label for="male">Male</label><br> 
				<input style="margin-bottom: 20px" type="radio" id="female" name="gender" value="F"> <label for="female">Female</label><br> 
			
				<label >Restaurant:</label><br>
				<input style="border-radius: 5px; margin-bottom: 20px" type="text" id="restaurant" name="restaurant"><br>
				
				<label>Type:</label><br>
				<select class="selecttag" name="type" id="type">
					
					<?php //php to show all restaurant types from the database
						$conn = new PDO("pgsql:host=localhost;dbname=restaurant_reviews", "postgres", "2828");
						if($conn)
						{
							$sql = "SELECT type_name FROM \"Restaurant_Types\"";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$result = $stmt->fetchAll();
							if(count($result) > 0)
							{
								foreach($result as $row)
								{
									echo '<option value="' . $row["type_name"] . '">' . $row["type_name"] . '</option>"';
								}
							}
						}
					?>
				</select>
				
				<div style="margin-bottom: 5px">
				
				</div>

				
				
				<label >Review title:</label><br>
				<input style="border-radius: 5px; margin-bottom: 20px" type="review_title" id="review_title" name="review_title"><br>
				
				
				<label for="review">Review:</label><br>
				<textarea class="textarea_for_review" id="review" name="review"></textarea><br>
			</form>
			
			<div style="margin-top: 10px; margin-right: 5px">
				<label>Select features described in your review:</label>
				<ul>
					  <li>
							<input form="review_form" type="checkbox" id="cleanliness" name="cleanliness" value="true">
							<label for="cleanliness">cleanliness</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="serving" name="serving" value="true">
							<label for="serving">serving</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="food" name="food" value="true">
							<label for="food">food</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="hospitality" name="hospitality" value="true">
							<label for="hospitality">hospitality</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="serving_speed" name="serving_speed" value="true">
							<label for="serving_speed">serving speed</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="prices" name="prices" value="true">
							<label for="prices">prices</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="vegan_options" name="vegan_options" value="true">
							<label for="vegan_options">vegan options</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="menu" name="menu" value="true">
							<label for="menu">menu</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="takeaway" name="takeaway" value="true">
							<label for="takeaway">takeaway</label>
					  </li>
					  <li>
							<input form="review_form" type="checkbox" id="location" name="location" value="true">
							<label for="location">location</label>
					  </li>
				</ul>
				<button form="review_form" name="submit" class="submit" type="submit"><b>Submit Review</b></button>
			</div>
		</div>
	</div>
	
	

</body>

</html>

<style>
    * {
        box-sizing: border-box;
        font-family: sans-serif;
		
    }

	.bodyOfThePage {
		background-image: linear-gradient(to right, #7be1ed, #fdcb9e);
        height: auto;
        margin: 0;
    }
	
	.middleFrame{	
		background: white;
		width: 400px;
		height: 420px;
		border: solid;
		margin: auto;
		margin-top: 70px;
		border-radius: 20px;
	}
	
	.gridInFrame{
		display: grid;	
		grid-template-columns: 50% 50%;
	}
	
	.form{
		margin-left: 10px;
		margin-right: 10px;
		margin-top: 10px;
	}
	
	.textarea_for_review{
		margin-top: 5px;
		border-radius: 5px;
		border: 2px;
		border-style: solid;
		height: 64px;
		width: 172px;
	}
	
	.submit{
		height: 40px;
		width: 120px;
		background-color: orange;
		margin-left: 40px;
	}
	
	.submit:hover{
		background-color: orangered;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
		cursor: pointer;
	}
	
	input{
		margin-top: 2px;
		margin-bottom: 7px;
	}
	
	li{
		height: 26px;
	}
	
	ul{
		margin: 0;
		margin-top: 10px;
	}
	
	.selecttag{
		border-style: solid;
		border-width: 2px;
		border-color: black;
		border-radius: 5px;
		height: 22px;
		width: 172px;
		margin-top: 5px;
		margin-bottom: 10px;
	}
	
</style>

<script>
	
</script>