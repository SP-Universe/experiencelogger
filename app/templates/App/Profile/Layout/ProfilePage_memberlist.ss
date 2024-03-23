<div class="section section--memberlist">
    <div class="section_content">
        <div class="section_title">
            <h2>Members</h2>
        </div>
        <div class="memberlist">
            <% loop $MemberList %>
                <% include UserCard %>
            <% end_loop %>
        </div>

        <div class="memberlist_navigation">
            <% if $MemberList.NotFirstPage %>
                <a class="next" href="$MemberList.PrevLink">Previous</a>
            <% end_if %>
            <% if $MemberList.NotLastPage %>
                <a class="next" href="$MemberList.NextLink">Next</a>
            <% end_if %>
        </div>
    </div>
</div>
