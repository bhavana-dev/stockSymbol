<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" /> -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
                cursor: pointer;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .container {
                position: relative;
                max-width: 100%;
                margin: 0 auto;
            }

            .container img {vertical-align: middle;}

            .container .content {
                position: absolute;
                bottom: 0;
                background: rgb(0, 0, 0); /* Fallback color */
                background: rgba(0, 0, 0, 0.5); /* Black background with 0.5 opacity */
                color: #f1f1f1;
                width: 100%;
                padding: 20px;
            }

            /* 100% Image Width on Smaller Screens */
            @media only screen and (max-width: 700px){
                .modal-content {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">           
            <div class="top-right links">
                @auth
                {{ Auth::user()->name }}
                <a href="/fbLogout"><button type="button" class="btn btn-info">Logout</button></a>
                @else
                <a data-toggle="modal" data-target="#Login">Login</a>
                @endauth
            </div>            
            <div class="container-fluid">
                <div class="container">
                @auth<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Get Price</button> @endauth
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Low</th>
                            <th>High</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($stocks as $s)
                        <tr>
                            <td>{{$s['symbol']}}</td>
                            <td>{{$s['low']}}</td>
                            <td>{{$s['high']}}</td>
                            <td>{{$s['price']}}</td>
                        </tr>
                        @empty
                            <h4>No records found of</h4>
                        @endforelse
                        </tbody>
                    </table>            
                </div>
        </div>

    <div id="Login" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Login in with FB</h4>
                </div>
                <div class="modal-body">
                    <fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
                    <div id="status"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Get Price</h4>
            </div>
            <div class="modal-body">
            <form >
                <div class="form-group">
                    <label for="symbol">Symbol name:</label>
                    <input type="text" name="symbol" value="AMZN" readonly class="form-control symbol" />
                </div>
                <input type="button" name="submit" class="submit btn btn-default" value="Get Price" id="formSubmit" />
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>

        </div>
    </div>
    </body>
    <script>
    $(document).ready(function(){
        $('#formSubmit').click(function(event){
        
            var symbol= $(".symbol").val();
            $.getJSON("https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol="+symbol+"&apikey=0O18XUJW9P8QVGQJ", function(result){
                $.ajax({
                    type: 'POST',
                    url: '/saveData',
                    data: {"_token":"{{ csrf_token() }}", response: result},
                    success: function(response) {
                        
                        if(response == "success"){
                            alert("Api Data is inserted into database.")
                            window.location.replace("/");
                        }else{
                            alert("Somthing wrong. Plz wait and try again.")
                        }
                    }
                });
            });
        });
        
    });
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
        // Logged into your app and Facebook.
        testAPI();
        $(".content").show();
        } else {
        // The person is not logged into your app or we are unable to tell.
        document.getElementById('status').innerHTML = 'Please log ' +
            'into this app.';
            $(".content").hide();
        }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {
        FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
        });
    }

    window.fbAsyncInit = function() {
    FB.init({
        appId      : '1129286207959734',
        cookie     : true,  // enable cookies to allow the server to access 
                            // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v15.0' // Specify the Graph API version to use
    });

    // Now that we've initialized the JavaScript SDK, we call 
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });

    };

    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
        console.log('Successful login for: ' + response.name);
        document.getElementById('status').innerHTML =
            'Thanks for logging in, ' + response.name + '!';
            $.ajax({
                type: 'POST',
                url: '/fbLoginPost',
                data: {"_token":"{{ csrf_token() }}", "data": response},
                success: function(response) {
                    
                    if(response == "success"){
                        
                        window.location.replace("/");
                    }else{
                        alert("OOPPSSS Something wrong...");
                    }
                }
            });
        });
    }
</script>
</html>
