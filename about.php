<?php
session_start();
$isLoggedIn = isset($_SESSION['UserID']);
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
	<link rel="stylesheet"  href="css/about.css">
	<link rel="stylesheet"  href="css/style.css">
	<link rel="stylesheet"  href="css/responsive.css">
	<link rel="stylesheet"  href="css/navbar.css">

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
    <!-- <li><a class="dreamcountry-option" href="desh4.html"><b>Italy</b></a></li> -->
    <li><a class="dreamcountry-option" href="desh5.html"><b>United States of America</b></a></li>
    <li><hr class="dropdown-divider"></li>
  </ul>
</li>
  
		  <li class="nav-item">
			<a class="nav-link <?php echo $isLoggedIn ? 'unlocked' : 'locked'; ?>" href="dashboard.php" id="ieltsBtn">IELTS Prep</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link <?php echo $isLoggedIn ? 'unlocked' : 'locked'; ?>" href="<?php echo $isLoggedIn ? 'offerletter.php' : '#'; ?>" id="offerLetterBtn">Get Offer Letter</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="contact.php">Contacts</a>
		  </li>
		  

				<li><hr class="dropdown-divider"></li>
			</ul>
		<!-- Search Form -->
		<div id="suggestions" class="suggestions"></div>
		<form class="d-flex" role="search" onsubmit="redirectCountry(event)">
			<input id="searchInput" class="search-input" type="search" placeholder="Search for country" aria-label="Search">
			<button class="search-button" type="submit">
			  <img src="img/search.png" alt="Search" class="search-icon">
			</button>
		  </form>		  
	  </div>
	</div>
  </nav>
 <!-- Navbar HTML part end  -->

 <?php include('second_navbar.php'); ?>

 <?php include('profile.php'); ?>


<!-- banner part start -->
<section class="about-banner py-5">
	<div class="container">
	  <div class="row align-items-center">
		
		<!-- Text Content -->
		<div class="col-lg-7 col-md-12">
		  <h2 class="mb-4"><b>About Us</b></h2>
		  <p>EdAcademix is a smart, student-centric web platform built to streamline the journey of Bangladeshi students planning to study abroad. From selecting your dream country to preparing for IELTS, discovering scholarships, and receiving automated university offer letters — we bring it all under one intuitive, AI-powered interface. Our system eliminates the complexity of foreign admission processes by integrating data-driven tools and personalized recommendations, helping students make confident, well-informed decisions.</p>
		  
		  <h4 class="mt-4"><b>🌍 Our Vision</b></h4>
		  <p>To become Bangladesh’s leading digital gateway for global education — simplifying study abroad planning, empowering students with information, and connecting them with the right opportunities.</p>
  
		  <h4 class="mt-4"><b>🎯 Our Mission</b></h4>
		  <ul>
			<li>To democratize access to international education resources.</li>
			<li>To offer personalized guidance through AI and automation.</li>
			<li>To support students end-to-end, from exploration to application.</li>
			<li>To ensure transparency, accuracy, and simplicity in every step.</li>
		  </ul>
  
		  <h4 class="mt-4"><b>🥇 Our Core Goals</b></h4>
		  <ul>
			<li>🧭 <b>Simplify Country & University Selection</b> — Using smart filters and data-mapping, students can explore institutions globally based on fields of study, cost, and requirements.</li>
			<li>🎓 <b>Centralize University Information</b> — One platform to compare programs, tuition fees, admission details, and scholarship opportunities.</li>
			<li>🤖 <b>Use AI for Smarter Counseling</b> — Chatbots assist users in choosing the right university, and in IELTS practice, offering 24/7 intelligent support.</li>
			<li>📝 <b>Automate Application Processes</b> — With pre-filled forms and system-generated offer letters, we eliminate repetitive paperwork.</li>
			<li>💬 <b>Prepare for IELTS with Confidence</b> — Integrated tools and a specialized IELTS chatbot give users structured support for test prep.</li>
			<li>🚀 <b>Build a Scalable, Future-Ready Platform</b> — Modular design ready for regional expansion, counselor integration, and real-time data analytics.</li>
		  </ul>
		</div>
  
	  </div>
	</div>
  </section>
<!-- banner part end -->

  <!-- Chatbot Icon -->
<!-- <a href="https://www.chatbase.co/chatbot-iframe/4tWu1xXCpXyQ1QzEPtE4p" class="chatbot-icon" title="Chat with us">
	<img src="img/CB.png" alt="Chatbot"></a> -->

	<?php if ($isLoggedIn): ?>

    <!-- Chatbase Embedded Chatbot Script -->
    <script>
        (function () {
            if (!window.chatbase || window.chatbase("getState") !== "initialized") {
                window.chatbase = (...arguments) => {
                    if (!window.chatbase.q) { window.chatbase.q = []; }
                    window.chatbase.q.push(arguments);
                };
                window.chatbase = new Proxy(window.chatbase, {
                    get(target, prop) {
                        if (prop === "q") return target.q;
                        return (...args) => target(prop, ...args);
                    }
                });

                const onLoad = function () {
                    const script = document.createElement("script");
                    script.src = "https://www.chatbase.co/embed.min.js";
                    script.id = "4tWu1xXCpXyQ1QzEPtE4p";
                    script.setAttribute("chatbotId", "4tWu1xXCpXyQ1QzEPtE4p");
                    script.setAttribute("domain", "www.chatbase.co");
                    document.body.appendChild(script);
                };

                if (document.readyState === "complete") {
                    onLoad();
                } else {
                    window.addEventListener("load", onLoad);
                }
            }
        })();
    </script>

<?php endif; ?>


  <div id="faqOverlay">
	<div id="faqPopup">
	  <i class="fas fa-times" id="closeFaqPanel"></i>
  
	  <div class="faq-header-bar">
		<img src="img/EDLOGO.png" alt="EdAcademix Logo" class="faq-logo" />
		<h3><b>Here are some frequently asked questionnaires about EdAcademix!</b></h3>
	  </div>
  
	  <div class="faq-item">
		<button class="faq-question">
		  1. What is EdAcademix? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>EdAcademix is an all-in-one educational platform designed to help Bangladeshi students plan their higher studies abroad. It offers country selection tools, university databases, scholarship info, IELTS prep, and applying for offer letter support — all in one place.</p>
		</div>
	  </div>
  
	  <div class="faq-item">
		<button class="faq-question">
		  2. How do I choose my dream country for study? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>You can use our Dream Country Selection tool to filter countries based on your budget, field of interest, tuition fees, and scholarship opportunities.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			3.Can I search for universities based on my preferred subject or tuition budget? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>Yes! Our Advanced Search & Filter System lets you explore universities based on program type, field of study, tuition fee range, and more.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			4.How reliable is the information in university profiles? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>Each University Profile is curated from official sources and updated regularly to ensure students get accurate details on programs, costs, location, and admission criteria.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			5.What types of scholarships are listed on EdAcademix? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>We provide details on both government and university scholarships, including eligibility, deadlines, and how to apply — filtered by country and program.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			6.How does the automated offer letter feature work? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>Our Smart Apply system generates a pre-filled application form based on your profile and preferences, which you can submit to get your University Offer Letter with minimal effort.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			7.Is IELTS preparation available for free? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>Yes! Our IELTS Preparation Hub offers free access to practice tests, vocabulary training, writing tips, and more, powered by a specialized chatbot for instant help.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			8.How do the chatbots work on EdAcademix? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>There are two AI-driven bots:

			One is your Study Abroad Advisor (powered by GPT), which helps you decide on country, course, and university based on your profile. The other is the IELTS Assistant, which offers keyword-based help for your IELTS prep journey.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			9.Do I need to sign up to use all features? <span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>Yes, you must log in or sign up to access personalized tools like offer letter automation, chatbot conversations, and IELTS preparation resources</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			10.Can I talk to a real counselor through EdAcademix?<span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>While the current version offers AI support, we plan to integrate live counselor chat in the future for personalized guidance.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			11.Is EdAcademix suitable for students outside Bangladesh?<span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>While our primary focus is Bangladeshi students, international students can also explore universities and scholarship opportunities through the platform.</p>
		</div>
	  </div>
	  <div class="faq-item">
		<button class="faq-question">
			12.How often is the platform updated?<span class="icon">+</span>
		</button>
		<div class="faq-answer">
		  <p>Our backend ensures frequent data updates on university info, scholarship deadlines, and admission policies to keep users well-informed.</p>
		</div>
	  </div>

  
	  <!-- Repeat the same pattern for all FAQ items -->
	</div>
  </div>
  
  	  <!-- Notification Banner -->
		<div id="notificationBanner">
			<div class="banner-header">
			  Access Required!
			  <span class="banner-controls" style="float: right; cursor: pointer;">
				<span id="closeBtn">X</span>
			  </span>
			</div>
		  
			<div class="banner-body1">
			  <img src="img/error.png" alt="Warning" class="warning-icon">
			  <span>Currently, you cannot access this feature.</span>
			</div>
		  
			<div class="banner-body">
			  Please login or sign up to get access of this feature.
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
						<li><a href="about.html">About EdAcademix</a></li>
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
  
  

<!-- js link start -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldZa5p5fC2G6KZs4Ks8lg2a44cId4jTkz76PKaX" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-cn7l7gDp0eyIG6mvEH16lAaqjZfGnirKwrjvJoaAqh6Ez4eZ0CtFLR69Zo5I2sT" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/custom.js"></script>
<!-- js link end -->

 <script>
  // ✅ Detect login from PHP by checking if the buttons have class "unlocked"
  function userIsLoggedIn() {
    return document.querySelectorAll('.unlocked').length > 0;
  }

  const slideBtn = document.getElementById('slideBtn'); 
  const closeSlideBtn = document.getElementById('closeSlideBtn');
  const slidePanel = document.getElementById('slidePanel');
  const chatbotIcon = document.querySelector('.chatbot-icon');

  if (chatbotIcon) {
    chatbotIcon.addEventListener('click', (e) => {
      if (!userIsLoggedIn()) {
        e.preventDefault();
        pendingLink = null;
        showBanner();
        return;
      }
      slidePanel.classList.add('open');
    });
  }

  // Open sliding panel
  if (slideBtn) {
    slideBtn.addEventListener('click', () => {
      slidePanel.classList.add('open');
    });
  }

  // Close sliding panel
  if (closeSlideBtn) {
    closeSlideBtn.addEventListener('click', () => {
      slidePanel.classList.remove('open');
    });
  }

  // Dropdown handlers
  function toggleCountryList(event) {
    event.preventDefault();
    const countryList = document.getElementById('country-list');
    countryList.classList.toggle('d-none');
  }

  function toggleProgramList(event) {
    event.preventDefault();
    const programList = document.getElementById('program-list');
    programList.classList.toggle('d-none');
  }

  function selectOption(type, value) {
    if (type === 'country') {
      document.getElementById('selected-country-label').textContent = value;
    } else if (type === 'program') {
      document.getElementById('selected-program-label').textContent = value;
    }
  }

  const faqsOption = document.getElementById('faqsOption');
  const faqOverlay = document.getElementById('faqOverlay');
  const faqPopup = document.getElementById('faqPopup');
  const carousel = document.getElementById('carouselExampleIndicators');
  const closeFaqPanel = document.getElementById('closeFaqPanel');

  function positionFaqOverBanner() {
    const rect = carousel.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    faqPopup.style.top = `${rect.top + scrollTop + 20}px`;
  }

  function applyBlurToBackground() {
    document.querySelectorAll('body > *:not(#faqOverlay)').forEach(el => {
      el.style.filter = 'blur(4px)';
      el.style.transition = 'filter 0.3s ease';
    });
  }

  function removeBlurFromBackground() {
    document.querySelectorAll('body > *:not(#faqOverlay)').forEach(el => {
      el.style.filter = '';
    });
  }

  function freezePage() {
    document.body.style.overflow = 'hidden';
    document.body.style.pointerEvents = 'none';
    faqOverlay.style.pointerEvents = 'auto';
    applyBlurToBackground();
  }

  function unfreezePage() {
    document.body.style.overflow = '';
    document.body.style.pointerEvents = '';
    removeBlurFromBackground();
  }

  faqsOption.addEventListener('click', (e) => {
    e.preventDefault();
    positionFaqOverBanner();
    faqOverlay.style.display = 'block';
    void faqPopup.offsetWidth;
    faqOverlay.classList.add('open');
    freezePage();
  });

  faqOverlay.addEventListener('click', (e) => {
    if (e.target === faqOverlay) {
      faqOverlay.classList.remove('open');
      unfreezePage();
      setTimeout(() => {
        faqOverlay.style.display = 'none';
      }, 500);
    }
  });

  closeFaqPanel.addEventListener('click', () => {
    faqOverlay.classList.remove('open');
    unfreezePage();
    setTimeout(() => {
      faqOverlay.style.display = 'none';
    }, 500);
  });

  window.addEventListener('resize', () => {
    if (faqOverlay.classList.contains('open')) {
      positionFaqOverBanner();
    }
  });

  // ✅ FAQ Question-Answer Toggle with auto-close
  document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function () {
      const allAnswers = document.querySelectorAll('.faq-answer');
      const answer = this.nextElementSibling;

      // Close all answers first
      allAnswers.forEach(ans => {
        ans.style.display = 'none';
      });

      // Toggle current answer (open if was closed)
      if (answer && (answer.style.display === 'none' || getComputedStyle(answer).display === 'none')) {
        answer.style.display = 'block';
      }
    });
  });

  const banner = document.getElementById('notificationBanner');
  const okBtn = document.getElementById('okBtn');
  const cancelBtn = document.getElementById('cancelBtn');
  const closeBtn = document.getElementById('closeBtn');
  const minimizeBtn = document.getElementById('minimizeBtn');
  const expandBtn = document.getElementById('expandBtn');
  const body = banner.querySelector('.banner-body');
  const footer = banner.querySelector('.banner-footer');

  let pendingLink = null;
  let isMinimized = false;
  let isExpanded = false;

  function disableOtherButtons() {
    const clickableElements = document.querySelectorAll('a, button, [role="button"], [data-toggle], [onclick], .dropdown-toggle, .dropdown-menu');
    clickableElements.forEach(el => {
      if (!banner.contains(el)) {
        el.dataset.originalPointerEvents = el.style.pointerEvents;
        el.style.pointerEvents = 'none';
        el.dataset.originalOpacity = el.style.opacity;
        el.style.opacity = '0.5';
      }
    });
  }

  function enableOtherButtons() {
    const clickableElements = document.querySelectorAll('a, button, [role="button"], [data-toggle], [onclick], .dropdown-toggle, .dropdown-menu');
    clickableElements.forEach(el => {
      if (el.dataset.originalPointerEvents !== undefined) {
        el.style.pointerEvents = el.dataset.originalPointerEvents;
        delete el.dataset.originalPointerEvents;
      }
      if (el.dataset.originalOpacity !== undefined) {
        el.style.opacity = el.dataset.originalOpacity;
        delete el.dataset.originalOpacity;
      }
    });
  }

  function applyBlurToBackgroundForBanner() {
    document.querySelectorAll('body > *:not(#notificationBanner)').forEach(el => {
      el.style.filter = 'blur(4px)';
      el.style.transition = 'filter 0.3s ease';
    });
  }

  function removeBlurFromBackgroundForBanner() {
    document.querySelectorAll('body > *:not(#notificationBanner)').forEach(el => {
      el.style.filter = '';
    });
  }

  function showBanner() {
    banner.style.display = 'block';
    disableOtherButtons();
    applyBlurToBackgroundForBanner();
  }

  function hideBanner() {
    banner.style.display = 'none';
    enableOtherButtons();
    removeBlurFromBackgroundForBanner();
  }

  document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function (event) {
      const linkText = this.textContent.trim();
      if (!userIsLoggedIn() && (linkText.includes('IELTS Prep') || linkText.includes('Get Offer Letter'))) {
        event.preventDefault();
        pendingLink = this.href;
        showBanner();
      }
    });
  });

  okBtn.addEventListener('click', () => {
    hideBanner();
    pendingLink = null;
  });

  cancelBtn.addEventListener('click', () => {
    hideBanner();
    pendingLink = null;
  });

  closeBtn.addEventListener('click', () => {
    hideBanner();
    pendingLink = null;
  });

  minimizeBtn.addEventListener('click', () => {
    if (!isMinimized) {
      body.style.display = 'none';
      footer.style.display = 'none';
      minimizeBtn.textContent = '+';
      isMinimized = true;
    } else {
      body.style.display = 'block';
      footer.style.display = 'block';
      minimizeBtn.textContent = '–';
      isMinimized = false;
    }
  });

  expandBtn.addEventListener('click', () => {
    if (!isExpanded) {
      banner.style.width = '100%';
      banner.style.height = '100%';
      banner.style.top = '0';
      banner.style.left = '0';
      banner.style.transform = 'none';
      expandBtn.textContent = '❐';
      isExpanded = true;
    } else {
      banner.style.width = '400px';
      banner.style.height = 'auto';
      banner.style.top = '50%';
      banner.style.left = '50%';
      banner.style.transform = 'translate(-50%, -50%)';
      expandBtn.textContent = '□';
      isExpanded = false;
    }
  });

  function redirectCountry(event) {
    event.preventDefault();
    const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();
    if (searchInput) {
      const countryMap = {
        "australia": "desh.html",
        "england": "desh2.html",
        "germany": "desh3.html",
        "usa": "desh5.html"
      };
      const url = countryMap[searchInput];
      if (url) {
        window.location.href = url;
      } else {
        alert("Country not found!");
      }
    } else {
      alert("Please enter a country name.");
    }
  }


</script>

</body>
</html>