<html>

<head>
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="{{ url('storage/images/favicon.png'); }}">
  <title>Pasar Negeri</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
  <style type="text/css">
    
    body {
    background: #fff;
    }
    .page-wrap {
        min-height: 100vh;
        -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .container {
        max-width: 900px;
    }
  </style>
  
</head>

  <body>
    
    <div class="page-wrap d-flex flex-row align-items-center">
        <div class="container">
          <div class="row">
            <div class="col-md-3 text-center">
                <img src="{{ url('storage/images/logo-pasarnegeri-240.png'); }}" Content-Type="image/png" class="img-fluid" alt="" />
            </div>

            <div class="col-md-9">
    		    <h2 class="font-weight-light">Change Email?</h2>
    		    Just enter your new email below and we'll send notification to your new email.
    		    <form action="{{ url('authentication/reset-email') }}" method="POST" class="mt-3">
    		        <input class="form-control form-control-lg" name="email" type="email" placeholder="Your New Email" required/>
    		        <input class="form-control form-control-lg" name="id" value="{{ $id }}" hidden/>
    		        
    		        <div class="text-right my-3">
    		            <button type="submit" class="btn btn-lg btn-success">Submit</button>
    		        </div>
    		    </form>
    		</div>
          </div>
        </div>
    </div>
      
  </body>

</html>