//Dark Mode Toggle
var checkbox = document.querySelector('input[name=darkmode]');
if(checkbox){
    checkbox.addEventListener('change', function() {
        if(this.checked) {
            document.body.classList.add('theme--dark');
            document.body.classList.remove('theme--light');
            if(hasAcceptedCookieConsent) {
                setCookie("darkmode", "true", 30);
            }
        } else {
            document.body.classList.remove('theme--dark');
            document.body.classList.add('theme--light');
            if(hasAcceptedCookieConsent) {
                setCookie("darkmode", "false", 30);
            }
        }
    });
}
