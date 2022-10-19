<div class="train_visualizer">
    <% loop $SortedTrains() %>
        <div class="train <% if $Up.SortedTrains.Count <= 1 %>active<% end_if %> $Up.Traintype" data-train="$Train" data-type="train">
            <p class="trainname">$Up.Traintype $Train</p>
            <% loop $Children.GroupedBy("Wagon") %>
                <div class="wagon">
                    <% if $Up.Up.Children.GroupedBy("Wagon").Count > 1 %><p>Wagon $Wagon</p><% end_if %>
                    <% loop $Children.GroupedBy("Row") %>
                        <div class="row">
                        <% if $Up.Up.Children.GroupedBy("Row").Count > 1 %><p>$Row</p><% end_if %>
                            <% loop $Children.GroupedBy("Seat") %>
                                <div class="seat $Up.Up.Up.Up.Up.Up.Up.PageController.getTypeForSeat($Up.Up.Up.Up.Up.Up.Train, $Up.Up.Up.Up.Wagon, $Up.Up.Row, $Seat)" data-behaviour="seat_selector" data-train="$Up.Up.Up.Up.Up.Up.Train" data-wagon="$Up.Up.Up.Up.Wagon" data-row="$Up.Up.Row" data-seat="$Seat">
                                    <% if $Up.Up.Children.GroupedBy("Seat").Count > 1 %> <p>$Seat</p><% end_if %>
                                    <% if $Up.Up.Children.GroupedBy("Seat").Count < 2 %> <p>$Up.Up.Row</p><% end_if %>
                                    <p class="count">( $Up.Up.Up.Up.Up.Up.Up.PageController.getLogCountForSeat($Up.Up.Up.Up.Up.Up.Train, $Up.Up.Up.Up.Wagon, $Up.Up.Row, $Seat) )</p>
                                </div>
                            <% end_loop %>
                        </div>
                    <% end_loop %>
                </div>
            <% end_loop %>
        </div>
    <% end_loop %>


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
