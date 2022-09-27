<header>
    <div class="header_nav">
        <a href="" class="nav_brand">
            <img src="_resources/app/client/icons/ExperienceLogger-Symbol.svg">
        </a>

        <div class="nav_button" data-behaviour="toggle-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
            <div class="bar4"></div>
        </div>
    </div>
</header>

<div class="section section--navigation">
    <ul class="nav_menu">
        <% loop $Menu(1) %>
            <% if $MenuPosition != "footer" %>
                <a href="$Link">
                    <li class="nav_link<% if $LinkOrSection == "section" %> nav_link--active<% end_if %>">
                        <% if $MenuIcon %>
                            <div class="menuicon" style="mask-image: url($MenuIcon.Url); -webkit-mask-image: url($MenuIcon.Url);"/>
                            </div>
                        <% end_if %>
                        <p>$MenuTitle</p>
                    </li>
                </a>
            <% end_if %>
        <% end_loop %>
    </ul>
</div>

<div class="section section--headertitle">
    <div class="section_content">
        <% if $ShowTitle %>
            <h1>$Title</h1>
        <% end_if %>
    </div>
</div>
