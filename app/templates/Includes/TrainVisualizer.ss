<div class="train_visualizer">

    <% if $Entrance == "Left" %>
        <div class="entrance">
            Entrance →
        </div>
    <% end_if %>

    <% loop $ExperienceTrains() %>
        <div class="train <% if $Up.ExperienceTrains.Count <= 1 %>active<% end_if %> $Up.Traintype" <% if $Color %> style="background-color: $Color;" <% end_if %> data-train="$SortOrder" data-type="train">
            <p class="trainname" <% if $Color %>style="color: $Color; filter: invert(1) brightness(1.5);"<% end_if %>><% if $Up.ExperienceTrains.Count > 1 %><% if $Up.CustomTrainType %>$Up.CustomTrainType <% else %><% if $Up.Traintype != "None" %>$Up.Traintype <% end_if %><% end_if %>$Title<% end_if %></p>
            <% loop $Wagons %>
                <div class="wagon" <% if $Color %> style="background-color: $Color;" <% end_if %>>
                    <% if $Title %><% if $Up.Wagons.Count > 1 %><p>Wagon $Title</p><% end_if %><% end_if %>
                    <% loop $Rows %>
                        <div class="row" <% if $Color %> style="background-color: $Color;" <% end_if %>>
                            <% if $Title %><p <% if $Up.Color %>style="color: $Up.Color;"<% end_if %>>$Title</p><% end_if %>
                            <% loop $Seats %>
                                <div class="seat $Type $Rotation" data-train="$Up.Up.Up.SortOrder" data-wagon="$Up.Up.SortOrder" data-row="$Up.SortOrder" data-seat="$SortOrder" data-type="seat" data-behaviour="seat_selector" <% if $Color %> style="background-color: $Color;" <% end_if %>>
                                    <p class="seattitle">$Title</p>
                                    <% if $Up.Up.Up.Up.PageController.getLogCountForSeat($Up.Up.Up.SortOrder, $Up.Up.SortOrder, $Up.SortOrder, $SortOrder) > 0 %>
                                        <p class="count">$Up.Up.Up.Up.PageController.getLogCountForSeat($Up.Up.Up.SortOrder, $Up.Up.SortOrder, $Up.SortOrder, $SortOrder)</p>
                                    <% end_if %>
                                </div>
                            <% end_loop %>
                        </div>
                    <% end_loop %>
                </div>
            <% end_loop %>
        </div>
    <% end_loop %>

    <% if $Entrance == "Right" %>
        <div class="entrance">
            ← Entrance
        </div>
    <% end_if %>

    <div class="hidden_datafields">
        <label for="train">Train</label>
        <input type="text" id="train" name="train">

        <label for="wagon" min="0" max="99">Wagon</label>
        <input type="number" id="wagon" name="wagon">

        <label for="row" min="0" max="99">Row</label>
        <input type="number" id="row" name="row">

        <label for="seat" min="0" max="99">Seat</label>
        <input type="number" id="seat" name="seat">
    </div>
</div>
