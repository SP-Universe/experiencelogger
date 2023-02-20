console.log("jsonloader loaded");

let experiences = new Map();

window.loadOnlineData = function loadOnlineData() {
    console.log("User is online. Reading from server");

    //Load Experiencecard
    let experiencecards = document.querySelectorAll('[data-behaviour="experiencecard"]');

    Object.keys(localStorage).forEach(function(key){
        //console.log(localStorage.getItem(key));
        experiences.set(key, localStorage.getItem(key));
    });

    if(experiencecards.length){
        experiencecards.forEach(experiencecard => {
            if(experiencecard.querySelector('.experiencedata').textContent) {
                var rawexperience = experiencecard.querySelector('.experiencedata');

                var data = JSON.parse(rawexperience.textContent);

                localStorage.setItem(data["Title"], rawexperience.textContent);
                experiences.set(data["Title"], data);

                createExperiencecard();

                experiencecard.querySelector('.experience_title').innerHTML = data["Title"];
                experiencecard.querySelector('.experience_type').innerHTML = data["ExperienceType"];
                experiencecard.querySelector('.experience_type').setAttribute("data-filter", data["Title"]);
                experiencecard.querySelector('.experience_entry_image').style = "background-image: url(" + data["ExperienceImage"] + ")";
                experiencecard.querySelector('.experience_state').innerHTML = data["State"];
                experiencecard.querySelector('.experience_entry').href = data["ExperienceLink"];
                experiencecard.classList.add("data--loaded");
                experiencecard.classList.remove("data--loading");
            }
        });
    }
}

window.loadOfflineData = function loadOfflineData() {
    console.log("User is offline. Reading from local storage");
    Object.keys(localStorage).forEach(function(key){
        console.log(localStorage.getItem(key));
    });

    createExperiencecard();
}

const experienceholder = document.querySelector('.experience_list');
function createExperiencecard(){
    if(experienceholder) {
        var card = document.createElement('div');
        experienceholder.appendChild(card);
        card.classList.add('experience_card');
        card.classList.add('data--loading');
        card.classList.add('test');

        var cardcontent = document.createElement('div');
        cardcontent.classList.add('experience_entry');
        card.appendChild(cardcontent);

        var cardimage = document.createElement('div');
        cardimage.classList.add('experience_entry_image');
        cardcontent.appendChild(cardimage);

        var cardtext = document.createElement('div');
        cardtext.classList.add('experience_entry_content');
        cardcontent.appendChild(cardtext);

        var cardtitle = document.createElement('h2');
        cardtitle.classList.add('experience_title');
        cardtitle.innerText = "Experience Title";
        cardtext.appendChild(cardtitle);

        var cardtype = document.createElement('h4');
        cardtype.classList.add('experience_type');
        cardtype.innerText = "Experience Type";
        cardtext.appendChild(cardtype);

        var cardstate = document.createElement('p');
        cardstate.classList.add('experience_state');
        cardstate.innerText = "Experience State";
        cardtext.appendChild(cardstate);

        var carddistance = document.createElement('p');
        carddistance.classList.add('experience_distance');
        carddistance.innerText = "Experience Distance";
        cardtext.appendChild(carddistance);

        var cardlogging = document.createElement('div');
        cardlogging.classList.add('experience_logging');
        card.appendChild(cardlogging);

        var cardlogbutton = document.createElement('a');
        cardlogbutton.classList.add('logging_link');
        cardlogbutton.href = "test.de";
        cardlogbutton.style = "height: 48px; width: 48px;";
        cardlogging.appendChild(cardlogbutton);

        var cardlogsvg = document.createElement('svg');
        cardlogsvg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        cardlogsvg.setAttribute('height', '48');
        cardlogsvg.setAttribute('width', '48');

        var newElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        newElement.setAttribute('fill', 'currentColor');
        newElement.setAttribute('d', 'M22.65 34h3v-8.3H34v-3h-8.35V14h-3v8.7H14v3h8.65ZM24 44q-4.1 0-7.75-1.575-3.65-1.575-6.375-4.3-2.725-2.725-4.3-6.375Q4 28.1 4 23.95q0-4.1 1.575-7.75 1.575-3.65 4.3-6.35 2.725-2.7 6.375-4.275Q19.9 4 24.05 4q4.1 0 7.75 1.575 3.65 1.575 6.35 4.275 2.7 2.7 4.275 6.35Q44 19.85 44 24q0 4.1-1.575 7.75-1.575 3.65-4.275 6.375t-6.35 4.3Q28.15 44 24 44Z');
        cardlogsvg.appendChild(newElement);
        cardlogbutton.appendChild(cardlogsvg);

        console.log("New Card created");
    }
}
