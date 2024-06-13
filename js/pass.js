let eye = document.querySelector(".login form div:nth-of-type(2) i");
let input = document.querySelector(".login form div:nth-of-type(2) input");

eye.addEventListener("click",()=>{
    if (input.type == "password") {
        input.type = "text";
        eye.classList.replace("fa-eye","fa-eye-slash");
    }else {
        input.type = "password";
        eye.classList.replace("fa-eye-slash","fa-eye");
    }
})