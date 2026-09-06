<?php
// Include the database connection file
include('connect.php'); // Ensure the database connection is established

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $country = $_POST['country'];
    $program = $_POST['program'];
    $language_score = $_POST['language_score'];

    // Prepare the SQL query with case-insensitive matching and numeric comparison
    $query = "SELECT u.name AS university_name, 
               u.global_rank, 
               u.location, 
               p.name AS program_name, 
               ar.language_requirement
        FROM University u
        JOIN CountryList c ON u.country_id = c.country_id
        JOIN admissionrequirements ar ON u.university_id = ar.university_id
        JOIN Program p ON ar.program_id = p.program_id
        WHERE LOWER(c.name) = LOWER(?) 
          AND LOWER(p.name) = LOWER(?) 
          AND (
              LOWER(ar.language_requirement) LIKE CONCAT('%', LOWER(?), '%')
              OR CAST(SUBSTRING_INDEX(LOWER(ar.language_requirement), ' ', 1) AS DECIMAL) <= ?
          )
    ";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($query)) {
        // Bind the form data to the query
        $stmt->bind_param("sssd", $country, $program, $language_score, $language_score);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            // Check if there are results
            if ($result->num_rows > 0) {
                // Output the results as cards
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<h5>' . $row['university_name'] . '</h5>';
                    echo '<p><b>Global Rank:</b> ' . $row['global_rank'] . '</p>';
                    echo '<p><b>Location:</b> ' . $row['location'] . '</p>';
                    echo '<p><b>Program:</b> ' . $row['program_name'] . '</p>';
                    echo '<p><b>Language Requirement:</b> ' . $row['language_requirement'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo 'No universities found matching your criteria.';
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
}
?>








<ul class="nav">
			<li class="nav-item dropdown">
			  <a 
				id="dropdownTitle" 
				class="nav-link dropdown-toggle" 
				href="#" 
				role="button" 
				data-bs-toggle="dropdown" 
				aria-expanded="false">
				Preferred University
			  </a>
			  <ul class="dropdown-menu">
				<!-- Dropdown Option: Select Country -->
				<li>
				  <a 
					id="country-label"
					class="dropdown-item dropdown-toggle no-icon-dropdown" 
					href="#" 
					onclick="toggleCountryList(event)">
					<b>Select Country:</b>
					<span id="selected-country-label" class="selected-value"></span>
				  </a>
				  <ul id="country-list" class="nested-dropdown d-none">
					<li><a class="dropdown-item" href="#" onclick="selectOption('country', 'Australia')">Australia</a></li>
					<li><a class="dropdown-item" href="#" onclick="selectOption('country', 'England')">England</a></li>
					<li><a class="dropdown-item" href="#" onclick="selectOption('country', 'Germany')">Germany</a></li>
					<li><a class="dropdown-item" href="#" onclick="selectOption('country', 'Italy')">Italy</a></li>
					<li><a class="dropdown-item" href="#" onclick="selectOption('country', 'United States of America')">United States of America</a></li>
				  </ul>
				</li>
				<!-- Dropdown Option: Select Program -->
				<li>
				  <a 
					id="program-label"
					class="dropdown-item dropdown-toggle no-icon-dropdown" 
					href="#" 
					onclick="toggleProgramList(event)">
					<b>Select Program:</b>
					<span id="selected-program-label" class="selected-value"></span>
				  </a>
				  <ul id="program-list" class="nested-dropdown d-none">
					<li><a class="dropdown-item" href="#" onclick="selectOption('program', 'Undergraduate')">Undergraduate</a></li>
					<li><a class="dropdown-item" href="#" onclick="selectOption('program', 'Graduate')">Graduate</a></li>
					<li><a class="dropdown-item" href="#" onclick="selectOption('program', 'PhD')">PhD</a></li>
				  </ul>
				</li>
				<!-- Dropdown Option: Enter Score -->
				<li class="dropdown-item">
				  <div class="language-score-container">
					<label for="language-score"><b>Language Score:</b></label>
					<input type="text" id="language-score" name="language_score" placeholder="Enter your score" oninput="updateLanguageScoreInput()" />
				  </div>  
				</li>
				<form method="POST" action="Preferred_university.php" style="display: inline;">
					<input type="hidden" name="country" id="country-input" value="">
					<input type="hidden" name="program" id="program-input" value="">
					<input type="hidden" name="language_score" id="language-score-input" value="">
					<button type="button" class="go-button" style="width: 100px;" onclick="submitForm(event)">Go</button>
				</form>				
			  </ul>
			</li>
		  </ul>



          <script>
// JavaScript for Sliding Panel
const slideBtn = document.getElementById('slideBtn'); // Ensure a button with this ID exists
const closeSlideBtn = document.getElementById('closeSlideBtn');
const slidePanel = document.getElementById('slidePanel');

// Open the sliding panel
if (slideBtn) {
  slideBtn.addEventListener('click', () => {
    slidePanel.classList.add('open');
  });
}

// Close the sliding panel
if (closeSlideBtn) {
  closeSlideBtn.addEventListener('click', () => {
    slidePanel.classList.remove('open');
  });
}

// Toggle the visibility of the country list
function toggleCountryList(event) {
    event.preventDefault();
    const countryList = document.getElementById('country-list');
    countryList.classList.toggle('d-none');
}

// Toggle the visibility of the program list
function toggleProgramList(event) {
    event.preventDefault();
    const programList = document.getElementById('program-list');
    programList.classList.toggle('d-none');
}

// Handle selection and update the label inside the dropdown
function selectOption(type, value) {
    if (type === 'country') {
        // Update the country label to the selected option
        const countryLabel = document.getElementById('selected-country-label');
        countryLabel.textContent = value;

        // Set the hidden country input value
        document.getElementById('country-input').value = value;

        // Close the country dropdown
        const countryList = document.getElementById('country-list');
        countryList.classList.add('d-none');
    } else if (type === 'program') {
        // Update the program label to the selected option
        const programLabel = document.getElementById('selected-program-label');
        programLabel.textContent = value;

        // Set the hidden program input value
        document.getElementById('program-input').value = value;

        // Close the program dropdown
        const programList = document.getElementById('program-list');
        programList.classList.add('d-none');
    }
}

// Validate language score and set the hidden input value
document.getElementById('language-score').addEventListener('input', function () {
    const languageScore = this.value;

    // Regex to validate format (example for IELTS or TOEFL with numerical values)
    const regex = /^(IELTS|TOEFL)\s\d+(\.\d{1,2})?$/;

    if (regex.test(languageScore)) {
        // Only set the hidden input if the format is valid
        document.getElementById('language-score-input').value = languageScore;
    } else {
        // Optionally: handle invalid input (e.g., show a message or reset the hidden input)
        document.getElementById('language-score-input').value = ''; // Reset hidden input if invalid
    }
});

// Function to submit the form with dynamic values
function submitForm(event) {
    // Get the selected country, program, and language score from the UI
    var country = document.getElementById("selected-country-label").textContent.trim();
    var program = document.getElementById("selected-program-label").textContent.trim();
    var language_score = document.getElementById("language-score").value.trim();

    // Check if language score is valid (optional step)
    if (language_score === '') {
        alert("Please enter a valid language score.");
        return; // Prevent form submission if the input is invalid
    }

    // Set the hidden inputs with the selected values
    document.getElementById("country-input").value = country;
    document.getElementById("program-input").value = program;
    document.getElementById("language-score-input").value = language_score;

    // Now submit the form (you can either let it submit normally or do it via JavaScript)
    document.querySelector('form').submit();  // This submits the form
}

  </script>