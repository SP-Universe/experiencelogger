<div class="section section--lastloggedexperiences">
    <div class="section_content">
        <h2>Your last logged Experiences</h2>
        <% if $CurrentUser %>
            <% if $LastLogged %>
                <% loop $LastLogged %>
                    <% include LogCard %>
                <% end_loop %>
            <% else %>
                <p>You don't have any logged experiences yet.</p>
            <% end_if %>
        <% else %>
            <p>You need to be logged in to see your last logged experiences. <a href="$ProfilePage.Link">Login ></a></p>
        <% end_if %>
    </div>
</div>
