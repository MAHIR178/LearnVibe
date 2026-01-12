function validateStudentForm() {
    let isValid = true;

    const name = document.getElementById('student_name').value.trim();
    const nameErr = document.getElementById('student_name_err');
    if (name === "") {
        nameErr.innerHTML = "Please enter your name";
        isValid = false;
    } else if (name.length < 3) {
        nameErr.innerHTML = "Name must be at least 3 characters";
        isValid = false;
    } else {
        nameErr.innerHTML = "";
    }

    const email = document.getElementById('student_email').value.trim();
    const emailErr = document.getElementById('student_email_err');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === "") {
        emailErr.innerHTML = "Please enter your email";
        isValid = false;
    } else if (!emailPattern.test(email)) {
        emailErr.innerHTML = "Please enter a valid email";
        isValid = false;
    } else {
        emailErr.innerHTML = "";
    }

    const contact = document.getElementById('student_contact_number').value.trim();
    const contactErr = document.getElementById('student_contact_err');
    if (contact === "") {
        contactErr.innerHTML = "Please enter your contact number";
        isValid = false;
    } else if (!/^\d{6,15}$/.test(contact)) {
        contactErr.innerHTML = "Contact must be 6–15 digits";
        isValid = false;
    } else {
        contactErr.innerHTML = "";
    }

    const uni = document.getElementById('student_university_name').value.trim();
    const uniErr = document.getElementById('student_university_err');
    if (uni === "") {
        uniErr.innerHTML = "Please enter your university name";
        isValid = false;
    } else if (uni.length < 2) {
        uniErr.innerHTML = "University Name must be at least 2 characters";
        isValid = false;
    } 
      else {
        uniErr.innerHTML = "";
    }

    const dept = document.getElementById('student_department').value.trim();
    const deptErr = document.getElementById('student_department_err');
    if (dept === "") {
        deptErr.innerHTML = "Please enter your department";
        isValid = false;
    } else if (dept.length < 2) {
        deptErr.innerHTML = "Department Name must be at least 2 characters";
        isValid = false;
    }
      else {
        deptErr.innerHTML = "";
    }

    const year = document.getElementById('student_year').value;
    const yearErr = document.getElementById('student_year_err');
    if (year === "") {
        yearErr.innerHTML = "Please select your year";
        isValid = false;
    } else {
        yearErr.innerHTML = "";
    }

    const pass = document.getElementById('student_password').value;
    const passErr = document.getElementById('student_password_err');
    if (pass.length < 8) {
        passErr.innerHTML = "Password must be at least 8 characters";
        isValid = false;
    } else {
        passErr.innerHTML = "";
    }

    const cpass = document.getElementById('student_confirm_password').value;
    const cpassErr = document.getElementById('student_confirm_password_err');
    if (cpass !== pass) {
        cpassErr.innerHTML = "Passwords do not match";
        isValid = false;
    } else {
        cpassErr.innerHTML = "";
    }

    return isValid;
}

function validateInstructorForm() {
    let isValid = true;

    const name = document.getElementById('instructor_name').value.trim();
    const nameErr = document.getElementById('instructor_name_err');
    if (name === "") {
        nameErr.innerHTML = "Please enter your name";
        isValid = false;
    } else if (name.length < 3) {
        nameErr.innerHTML = "Name must be at least 3 characters";
        isValid = false;
    } else {
        nameErr.innerHTML = "";
    }

    const email = document.getElementById('instructor_email').value.trim();
    const emailErr = document.getElementById('instructor_email_err');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === "") {
        emailErr.innerHTML = "Please enter your email";
        isValid = false;
    } else if (!emailPattern.test(email)) {
        emailErr.innerHTML = "Please enter a valid email";
        isValid = false;
    } else {
        emailErr.innerHTML = "";
    }

    const contact = document.getElementById('instructor_contact_number').value.trim();
    const contactErr = document.getElementById('instructor_contact_err');
    if (contact === "") {
        contactErr.innerHTML = "Please enter your contact number";
        isValid = false;
    } else if (!/^\d{6,15}$/.test(contact)) {
        contactErr.innerHTML = "Contact must be 6–15 digits";
        isValid = false;
    } else {
        contactErr.innerHTML = "";
    }

    const uni = document.getElementById('instructor_university_name').value.trim();
    const uniErr = document.getElementById('instructor_university_err');
    if (uni === "") {
        uniErr.innerHTML = "Please enter your university name";
        isValid = false;
    } else if (uni.length < 2) {
        uniErr.innerHTML = "University Name must be at least 2 characters";
        isValid = false;
    }
      else {
        uniErr.innerHTML = "";
    }

    const dept = document.getElementById('instructor_department').value.trim();
    const deptErr = document.getElementById('instructor_department_err');
    if (dept === "") {
        deptErr.innerHTML = "Please enter your department";
        isValid = false;
    } else if (dept.length < 2) {
        deptErr.innerHTML = "Department Name must be at least 2 characters";
        isValid = false;
    }
     else {
        deptErr.innerHTML = "";
    }

    const exp = document.getElementById('expertise').value.trim();
    const expErr = document.getElementById('instructor_expertise_err');
    if (exp === "") {
        expErr.innerHTML = "Please enter your area of expertise";
        isValid = false;
    } else {
        expErr.innerHTML = "";
    }

    const pass = document.getElementById('instructor_password').value;
    const passErr = document.getElementById('instructor_password_err');
    if (pass.length < 8) {
        passErr.innerHTML = "Password must be at least 8 characters";
        isValid = false;
    } else {
        passErr.innerHTML = "";
    }

    const cpass = document.getElementById('instructor_confirm_password').value;
    const cpassErr = document.getElementById('instructor_confirm_password_err');
    if (cpass !== pass) {
        cpassErr.innerHTML = "Passwords do not match";
        isValid = false;
    } else {
        cpassErr.innerHTML = "";
    }

    return isValid;
}

document.addEventListener('DOMContentLoaded', function () {
    const studentForm = document.querySelector('.student-form');
    const instructorForm = document.querySelector('.instructor-form');

    if (studentForm) {
        studentForm.addEventListener('submit', function (e) {
            if (!validateStudentForm()) e.preventDefault();
        });
    }

    if (instructorForm) {
        instructorForm.addEventListener('submit', function (e) {
            if (!validateInstructorForm()) e.preventDefault();
        });
    }});
  document.addEventListener("DOMContentLoaded", function () {
  const eyes = document.querySelectorAll(".eye");

  eyes.forEach(function (eye) {
    eye.addEventListener("click", function () {
      const id = eye.getAttribute("data-target");
      const input = document.getElementById(id);

      if (input.type === "password") input.type = "text";
      else input.type = "password";
    });
  });
});
  





