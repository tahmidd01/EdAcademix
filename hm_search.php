<?php
include('connect.php');  // Database connection file

// Define a variable for the error message
$error_message = '';

if (isset($_GET['country_name'])) {
    $country_name = $_GET['country_name'];

    // Sanitize the input to prevent malicious code (XSS, etc.)
    $country_name = htmlspecialchars($country_name, ENT_QUOTES, 'UTF-8');

    // Convert the country name to lowercase to make it case-insensitive
    $country_name = strtolower(trim($country_name));

    // Prepare SQL query to check for country in the database
    $query = "SELECT name FROM countrylist WHERE LOWER(name) = ?";
    $stmt = $conn->prepare($query);  // Use prepared statements to prevent SQL injection
    $stmt->bind_param("s", $country_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the country exists in the database
    if ($result->num_rows > 0) {
        // Country found, redirect to the corresponding HTML page
        switch ($country_name) {
            case 'australia':
                $country_url = 'desh.html';
                break;
            case 'england':
                $country_url = 'desh2.html';
                break;
            case 'germany':
                $country_url = 'desh3.html';
                break;
            case 'italy':
                $country_url = 'desh4.html';
                break;
            case 'united states of america':
                $country_url = 'desh5.html';
                break;
            default:
                // If the country is not found, display an error message
                $error_message = "Country page not found.";
                break;
        }

        if (!$error_message) {
            // Redirect to the country page (e.g., desh.html for Australia)
            header("Location: $country_url");
            exit; // Ensure no further code is executed after the redirect
        }
    } else {
        // If the country does not exist in the database, set the error message
        $error_message = "Sorry! The country is not Found.";
    }

    // Close the database statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case where 'country_name' is not provided in the GET request
    $error_message = "Please enter a country name to search.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>EdAcademix</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-5EH+HLwMxaZJzDdMWNxlWfOmkR1F7xhEMBkCtKHxdsOTbPdCoP0U8yEFQRwrb5Pg" crossorigin="anonymous">
	<link rel="stylesheet" href="css/fontawesome.min.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	<!-- <link rel="stylesheet"  href="css/style.css"> -->
	<link rel="stylesheet"  href="css/responsive.css">
    <style>
        * {
    margin: 0;
    padding: 0;
    outline: 0;
  }
  a{
    text-decoration: none;
  }
ul {
    list-style: none;
    padding: 0;
}

  ul,ol{
    list-style: none;
  }
  h1,h2,h3,h4,h5,h6,p,nav,span,a,div,ul,li,footer{
  padding: 0;
  margin: 0;
  }
  /*header part css start*/
  header{
    background-color:#0a0a0a;
    padding: 10px;
  }
  .welcome p{
    color: white;
  }
  .header_text{
    font-style: italic;
    float: right;
    color: #ffffff;
  }
  /* header css part end */

  /*navbar css part start*/
  .navbar-nav .nav-item .nav-link{
    color:white;
   }
.navbar-nav .nav-item .nav-link:hover{
  color:#007BFF;
 }
 .navbar-brand img{
  max-width: 100%;
  height: auto;
  width: 50px; /* Adjust if  needed */
  display: block;
  margin: 0 auto;
 }
 .navbar{
  background-color: #353535;
 }
 /* Reset default styles */
body {
  margin: 0;
  font-weight: bold;
  font-family: Arial, sans-serif;
}

.navbar {
  display: flex;
  justify-content: space-around;
  align-items: center;
  background-color: #333;
  color: white;
  padding: 10px 0;
  z-index: 1100; /* Higher than the slide panel */
  position: relative; /* Required to apply z-index properly */
}


.menu-item {
  position: relative; /* For dropdown alignment */
  cursor: pointer;
  padding: 10px 20px;
  color: white;
  text-decoration: none;
  transition: background-color 0.3s;
}

.menu-item:hover {
  background-color: #444;
}

/* Dropdown menu */
.dropdown-menu {
  display: none; /* Hidden by default */
  position: absolute;
  top: 100%; /* Place below the menu item */
  left: 0;
  background-color: white;
  color: black;
  font-weight: bold;
  border: 1px solid #ccc;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  min-width: 150px;
}

.dropdown-menu a {
  display: block;
  padding: 10px 15px;
  color: black;
  text-decoration: none;
  transition: background-color 0.3s;
}

.dropdown-menu a:hover {
  background-color: #ddd;
}

/* Show dropdown on click */
.dropdown:hover .dropdown-menu {
  display: block;
}
.go-button {
  display: flex;
  padding: 8px 20px;
  font-size: 18px;
  color: #ffffff;
  background-color: #007bff;
  border: none;
  border-radius: 10px;
  text-decoration: none;
  text-align: center;
  float: right;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  justify-content: center;
  width: 100px;
}
.go-button:hover {
  background-color: #0056b3;
  transform: scale(0.8);
}
 /* the search form container */
.search-form {
  display: flex; 
  align-items: center;
  gap: 10px;
  margin: 0 auto;
  padding-left: 50px;
}

/* the search input field */
.search-input {
  padding: 10px 15px;
  border: 3px solid #ccc;
  border-radius: 20px;
  outline: none;
  font-size: 16px;
  width: 200px; 
  transition: border-color 0.3s; 
}
.search-button {
  padding: 10px 20px; 
  background-color: #007BFF; 
  color: white; 
  border: none; 
  border-radius: 20px; 
  cursor: pointer; 
  font-size: 16px; 
  transition: background-color 0.3s, transform 0.3s;
}

/* Hover effect for the button */
.search-button:hover {
  background-color: #0056b3;
}
.search-button:active {
  transform: scale(0.95); 
}

 /*navbar css part end*/

 #banner {
  position: relative;
  height: 70vh; /* Full viewport height */
  background-color: #0C2132;
  background-size: cover; 
  background-position: center; 
  background-repeat: no-repeat; 
  padding: 20px;
}
.banner_overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6); 
  z-index: 1; /
}

/* Banner content styling */
.banner_content {
  position: relative;
  z-index: 2;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: #ffffff;
  height: 100%; /* Ensure it takes up the full height of the banner */
}

.text-center {
  position: absolute;
  top: 50px; /* Adjust to move h1 to the top center */
  width: 100%; /* Ensure h1 is centered */
  text-align: center;
  font-size: 2.5rem; /* Adjust font size as needed */
}

.subtext {
  position: absolute;
  top: 200px; /* Center the subtext vertically */
  transform: translateY(-50%); /* Adjust for precise vertical centering */
  font-size: 3rem;
  line-height: 1.5;
  text-shadow: 1px 1px 10px rgba(0, 0, 0, 0.6);
  text-align: center; /* Ensure subtext is centered horizontally */
}


 /* footer css part start  */
 .footer {
	background-color:#0C2132;
	padding: 20px 10px; 
  margin: 5px ;
}
 /* footer css part end  */

 /* footer2 css part start */
 footer {
	background-color: #000000;
	color: #ffffff;
	padding: 20px 10px; 
  margin: 0;
}
footer h4 {
	font-size: 1.2rem;
	font-weight: bold;
	margin-bottom: 15px;
}

.footer_text p {
	margin: 5px 0;
  margin-right: 10px 0;
	font-size: 0.9rem;
}
.footer_links {
	text-align: center; /* Center aligns the "Links" column */
}
.footer_links ul {
	list-style: none;
	padding: 0;
}

.footer_links ul li {
	margin-bottom: 10px;
}

.footer_links ul li a {
	color: #ffffff;
	text-decoration: none;
	font-size: 0.9rem;
}

.footer_links ul li a:hover {
	text-decoration: underline;
}

.footer_social a {
	margin-right: 10px;
	color: #ffffff;
	font-size: 1.2rem;
	text-decoration: none;
}
.footer_social a:hover {
	color: #cccccc; 
}
.footer_social{
  float: right;
}
    </style>
</head>
<body>
<body>
	<!-- header part html start -->
	<header>
	<div>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="welcome">
					<p>Welcome to EdAcademix</p>
				</div>
			</div>
			<div class="col-md-8">
				<div class="header_text">
					<p>Your Bridge to Global Success</p>
				</div>
			</div>
		</div>
	</div>
</div>
</header>
	 <!-- header part html end -->

<!-- Navbar HTML part start -->
<nav class="navbar navbar-expand-lg">
	<div class="container">
	  <a class="navbar-brand" href="#">
		<img src="img/logopro.png">
	  </a>
	  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
		  <li class="nav-item">
			<a class="nav-link " aria-current="page" href="index.html">Home</a>
		  </li>
           <!-- Dream Countries Dropdown -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				Dream Countries
				</a>
				<ul class="dropdown-menu">
				<li><a class="dream-country-options" href="desh.html"><b>Australia</b></a></li>
				<li><a class="dream-country-options" href="desh2.html"><b>England</b></a></li>
				<li><a class="dream-country-options" href="desh3.html"><b>Germany</b></a></li>
				<li><a class="dream-country-options" href="desh4.html"><b>Italy</b></a></li>
				<li><a class="dream-country-options" href="desh5.html"><b>United States of America</b></a></li>
				<li><hr class="dropdown-divider"></li>
				</ul>
			</li>
  
		  <li class="nav-item">
			<a class="nav-link" href="./FAQs.html">FAQs</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="./contact.html">Contact</a>
		  </li>
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
			  Preferred University
			</a>
			<ul class="dropdown-menu">
			  <!-- Dropdown Option: Select Country -->
			  <li>
				<a 
				  class="dropdown-item dropdown-toggle" 
				  href="#" 
				  onclick="toggleCountryList(event)"
				>
				  <b>Select Country :</b>
				</a>
				<ul id="country-list" class="nested-dropdown d-none">
				  <li><a class="dropdown-item" href="#">Germany</a></li>
				  <li><a class="dropdown-item" href="#">United Kingdom</a></li>
				  <li><a class="dropdown-item" href="#">Italy</a></li>
				  <li><a class="dropdown-item" href="#">Netherlands</a></li>
				  <li><a class="dropdown-item" href="#">Finland</a></li>
				</ul>
			  </li>
			  <!-- Dropdown Option: Select Program -->
			  
			  <li>
				<a 
				  class="dropdown-item dropdown-toggle" 
				  href="#" 
				  onclick="toggleProgramList(event)"
				>
				  <b>Select Program :</b>
				</a>
				<ul id="program-list" class="nested-dropdown d-none">
				  <li><a class="dropdown-item" href="#">Undergraduate</a></li>
				  <li><a class="dropdown-item" href="#">Graduate</a></li>
				  <li><a class="dropdown-item" href="#">PhD</a></li>
				</ul>
			  </li>
			  <!-- Dropdown Option: Enter Score -->
			  <li class="dropdown-item">
				<div class="language-score-container">
					<label for="language-score"><b>Language Score :</b></label>
					<input type="text" id="language-score" placeholder="Enter your score" />
				  </div>  
			  </li>
			  <a href="#" class="go-button" style="width: 200px;">Go</a>
			</ul>
		  </li>
				<li><hr class="dropdown-divider"></li>
			</ul>
		  </ul>
		</li>
		</ul>
		<!-- Search Form -->
        <div id="suggestions" class="suggestions"></div>
		<form class="search-form" role="search" method="GET" action="hm_search.php">
			<input type="text" class="search-input" name="country_name" placeholder="Search for a country" />
			<button type="submit" class="search-button">Search</button>
		</form>
  
	  </div>
	</div>
  </nav>
 <!-- Navbar HTML part end  -->


<!-- banner part start -->
<div id="banner">
  <div class="banner_overlay">
    <div class="container">
      <div class="banner_content">
        <?php if ($error_message): ?>
          <h1 class="text-center"><b>Searched Country</b></h1>
          <p class="subtext"><?php echo $error_message; ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- banner part end -->




 <!-- footer html part start  -->
 <!-- footer part start -->
 <footer>
	<div class="container">
		<div class="row">
			<!-- 1st Column: Company Info -->
			<div class="col-md-4">
				<div class="footer_text">
					<h4>EdAcademix.Co</h4>
					<p>+8801639149794</p>
					<p>+8801741772525</p>
				</div>
			</div>
			
			<!-- 2nd Column: Links -->
			<div class="col-md-4">
				<div class="footer_links">
					<h4>Links</h4>
					<ul>
						<li><a href="about.html">About EdAcademix</a></li>
						<li><a href="#">Facebook/Page</a></li>
						<li><a href="#">LinkedIn/Page</a></li>
					</ul>
				</div>
			</div>
			
			<!-- 3rd Column: Social Media -->
			<div class="col-md-4">
				<div class="footer_social">
					<h4>Follow us</h4>
					<a href="#"><i class="fab fa-facebook-f"></i></a>
					<a href="#"><i class="fab fa-linkedin-in"></i></a>
					<a href="#"><i class="fab fa-twitter"></i></a>
					<a href="#"><i class="fas fa-envelope"></i></a>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- footer html part end  -->

</body>
</html>
