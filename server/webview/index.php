<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHPLogger Webview</title>
    <link href="css/vendor/pure-min.css" type="text/css" rel="stylesheet" />
    <link href="css/style.css" type="text/css" rel="stylesheet" />
    <script src="js/vendor/jquery-1.10.2.min.js"></script>
    <script src="js/vendor/mustache.min.js"></script>
    <script src="js/functions.js"></script>

    <script type="mustache/template" id="log_row">
        <table class="pure-table pure-table-bordered pure-table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Site</th>
                <th>Sitegroup</th>
                <th>Level</th>
                <th>Message</th>
                <th>Confirm</th>
            </tr>
            </thead>
            <tbody>
            {{#logs}}
            <tr>
                <td>{{id}}</td>
                <td>{{timestamp}}</td>
                <td>{{site}}</td>
                <td>{{sitegroup}}</td>
                <td>{{level}}</td>
                <td>{{message}}</td>
                <td>[Confirm]</td>
            </tr>
            {{/logs}}
            </tbody>
        </table>
    </script>

    <script type="mustache/template" id="log_row_errors">
        <table class="pure-table pure-table-bordered pure-table-striped" style="color:red">
            <thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Site</th>
                <th>Sitegroup</th>
                <th>Level</th>
                <th>Message</th>
                <th>Confirm</th>
            </tr>
            </thead>
            <tbody>
            {{#logs}}
            <tr>
                <td>{{id}}</td>
                <td>{{timestamp}}</td>
                <td>{{site}}</td>
                <td>{{sitegroup}}</td>
                <td>{{level}}</td>
                <td><pre>{{message}}</pre></td>
                <td>[Confirm]</td>
            </tr>
            {{/logs}}
            </tbody>
        </table>
    </script>

</head>
<body>
    <h1>PHP Logger</h1>
    <h2>Errors</h2>
    <div id="results_error"></div>
    <h2>Results</h2>
    <div id="results"></div>
</body>
</html>