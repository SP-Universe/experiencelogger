<% if $AutoFillGroups %>
    <div class="import_block import_block--autofills">
        <h2>Will be auto-filled <span class="import_count">{$AutoFillCount}</span></h2>
        <p class="import_intro">Check "Skip" for any field you don't want to fill in automatically.</p>
        <table class="import_autofills_table">
            <thead>
                <tr>
                    <th class="import_autofills_col--field">Field</th>
                    <th class="import_autofills_col--new">New value</th>
                    <th class="import_autofills_col--action">Skip</th>
                </tr>
            </thead>
            <% loop $AutoFillGroups %>
                <tbody class="import_autofills_group">
                    <tr class="import_autofills_heading">
                        <th colspan="3">$Title</th>
                    </tr>
                    <% loop $Fields %>
                        <tr>
                            <td data-label="Field" class="import_autofills_col--field">$FieldLabel</td>
                            <td data-label="New value" class="import_autofills_col--new">$NewValue</td>
                            <td data-label="Skip" class="import_autofills_col--action">
                                <label class="import_toggle">
                                    <input type="checkbox" name="skipAutoFill_{$Index}" value="1">
                                    <span>Skip</span>
                                </label>
                            </td>
                        </tr>
                    <% end_loop %>
                </tbody>
            <% end_loop %>
        </table>
    </div>
<% end_if %>
