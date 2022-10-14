<% if $SortedTrains.Count > 1 %>
    <div class="train_selector">
        <select name="train" id="train" onchange="change_train(this)">
            <option value="-1">Select a $Traintype</option>
            <% loop $getSortedTrains() %>
                <option value="$Train">$Up.Traintype: $Train</option>
            <% end_loop %>
        </select>
    </div>
<% end_if %>

<div class="train_visualizer">
    <% loop $SortedTrains() %>
    <div class="train <% if $Up.SortedTrains.Count <= 1 %>active<% end_if %>" data-train="$Train" data-type="train">
            <p class="trainname"><% if $Up.HasBoats %>Boat<% else %>Train<% end_if %> $Train</p>
            <% loop $Children.GroupedBy("Wagon") %>
                <div class="wagon">
                    <p>Wagon $Wagon</p>
                    <% loop $Children.GroupedBy("Row") %>
                        <div class="row">
                            <p>$Row</p>
                            <% loop $Children.GroupedBy("Seat") %>
                                <p class="seat" data-behaviour="seat_selector" data-train="$Up.Up.Up.Up.Up.Up.Train" data-wagon="$Up.Up.Up.Up.Wagon" data-row="$Up.Up.Row" data-seat="$Seat">$Seat</p>
                            <% end_loop %>
                        </div>
                    <% end_loop %>
                </div>
            <% end_loop %>
        </div>
    <% end_loop %>

    <div class="hidden_datafields">
        <label for="boat" min="0" max="99">Boat</label>
        <input type="text" id="boat" name="boat">
        <label for="trainField" min="0" max="99">Train</label>
        <input type="text" id="trainField" name="train">
        <label for="wagon" min="0" max="99">Wagon</label>
        <input type="number" id="wagon" name="wagon">
        <label for="row" min="0" max="99">Row</label>
        <input type="number" id="row" name="row">
        <label for="seat" min="0" max="99">Seat</label>
        <input type="number" id="seat" name="seat">
    </div>
</div>
