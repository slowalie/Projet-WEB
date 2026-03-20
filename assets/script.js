
/* 
les fonctions et variables sont écrie en CaMeL.

fonction: majuscule au début
variable: minuscule au début
*/

/* --- fonction affichant et cachant la page de login --- */

function OpenLoginPage() {
    document.getElementById("popUpLoginPage").style.display="flex"
    document.getElementById("coreWebsite").style.filter="blur(30px)"
    document.getElementById("loginPart").style.display="flex"
    document.getElementById("signUpPart").style.display="none"
    document.getElementById("closedLoginPart").style.display="none"
    document.getElementById("closedSignUpPart").style.display="flex"
}

function CloseLoginPage() {
    document.getElementById("popUpLoginPage").style.display="none"
    document.getElementById("coreWebsite").style.filter="none"
}

function OpenSignUpPage() {
    document.getElementById("popUpLoginPage").style.display="flex"
    document.getElementById("coreWebsite").style.filter="blur(30px)"
    document.getElementById("loginPart").style.display="none"
    document.getElementById("signUpPart").style.display="flex"
    document.getElementById("closedLoginPart").style.display="flex"
    document.getElementById("closedSignUpPart").style.display="none"
}

/* --- script des boutons affichant/cachant le mot de passe --- */

function ShowPasswordLin() {
    if(document.getElementById("mdpLin").type=="password"){
        document.getElementById("mdpLin").type="text";
        document.getElementById("showLoginPswd").textContent="/";
    }
    else{
        document.getElementById("mdpLin").type="password";
        document.getElementById("showLoginPswd").textContent="0";
    }
}

function ShowPasswordSup() {
    if(document.getElementById("mdpSup").type=="password"){
        document.getElementById("mdpSup").type="text";
        document.getElementById("showSignUpPswd").textContent="/";
    }
    else{
        document.getElementById("mdpSup").type="password";
        document.getElementById("showSignUpPswd").textContent="0";
    }
}

function ShowPasswordConfSup() {
    if(document.getElementById("confirmMdpSup").type=="password"){
        document.getElementById("confirmMdpSup").type="text";
        document.getElementById("showSignUpConfPswd").textContent="/";
    }
    else{
        document.getElementById("confirmMdpSup").type="password";
        document.getElementById("showSignUpConfPswd").textContent="0";
    }
}