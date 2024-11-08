<?php
include '../connection.php';

if (isset($_GET['Alumni_ID_Number'])) {
    $id = $_GET['Alumni_ID_Number'];

    $stmt = $con->prepare("SELECT 
                            a.Alumni_ID_Number,
                            a.Student_Number, 
                            a.Last_Name, 
                            a.First_Name, 
                            a.Middle_Name, 
                            a.College, 
                            a.Department, 
                            a.Section, 
                            a.Year_Graduated, 
                            a.Contact_Number, 
                            a.Personal_Email, 
                            ed.Employment,
                            ed.Employment_Status, 
                            ed.Present_Occupation, 
                            ed.Name_of_Employer, 
                            ed.Address_of_Employer, 
                            ed.Number_of_Years_in_Present_Employer, 
                            ed.Type_of_Employer, 
                            ed.Major_Line_of_Business
                          FROM `2024-2025` a 
                          LEFT JOIN `2024-2025_ed` ed ON a.Alumni_ID_Number = ed.Alumni_ID_Number 
                          WHERE a.Alumni_ID_Number = :id");

    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();

    $alumni = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$alumni) {
        header('Location: alumni_list.php');
        exit;
    }
} else {
    header('Location: alumni_list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alumni</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/reg.css">
</head>

<body>
    <div class="container form-container mt-5">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <header class="mb-4">Update Alumni</header>
        <form action="alumni_process.php" method="POST" class="form">
            <input type="hidden" name="alumni_id" value="<?php echo htmlspecialchars($alumni['Alumni_ID_Number'] ?? ''); ?>">

            <div class="mb-3">
                <label for="student-number" class="form-label">Student Number</label>
                <input type="text" id="student-number" name="student_number" class="form-control" value="<?php echo htmlspecialchars($alumni['Student_Number'] ?? ''); ?>" required autocomplete="student-number">
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($alumni['Last_Name'] ?? ''); ?>" required autocomplete="family-name">
                </div>
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($alumni['First_Name'] ?? ''); ?>" required autocomplete="given-name">
                </div>
                <div class="col-md-4">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($alumni['Middle_Name'] ?? ''); ?>" autocomplete="additional-name">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="college" class="form-label">College</label>
                    <select class="form-control" id="college" name="college">
                        <option value="">Select College</option>
                        <option value="CITCS" <?php echo ($alumni['College'] == 'CITCS') ? 'selected' : ''; ?>>CITCS</option>
                        <option value="CAS" <?php echo ($alumni['College'] == 'CAS') ? 'selected' : ''; ?>>CAS</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="department" class="form-label">Department</label>
                    <select class="form-control" id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="PSYCH" <?php echo ($alumni['Department'] == 'PSYCH') ? 'selected' : ''; ?>>PSYCH</option>
                        <!-- Add other departments as needed -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="section" class="form-label">Section</label>
                    <select class="form-control" id="section" name="section" required>
                        <option value="">Select Section</option>
                        <option value="PS4B" <?php echo ($alumni['Section'] == 'PS4B') ? 'selected' : ''; ?>>PS4B</option>
                        <!-- Add other sections as needed -->
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="year_graduated" class="form-label">Year Graduated</label>
                    <input type="text" class="form-control" id="year_graduated" name="year_graduated" value="<?php echo htmlspecialchars($alumni['Year_Graduated'] ?? ''); ?>" required autocomplete="bday-year">
                </div>
                <div class="col-md-4">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($alumni['Contact_Number'] ?? ''); ?>" required autocomplete="tel">
                </div>
                <div class="col-md-4">
                    <label for="personal_email" class="form-label">Personal Email</label>
                    <input type="email" class="form-control" id="personal_email" name="personal_email" value="<?php echo htmlspecialchars($alumni['Personal_Email'] ?? ''); ?>" required autocomplete="email">
                </div>
            </div>

            <div class="mb-3 row align-items-center">
                <div class="col-md-6">
                    <label for="employment" class="form-label">Employment</label>
                    <select id="employment" name="employment" class="form-control" required>
                        <option value="">Select Employment</option>
                        <option value="Employed" <?php echo ($alumni['Employment'] == 'Employed') ? 'selected' : ''; ?>>Employed</option>
                        <option value="Self-employed" <?php echo ($alumni['Employment'] == 'Self-employed') ? 'selected' : ''; ?>>Self-employed</option>
                        <option value="Actively looking for a job" <?php echo ($alumni['Employment'] == 'Actively looking for a job') ? 'selected' : ''; ?>>Actively Looking for a Job</option>
                        <option value="Never been employed" <?php echo ($alumni['Employment'] == 'Never been employed') ? 'selected' : ''; ?>>Never Been Employed</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="employment-status" class="form-label" id="employment-status-label">Employment Status</label>
                    <select id="employment-status" name="employment_status" class="form-control" required>
                        <option value="">Select Employment Status</option>
                        <option value="Regular/Permanent" <?php echo ($alumni['Employment_Status'] == 'Regular/Permanent') ? 'selected' : ''; ?>>Regular/Permanent</option>
                        <option value="Contractual" <?php echo ($alumni['Employment_Status'] == 'Contractual') ? 'selected' : ''; ?>>Contractual</option>
                        <option value="Temporary" <?php echo ($alumni['Employment_Status'] == 'Temporary') ? 'selected' : ''; ?>>Temporary</option>
                        <option value="Part-time (seeking full-time)" <?php echo ($alumni['Employment_Status'] == 'Part-time (seeking full-time)') ? 'selected' : ''; ?>>Part-time (seeking full-time)</option>
                        <option value="Part-time (but not seeking full-time)" <?php echo ($alumni['Employment_Status'] == 'Part-time (but not seeking full-time)') ? 'selected' : ''; ?>>Part-time (but not seeking full-time)</option>
                        <option value="Other" <?php echo ($alumni['Employment_Status'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div id="employmentFields" style="display: <?php echo ($alumni['Employment'] == 'Employed') ? 'block' : 'none'; ?>;">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="present_occupation" class="form-label">Present Occupation</label>
                        <input type="text" class="form-control" id="present_occupation" name="present_occupation" value="<?php echo htmlspecialchars($alumni['Present_Occupation'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="name_of_employer" class="form-label">Name of Employer</label>
                        <input type="text" class="form-control" id="name_of_employer" name="name_of_employer" value="<?php echo htmlspecialchars($alumni['Name_of_Employer'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="address_of_employer" class="form-label">Address of Employer</label>
                        <input type="text" class="form-control" id="address_of_employer" name="address_of_employer" value="<?php echo htmlspecialchars($alumni['Address_of_Employer'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="number_of_years_in_present_employer" class="form-label">Years in Present Employer</label>
                        <input type="number" class="form-control" id="number_of_years_in_present_employer" name="number_of_years_in_present_employer" value="<?php echo htmlspecialchars($alumni['Number_of_Years_in_Present_Employer'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="type_of_employer" class="form-label">Type of Employer</label>
                        <input type="text" class="form-control" id="type_of_employer" name="type_of_employer" value="<?php echo htmlspecialchars($alumni['Type_of_Employer'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="major_line_of_business" class="form-label">Major Line of Business</label>
                        <input type="text" class="form-control" id="major_line_of_business" name="major_line_of_business" value="<?php echo htmlspecialchars($alumni['Major_Line_of_Business'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="history.back()">Back</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const collegeSelect = document.getElementById('college');
            const departmentSelect = document.getElementById('department');
            const sectionSelect = document.getElementById('section');
            const employmentSelect = document.getElementById('employment');
            const employmentStatusLabel = document.getElementById('employment-status-label');
            const employmentStatusSelect = document.getElementById('employment-status');
            const employmentFields = document.getElementById('employmentFields');

            function updateDepartments() {
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                let departments = [];

                if (collegeSelect.value === 'CITCS') {
                    departments = ['BSCS', 'BSIT', 'ACT'];
                } else if (collegeSelect.value === 'CAS') {
                    departments = ['MASCOM', 'PSYCH'];
                }

                departments.forEach(dept => {
                    departmentSelect.innerHTML += `<option value="${dept}" ${dept === "<?php echo htmlspecialchars($alumni['Department'] ?? ''); ?>" ? 'selected' : ''}>${dept}</option>`;
                });

                updateSections(); // Update sections after departments are updated
            }

            function updateSections() {
                sectionSelect.innerHTML = '<option value="">Select Section</option>';
                let sections = [];

                if (departmentSelect.value === 'BSCS') {
                    sections = ['CS4A', 'CS4B', 'CS4C', 'CS4D'];
                } else if (departmentSelect.value === 'BSIT') {
                    sections = ['IT4A', 'IT4B', 'IT4C', 'IT4D'];
                } else if (departmentSelect.value === 'ACT') {
                    sections = ['ACT4A', 'ACT4B', 'ACT4C', 'ACT4D'];
                } else if (departmentSelect.value === 'MASCOM') {
                    sections = ['MC4A', 'MC4B', 'MC4C', 'MC4D'];
                } else if (departmentSelect.value === 'PSYCH') {
                    sections = ['PS4A', 'PS4B', 'PS4C', 'PS4D'];
                }

                sections.forEach(section => {
                    sectionSelect.innerHTML += `<option value="${section}" ${section === "<?php echo htmlspecialchars($alumni['Section'] ?? ''); ?>" ? 'selected' : ''}>${section}</option>`;
                });
            }

            // Set initial values and update fields
            collegeSelect.value = "<?php echo htmlspecialchars($alumni['College'] ?? ''); ?>";
            updateDepartments();
            departmentSelect.value = "<?php echo htmlspecialchars($alumni['Department'] ?? ''); ?>";
            updateSections();
            sectionSelect.value = "<?php echo htmlspecialchars($alumni['Section'] ?? ''); ?>";

            // Event listeners
            collegeSelect.addEventListener('change', updateDepartments);
            departmentSelect.addEventListener('change', updateSections);

            function toggleEmploymentFields() {
                employmentFields.style.display = (employmentSelect.value === 'Employed') ? 'block' : 'none';
            }

            // Initialize display state
            toggleEmploymentFields();

            // Event listener for employment select
            employmentSelect.addEventListener('change', toggleEmploymentFields);
        });
    </script>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>