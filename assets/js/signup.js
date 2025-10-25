document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.signup-form');
    const fullNameInput = document.querySelector('input[name="full_name"]');
    const contactInput = document.querySelector('input[name="contact"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const gradeSel = document.getElementById('gradeID');
    const dobInput = document.getElementById('dob');
    const gradeAgeMap = { // Grade <-> DOB validation
        "1": {min:5, max:7},
        "2": {min:6, max:8},
        "3": {min:7, max:9},
        "4": {min:8, max:10},
        "5": {min:9, max:11}
    };

    function fmt(d) { // format YYYY-MM-DD
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    if (dobInput) { // Set up DOB range
        const today = new Date();
        dobInput.setAttribute('max', fmt(today));
        
        function updateDobBounds() { // no grade selected
            const grade = gradeSel ? gradeSel.value : null;
            if (!grade || !gradeAgeMap[grade]) {
                dobInput.removeAttribute('min');
                return;
            }

            const { min: minAge, max: maxAge } = gradeAgeMap[grade];

            const earliest = new Date();
            earliest.setFullYear(earliest.getFullYear() - maxAge);

            const latest = new Date();
            latest.setFullYear(latest.getFullYear() - minAge);

            dobInput.setAttribute('min', fmt(earliest));
            dobInput.setAttribute('max', fmt(latest));
        }

        if (gradeSel) gradeSel.addEventListener('change', updateDobBounds);
        updateDobBounds();
    }

    function calcAge(dobStr) {  // calculate age from DOB value 
        if (!dobStr) return null;
        const dob = new Date(dobStr);
        if (isNaN(dob.getTime())) return null;
        const now = new Date();
        let age = now.getFullYear() - dob.getFullYear();
        const m = now.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < dob.getDate())) age--;
        return age;
    }
    
    addErrorContainer(fullNameInput, 'fullname-error'); // Create error containers
    addErrorContainer(contactInput, 'contact-error');
    addErrorContainer(passwordInput, 'password-error');
    addErrorContainer(dobInput, 'dob-error');
    addErrorContainer(gradeSel, 'grade-error');
    
    fullNameInput.addEventListener('blur', () => validateField('fullname'));  // Event listeners
    contactInput.addEventListener('input', () => validateField('contact'));
    contactInput.addEventListener('keypress', allowOnlyNumbers);
    passwordInput.addEventListener('blur', () => validateField('password'));
    dobInput.addEventListener('change', () => validateDob());
    gradeSel.addEventListener('change', () => {
        updateDobBounds();
        validateDob();
    });
    form.addEventListener('submit', handleSubmit);
    
    function addErrorContainer(input, id) {
        const error = document.createElement('div');
        error.className = 'error-message';
        error.id = id;
        input.parentNode.insertBefore(error, input.nextSibling);
    }
    
    function validateField(type) {
        let input, errorId, value;
        
        if (type === 'fullname') {
            input = fullNameInput;
            errorId = 'fullname-error';
        } else if (type === 'contact') {
            input = contactInput;
            errorId = 'contact-error';
        } else if (type === 'password') {
            input = passwordInput;
            errorId = 'password-error';
        }
        
        value = input.value.trim();
        let isValid = true;
        let message = '';
        
        if (type === 'fullname') {
            if (!value) {
                message = 'Full name is required';
                isValid = false;
            } else if (value.charAt(0) !== value.charAt(0).toUpperCase()) {
                message = 'Full name must start with a capital letter';
                isValid = false;
            } else if (!/^[A-Za-z\s]+$/.test(value)) {
                message = 'Full name can only contain letters and spaces';
                isValid = false;
            }
        } else if (type === 'contact') {
            if (!value) {
                message = 'Contact number is required';
                isValid = false;
            } else if (!/^\d{10}$/.test(value)) {
                message = 'Contact number must be exactly 10 digits';
                isValid = false;
            }
        } else if (type === 'password') {
            if (!value) {
                message = 'Password is required';
                isValid = false;
            } else if (value.length < 6) {
                message = 'Password must be at least 6 characters long';
                isValid = false;
            }
        }
        
        showError(input, errorId, message, !isValid);
        return isValid;
    }
    
    function showError(input, errorId, message, hasError) { // show error or remove
        const errorElement = document.getElementById(errorId);
        if (hasError) {
            input.classList.add('input-error');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        } else {
            input.classList.remove('input-error');
            errorElement.classList.remove('show');
        }
    }
    
    function validateDob() { // after validate get dob and grad
        const dobValue = dobInput ? dobInput.value : null;
        const grade = gradeSel ? gradeSel.value : null;
        let isValid = true;
        let message = '';
        
        // DOB validation
        if (!dobValue) {
            message = 'Date of Birth is required';
            isValid = false;
        } else {
            const dobDate = new Date(dobValue);
            if (isNaN(dobDate.getTime())) {
                message = 'Please enter a valid Date of Birth';
                isValid = false;
            } else if (dobDate > new Date()) {
                message = 'Date of birth cannot be in the future';
                isValid = false;
            } else if (grade && gradeAgeMap[grade]) {
                // Grade-age validation
                const age = calcAge(dobValue);
                if (age === null) {
                    message = 'Invalid Date of Birth';
                    isValid = false;
                } else {
                    const { min: minAge, max: maxAge } = gradeAgeMap[grade];
                    if (age < minAge || age > maxAge) {
                        message = `Grade ${grade} requires age between ${minAge} and ${maxAge} years. Your age: ${age}`;
                        isValid = false;
                    }
                }
            }
        }
        
        showError(dobInput, 'dob-error', message, !isValid);
        return isValid;
    }
    
    function allowOnlyNumbers(e) {
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    }
    
    function handleSubmit(e) {
        e.preventDefault();
        
        const isNameValid = validateField('fullname');
        const isContactValid = validateField('contact');
        const isPasswordValid = validateField('password');
        const isDobValid = validateDob();
        
        // Check required fields
        const allValid = Array.from(form.querySelectorAll('[required]')).every(field => {
            if (!field.value.trim()) {
                field.classList.add('input-error');
                return false;
            }
            field.classList.remove('input-error');
            return true;
        });
        
        if (!allValid) {
            alert('Please fill in all required fields');
            return;
        }
        
        if (isNameValid && isContactValid && isPasswordValid && isDobValid) {
            showSuccessPopup();
            setTimeout(() => form.submit(), 2000);
        }
    }
    
    function showSuccessPopup() {
        alert('Submitted successfully!');
    }
});