console.log("jsonloader loaded");

//Load Experiencecard
let experiencecards = document.querySelectorAll('[data-behaviour="experiencecard"]');

if(experiencecards.length){
    experiencecards.forEach(experiencecard => {
        if(experiencecard.querySelector('.experiencedata').textContent) {
            var rawexperience = experiencecard.querySelector('.experiencedata');
            //rawexperience.remove();
            //console.log(rawexperience.textContent);
            var data = JSON.parse(rawexperience.textContent);
            experiencecard.querySelector('.experience_title').innerHTML = data["Title"];
            experiencecard.querySelector('.experience_type').innerHTML = data["ExperienceType"] + " <span>in " + data["ExperienceArea"] + "</span>";
            experiencecard.querySelector('.experience_type').setAttribute("data-filter", data["Title"]);
            experiencecard.querySelector('.experience_entry_image').style = "background-image: url(" + data["ExperienceImage"] + ")";
            experiencecard.querySelector('.experience_state').innerHTML = data["State"];
            experiencecard.querySelector('.experience_entry').href = data["ExperienceLink"];
            experiencecard.classList.add("data--loaded");
            experiencecard.classList.remove("data--loading");
        }
    });
}
