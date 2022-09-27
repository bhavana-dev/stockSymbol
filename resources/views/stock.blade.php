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
                    <a href="/fbLogin">Login</a>
                @endauth
            </div>            
            <div class="container-fluid">
                <div class="container">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Get Price</button> 
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

    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
      <form >
        <div class="form-group">
            <label for="symbol">Symbol name:</label>
            <input type="text" name="symbol" class="form-control symbol" />
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
    console.log(symbol);
    // AJAX request
    $.getJSON("https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol="+symbol+"&apikey=0O18XUJW9P8QVGQJ", function(result){
        $.ajax({
            type: 'POST',
            url: '/saveData',
            data: {"_token":"{{ csrf_token() }}", response: result},
            success: function(response) {
                
                if(response == "success"){
                    alert("Api Data is inserted into database.")
                    window.location.replace("/stock");
                }
            }
        });
    });
      

});
});
</script>
</html>
