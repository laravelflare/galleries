<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
                color: #dd4b39;
            }

            .dump {
                background: #18171b;
                box-shadow: 0 1px 7px 0 rgba(221, 75, 57, 0.5);
                width: 620px;
                margin-top: 40px;
                text-align: left;
                max-height: 480px;
                overflow: scroll;
                padding-left: 10px;
                padding-right: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Flare Galleries</div>

                <div class="dump">
                    {{ dump($model) }}
                </div>
            </div>
        </div>
    </body>
</html>