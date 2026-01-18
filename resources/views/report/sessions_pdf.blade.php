<!DOCTYPE html>
<html>
<head>
    <title>Session Logs</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Session Logs</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Router Name</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessionLogs as $log)
            <tr>
                <td>{{ $log->username }}</td>
                <td>{{ $log->router_name }}</td>
                <td>{{ \Carbon\Carbon::parse($log->login_time)->format('d M Y, H:i:s') }}</td>
                <td>{{ \Carbon\Carbon::parse($log->logout_time)->format('d M Y, H:i:s') }}</td>
                <td>{{ \Carbon\Carbon::parse($log->logout_time)->diffForHumans(\Carbon\Carbon::parse($log->login_time), true) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
