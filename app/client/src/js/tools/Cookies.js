
// Check if user has accepted the cookie consent
window.hasAcceptedCookieConsent = function hasAcceptedCookieConsent(){
    var hasCookie = false;

    if (document.cookie.split(';').some((item) => item.trim().startsWith('acceptedCookieConsent='))) {
        hasCookie = true;
    }
    return hasCookie;
}

// Function to set a specific cookie with name, value and days
window.setCookie = function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/" + "; SameSite=Strict";
}

// Function to get specific cookie by name
window.getCookie = function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// Cookie Consent for Youtube Videos
let ytelements = document.querySelectorAll('[data-behaviour="youtube_wrap"]');
if(hasAcceptedCookieConsent()){
    ytelements.forEach(element => {

        var yturl = element.children[0].getAttribute('data-src');
        console.log("accepted Cookie found " + yturl);
        element.innerHTML = '<iframe width="560" height="315" src="' + yturl + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
    });
} else {
    ytelements.forEach(element => {
        var yturl = element.getAttribute('data-src');
        element.innerHTML = `
        <div class="youtube_consent_missing">
            <p><b>Du hast unsere Cookies noch nicht akzeptiert!</p></b>
            <p>Deshalb können wir Dir hier kein Youtube-Video anzeigen</p>
            <a class="link--button hollow white" data-behaviour="youtube_accept">Cookies akzeptieren</a>
        </div>
        `;
    });
}

// Accept Cookies with a button
var cookieAcceptButton = document.querySelector('[data-behaviour="cookie_accept_button"]');
if(cookieAcceptButton){
    cookieAcceptButton.addEventListener("click", function() {
        setCookie('acceptedCookieConsent', 'yes', 30);
        window.location.reload();
        console.log("Cookies accepted!");
    }, false);
}
