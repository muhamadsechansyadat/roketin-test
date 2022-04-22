<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('compare.action') }}" method="post">
        @csrf
        <p>Time</p>
        <input id="settime" name="time" type="time" step="1" />
        <input type="submit" value="submit">
    </form>
    <script>
        document.getElementById("settime").value = "00:00:00";
    </script>
</body>
</html>