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
                        <th colspan="3">
                            <button type="button" class="import_group_toggle" data-behaviour="import_group_toggle" aria-expanded="true">
                                <span class="import_group_toggle_icon" aria-hidden="true"></span>
                                $Title
                            </button>
                            <% if $ExtraFields %>
                                <span class="import_addfield" data-behaviour="import_addfield">
                                    <button type="button" class="import_addfield_button" title="Feld hinzufügen" aria-label="Feld hinzufügen">+</button>
                                    <select class="import_addfield_select" hidden>
                                        <option value="">Feld wählen…</option>
                                        <% loop $ExtraFields %>
                                            <option value="{$RowId}">$FieldLabel</option>
                                        <% end_loop %>
                                    </select>
                                </span>
                            <% end_if %>
                        </th>
                    </tr>
                    <% loop $Fields %>
                        <tr>
                            <td data-label="Field" class="import_autofills_col--field">$FieldLabel</td>
                            <td data-label="New value" class="import_autofills_col--new">$ValueControl.RAW</td>
                            <td data-label="Skip" class="import_autofills_col--action">
                                <label class="import_toggle">
                                    <input type="checkbox" name="skipAutoFill_{$Index}" value="1"<% if $DefaultSkip %> checked<% end_if %>>
                                    <span>Skip</span>
                                </label>
                            </td>
                        </tr>
                    <% end_loop %>
                    <% loop $ExtraFields %>
                        <tr id="{$RowId}" class="import_autofills_extra_row" hidden>
                            <td data-label="Field" class="import_autofills_col--field">$FieldLabel</td>
                            <td data-label="New value" class="import_autofills_col--new">$ValueControl.RAW</td>
                            <td data-label="Skip" class="import_autofills_col--action">
                                <label class="import_toggle">
                                    <input type="checkbox" name="skipAutoFill_{$Index}" value="1"<% if $DefaultSkip %> checked<% end_if %>>
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
