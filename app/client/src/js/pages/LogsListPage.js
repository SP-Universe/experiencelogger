//Showhide Logs
let showhide = document.querySelectorAll('[data-behaviour="showhide"]');
if(showhide.length){
    showhide.forEach(showhideItem => {
        showhideItem.style.height = "0px";
        showhideItem.style.opacity = "0";

        showhideItem.addEventListener("click", function (event) {
            event.preventDefault();
            showhideItem.classList.toggle("active");
            if (showhideItem.classList.contains("active")){
                showhideItem.style.height = showhideItem.scrollHeight + "px";
                showhideItem.style.opacity = "1";
            } else {
                showhideItem.style.height = "0px";
                showhideItem.style.opacity = "0";
            }
        });
    });
}

//Showhide Logs
let showhide_numbers = document.querySelectorAll('[data-behaviour="showhide_numbers"]');
if(showhide_numbers.length){
    showhide_numbers.forEach(showhideItem => {

        showhideItem.addEventListener("click", function (event) {
            event.preventDefault();
            showhideItem.classList.toggle("active");
        });
    });
}

//Showhide Logs
let showhideLogs = document.querySelectorAll('[data-behaviour="showhide_log"]');
if(showhideLogs.length){
    showhideLogs.forEach(showhideLog => {

        const seemoreOnce = showhideLog.querySelector(".seemore");
        seemoreOnce.style.height = "0px";
        seemoreOnce.style.opacity = "0";

        showhideLog.addEventListener("click", function (event) {
            event.preventDefault();
            showhideLog.classList.toggle("active");
            const seemore = showhideLog.querySelector(".seemore");
            if (showhideLog.classList.contains("active")){
                seemore.style.height = seemore.scrollHeight + "px";
                seemoreOnce.style.opacity = "1";
            } else {
                seemore.style.height = "0px";
                seemoreOnce.style.opacity = "0";
            }
        });
    });
}

//logsfilter
const logsfilter = document.querySelector('[data-behaviour="filter_logs"]');
const filteredlogs = document.querySelectorAll('[data-behaviour="filtered_log"]');

if(logsfilter && filteredlogs){
    logsfilter.addEventListener("change", function(e) {
        e.preventDefault();
        filteredlogs.forEach(element => {
            if(e.target.value === "All"){
                element.classList.remove('hide');
                return;
            } else {
                element.classList.add('hide');
                if(element.dataset.experiencetype === e.target.value) {
                    element.classList.remove('hide');
                }
            }
        });
    });
}
