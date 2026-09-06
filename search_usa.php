<?php
// Include the database connection file
include('connect.php');

// Check if search form was submitted and capture the search term
$searchTerm = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare SQL query to search for universities
$sql = "SELECT 
        u.name AS University_Name,
        u.global_rank AS World_Rank,
        GROUP_CONCAT(DISTINCT TRIM(TRAILING 's' FROM REPLACE(p.name, 'Program', '')) SEPARATOR ', ') AS Program_Type,
        tf.fee_category AS Tuition_Fee_Type,
        u.location AS Location
    FROM 
        University u
    INNER JOIN 
        UniversityProgramField upf ON u.university_id = upf.university_id
    INNER JOIN 
        Program p ON upf.program_id = p.program_id
    INNER JOIN 
        tuitionfee tf ON u.university_id = tf.university_id
    WHERE 
        u.country_id = (
            SELECT country_id 
            FROM CountryList 
            WHERE name = 'United States of America'
        )
    AND 
        (u.name LIKE ? OR u.name LIKE ? OR u.name LIKE ? OR u.name LIKE ?)
    GROUP BY u.university_id
";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind the search term to the query
$searchTermWithWildcard = "%" . $searchTerm . "%";
$stmt->bind_param('ssss', $searchTermWithWildcard, $searchTermWithWildcard, $searchTermWithWildcard, $searchTermWithWildcard);

// Execute query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countries</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        /* Insert the styles from your existing code here */

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
  background: none;
  border: none;
  padding: 5px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.3s;
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
  padding: 6px 0; /* Match padding closer to main navbar */
  position: relative;
  border-bottom: 1px solid #ddd; /* Optional: subtle separator line */
}


.second-navbar .nav-list {
  list-style-type: none;
  display: flex;
  justify-content: right; /* Align items to the right */
  margin: 0; /* Remove default margin */
  align-items: center;
  gap: 20px; /* Space between the items */
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
/* 2nd navbar css part end */

 /* banner css part start */
/* Set height and fit for carousel images */
/* General container */
.container-fluid {
  width: 100%;
  padding: 0 20px;
  padding-bottom: 30px;
}

/* Text section (left) */
.col-md-5:first-child {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-left: 70px;
  padding-right: 30px;
  perspective: 1000px; /* Enable 3D animation */
  animation: textFlipIn 1s ease-out forwards;
  transform-style: preserve-3d;
}

/* 3D Flip-in animation */
@keyframes textFlipIn {
  0% {
    transform: rotateY(-90deg);
    opacity: 0;
  }
  100% {
    transform: rotateY(0);
    opacity: 1;
  }
}

.col-md-5:first-child h2 {
  font-size: 32px;
  margin-bottom: 15px;
  white-space: nowrap;
  overflow: hidden;
  border-right: 2px solid black;
  width: 0;
  animation:
    typingLoop 8s steps(40, end) infinite,
    blinkCaret 0.8s step-end infinite;
}

/* Typing + retyping animation */
@keyframes typingLoop {
  0% {
    width: 0ch;
  }
  40% {
    width: 30ch; /* Adjust this to match your actual text length */
  }
  60% {
    width: 30ch;
  }
  100% {
    width: 0ch;
  }
}

@keyframes blinkCaret {
  0%, 100% {
    border-color: transparent;
  }
  50% {
    border-color: black;
  }
}

.col-md-5:first-child p {
  font-size: 20px;
  line-height: 1.6;
  animation: fadeInText 1s ease-out 0.5s forwards;
  opacity: 0;
}

/* Smooth fade for paragraph */
@keyframes fadeInText {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Carousel section (right) */
.col-md-5.p-0 {
  padding-right: 0;
  padding-left: 0;
}

/* Carousel image */
#carouselExampleIndicators {
  width: 100%;
}

.carousel-item img {
  width: 90%;
  height: 500px;
  object-fit: cover;
  padding-top: 0px;
}


/* Optional: Adjust carousel indicators */
.carousel-indicators [data-bs-target] {
  background-color: #333;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
  filter: invert(1); /* Make arrows visible over any background */
}
/* Optional: style the text to wrap nicely */
/* Target the left text column */
.col-md-6:first-child {
  height: 500px; /* match carousel height */
  display: flex;
  align-items: center;  /* vertically center content */
  justify-content: center;
}

/* Narrow the width of the inner text block */
.col-md-6:first-child > div {
  max-width: 300px; /* adjust width as needed */
  width: 100%;
}

/* Optional: style the text to wrap nicely */
.col-md-6:first-child h2,
.col-md-6:first-child p {
  word-wrap: break-word;
  text-align: left;
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .col-md-6:first-child {
      padding: 20px;
      text-align: center;
  }

  .col-md-6:first-child h2 {
      font-size: 24px;
  }

  .col-md-6:first-child p {
      font-size: 14px;
  }

  .carousel-item img {
      height: 400px;
  }
}


/* banner css part end */

/* chatbot part starts */
.chatbot-icon {
  background-color: black;
  border-radius:30%; 
  position: fixed;
  bottom: 60px;
  right: 30px;
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.chatbot-icon:hover {
  transform: scale(1.1);
}

.chatbot-icon img {
  width: 70px;   /* Increased image size inside circle */
  height: 70px;
  object-fit: contain;
}

/* chatbot part ends */

/* filter section css start */

/* Filter part CSS - updated design covering full container */
.filter-section {
  display: flex;
  width: 100%;
  border: 1px solid #ccc;
  overflow: hidden; /* rounded corners apply to inner buttons */
  background-color: #f9f9f9;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding-bottom: 30px;
}

.filter-label,
.filter-option {
  flex: 1;
  padding: 12px 0;
  font-size: 14px;
  text-align: center;
  border-right: 1px solid #ccc;
}

.filter-label {
  background-color: #e0e0e0;
  font-weight: 600;
  color: #333;
  cursor: default;
}

.filter-option {
  background-color: #fff;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
  font-weight: bold;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 18px;
}

.filter-option:hover {
  background-color: #38B2AC;
  color: #fff;
}

.filter-section button:last-child {
  border-right: none; /* remove right border on last button */
}



/* card animation starts */
.card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 20px;
    box-shadow: 0 20px 20px rgba(0, 0, 0, 0.4);
    padding: 10px 10px;
    text-align: center;
    transform-style: preserve-3d;
    transform: rotateY(0deg);
    animation: floatIn 0.8s ease-out;
    transition: transform 0.6s ease;
    max-height: 400px; /* 👈 Add this */
    overflow: hidden;   /* 👈 Ensures content doesn't spill */
}

.card:hover {
    transform: rotateY(12deg) rotateX(8deg);
}

@keyframes floatIn {
    from {
        transform: translateY(100px) rotateY(-10deg);
        opacity: 0;
    }
    to {
        transform: translateY(0) rotateY(0deg);
        opacity: 1;
    }
}
/* card animation ends */


/* uni-name animation starts */
.university-link {
    text-decoration: none;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    display: inline-block;
    color: black;
}

.university-link:hover {
    color: white; /* Hover color */
    transform: scale(1.05); /* Slight zoom */
}

/* Removed the underline animation completely */
.university-link::after {
    content: none;
}

/* Optional: Keep .uni-title for consistent heading styling */
.uni-title {
    font-family: 'Lucida Sans', sans-serif;
    font-weight: bolder;
    font-size: 25px;
    text-align: center;
}
/* uni-name animation ends */


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

/* 2nd navbar css part end */


 /* Center single card in the row */
.row.single-result {
    justify-content: center;
}


/* notification panel start */

#notificationBanner {
position: fixed;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 400px;
background-color: white;
border: 2px solid white;
border-radius: 8px;
box-shadow: 0 4px 8px rgba(0,0,0,0.2);
display: none;
z-index: 1000;
transition: width 0.3s, height 0.3s;
}

.banner-header {
background-color: #007bff;
color: white;
padding: 10px;
font-weight: bold;
border-top-left-radius: 6px;
border-top-right-radius: 6px;
display: flex;
justify-content: space-between;
align-items: center;
}

.banner-controls span {
margin-left: 10px;
cursor: pointer;
}

.banner-body {
padding: 15px;
text-align: center;
}

.banner-footer {
padding: 10px;
text-align: right;
}

.banner-footer button {
margin-left: 10px;
}

#closeBtn {
font-size: 18px;
padding: 5px;
transition: transform 0.2s;

}
#minimizeBtn,
#expandBtn {
font-size: 20px;     /* increase the icon size */
padding: 6px;        /* increase clickable area */
cursor: pointer;
transition: transform 0.2s;
}

#minimizeBtn:hover,
#expandBtn:hover {
transform: scale(1.2); /* optional hover zoom effect */
}


#notificationBanner button {
padding: 5px 12px;
border: 2px solid black;
border-radius: 6px;
background: white;
cursor: pointer;
}

#notificationBanner button:hover {
background-color: #f0f0f0;
}
/* notification panel end */


/* FAQ Overlay (invisible backdrop to detect outside click) */

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
    <!-- Header -->
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
    <!-- <li><a class="dreamcountry-option" href="desh4.html"><b>Italy</b></a></li> -->
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
			<a class="nav-link" href="./contact.php">Contacts</a>
		  </li>
		  

				<li><hr class="dropdown-divider"></li>
			</ul>
		<!-- Search Form -->
        <div id="suggestions" class="suggestions"></div>
<form class="d-flex" role="search" method="get" action="search_usa.php" onsubmit="return validateSearch()">
    <input id="searchInput" name="search_query" class="search-input" type="search" placeholder="Search for universities" aria-label="Search" required>
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
		<li class="left-group">
		  <button class="home-button">
			<img src="img/h1.png" alt="Home">
		  </button>
		  <a href="about_usa.php">About</a>
		  <a href="uni_usa.php">Universities</a>
		</li>
  
		<!-- RIGHT SIDE -->
		<li class="right-group">
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

  <!-- Carousel Start -->
 <div class="container-fluid mt-0">
	<div class="row">
	  <!-- Left text section -->
	  <div class="col-md-5">
		<div>
		  <h2><b>“Hello and welcome to the USA,</b></h2>
		  <p>
			a land of endless possibilities spanning North America, where vast landscapes meet iconic cities, offering a dynamic lifestyle, a top-ranked education system fostering innovation, the US Dollar (USD), and a vibrant cultural tapestry of music, arts, and global influences."
		  </p>
		</div>
	  </div>
  
	  <!-- Right carousel section -->
	  <div class="col-md-7 p-0">
		<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
		  <div class="carousel-indicators">
			<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
			<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
			<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
		  </div>
		  <div class="carousel-inner">
			<div class="carousel-item active" data-bs-interval="2000">
			  <img src="img/usa1.jpg" class="d-block w-100" alt="Banner 1">
			</div>
			<div class="carousel-item" data-bs-interval="2000">
			  <img src="img/usa2.jpeg" class="d-block w-100" alt="Banner 2">
			</div>
			<div class="carousel-item" data-bs-interval="2000">
			  <img src="img/usa3.jpg" class="d-block w-100" alt="Banner 3">
			</div>
		  </div>
		  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		  </button>
		  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		  </button>
		</div>
	  </div>
	</div>
  </div>
  
  
  <!-- Carousel End -->		

  <!-- Chatbot Icon -->
<!-- <a href="#" class="chatbot-icon" title="Chat with us">
	<img src="img/CB.png" alt="Chatbot"></a> -->

   <!-- filter option html start -->
     
   <!-- <div class="container mt-4"> -->
	<div class="row">
	  <div class="col-12">
		<div class="filter-section">
<button class="filter-label">FILTER BY</button>
<button class="filter-option" onclick="location.href='program_usa.php'">Program Wise</button>
<button class="filter-option" onclick="location.href='tuitionfee_usa.php'">Tuition Fees Wise</button>
<button class="filter-option" onclick="location.href='rank_usa.php'">World Ranking Wise</button>
		</div>
	  </div>
	</div>
  <!-- </div> -->
  
  <!-- filter option html end --> 

  <!-- Notification Banner -->
<div id="notificationBanner">
	<div class="banner-header">
	  Access Required! 🔒
	  <span class="banner-controls" style="float: right; cursor: pointer;">
		<span id="minimizeBtn" title="Minimize" style="margin-right: 10px;">–</span>
		<span id="expandBtn" title="Expand" style="margin-right: 10px;">□</span>
		<span id="closeBtn">&#x274C;</span>
	  </span>
	</div>
	<div class="banner-body">
	  You need to log in or sign up to access this feature, thank you!
	</div>
	<div class="banner-footer">
	  <button id="cancelBtn">Cancel</button>
	  <button id="okBtn">Okay</button>
	</div>
  </div>


<!-- Main Content -->
<div id="banner">
    <div class="banner_overlay">
        <div class="container mt-5">
            <div class="banner_content">
  <h2 class="text-center" style="font-family: Times New Roman, Times, serif; font-size: 30px; font-weight: bold; text-decoration: underline; display: inline-block; white-space: nowrap; overflow: hidden; animation: typing 6s steps(20, end) infinite, flipIn 1.2s ease-out forwards; transform-origin: top; margin: 0 auto; text-align: center;">
  Searched University
</h2>
                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="row mt-4 <?php echo ($result->num_rows === 1) ? 'single-result' : ''; ?>">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="col-md-7">
                                <div class="card mb-5">
                                    <!-- Set Image Path for Specific Universities -->
                                    <?php
                                        $university_name = strtolower($row['University_Name']);
                                        $logo_path = ''; // Default logo path

                                        // Set specific logo paths for the universities
                                        if ($university_name == 'california institute of technology') {
                                            $logo_path = 'universities/img/caltechlogo.jpeg';
                                        } elseif ($university_name == 'harvard university') {
                                            $logo_path = 'universities/img/harvardlogo.jpeg';
                                        } elseif ($university_name == 'massachusetts institute of technology') {
                                            $logo_path = 'universities/img/mitlogo.jpg';
                                        } elseif ($university_name == 'stanford university') {
                                            $logo_path = 'universities/img/sulogo.png';
                                        }

                                        // Fallback to a default logo if no match
                                        if ($logo_path == '') {
                                            $logo_path = 'universities/img/default_logo.jpeg'; 
                                        }
                                    ?>
                                    <img src="<?php echo $logo_path; ?>" class="card-img-top" alt="University Logo" style="height: 150px; object-fit: contain; width: 100%;">
                                    <div class="card-body" style="background-color: rgb(1, 133, 140); color: black;">
                                        <h5 class="card-title">
                                        <?php
                                            // Check university name and link accordingly
if ($university_name == 'california institute of technology') {
    echo '<h5 class="text-center uni-title">';
    echo '<a href="universities/CIT.html" class="university-link"><strong>' . htmlspecialchars($row['University_Name']) . '</strong></a>';
} elseif ($university_name == 'harvard university') {
    echo '<h5 class="text-center uni-title">';
    echo '<a href="universities/harvard.html" class="university-link"><strong>' . htmlspecialchars($row['University_Name']) . '</strong></a>';
} elseif ($university_name == 'massachusetts institute of technology') {
    echo '<h5 class="text-center uni-title">';
    echo '<a href="universities/mit.html" class="university-link"><strong>' . htmlspecialchars($row['University_Name']) . '</strong></a>';
} elseif ($university_name == 'stanford university') {
    echo '<h5 class="text-center uni-title">';
    echo '<a href="universities/stanford.html" class="university-link"><strong>' . htmlspecialchars($row['University_Name']) . '</strong></a>';
} else {
    echo '<h5 class="text-center uni-title"><strong>' . htmlspecialchars($row['University_Name']) . '</strong></h5>';
}
                                        ?>
                                        </h5>
<p><strong style="font-family: 'Open Sans', sans-serif; font-size: 17px">Location:</strong> <span style="font-family: 'Times New Roman', serif; font-size: 19px"><?php echo htmlspecialchars($row['Location']); ?></span></p>
<p><strong style="font-family: 'Open Sans', sans-serif; font-size: 17px">Program Types:</strong> <span style="font-family: 'Times New Roman', serif; font-size: 19px"><?php echo htmlspecialchars($row['Program_Type']); ?></span></p>
<p><strong style="font-family: 'Open Sans', sans-serif; font-size: 17px">Tuition Fee Type:</strong> <span style="font-family: 'Times New Roman', serif; font-size: 19px"><?php echo htmlspecialchars($row['Tuition_Fee_Type']); ?></span></p>    
<p><strong style="font-family: 'Open Sans', sans-serif; font-size: 17px">World Rank:</strong> <span style="font-family: 'Times New Roman', serif; font-size: 19px"><?php echo htmlspecialchars($row['World_Rank']); ?></span></p>  
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mt-2 text-center" id="search-alert" role="alert">
                        No universities found for USA.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php
// Close the database connection
$conn->close();
?>


<!-- Notification Banner -->
<div id="notificationBanner">
	<div class="banner-header">
	  Access Required! 🔒
	  <span class="banner-controls" style="float: right; cursor: pointer;">
		<span id="minimizeBtn" title="Minimize" style="margin-right: 10px;">–</span>
		<span id="expandBtn" title="Expand" style="margin-right: 10px;">□</span>
		<span id="closeBtn">&#x274C;</span>
	  </span>
	</div>
	<div class="banner-body">
	  You need to log in or sign up to access this feature, thank you!
	</div>
	<div class="banner-footer">
	  <button id="cancelBtn">Cancel</button>
	  <button id="okBtn">Okay</button>
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




    <!-- JavaScript -->
    <script src="js/bootstrap.bundle.min.js">

    </script>

<style>
@keyframes typing {
  0% {
    width: 0ch;
  }
  40% {
    width: 30ch; /* Adjust this to match your actual text length */
  }
  60% {
    width: 30ch;
  }
  100% {
    width: 0ch;
  }

}

@keyframes flipIn {
  0% {
    transform: rotateX(-90deg);
    opacity: 0;
  }
  100% {
    transform: rotateX(0deg);
    opacity: 1;
  }
}
.banner_content {
  text-align: center !important;
}
</style>

</body>
</html>
