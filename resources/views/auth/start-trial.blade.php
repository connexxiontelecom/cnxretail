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
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>New Business Registration</h5>
                            <p>Start your <strong>FREE</strong> trial today.</p>
                        </div>
                        <div class="card-block">
                            <div class="text-center mb-4">
                                <img src="/assets/images/logo.png" height="33" width="90" alt="logo.png">
                            </div>
                            @if (session()->has('success'))
                                <div class="alert alert-success background-success">{!! session()->get('success') !!}</div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-success background-success">{!! session()->get('error') !!}</div>
                            @endif
                            <form class="form-material" action="{{route('start-trial')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group form-default">
                                        <input type="text" id="company_name" name="company_name" id="company_name" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Company Name</label>
                                        @error('company_name')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" name="email" id="email" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Email Address</label>
                                         @error('email')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" id="phone_no" name="phone_no" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Phone No.</label>
                                        @error('phone_no')
                                            <i class="text-danger mr-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" id="address" name="address" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Address</label>
                                        @error('address')
                                            <i class="text-danger mr-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>

                                    <div class="col-md-6">

                                    <div class="form-group form-default">
                                        <input type="text" id="full_name" name="full_name" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Full Name</label>
                                        @error('full_name')
                                            <i class="text-danger mr-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="text" id="nature_of_business" name="nature_of_business" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Nature of Business</label>
                                        @error('nature_of_business')
                                            <i class="text-danger mr-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="password" id="password" name="password" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Password</label>
                                        @error('password')
                                            <i class="text-danger mr-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group form-default">
                                        <input type="password" name="password_confirmation" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Re-type Password</label>
                                    </div>
                                </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="" class="btn btn-mini btn-danger"><i class="ti-close mr-2"></i>Cancel</a>
                                            <button id="proceedToPay" type="submit" class="btn btn-mini btn-primary"><i class="ti-check mr-2"></i>Submit</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                        </div>
                    </div>
                </div>
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
