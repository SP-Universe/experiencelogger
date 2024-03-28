//Profile edit button
const profile_edit_button = document.querySelector('[data-behaviour="profile_edit_button"]');
const profile_canceledit_button = document.querySelector('[data-behaviour="profile_canceledit_button"]');
if(profile_edit_button) {
    profile_edit_button.addEventListener('click', function(e) {
        e.preventDefault();
        document.body.classList.toggle('profile_edit_active');
    });
    profile_canceledit_button.addEventListener('click', function(e) {
        e.preventDefault();
        document.body.classList.toggle('profile_edit_active');
    });
}
