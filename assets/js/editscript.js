document.addEventListener('DOMContentLoaded', function () {
    const collegeSelect = document.getElementById('college');
    const departmentSelect = document.getElementById('department');
    const sectionSelect = document.getElementById('section');

    const currentCollege = "<?php echo htmlspecialchars($alumni['College']); ?>";
    const currentDepartment = "<?php echo htmlspecialchars($alumni['Department']); ?>";
    const currentSection = "<?php echo htmlspecialchars($alumni['Section']); ?>";

    function updateDepartments() {
        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        let departments = [];

        if (collegeSelect.value === 'CITCS') {
            departments = ['BSCS', 'BSIT', 'ACT'];
        } else if (collegeSelect.value === 'CAS') {
            departments = ['MASCOM', 'PSYCH'];
        }

        departments.forEach(dept => {
            const selected = dept === currentDepartment ? 'selected' : '';
            departmentSelect.innerHTML += `<option value="${dept}" ${selected}>${dept}</option>`;
        });

        updateSections(); // Call to update sections after departments are populated
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
            const selected = section === currentSection ? 'selected' : '';
            sectionSelect.innerHTML += `<option value="${section}" ${selected}>${section}</option>`;
        });
    }

    // Set initial values and update dropdowns
    collegeSelect.value = currentCollege;
    updateDepartments();
    departmentSelect.value = currentDepartment;
    updateSections();
    sectionSelect.value = currentSection;

    collegeSelect.addEventListener('change', updateDepartments);
    departmentSelect.addEventListener('change', updateSections);



    function toggleEmploymentFields() {
        const employmentStatus = document.getElementById('employment-status');
        const employmentFields = document.getElementById('employmentFields');

        if (employmentStatus && employmentFields) {
            employmentFields.style.display = (employmentStatus.value === 'employed') ? 'block' : 'none';
        }
    }

    toggleEmploymentFields();

    const employmentStatusSelect = document.getElementById('employment-status');
    employmentStatusSelect.addEventListener('change', toggleEmploymentFields);

    collegeSelect.addEventListener('change', updateDepartments);
    departmentSelect.addEventListener('change', updateSections);
});
