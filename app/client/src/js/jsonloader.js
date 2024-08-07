//console.log("jsonloader loaded");

//Load Experiencecard
let experiencecards = document.querySelectorAll('[data-behaviour="experiencecard"]');

//console.log(experiencecards);
/*if(experiencecards.length){
    experiencecards.forEach(experiencecard => {
        if(experiencecard.querySelector('.experiencedata').textContent) {
            var rawexperience = experiencecard.querySelector('.experiencedata');
            //rawexperience.remove();
            //console.log(rawexperience.textContent);
            var data = JSON.parse(rawexperience.textContent);
            experiencecard.querySelector('.experience_title').innerHTML = data["Title"];
            if(data["ExperienceStage"]){
                experiencecard.querySelector('.experience_type').innerHTML = data["ExperienceType"] + " <span>in " + data["ExperienceStage"] + "</span>";
            } else if(data["ExperienceArea"]) {
                experiencecard.querySelector('.experience_type').innerHTML = data["ExperienceType"] + " <span>in " + data["ExperienceArea"] + "</span>";
            } else {
                experiencecard.querySelector('.experience_type').innerHTML = data["ExperienceType"]
            }
            experiencecard.querySelector('.experience_type').setAttribute("data-filter", data["Title"]);
            //experiencecard.querySelector('.experience_entry_image').style = "background-image: url(" + data["ExperienceImage"] + ")";
            //experiencecard.querySelector('.experience_state').innerHTML = data["State"];
            experiencecard.querySelector('.experience_entry').href = data["ExperienceLink"];
            experiencecard.classList.add("data--loaded");
            experiencecard.classList.remove("data--loading");
        }
    });
}*/


//Load Experiencecard
let charactercards = document.querySelectorAll('[data-behaviour="charactercard"]');

//console.log(experiencecards);
if(charactercards.length){
    console.log("charactercards");
    charactercards.forEach(charactercard => {
        if(charactercard.querySelector('.characterdata').textContent) {
            var rawcharacter = charactercard.querySelector('.characterdata');
            var data = JSON.parse(rawcharacter.textContent);
            charactercard.querySelector('.character_title').innerHTML = data["Title"];
            charactercard.querySelector('.character_entry').href = data["ExperienceLink"];
            charactercard.classList.add("data--loaded");
            charactercard.classList.remove("data--loading");
        }
    });
}
