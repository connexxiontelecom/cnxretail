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

<body themebg-pattern="theme1">
<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Thank You For Signing UP</h5>
                    </div>
                    <div class="card-block">
                        <div class="text-center mb-4">
                            <img src="/assets/images/logo.png" height="33" width="90" alt="logo.png">
                        </div>
                        @if (session()->has('success'))
                            <div class="alert alert-success background-success">{!! session()->get('success') !!}</div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-success background-warning">{!! session()->get('error') !!}</div>
                        @endif
                        <p>Your Order was completed successfully</p>
                        <p>An email receipt including the details of your order has been sent to the email address provided. Please keep it for your records. Expect the following in your mailbox shortly...</p>
                        <ul>
                            <li>Your Link To A Live Facebook Marketing Masterclass where you learn how to find clients online and make sales</li>
                            <li>A Link To Download an E-book Containing Step By Step Guide On How To Find Clients On Facebook</li>
                            <li>2 Months Subscription to CNX Retail Solution where you can store and manage client and sales details</li>
                            <li>A Direct Line To Your Online Sales Coach</li>
                            <li>A Link To The Recorded Version of the Webinar After the Session</li>
                            <li>An explainer video on how to use CNX Retail.</li>
                        </ul>
                        <p>Please get acquainted with the CNX Retail Dashboard.</p>
                        <p>We'll be in touch soon</p>
                        
                    </div>
                </div>


            </div>
        </div>
        <!-- end of row -->
    </div>
    <!-- end of container-fluid -->
</section>
<script type="text/javascript" src="/assets/js/jquery/jquery.min.js "></script>
<script type="text/javascript" src="/assets/js/jquery-ui/jquery-ui.min.js "></script>
<script type="text/javascript" src="/assets/js/popper.js/popper.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap/js/bootstrap.min.js "></script>
<script src="assets/pages/waves/js/waves.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
<script type="text/javascript" src="/assets/js/common-pages.js"></script>

</body>

</html>
