<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{config('app.name')}}</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Meta -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />

      <meta name="keywords" content="{{config('app.keywords')}}" />
      <meta name="author" content="{{config('app.name')}}" />

      <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
      <link rel="stylesheet" type="text/css" href="/assets/icon/themify-icons/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="/assets/icon/icofont/css/icofont.css">
      <link rel="stylesheet" type="text/css" href="/assets/icon/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
  </head>

  <body>
    <section class="login-block p-4">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12 ">
                    <h4>Payment Confirmation</h4>
                    <p>Thank you for fulfilling your payment. <strong>{{$tenant->company_name ?? 'CNXRetail'}}</strong> will get in touch with you
                    soon regarding this payment. </p>
                    <div>
                        <a href="http://www.cnxretail.com" class="btn btn-mini btn-primary text-center">Home</a>
                    </div>
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jquery -->
<script type="text/javascript" src="/assets/js/jquery/jquery.min.js "></script>
<script type="text/javascript" src="/assets/js/jquery-ui/jquery-ui.min.js "></script>
<script type="text/javascript" src="/assets/js/popper.js/popper.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap/js/bootstrap.min.js "></script>
<script src="assets/pages/waves/js/waves.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
<script type="text/javascript" src="/assets/js/common-pages.js"></script>
</body>

</html>
