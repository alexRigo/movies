import '../styles/security.scss';

const form = document.querySelector('form');

const usernameInput = document.querySelector('#registration_username');
const passwordInput = document.querySelector('#registration_password_first');
const passwordVerifyInput = document.querySelector('#registration_password_second');
const emailInput = document.querySelector('#registration_email');

let passwordConstraints = document.querySelector('#password-constraints');
passwordInput.insertAdjacentElement('afterend', passwordConstraints);

let [isUsernameValid, isPasswordValid, isPasswordVerifyValid, isEmailValid] = [false, false, false, false];

function validateUsername() 
{
    let username = usernameInput.value;
    let usernameMessage = document.querySelector('#username-message');
    const regex = /^[a-zA-Z0-9-_]+$/;

    if (username.length < 3) {
        usernameMessage.innerHTML = "Le pseudo doit contenir un minimum de 3 caractères";
        isUsernameValid = false;
    
    } else if (username.length > 20) { 
        usernameMessage.innerHTML = "Le pseudo doit contenir un maximum de 20 caractères";
        isUsernameValid = false;
      
    } else if (!regex.test(username)) {
        usernameMessage.innerHTML = "Le pseudo ne peut contenir que des chiffres, des lettres, '_' et '-'";
        isUsernameValid = false;
      
    } else {
        checkUsernameAvailability(username)
        .then(isUsernameAvailable => {
            if (isUsernameAvailable) {
                usernameMessage.innerHTML = "Le pseudo existe déjà, veuillez en choisir un autre";
                isUsernameValid = false;
          
            } else {
                usernameMessage.innerHTML = "";
                isUsernameValid = true;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification du pseudo:', error);
        });
    }

    usernameInput.classList.toggle('error', !isUsernameValid);
    usernameInput.classList.toggle('validate', isUsernameValid);

    return true;
}

function checkUsernameAvailability(username) 
{
    let checkUsernameUrl = form.getAttribute('data-check-username-url'); 

    return new Promise((resolve, reject) => {
        fetch(checkUsernameUrl, {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: username })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                resolve(true);
            } else {
                resolve(false);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification du pseudo:', error);
            reject(error);
        });
    });
}

function validatePassword() 
{
    let constraintMinimum = document.querySelector('#minimum');
    let constraintUppercase= document.querySelector('#uppercase');
    let constraintSpecialCharacter = document.querySelector('#special-character');

    let password = passwordInput.value;

    if (password.length > 0) {
        passwordConstraints.style.display = "block"
    } else {
        passwordConstraints.style.display = "none";
    }
 
    let isMinimumValid = password.length >= 6;
    let isUppercaseValid = /[A-Z]/.test(password);
    let isSpecialCharacterValid = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    constraintMinimum.classList.toggle('valid', isMinimumValid);
    constraintUppercase.classList.toggle('valid', isUppercaseValid);
    constraintSpecialCharacter.classList.toggle('valid', isSpecialCharacterValid);
 
    isPasswordValid = isMinimumValid && isUppercaseValid && isSpecialCharacterValid;

    passwordInput.classList.toggle('error', !isPasswordValid);
    passwordInput.classList.toggle('validate', isPasswordValid);

    return true;
}

function validatePasswordVerify() {

    let constraintSecondPassword = document.querySelector('#password-verify-message');

    let password = passwordInput.value;
    let passwordSecond = passwordVerifyInput.value;

    if (password != passwordSecond && isPasswordValid) {
        constraintSecondPassword.textContent = "Les mots de passe doivent êtres similaires";
        isPasswordVerifyValid = false;
    } else if (password === passwordSecond && isPasswordValid) {
        constraintSecondPassword.textContent = "";
        isPasswordVerifyValid = true;
    }

    passwordVerifyInput.classList.toggle('error', !isPasswordVerifyValid);
    passwordVerifyInput.classList.toggle('validate', isPasswordVerifyValid);

    return true;
}


function validateEmail() 
{
    let emailMessage = document.querySelector('#email-message');
    let email = emailInput.value;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        emailMessage.textContent = "Email invalide";
        isEmailValid = false;
    } else {
        isEmailValid = true;
        emailMessage.textContent = "";
    }

    emailInput.classList.toggle('error', !isEmailValid);
    emailInput.classList.toggle('validate', isEmailValid);
        
    return true;
}

usernameInput.addEventListener('input', () => validateUsername());
passwordInput.addEventListener('input', () => validatePassword());
passwordVerifyInput.addEventListener('input', () => validatePasswordVerify());
emailInput.addEventListener('input', () => validateEmail());

form.addEventListener('submit', (e) => {
    e.preventDefault();

    validateUsername();
    validatePassword();
    validatePasswordVerify() 
    validateEmail();

    if (isUsernameValid && isPasswordValid  && isPasswordVerifyValid && isEmailValid) {
        form.submit();
    }
});