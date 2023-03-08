<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="application/json" content="IE=edge" />
        <meta charset="utf-8">
        <title>LogsAPI - XPL</title>
    </head>
    <body>
        <pre>
        {
            <% loop Logs %>
                {
                    "ID": "$ID",
                    "Experience": "$Experience.LinkTitle",
                    "Date": "$VisitTime",
                    "Weather": "$Weather",
                    "Notes": "Varchar(500)",
                    "Score": "Varchar(255)",
                    "Podest": "Int",
                    "Train": "Varchar(255)",
                },
            <% end_loop %>
        }
        </pre>
    </body>
</html>
