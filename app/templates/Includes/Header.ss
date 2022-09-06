<div class="header">
    <div class="header_nav">
        <a href="" class="nav_brand">
            <img src="_resources/app/client/icons/lightmode.svg">
        </a>
        <div class="nav_menu">
            <% loop $Menu(1) %>
            <% if $MenuPosition != "footer" %>
            <a href="$Link" class="nav_link<% if $LinkOrSection == "section" %> nav_link--active<% end_if %>">$MenuTitle</a>
            <% end_if %>
            <% end_loop %>
        </div>
        <div class="nav_button" data-behaviour="toggle-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
            <div class="bar4"></div>
        </div>
        <div class="nav_mobile">
            <ul>
                <% loop $Menu(1) %>
                <% if $MenuPosition != "footer" %>
                <li class="<% if $LinkOrSection == "section" %> nav_link--active<% end_if %>">
                    <a href="$Link">$MenuTitle</a>
                </li>
                <% end_if %>
                <% end_loop %>
            </ul>
        </div>
    </div>
</div>
