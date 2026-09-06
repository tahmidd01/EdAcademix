<?php
// Include the database connection file
include('connect.php');

// Define the SQL query
$query = "SELECT 
    p.name AS Program_Name,
    f.name AS Field_Of_Study,
    tf.semesters_or_trimesters AS Semesters_Or_Trimesters,
    tf.duration AS Duration,
    tf.tuition_fee AS USD$,
    tf.semesters_or_trimesters_fee AS Semester_Or_Trimester_Fees,
    tf.fee_category AS Tuition_Fee_Category
FROM 
    tuitionfee tf
INNER JOIN 
    program p ON tf.program_id = p.program_id
INNER JOIN 
    FieldOfStudy f ON tf.field_id = f.field_id
INNER JOIN 
    University u ON tf.university_id = u.university_id
INNER JOIN 
    countrylist c ON u.country_id = c.country_id
WHERE 
    c.name = 'United States of America'
    AND u.name = 'Stanford University'
ORDER BY 
    p.name, f.name";

// Execute the query
$result = $conn->query($query);

// Group the results by Program Name
$groupedTuitionData = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $programName = $row['Program_Name'];
        $groupedTuitionData[$programName][] = $row;
    }
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
	<!-- <link rel="stylesheet"  href="css/anu.css"> -->
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
/* header part css start */
header {
  background-color: #0C2132;
  padding: 8px;
  position: relative;
  transform-style: preserve-3d;
  animation: slideInHeader 1s ease-out forwards;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
}

/* 3D flip animation */
@keyframes slideInHeader {
  0% {
    transform: rotateX(-90deg);
    opacity: 0;
  }
  100% {
    transform: rotateX(0deg);
    opacity: 1;
  }
}

.welcome p {
  color: white;
  font-size: 1.1rem;
  font-weight: bold;
  margin: 0;
  animation: fadeInText 1.2s ease-out forwards;
}

@keyframes fadeInText {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.header_text {
  font-style: italic;
  float: right;
  color: #ffffff;
  font-size: 1.1rem;
  white-space: nowrap;
  overflow: hidden;
  /* caret removed: no border-right */
  width: 0;
  animation: typingLoop 8s steps(35, end) infinite;
}

/* Typing + Erasing Loop Animation */
@keyframes typingLoop {
  0% {
    width: 0;
  }
  40% {
    width: 340px;
  }
  60% {
    width: 340px;
  }
  100% {
    width: 0;
  }
}
/* header css part end */

   /*navbar css part start*/
.navbar-nav .nav-item .nav-link{
  color:black;
 }
.navbar-nav .nav-item .nav-link:hover{
color:#007BFF;
}


.navbar-brand img{
  max-width: 100%;
  height: auto;
  width: 50px; /* Adjust if  needed */
  display: block;
  border-radius: 30%;
  margin: 0 auto;
  border: 3px solid #155C60; /* Add this line for border */
  }


.navbar{
background-color: black;
}
/* Reset default styles */
body {
margin: 0;
font-weight: bold;
font-family: Arial, sans-serif;
overflow-x: hidden;
}

.navbar {
display: flex;
justify-content: space-around;
align-items: center;
background-color:#95CCCF;
color: #0C2132; /* Dark background color */
padding: 10px 0;
z-index: 1100; /* Higher than the slide panel */
position: relative; /* Required to apply z-index properly */
}

.menu-item {
position: relative; /* For dropdown alignment */
cursor: pointer;
padding: 10px 20px;
color: black;
text-decoration: none;
transition: background-color 0.3s;
}

.menu-item:hover {
background-color: #444;
}

/* Dropdown menu */
.dreamcountry .dropdown-menu.show {
  display: flex !important;
  flex-direction: column !important;
}

.dreamcountry .dropdown-menu li {
  margin: 0 !important;
  padding: 0 !important;
  width: 100% !important;
  box-sizing: border-box !important;
  list-style: none !important;
}

.dreamcountry-option {
  display: block !important;
  width: 100% !important;
  padding: 10px 16px !important;
  box-sizing: border-box !important;
  text-decoration: none !important;
  font-family: 'Times New Roman', Times, serif;
  font-weight: bold !important;
  color: black !important;
  white-space: nowrap !important;
}

.dreamcountry-option:hover {
  background-color: #38B2AC !important;
  color: white !important;
}

.dropdown-divider {
  margin: 0.25rem 0 !important;
  border: none !important;
  border-top: 1px solid #ddd !important;
}

.dropdown-content div:hover {
  background-color: #38B2AC;
  color: black;
  cursor: pointer;
}


/* Show dropdown on click */
.dropdown:hover .dropdown-menu {
display: block;
}


/* the search form container */
.search-form {
  display: flex; 
  align-items: center;
  gap: 10px;
  margin: 0 auto;
  }
  
  /* the search input field */
  .search-input {
  padding: 10px 15px;
  border: 2px solid #ccc;
  border-radius: 20px;
  outline: none;
  font-size: 16px;
  width: 200px; 
  transition: border-color 0.3s; 
  }

  .search-button {
  padding: 10px 20px; 
  color: white; 
  border: none; 
  border-radius: 20px; 
  cursor: pointer; 
  font-size: 20px; 
  transition: background-color 0.3s, transform 0.3s;
  }
  
  .search-button {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
  }
  
  .search-icon {
    width: 35px;
    height: 35px;
  }
  
  
  .search-button:active {
  transform: scale(0.95); 
  }

/* 2nd navbar css part start */
.second-navbar {
  background-color: white;
  color: black;
  padding: 1px 0; 
  line-height: 0.6 !important;
  position: relative;
  border-bottom: 1px solid #ddd; /* Optional: subtle separator line */
  border-color: black;/* Explicit border color */
  border-width: 1px;
}


.second-navbar .nav-list {
  list-style-type: none;
  display: flex;
  justify-content: right; /* Align items to the right */
  margin: 0; /* Remove default margin */
  align-items: center;
  gap: 30px; /* Space between the items */
}

.second-navbar .nav-list li {
  display: inline-block;
}

.second-navbar .nav-list a {
  text-decoration: none; /* Remove underline */
  color: black;
  font-size: 16px; /* Text size */
  padding: 15px 25px;
  transition: background-color 0.3s ease; /* Smooth hover effect */
  border-radius: 4px; /* Rounded corners */
}

.second-navbar .nav-list a:hover {
  background-color: #155C60; /* Slightly lighter shade on hover */
}

.second-navbar .nav-list a:active {
  background-color: #444; /* Even darker shade when active */
}

/* 2nd navbar css part start */
/* shared container */
.second-navbar .nav-list {
  display: flex;
  justify-content: space-between; /* push left and right groups apart */
  align-items: center;
  list-style: none;
  padding: 0;
  margin: 0;
}

.second-navbar .left-group,
.second-navbar .right-group {
  display: flex;
  align-items: center;
  gap: 20px; /* space between buttons/links */
}

.second-navbar li {
  list-style: none;
}

.second-navbar .home-button,
.second-navbar .profile-button {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

.second-navbar a {
  text-decoration: none;
  font-weight: bold;
}

.nav-list .right-group {
  margin-left: auto; /* Push this item to the far left */
  display: flex;
  align-items: center;
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

.left-group img {
  width: 28px;
  height: 28px;
  object-fit: contain;
  vertical-align: middle;
}

.right-group {
  background: none;
  border: none;
  padding: 0;
  margin: 0;
  cursor: pointer;
  display: flex;
  align-items: center;
}

.right-group img {
  width: 28px;
  height: 28px;
  object-fit: contain;
  vertical-align: middle;
}

/* Ensure dropdown shows below the button and stacks vertically */
.dropdown-ac {
  position: relative; /* Needed to position dropdown menu absolutely inside */
}

/* Ensure dropdown is positioned above everything */
.dropdown-content {
  position: absolute;
  top: 100%;
  left: 0;
  display: none;
  background-color: #f9f9f9;
  border: 1px solid #ccc;
  border-radius: 10px;
  margin-top: 8px;
  padding: 10px 15px;
  font-size: 14px;
  line-height: 1.6!important; /* restore your original value */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  min-width: 200px;
  z-index: 9999; /* 👈 ensure it stays on top */
}

/* Optional styling for dropdown items */
.dropdown-content div {
  padding: 6px 10px;
  margin-bottom: 6px;
  background-color: black;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* Fix clipping: allow dropdown to overflow out of containers */
.banner-container,
.diagonal-overlay,
.banner-content {
  overflow: visible !important;
  position: relative;
  z-index: 10;
}


.dropdown-menu.show {
  display: flex !important;
  flex-direction: column !important;
}

.dropdown-menu li {
  margin: 0 !important;
  padding: 0 !important;
  width: 100% !important;
  box-sizing: border-box !important;
  list-style: none !important;
}

.academics-options {
  display: block !important;
  width: 100% !important;
  padding: 10px 16px !important;
  box-sizing: border-box !important;
  text-decoration: none !important;
  font-weight: bold !important;
  color: black !important;
  white-space: nowrap !important;
}

.academics-options:hover {
  background-color: #155C60 !important;
  color: black !important;
}

.dropdown-divider {
  margin: 0.25rem 0 !important;
  border: none !important;
  border-top: 1px solid #ddd !important;
}
.dropdown-content div:hover {
  background-color: #155C60;
  color: black;
  cursor: pointer;
}

/* ✅ FIX: Override line-height inheritance from .second-navbar */
.second-navbar .dropdown-content,
.second-navbar .dropdown-content *,
.second-navbar .dropdown-menu,
.second-navbar .dropdown-menu *,
.second-navbar .academics-options,
.second-navbar .dropdown-content div {
  line-height: 1.4 !important;
}
/*navbar css part end*/


/* banner css part start */
 #banner {
  position: relative;
  height: 70vh; /* Full viewport height */
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px;
  background-color: white;
  box-sizing: border-box;
  overflow: auto;
}
.banner_overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* background: rgba(0, 0, 0, 0.6);  */
  z-index: 1; /
}

/* Banner content styling */
.banner_content {
  position: relative;
  z-index: 2;
  /* text-align: center; */
  display: table-row;
  /* justify-content: center; */
  /* align-items: center; */
  color: #ffffff;
  padding: 150px;
}
.highlight {
  font-style: italic;
  font-size: 2rem;
  font-weight: normal;
  text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6);
}
.subtext {
  font-style: bold;
  font-size: 3rem;
  line-height: 1.5;
  text-shadow: 1px 1px 10px rgba(0, 0, 0, 0.6);
}
 /* banner css part end */

  .carousel-item img {
    height: 800px; /* Adjust based on your design */
    object-fit: cover;
    transition: all linear .3s;
    text-align: center;
  }

   /* banner css part end */

/* footer css part start  */
.secondary-footer {
  background-color: #155C60;
  padding: 8px 5px;
  margin: 0; /* optional: removes outer spacing */
  border: none; /* explicitly removes any border */
  outline: none; /* removes focus outlines if any */
  box-shadow: none; /* removes any shadow */
}

/* footer css part end  */
  
   /* footer2 css part start */
footer {
background-color: #95CCCF;
color: black;
padding: 20px 10px; 
margin: 0;
}
footer h3 {
font-size: 1.3rem;
font-weight: bold;
margin-bottom: 15px;
font-family: Arial, Helvetica, sans-serif;
}

.footer_text p {
margin: 5px 0;
margin-right: 10px 0;
font-size: 1.3rem;
font-family: 'Times New Roman', Times, serif;
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
color: black;
text-decoration: none;
font-size: 1.3rem;
font-family: 'Times New Roman', Times, serif;
}

.footer_links ul li a:hover {
text-decoration: underline;
}

.footer_social a {
  display: flex;
  align-items: center;
  gap: 10px; /* space between icon and text */
  text-decoration: none;
  color: Black; /* Adjust to your theme */
  margin-bottom: 12px; /* space between each row */
  font-size: 20px;
  font-family: 'Times New Roman', Times, serif;
}


.footer_social a:hover {
color: black; 
}
.footer_social{
float: right;
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  font-family: 'Open Sans', sans-serif;
  font-size: 1rem;
}

.footer-bottom-left .copyright {
  color: #0000ff; /* Blue link */
  text-decoration: none;
}

.footer-bottom-left .copyright:hover {
  text-decoration: underline;
}

.footer-bottom-right a {
  color: black;
  text-decoration: none;
  margin: 0 8px;
}

 .footer-bottom-right a:hover {
  text-decoration: underline;
} 

.footer-bottom-right span {
  font-weight: bold;
}
footer {
  position: relative;
  z-index: 1000;
}

/* respnsive part start */
@media(max-width:1024px){
  .navbar-nav .nav-item .nav-link{
    font-size: 16px; /* Adjust font size for smaller screens */
  }
  .search-form {
    flex-direction: column; /* Stack elements vertically */
    align-items: flex-start; /* Align to the left */
  }
  .search-input {
    width: 100%; /* Full width on smaller screens */
    margin-bottom: 10px; /* Space between input and button */
  }
  .go-button {
    width: auto; /* Auto width for button */
  }
}
@media (max-width: 992px) {
  .navbar-nav .nav-item .nav-link {
    font-size: 16px; /* Adjust font size for smaller screens */
  }
  .search-form {
    flex-direction: column; /* Stack elements vertically */
    align-items: flex-start; /* Align to the left */
  }
  .search-input {
    width: 100%; /* Full width on smaller screens */
    margin-bottom: 10px; /* Space between input and button */
  }
  .go-button {
    width: auto; /* Auto width for button */
  }
}

@media (max-width: 768px) {
  .navbar-nav .nav-item .nav-link {
    font-size: 14px; /* Adjust font size for smaller screens */
  }
  .search-form {
    flex-direction: column; /* Stack elements vertically */
    align-items: flex-start; /* Align to the left */
  }
  .search-input {
    width: 100%; /* Full width on smaller screens */
    margin-bottom: 10px; /* Space between input and button */
  }
  .go-button {
    width: auto; /* Auto width for button */
  }
}
@media (max-width: 576px) {
  .navbar-nav .nav-item .nav-link {
    font-size: 12px; /* Further adjust font size for very small screens */
  }
  .search-input {
    width: 100%; /* Full width on smaller screens */
    margin-bottom: 10px; /* Space between input and button */
  }
  .go-button {
    width: auto; /* Auto width for button */
  }
}
@media (max-width: 768px) {
  .second-navbar .nav-list {
    flex-direction: column; /* Stack items vertically */
    align-items: flex-start; /* Align to the left */
  }
  .second-navbar .nav-list li {
    margin-bottom: 10px; /* Space between items */
  }
  .second-navbar .nav-list a {
    padding: 10px; /* Adjust padding for smaller screens */
  }
}
@media (max-width: 576px) {
  .second-navbar .nav-list a {
    font-size: 14px; /* Further adjust font size for very small screens */
  }
  .second-navbar .nav-list li {
    margin-bottom: 5px; /* Space between items */
  }
}

@media (max-width: 768px) {
  .carousel-caption h5 {
    font-size: 1.5rem; /* Adjust font size for smaller screens */
  }
  .carousel-caption p {
    font-size: 1rem; /* Adjust font size for smaller screens */
  }
}
@media (max-width: 576px) {
  .carousel-caption h5 {
    font-size: 1.2rem; /* Further adjust font size for very small screens */
  }
  .carousel-caption p {
    font-size: 0.9rem; /* Further adjust font size for very small screens */
  }
}
@media (max-width: 768px) {
  .footer {
    padding: 10px; /* Adjust padding for smaller screens */
  }
  .footer_text p {
    font-size: 0.8rem; /* Adjust font size for smaller screens */
  }
}
/* respnsive part end */
    </style>
</head>
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
					<p>Your Bridge to Global Success!</p>
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
		<img src="img/EDLOGO.png">
	  </a>
	  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
		  <li class="nav-item">
			<a class="nav-link " aria-current="page" href="index.php">Home</a>
		  </li>
           <!-- Dream Countries Dropdown -->
<li class="nav-item dropdown" id="dreamDropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Dream Countries
  </a>
  <ul class="dropdown-menu">
    <li><a class="dreamcountry-option" href="desh.html"><b>Australia</b></a></li>
    <li><a class="dreamcountry-option" href="desh2.html"><b>England</b></a></li>
    <li><a class="dreamcountry-option" href="desh3.html"><b>Germany</b></a></li>
    <!-- <li><a class="dreamcountry-option" href=desh4.html"><b>Italy</b></a></li> -->
    <li><a class="dreamcountry-option" href="desh5.html"><b>United States of America</b></a></li>
    <li><hr class="dropdown-divider"></li>
  </ul>
</li>
  
		  <li class="nav-item">
			<a class="nav-link" href="#">IELTS Prep</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="#">Get Offer Letter</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="contact.php">Contacts</a>
		  </li>
		  

				<li><hr class="dropdown-divider"></li>
			</ul>
		<!-- Search Form -->
        <div id="suggestions" class="suggestions"></div>
<form class="d-flex" role="search" method="get" action="#" onsubmit="return validateSearch()">
    <input id="searchInput" name="search_query" class="search-input" type="search" placeholder="Search for subjects" aria-label="Search" required>
    <button class="search-button" type="submit">
        <img src="img/search.png" alt="Search" class="search-icon">
    </button>
</form>	  
	  </div>
	</div>
  </nav>
 <!-- Navbar HTML part end  -->

<!-- 2nd navbar html start-->
<nav class="second-navbar">
	<div class="container">
	  <ul class="nav-list">
      <!-- LEFT SIDE -->
      <li class="left-group d-flex align-items-center gap-3">

        <button class="home-button" onclick="location.href='index.php';">
          <img src="img/h1.png" alt="Home">
        </button>
				<!-- ✅ Academics Dropdown -->
<li class="nav-item dropdown-ac" id="academicsDropdown">

			<a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownToggle">
				Academics
			</a>
			<ul class="dropdown-menu" id="dropdownMenu">
				<li><a class="academics-options" href="pro_academic_stan.php">Programs</a></li>
				<li><a class="academics-options" href="scholar_academic_stan.php">Scholarships</a></li>
				<li><a class="academics-options" href="fee_academic_stan.php">Tution Fees</a></li>

				<li><hr class="dropdown-divider"></li>
			</ul>
			</li>

        <a href="req_stan.php">Requeirments</a>
        <a href="./universities/stanford_contact.html">Help-Line</a>
      </li>

      <!-- RIGHT SIDE -->
      <li class="right-group ms-auto d-flex align-items-center gap-3">
        <a href="#">FAQs</a>
        <a href="#">Log in / Sign up</a>
        <button class="profile-button">
          <img src="img/p1.png" alt="Profile">
        </button>
      </li>
	  </ul>
	</div>
  </nav>

  <!-- 2nd navbar html end-->

<div id="banner">
    <div class="banner_overlay">
        <div class="container mt-5">
            <div class="banner_content">
                <h1 class="text-center mb-5 fw-bold typing-container" style="color: black;">
  <u class="typing-text">Tuition Fees at Stanford University</u>
</h1>
                <div class="table-responsive">
                    <table class="table table-bordered" style="background-color: white; color: black; border: 2px solid black;">
                        <thead>
                            <tr>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">Program Name</th>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">Field of Study</th>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">Semesters/Trimesters</th>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">Duration</th>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">USD$</th>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">Semester/Trimester Fees</th>
                                <th style="background-color: white; color: black; border: 2px solid black; font-weight: bold; text-align: center;">Tuition Fee Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupedTuitionData as $programName => $entries): ?>
                                <tr>
                                    <td rowspan="<?= count($entries) ?>" style="background-color: white; color: black; border: 2px solid black; vertical-align: middle; text-align: center; font-weight: bold;">
                                        <?= htmlspecialchars($programName) ?>
                                    </td>
                                    <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[0]['Field_Of_Study']) ?></td>
                                    <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[0]['Semesters_Or_Trimesters']) ?></td>
                                    <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[0]['Duration']) ?></td>
                                    <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[0]['USD$']) ?></td>
                                    <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[0]['Semester_Or_Trimester_Fees']) ?></td>
                                    <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[0]['Tuition_Fee_Category']) ?></td>
                                </tr>
                                <?php for ($i = 1; $i < count($entries); $i++): ?>
                                    <tr>
                                        <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[$i]['Field_Of_Study']) ?></td>
                                        <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[$i]['Semesters_Or_Trimesters']) ?></td>
                                        <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[$i]['Duration']) ?></td>
                                        <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[$i]['USD$']) ?></td>
                                        <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[$i]['Semester_Or_Trimester_Fees']) ?></td>
                                        <td style="background-color: white; color: black; border: 2px solid black; font-weight: normal;"><?= htmlspecialchars($entries[$i]['Tuition_Fee_Category']) ?></td>
                                    </tr>
                                <?php endfor; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- footer line starts -->
  <div class="secondary-footer">
	<!-- your content -->
  </div>
  
  <!-- footer line ends -->

<!-- footer2 html part start  -->
 <footer>
	<div class="container">
		<div class="row">
			<!-- 1st Column: Company Info -->
			<div class="col-md-4">
				<div class="footer_text">
					<h3><b><u>ADDRESS</u></b></h3>
					<p>United International University, UIU Permanent Campus</p>
					<p>United City, Madani Avenue</p>
					<p>Notun Bazar, 100 - Feet, Dhaka - 1212</p>
				</div>
			</div>
			
			<!-- 2nd Column: Links -->
			<div class="col-md-4">
				<div class="footer_links">
					<h3><b><u>USEFUL LINKS</u></b></h3>
					<ul>
						<li><a href="about.php">About EdAcademix</a></li>
						<li><a href="#">Blogs</a></li>
						<li><a href="#">Success Stories</a></li>
						<li><a href="#">Terms & Conditions</a></li>
					</ul>
				</div>
			</div>
			
			<!-- 3rd Column: Social Media -->
			<div class="col-md-4">
				<div class="footer_social">
				  <h3><b><u>FOLLOW US</u></b></h3>
				  <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
				  <a href="#"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
				  <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
				  <a href="#"><i class="fas fa-envelope"></i> Email</a>
				</div>
			  </div>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="footer-bottom-left">
		  <a href="#" class="copyright">@Copyright</a>
		</div>
		<div class="footer-bottom-right">
		  <span>||</span>
		  <a href="#">Data Privacy Statement</a>
		  <span>||</span>
		  <a href="#">Cookies</a>
		  <span>||</span>
		</div>
	  </div>
	  
	  
</footer>
<!-- footer html part end  -->

    <!-- Bootstrap JS -->
    <!-- js link start -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldZa5p5fC2G6KZs4Ks8lg2a44cId4jTkz76PKaX" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-cn7l7gDp0eyIG6mvEH16lAaqjZfGnirKwrjvJoaAqh6Ez4eZ0CtFLR69Zo5I2sT" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
<!-- js link end -->

<script>
document.addEventListener('DOMContentLoaded', function () {
  // ✅ Toggle Dropdown Helper
  function setupDropdown(toggleSelector, menuSelector, containerId) {
    const toggle = document.querySelector(toggleSelector);
    const menu = document.querySelector(menuSelector);

    if (toggle && menu) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        menu.classList.toggle('show');
      });

      // Attach only one global click listener per dropdown
      document.addEventListener('click', function (e) {
        if (!document.getElementById(containerId).contains(e.target)) {
          menu.classList.remove('show');
        }
      });
    }
  }

  // ✅ Setup Academics and Dream Countries dropdowns
  setupDropdown('#academicsDropdown > a.nav-link.dropdown-toggle', '#academicsDropdown > ul.dropdown-menu', 'academicsDropdown');
  setupDropdown('#dreamDropdown > a.nav-link.dropdown-toggle', '#dreamDropdown > ul.dropdown-menu', 'dreamDropdown');

  // ✅ Banner dropdowns (unchanged)
  function toggleBannerDropdown(dropdownId) {
    document.querySelectorAll('.banner-dropdown').forEach(dropdown => {
      if (dropdown.id === dropdownId) {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
      } else {
        dropdown.style.display = 'none';
      }
    });
  }

  const fieldsBtn = document.getElementById("fieldsBtn");
  if (fieldsBtn) {
    fieldsBtn.addEventListener("click", function (e) {
      e.preventDefault();
      toggleBannerDropdown("fieldsDropdown");
    });
  }

  const programsBtn = document.getElementById("programsBtn");
  if (programsBtn) {
    programsBtn.addEventListener("click", function (e) {
      e.preventDefault();
      toggleBannerDropdown("programsDropdown");
    });
  }

  const feesBtn = document.getElementById("feesBtn");
  if (feesBtn) {
    feesBtn.addEventListener("click", function (e) {
      e.preventDefault();
      toggleBannerDropdown("feesDropdown");
    });
  }

  document.addEventListener("click", function (e) {
    if (!e.target.closest('.info-links')) {
      document.querySelectorAll('.banner-dropdown').forEach(dropdown => {
        dropdown.style.display = 'none';
      });
    }
  });
});
// sunject card flip functionality
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.subject-card').forEach(card => {
    card.addEventListener('click', () => {
      card.classList.toggle('flipped');
    });
  });
});
</script>
<style>
@keyframes typing {
  0% {
    width: 0ch;
  }
  50% {
    width: 45ch; /* Adjust based on your text length */
  }
  100% {
    width: 0ch;
  }
}

@keyframes blink {
  0%, 100% {
    border-color: transparent;
  }
  50% {
    border-color: black;
  }
}

.typing-container {
  overflow: hidden;
  white-space: nowrap;
}

.typing-text {
  display: inline-block;
  overflow: hidden;
  border-right: 2px solid black;
  animation: typing 12s steps(60, end) infinite, blink 0.7s step-end infinite;
  white-space: nowrap;
}

</style>
</body>
</html>