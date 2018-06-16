<!doctype html>
<html lang="en">

<head>
    {% block head %}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="BOOKING" />
    <link rel="shortcut icon" href="/favicon.ico">
    {% endblock %}
    {% block title %}
        {{ get_title() }}
    {% endblock %}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="bootstrap-4.1.0/dist/js/bootstrap.min.js"></script>
    {{ assets.outputCss('header') }}

</head>

<body>
    {% block content %}
        {{ content() }}
    {% endblock %}
    <link rel="stylesheet" href="style/simplex/bootstrap.min.css"
    {{ assets.outputJs('footer') }}
</body>

</html>
