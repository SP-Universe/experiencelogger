<div class="import_block import_block--conflicts">
    <% if $ConflictCount %>
        <h2>Conflicts <span class="import_count">{$ConflictCount}</span></h2>
        <p class="import_intro">These values differ from the existing data. Please choose which version to use for each field.</p>
    <% else %>
        <h2>No conflicts</h2>
    <% end_if %>

    <% if $ConflictGroups %>
        <table class="import_conflicts_table">
            <thead>
                <tr>
                    <th class="import_conflicts_col--field">Field</th>
                    <th class="import_conflicts_col--old">Existing value</th>
                    <th class="import_conflicts_col--new">New value (CSV)</th>
                </tr>
            </thead>
            <% loop $ConflictGroups %>
                <tbody class="import_conflicts_group">
                    <tr class="import_conflicts_heading">
                        <th colspan="3">$Title</th>
                    </tr>
                    <% loop $Fields %>
                        <tr>
                            <td data-label="Field" class="import_conflicts_col--field">$FieldLabel</td>
                            <td data-label="Existing value" class="import_conflicts_col--old">
                                <label class="import_conflicts_option">
                                    <input type="radio" name="resolution_{$Index}" value="old" checked>
                                    <span>$OldValue</span>
                                </label>
                            </td>
                            <td data-label="New value (CSV)" class="import_conflicts_col--new">
                                <label class="import_conflicts_option">
                                    <input type="radio" name="resolution_{$Index}" value="new">
                                    <span>$NewValue</span>
                                </label>
                            </td>
                        </tr>
                    <% end_loop %>
                </tbody>
            <% end_loop %>
        </table>
    <% end_if %>
</div>
