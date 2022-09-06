<div class="footer">
    <div class="footer_content">
        <div class="footer_text">Beispieltext</div>
        <div class="footer_text">Anderer Beispieltext</div>
        <div class="footer_menu">
            <ul role="list" class="footer_menu_list w-list-unstyled">
                <% loop $Menu(1) %>
                <% if $MenuPosition == "footer" %>
                <li class="footer_menu_item">
                    <a href="$Link" class="footer_text">$MenuTitle</a>
                </li>
                <% end_if %>
                <% end_loop %>
            </ul>
        </div>
    </div>
</div>
