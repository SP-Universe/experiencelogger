console.log("ExperienceCard-System loaded");

//Calculate Log Counts
const logCounters = document.querySelectorAll('[data-behaviour="logCounter"]');
if(logCounters.length > 0) {
    logCounters.forEach(logCounter => {
        const experienceID = logCounter.getAttribute('data-experienceID');
        let logCount = 0;

        //asynchronously fetch the current count from the server
        fetch(`./app-api/logCountForExperience/${experienceID}`)
            .then(response => response.json())
            .then(data => {
                logCount = data.LogCount;
                if(logCount > 0) {
                    logCounter.classList.add('notnew');
                    logCounter.querySelector('[data-behavior="logCounterValue"]').textContent = logCount;
                } else {
                    logCounter.querySelector('[data-behavior="logCounterValue"]').remove();
                }
            });

    });
}

//Calculate Ratings
const ratingStars = document.querySelectorAll('[data-behaviour="ratingStars"]');
if(ratingStars.length > 0) {
    ratingStars.forEach(rating => {
        const experienceID = rating.getAttribute('data-experienceID');
        let ratingScore = 0;

        //asynchronously fetch the current count from the server
        fetch(`./app-api/ratingForExperience/${experienceID}`)
            .then(response => response.json())
            .then(data => {
                ratingScore = data.Rating;
                if(ratingScore > 0) {
                    rating.classList.add('notnew');
                    rating.style.setProperty('--stars', ratingScore);
                    rating.classList.remove('ratingdisplay--loading');
                    rating.title = "" + ratingScore + "Stars";
                } else {
                    rating.previousElementSibling.remove();
                    rating.remove();
                }
            });

    });
}
