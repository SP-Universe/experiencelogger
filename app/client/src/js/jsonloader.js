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
