
@extends('template/frontend/userdashboard/layout/default')
 @section('content')




        <!--================ PAGE-COVER =================-->
        <section class="page-cover" id="cover-registration">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                      <h1 class="page-title">Login</h1>
                        <ul class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li class="active">Login</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->


        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
          <div id="registration" class="innerpage-section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">

                          <div class="flex-content">
                                <div class="custom-form custom-form-fields">
                                    <h3>LOG IN</h3>
                                    <p>Welcome to Mahatat Al Alam</p>
                                    <form method="post" enctype="multipart/form-data" action="{{URL::to('signin')}}">

                                        <input type="hidden" name="_token" value="{{csrf_token()}}">



                                        <div class="form-group">




                                             <input type="email" name="email" class="form-control" placeholder="Email"  required/>
                                             <span><i class="fa fa-envelope"></i></span>


                                             @if($errors->first('email'))
                                             <div class="alert alert-danger">
                                              <ul>
                                                <li>{{$errors->first('email')}}</li>
                                              </ul>
                                            </div>
                                            @endif
                                        </div>






                                        <div class="form-group">
                                             <input type="password" name="password" class="form-control" placeholder="Password"  required/>
                                             <span><i class="fa fa-lock"></i></span>


                                             @if($errors->first('password'))
                                             <div class="alert alert-danger">
                                              <ul>
                                                <li>{{$errors->first('password')}}</li>
                                              </ul>
                                            </div>
                                            @endif
                                             @if(session()->has('message'))
                                            <div class="alert alert-danger">
                                              {{session()->get('message')}}
                                            </div>
                                            @endif
                                        </div>







                                        <button class="btn btn-orange btn-block">Login</button>
                                    </form>

                                    <div class="other-links">
                                      <p class="link-line">Not registered yet ? <a href="{{URL::to('signup')}}">Register Here</a></p>
                                    </div><!-- end other-links -->
                                </div><!-- end custom-form -->

                                <div class="flex-content-img custom-form-img">
                                    <img src="{{URL::asset('public/assets/frontend/dow_images/cities/makkah3.jpg')}}" class="img-responsive" alt="registration-img" />
                                </div><!-- end custom-form-img -->
                            </div><!-- end form-content -->

                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end registration -->
        </section><!-- end innerpage-wrapper -->







@stop
