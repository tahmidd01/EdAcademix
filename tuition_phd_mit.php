<?php
// Include the database connection file
include('connect.php');

// Define the SQL query
$query = "
    SELECT 
        p.name AS Program_Name,
        f.name AS Field_Of_Study,
        tf.duration AS Duration,
        tf.semesters_or_trimesters AS Semesters_Or_Trimesters,
        tf.tuition_fee AS USD$,
        tf.semesters_or_trimesters_fee AS Semester_Or_Trimester_Fee,
        tf.fee_category AS Tuition_Fee_Category
    FROM 
        tuitionfee tf
    INNER JOIN 
        Program p ON tf.program_id = p.program_id
    INNER JOIN 
        FieldOfStudy f ON tf.field_id = f.field_id
    INNER JOIN 
        University u ON tf.university_id = u.university_id
    INNER JOIN 
        countrylist c ON u.country_id = c.country_id
    WHERE 
        c.name = 'United States of America'
        AND u.name = 'Massachusetts Institute of Technology'
        AND p.name = 'PhD Programs'
    ORDER BY 
        p.name, f.name
";

// Execute the query
$result = $conn->query($query);
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
  /*header part css start*/
  header{
    background-color:#0a0a0a;
    padding: 10px;
  }
  .welcome p{
    color: white;
    font-size: 1.2rem;
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
  height: 50px;
  width: 100px; /* Adjust if  needed */
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

/* .pop-up{
  height: 685px;
  width: 2000px;
  background-color:#0C2132 ;
  position: absolute;
  top: 6%;
  left: -500%;
  transform: translate(-50%, -50%);
  transform: scale(0);
  transition: all linear 0.3s;
}
.dropdown-item:focus .pop-up{
  transform: scale(1);
  transition: all linear 0.3s;
} */
 /*navbar css part end*/


/* banner css part start */
 #banner {
  position: relative;
  height: 70vh; /* Full viewport height */
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px;
  background-color: #0C2132;
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
   /* footer css part end */

   /* 2nd navbart css part start */
   .navbar-nav .nav-item2 .nav-link2{
    color:white;
    padding-right: 15px;
   }
.navbar-nav .nav-item2 .nav-link2:hover{
  color:#007BFF;
 }
 /* Reset default styles */
body {
  margin: 0;
  font-weight: bold;
  font-family: Arial, sans-serif;
}

.navbar2 {
  display: flex;
  justify-content: space-around;
  align-items: center;
  background-color:black;
  color: white;
  padding: 10px 0;
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
  justify-content: center;
  align-items: center;
  padding-right: 20px;
}

/* Custom dropdown styling */
.custom-dropdown {
  display: none; /* Hidden by default */
  width: 800px; /* Set a custom width */
  padding: 15px; /* Add padding for better readability */
  max-height: 400px; /* Optional: Add a maximum height */
  overflow-y: auto; /* Enable vertical scrolling if content is too long */
  position: absolute;
  left: 0; /* Position the dropdown */
  z-index: 1050; /* Ensure it appears above other elements */
  background-color: #f8f9fa; /* Light background color */
  border: 1px solid #ddd; /* Border for the dropdown */
  border-radius: 5px; /* Rounded corners */
}

/* When the dropdown is clicked, show it */
.nav-item2.dropdown.show .custom-dropdown {
  display: flex; /* Show the dropdown */
}

/* Disable hover-triggered dropdown */
.nav-item2.dropdown:hover .custom-dropdown {
  display: none !important; /* Ensure no hover behavior */
}

/* Optional styling for text */
.dropdown-item-text {
  font-size: 15px;
  line-height: 1.5; /* Better readability */
}
   /* 2nd navbart css part end */
   .about{
    background-color: #0C2132;
   }

   .about_content{
    padding: 20px;
    margin: 20px;
    color: white;
    border-radius: 10px;
    overflow: auto;
   }
    </style>
</head>
<body>
    <!-- header part html start -->
    <header>
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="welcome">
                            <p>Massachusetts Institute of Technology</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="header_text">
                            <p>EdAcademix.Co</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header part html end -->
    
    <!-- Navbar main HTML part start -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="#">
            <img src="universities/img/mitlogo.jpeg">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.html">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="desh5.html">Back to Country</a>
              </li>
              <li class="nav-item dropdown">
                
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Academics
                  </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="pro_academic_mit.php"><b>Programs</b></a></li>
                  <li><a class="dropdown-item" href="fee_academic_mit.php"><b>Tution Fees</b></a></li>
                  <li><a class="dropdown-item" href="scholar_academic_mit.php"><b>Scholarship</b></a></li>
                  <li><hr class="dropdown-divider"></li>
                 
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="req_mit.php">Requirements</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../anucontact.html">Contact</a>
              </li>
            </ul>
            <form class="d-flex" role="search" method="GET" action="search_anu.php">
    <input class="search-input" type="search" name="search_query" placeholder="Search for programs or subjects..." aria-label="Search">
    <button class="search-button" type="submit">Search</button>
</form>  	  
          </div>
        </div>
      </nav>
<!-- navbar 2nd html start  -->
<nav class="navbar2 navbar-expand-lg">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item2 dropdown">
          <a class="nav-link2 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Programs Offered
          </a>
          <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="pro_offer_undergrate_mit.php"><b>Undergraduate</b></a></li>
            <li><a class="dropdown-item" href="pro_offer_graduate_mit.php"><b>Graduate</b></a></li>
            <li><a class="dropdown-item" href="pro_offer_phd_mit.php"><b>PhD</b></a></li>
            <li><hr class="dropdown-divider"></li>
          </ul>
        </li>
        <li class="nav-item2 dropdown">
          <a class="nav-link2 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tuition Fees
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="tuition_undergrate_mit.php"><b>Undergraduate</b></a></li>
            <li><a class="dropdown-item" href="tuition_graduate_mit.php"><b>Graduate</b></a></li>
            <li><a class="dropdown-item" href="tuition_phd_mit.php"><b>PhD</b></a></li>
            <li><hr class="dropdown-divider"></li>
          </ul>
        </li>
        <li class="nav-item2">
          <a class="nav-link2 active" aria-current="page" href="fields_mit.php">Field Of Study</a>
        </li>
      </ul> 
    </div>
  </div>
</nav>
<!-- navbar 2nd html end -->

<div id="banner">
    <div class="banner_overlay">
        <div class="container mt-5">
            <div class="banner_content">
                <h1 class="text-center" style="color: white;"><b>PhD Fees at Massachusetts Institute of Technology</b></h1><br><br>
                <div class="table-responsive">
                    <table class="table table-bordered" style="background-color: #0C2132; color: white; border: 5px solid black;">
                        <thead>
                            <tr>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">Program Name</th>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">Field of Study</th>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">Duration</th>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">Semesters/Trimesters</th>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">USD$</th>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">Semester/Trimester Fees</th>
                                <th style="background-color: #0C2132; color: white; border: 5px solid black;">Tuition Fee Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($row = $result->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['Program_Name']); ?></td>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['Field_Of_Study']); ?></td>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['Duration']); ?></td>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['Semesters_Or_Trimesters']); ?></td>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['USD$']); ?></td>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['Semester_Or_Trimester_Fee']); ?></td>
                                    <td style="color: white; background-color: #0C2132; border: 5px solid black;"><?php echo htmlspecialchars($row['Tuition_Fee_Category']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- footer2 html part start  -->
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
						<li><a href="../about.html">About EdAcademix</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>