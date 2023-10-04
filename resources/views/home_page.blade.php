<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dafy Online Pvt. Ltd.,</title>
    <link rel="icon" type="image/x-icon" href="{{URL('public/assets/img/logo/loader.png')}}" >
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
   
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{URL('public/assets/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{URL('public/assets/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{URL('public/assets/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{URL('public/assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="{{URL('public/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{URL('public/assets/css/booking.css')}}" rel="stylesheet">
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 
        vh-100 top-50 start-50 d-flex align-items-center justify-content-center" data-delay="50000s">
                    <span><img src="{{URL('public/assets/img/logo/dafy.jpeg')}}" class="" width="100px" alt="" srcset=""> </span>
    </div>
    <!-- Spinner End -->
    <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0 m-0 w-100" id="navbar">
            <a href="#" class="navbar-brand d-flex align-items-center py-1 px-1 p-1 m-0">
                <img src="{{URL('public/assets/img/logo/dafy.jpeg')}}" style="width: 5%;" alt="icon" srcset="">
            </a>
            <button type="button" class="navbar-toggler me-2" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
                <ul class="navbar-nav p-3 p-lg-0">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">Home</a>
                    </li>
                    <!-- Drop down menu start-->
                    <li class="nav-item dropdown">
                        <a href="#service" class="nav-link">Services</a>
                        <!--a href="#service" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                        <div class="dropdown-menu fade-up m-0">
                            <a href="#service" class="dropdown-item">Chauffeur Service</a>
                            <a href="#service" class="dropdown-item">Hospital Assistance</a>
                        </div-->
                    </li>
                            <!-- <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Join Us</a>
                                <div class="dropdown-menu fade-up m-0 p-0">
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#book5">Join as Driver Partner</a>
                                    <a href="#" class="dropdown-item">Join as Business Partner</a>
                        </div>
                    </li> -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">About Us</a>
                        <div class="dropdown-menu fade-up m-0">
                            <a href="#team" class="dropdown-item">Our Team</a>
                            <a href="#contact" class="dropdown-item">Contact Us</a>
                            <a href="#expert" class="dropdown-item">Our Expert Help</a>
                            <a href="#testimonial" class="dropdown-item">Testimonial</a>
                        </div>
                    </li>
                    <!-- <li class="nav-item d-none d-lg-block col-sm-4 text-start">
                        <a href="https://play.google.com/store/apps/details?id=com.dafy" class="btn btn-warning rounded-pill py-3 px-1">
                            <b>Download App</b>
                        </a>
                    </li> -->
                </ul>
            </div>
        </nav>
    <!-- Navbar End -->
    <!----------------------------- slider Start ------------------------------------------------>
        <div class="container-fluid px-0 mb-5">
            <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                                <img class="w-100 h-100" src="{{URL('public/assets/img/banner/a.jpg')}}" alt="breeze-street-image">
                                <!-- <div class="carousel-caption">
                                    <div class="container">
                                        <div class="row justify-content-end">
                                            <div class="col-lg-7 text-end col-7">
                                                <h1 class="fs-4 animated slideInLeft" style="font-family: 'Lato', sans-serif;">
                                                <span class="highlight">Welcome to<strong>Dafy Online Service</strong></span></h1>
                                                <h1 class="display-5 mb-3 animated slideInLeft" style="font-family:'Lato', sans-serif;">
                                                    <span class="highlight">Most reliable and economical <br> professional driver on demand</span></h1>  
                                                <a href="" class="btn btn-none rounded-pill py-3 px-4 animated slideInLeft"
                                                data-bs-toggle="modal" data-bs-target="#book1"style="font-family: 'Lato', sans-serif; color:#ffd300"><b> Book Now</b></a>
                                                <a href="#service-type" class="btn btn-none rounded-pill py-3 px-4 animated slideInLeft"
                                                style="font-family: 'Lato', sans-serif;color:#ffd300"><b> Read More</b></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="carousel-caption">
                                    <div class="container">
                                        <div class="row justify-content-end">
                                            <div class="col-lg-7 text-end col-10">
                                                <p class="fs-4  animated slideInLeft" style="font-family: 'Lato', sans-serif; color:black;">
                                                <span class="highlight"> Welcome to</span>
                                                <strong class="highlight">Dafy Online Service</strong>
                                                </p>
                                                <h1 class="display-3  mb-4 animated slideInLeft" style="font-family: 'Lato', sans-serif; color:gold;">
                                                    <span class=""> Most reliable</span>
                                                    <span class="">and economical</span>
                                                    <span class="">professional driver</span>
                                                    <span class="">driver on demand</span>
                                                </h1>  
                                                <a class="d-inline-flex align-items-centerbtn btn-light rounded-pill py-3 px-4 animated slideInLeft"
                                                    href="tel:917592933933"><strong>call now</strong></a>
                                                <!-- <a href="" class="btn btn-light rounded-pill py-3 px-4 animated slideInLeft"
                                                data-bs-toggle="modal" data-bs-target="#book1"
                                                style="font-family: 'Lato', sans-serif;color:black;">Book Now</a> -->
                                                <a href="#service-type" class="btn btn-light rounded-pill py-3 px-4 animated slideInLeft" 
                                                style="font-family: 'Lato', sans-serif; color:black;"><strong>Read More</strong></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
                
                <!-- Additional Slider End -->
                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                    <!-- <span class="carousel-control-prev-icon" aria-hidden="true"></span> -->
                    <!-- <span class="visually-hidden">Previous</span> -->
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                    <!-- <span class="carousel-control-next-icon" aria-hidden="true"></span> -->
                    <!-- <span class="visually-hidden">Next</span> -->
                </button>
            </div>
        </div>
    <!--------------------------------------------------  Slider End       ----------------------------------->
        <!-- ================================================ Service Start ========================================== -->
    <section id="service">
        <div class="container-xxl py-5">
            <div class="container justify-content-center">
                <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.2s" style="max-width: 300px;">
                    <!--p class="fs-5 fw-medium" style="color: #ffae00;">Our Services</p-->
                    <h1 class="display-5 mb-5">Our Services</h1>
                </div>
                <div class="row g-4"> 
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item position-relative h-100">
                            <div class="service-text rounded p-5"style="font-family: 'Lato', sans-serif;">
                                <div class="btn-square bg-white rounded-circle mx-auto mb-4" style="width: 124px; height: 124px;">
                                    <img class="img-fluid w-100 h-100" src="{{URL('public/assets/img/icon/wheels3.png')}}" alt="Icon" >
                                </div>
                                <h5 class="mb-3">Professional Drivers</h5>
                                <p class="mb-0"> Hire the professionals,Experience a smooth and stress-free journey. <br>
                                    Forget about the hassle of navigating unfamiliar roads or finding parking spaces. <br>
                                    Well take care of everything, from pick-up todrop-off,ensuring you arrive at 
                                    your destination on time and in style. <br>
                                    our experienced drivers are here to provide a comfortable and supportive environment during their trip.
                                </p>
                            </div>
                            <div class="service-btn rounded-0 rounded-bottom bg-dark">
                            <a class="text-warning fw-medium" href="tel:917592933933"><strong>call now</strong>
                                <!-- <a class="text-warning fw-medium" href="" data-bs-toggle="modal" data-bs-target="#book1">Book Now -->
                                    <i class="bi bi-chevron-double-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                        
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="service-item position-relative h-100">
                            <div class="service-text rounded p-5" style="font-family: 'Lato', sans-serif;" >
                                <div class="btn-square bg-white rounded-circle mx-auto mb-4"
                                    style="width: 124px; height: 124px;">
                                    <img class="img-fluid w-100 h-100" src="{{URL('public/assets/img/icon/assistance.png')}}" alt="Icon" >
                                </div>
                                <h5 class="mb-3">Hospital Assistance</h5>
                                <p class="mb-0">Our driver assistants also act as an assistant who provides support for personal care. <br>
                                    the functions within the hospital which carry out much of the ground work. <br>
                                    Our user-friendly platform makes booking as well as taking them for the tests, helping 
                                    them with administrative tasks like paying bills. <br>
                                    Taking the assessments we give the reports to the person.
                                </p>
                            </div>
                            <div class="service-btn rounded-0 rounded-bottom bg-dark">
                                <!-- <a class="text-warning fw-medium" href="" data-bs-toggle="modal" data-bs-target="#book1">Book Now -->
                            <a class="text-warning fw-medium" href="tel:917592933933"><strong>call now</strong>
                                    <i class="bi bi-chevron-double-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================================================ Service End ========================================== -->
    <!-- =====================================Explore Service Start =================================================== -->
        <section id="service-type"><br><br>
            <div class="container-xxl service py-5">
                <div class="container">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.2s">
                        <h1 class="mb-5">Explore Our Services</h1>
                    </div>
                    <div class="row g-4 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="col-lg-4" >
                            <div class="nav w-100 nav-pills me-4 p-0">
                                <button class="nav-link w-100 d-flex align-items-center p-3 mb-3 active" data-bs-toggle="pill"
                                    data-bs-target="#tab-pane-1" type="button" style=" border-radius: 50% 50% 0 0;">
                                    <img class="img-fluid" src="{{URL('public/assets/img/icon/wheels.png')}}" style="width: 70px; height: 70px;" alt="Icon">
                                    <h4 class="m-0">One-Side Service</h4>
                                </button>
                                <button class="nav-link w-100 d-flex align-items-center p-3 mb-3" data-bs-toggle="pill"
                                    data-bs-target="#tab-pane-2" type="button" style=" border-radius: 0 0 0 0;">
                                    <img class="img-fluid" src="{{URL('public/assets/img/icon/wheels.png')}}" style="width: 70px; height: 70px;" alt="Icon">
                                    <h4 class="m-0">Round-Trip Service</h4>
                                </button>
                                <button class="nav-link w-100 d-flex align-items-center p-4 mb-4" data-bs-toggle="pill"
                                    data-bs-target="#tab-pane-3" type="button" style=" border-radius: 0 0 50% 50% ;">
                                    <img class="img-fluid" src="{{URL('public/assets/img/icon/wheels.png')}}" style="width: 70px; height: 70px;" alt="Icon">
                                    <h4 class="m-0">Hospital Assistance</h4>
                                </button>
                            </div>
                        </div>
                        <!-- One-side Ride -->
                        <div class="col-lg-8 ">
                            <div class="tab-content w-100">
                                <div class="tab-pane fade show active" id="tab-pane-1" >
                                    <div class="row g-4" >
                                        <div class="col-md-6" style="min-height: 350px;">
                                            <div class="position-relative h-100">
                                                <img class="position-absolute img-fluid" src="{{URL('public/assets/img/slider/Dafy-Single-Trip.gif')}}"
                                                    style="object-fit: cover; border-radius: 50% 0 50% 0 ;" alt="">
                                            </div>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <div class="collapsed-content d-none d-lg-block">
                                                <h3 class="mb-3 text-primary">One-Side Service</h3>
                                                <p><i class="fa fa-check text-success me-3"></i> Hire professional drivers to pick up from bars & eliminate the hustle of driving</p>
                                                <p><i class="fa fa-check text-success me-3"></i> No more driving in heavy traffic and locating a parking spot</p>
                                                <p><i class="fa fa-check text-success me-3"></i> Hire drivers for airport pickup or drop-off services</p>
                                                <a class="btn btn-warning rounded-pill py-3 px-5" href="tel:917592933933"><strong>call now</strong></a>
                                                <!-- <a class="btn btn-warning rounded-pill py-3 px-5" data-bs-toggle="modal" data-bs-target="#book1" href="">Book Now</a> -->
                                            </div>
                                            <input type="checkbox" class="d-lg-none .d-xl-block btn btn-link btn-light read-more  py-3 px-10" id="read-more"> 
                                        </div>
                                        
                                    </div>
                                </div>
        
                                <!-- Round Trip Ride -->
                                <div class="tab-pane fade" id="tab-pane-2">
                                    <div class="row g-4">
                                        <div class="col-md-6" style="min-height: 350px;">
                                            <div class="position-relative h-100">
                                                <img class="position-absolute img-fluid" src="{{URL('public/assets/img/slider/Dafy-Round-Trip.gif')}}"
                                                    style="object-fit: cover;border-radius: 0 50% 0 50%  ;" alt="">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="collapsed-content d-none d-lg-block">
                                                <h3 class="mb-3 text-primary">Round-Trip Service</h3>
                                                <p><i class="fa fa-check text-success me-3"></i> Hire professional drivers to commute your vehicle.</p>
                                                <p><i class="fa fa-check text-success me-3"></i> Simply get around in town for midnight cravings, shopping, and night parties.</p>
                                                <p><i class="fa fa-check text-success me-3"></i> Make a quick stop at any stores/pharmacies on your rides</p>
                                                <p><i class="fa fa-check text-success me-3"></i> No more delays on your meeting schedules</p>
                                                <a class="btn btn-warning rounded-pill py-3 px-5" href="tel:917592933933"><strong>call now</strong></a>
                                                <!-- <a class="btn btn-warning rounded-pill py-3 px-5" data-bs-toggle="modal" data-bs-target="#book1" href="">Book Now</a> -->
                                            </div>
                                             <input type="checkbox" class="d-lg-none .d-xl-block btn btn-link btn-light read-more  py-3 px-10" id="read-more">  
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Health and well-being -->
                                <div class="tab-pane fade" id="tab-pane-3">
                                    <div class="row g-4">
                                        <div class="col-md-6" style="min-height: 350px;">
                                            <div class="position-relative h-100">
                                                <img class="position-absolute img-fluid" src="{{URL('public/assets/img/banner/b.jpeg')}}"
                                                    style="object-fit: cover; border-radius:30% 30% 30% 30%;" alt="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="collapsed-content d-none d-lg-block">
                                                <h3 class="mb-3 text-primary">Hospital Assistance</h3>
                                                <p><i class="fa fa-check text-success me-3"></i> You can trust that your loved ones are in capable and caring hands.</p>
                                                <p><i class="fa fa-check text-success me-3"></i> We prioritize the physical and emotional well-being of seniors.</p>
                                                <p><i class="fa fa-check text-success me-3"></i> Our experienced drivers are here to provide a comfortable and supportive environment during their hospitalization.</p>
                                                <a class="btn btn-warning rounded-pill py-3 px-5" href="tel:917592933933"><strong>call now</strong></a>
                                                <!-- <a class="btn btn-warning rounded-pill py-3 px-5" data-bs-toggle="modal" data-bs-target="#book1" href="">Book Now</a> -->
                                            </div>
                                             <input type="checkbox" class="d-lg-none .d-xl-block btn btn-link btn-light read-more  py-3 px-10" id="read-more">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $('.read-more').on('click', function () {
                            var content = $(this).siblings('.collapsed-content');
                            content.toggleClass('d-none d-lg-block');

                            var icon = $(this).find('.fa');
                            icon.toggleClass('fa-angle-down fa-angle-up');
                        }); });
                </script>
                <style>
                     .read-more{
                        appearance: none;
                        border: transparent;
                        padding: .5em;
                        border-radius: .25em;
                        cursor: pointer;
                        margin-top: 1rem;
                        
                    }
                    .read-more:hover{
                        background-color:none;
                        grid-auto-rows:inherit;
                        
                    }
                    .read-more::before{
                        content: "↓ more";
                    }
                    .read-more:checked::before{
                        content: "↑ less";
                    }
                </style>
        </section>
    <!--------------------------------------------------- Service End ------------------------------------->
    <!--------------------------------------------- Features Start ------------------------------------------------->
 
     <section id="feature"> <br><br>
        <div class="container-xxl py-5">
            
            <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.2s" style="max-width:500px; max-height:100px;">
                    <!--p class="fs-5 fw-medium" style="color: #ffae00;">Features Service</p-->
                    <h1 class="display-5 mb-5">Explore Our Features</h1>
                </div>
                <div class="row g-0 feature-row"  style="background-color:black; height:fit-content; animation: fadeInRight 5s 1;color:white;">
                    <div class="col-md-6 col-lg-3 wow fadeIn animated " data-wow-delay="0.1s" >
                        <div class="feature-item border h-100 p-5 " style="animation: fadeInRight  5s 1;">
                            <div class="btn-square bg-white rounded-circle mb-4" style="width: 70px; height: 70px;">
                                <img class="img-fluid" src="{{URL('public/assets/img/icon/personal.png')}}" alt="Icon">
                            </div>
                            <h5 class="mb-3 text-warning">Personalized Service</h5>
                            <p class="mb-0"><i>Our commitment lies in understanding our client’s unique requirements and interests, ensuring and
                                providing customized service to meet all their expectations.</i> </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 wow fadeIn animated " data-wow-delay="0.3s">
                        <div class="feature-item border h-100 p-5 " style="animation: fadeInRight  5s 1;">
                            <div class="btn-square bg-white rounded-circle mb-4" style="width: 70px; height: 70px;">
                                <img class="img-fluid" src="{{URL('public/assets/img/icon/city.png')}}" alt="Icon">
                            </div>
                            <h5 class="mb-3 text-warning">In-City Rides</h5>
                            <p class="mb-0"> <i>Ensure that your drivers are familiar with the citys roads and traffic patterns. Optimize routing to
                                minimize travel time and provide efficient and timely rides.</i></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 wow fadeIn animated " data-wow-delay="0.5s">
                        <div class="feature-item border h-100 p-5 " style="animation: fadeInRight  5s 1;">
                            <div class="btn-square bg-white rounded-circle mb-4" style="width: 70px; height: 70px;">
                                <img class="img-fluid" src="{{URL('public/assets/img/icon/outstation.png')}}" alt="Icon">
                            </div>
                            <h5 class="mb-3 text-warning">Outstation Rides</h5>
                            <p class="mb-0"><i>
                                Reliable and Professional Drivers, who are experienced and skilful. who are familiar with long-
                                distance travel and have excellent customer service.
                            </i></p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 wow fadeIn animated " data-wow-delay="0.7s">
                        <div class="feature-item border h-100 p-5 " style="animation: fadeInRight  5s 1;">
                            <div class="btn-square bg-white rounded-circle mb-4" style="width: 70px; height: 70px;">
                                <img class="img-fluid" src="{{URL('public/assets/img/icon/bar.png')}}" alt="Icon">
                            </div>
                            <h5 class="mb-3 text-warning">Bar Pickup</h5>
                            <p class="mb-0"><i > Bar pick-up driver service offers convenience to customers by providing a designated driver.
                                It helps prevent drunk driving accidents and promotes responsible drinking. 
                                Patrons can enjoy a night out without worrying about getting home safely.</i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
     <!--------------------------------------------------- Feactures End ------------------------------------->
    <!--------------------------------------------------- About Start ----------------------->
            <section id="about"> <br><br>
                    <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.2s" style="max-width: 50%;"id="about">
                        <!--p class="fs-5 fw-medium" style="color: #ffae00;">Our Services</p-->
                        <h1 class="display-5 mb-5">About Us</h1>
                    </div>
                        <div class="container-xxl about my-5" >
                            <div class="container">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="h-100 d-flex align-items-center justify-content-center" style="height: 100%;">
                                            <button type="button" class="btn-play" data-bs-toggle="modal" data-bs-target="#videoModal">
                                                <span></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pt-lg-5 wow fadeIn" data-wow-delay="0.5s">
                                        <div class="bg-white rounded-top p-5 mt-lg-5 animated slideInRight">
                                            <p class="fs-5 fw-medium text-warning">About Us</p>
                                            <h1 class="display-6 mb-4">DAFY Online Pvt. Ltd.</h1>
                                            <p class="mb-4"><i class="fa fa-check text-success me-2"></i>At DAFY Online, we understand that your time is valuable, and 
                                                our services are designed to cater to your unique needs. </p>
                                                <p><i class="fa fa-check text-success me-2"></i>
                                                Whether you need a reliable driver for your daily commute, a professional chauffeur 
                                                for a special occasion, or assistance with transportation logistics, we have you covered.</p>
                                                <p><i class="fa fa-check text-success me-2"></i>Our user-friendly platform makes booking 
                                                our services quick and easy. 
                                                We are dedicated to provide convenient and reliable assistants 
                                                to help you manage your busy lifestyle with ease</p>
                                            <div class="row g-5 pt-2 mb-5">
                                                <div class="col-sm-6 animated slideInRight">
                                                    <img class="img-fluid mb-4" src="{{URL('public/assets/img/icon/icon-5.png')}}" alt="">
                                                    <h5 class="mb-3">Managed Services</h5>
                                                    <span>our experienced drivers are trained to provide reliable and discreet transportation services.</span>
                                                </div>
                                                <div class="col-sm-6 animated slideInLeft">
                                                    <img class="img-fluid mb-4" src="{{URL('public/assets/img/icon/icon-2.png')}}" alt="">
                                                    <h5 class="mb-3">Dedicated Experts</h5>
                                                    <span>With our experienced caregivers, you can trust that
                                                        your loved ones are in capable and caring hands. </span>
                                                </div>
                                            </div>
                                            <a class="btn btn-white rounded-pill py-3 px-5" href=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <!-- About End -->        

                <!-- Video Modal Start -->
                <div class="modal modal-video fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-0">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">DAFY Video</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- 16:9 aspect ratio -->
                                <div class="ratio ratio-16x9 clip">
                                    <!-- <iframe class="embed-responsive-item" src="" id="video" allowfullscreen
                                        allowscriptaccess="always" allow="autoplay"></iframe> -->
                                        <video id="videoPlayer"  autoplay>
                                        <source src="{{URL('public/assets/video/01_1.mp4')}}" type="video/mp4">
                                        <source src="{{URL('public/assets/video/02-2.mp4')}}" type="video/mp4">
                                        </video>  
                                     
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Video Modal End -->
           

            </section>
    <!--------------------------------------------------- Service Start ------------------------------------->
    <!--------------------------------------------------- enquiry End ------------------------------------->
        
                <section id="expert"><br><br>
                    <div class="container-xxl py-5" >
                        <div class="container">
                            <form action="" method="post">
                                <div class="row g-5">
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                                        <!--p class="fs-5 fw-medium text-warning">Get A Quote</p-->
                                        <h1 class="display-5 mb-4">Need Our Expert Help? We're Here!</h1>
                                        <p> Our user-friendly platform makes booking our services quick and easy. 
                                            We are dedicated to provide convenient and reliable assistants to help you</p>
                                        <p class="mb-4">Whether you need a reliable driver for your daily commute, 
                                            a professional chauffeur for a special occasion, or 
                                            assistance with transportation logistics, we have you covered.</p>
                                        <a class="d-inline-flex align-items-center rounded overflow-hidden border border-warning"
                                        href="tel:917592933933">
                                            <span class="btn-lg-square bg-warning" style="width: 55px; height: 55px;">
                                                <i class="fa fa-phone-alt text-white"></i>
                                            </span>
                                            <span class="fs-5 fw-medium mx-4" style="color:black;">+91 7592933933</span>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                                        <div class="mapouter">
                                            <div class="gmap_canvas">
                                                <iframe class="gmap_iframe" width="100%" frameborder="0" 
                                                scrolling="no" marginheight="0" marginwidth="0" 
                                                src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=kochin&amp;
                                                t=p&amp;z=10&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" style="min-height: 450px; border:0;" 
                                                allowfullscreen="" aria-hidden="false"
                                                tabindex="0">
                                            </iframe><a href="https://capcuttemplate.org/">
                                                Capcut Template</a></div>
                                                <style>.mapouter{position:relative;
                                                    text-align:right;
                                                    width:100%;height:400px;}
                                                    .gmap_canvas {overflow:hidden;
                                                    background:none!important;
                                                    width:100%;height:400px;}
                                                    .gmap_iframe {height:400px!important;}
                                                </style>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
    <!--------------------------------------------------- enquiry End ------------------------------------->
    <!--------------------------------------------------- Team Start ------------------------------------->
        <section id="team">
            <!-- Project Start -->
            <div class="container-xxl pt-5">
                <div class="container">
                    <div class="text-center text-md-start pb-5 pb-md-0 wow fadeInUp" data-wow-delay="0.1s"
                        style="max-width: 500px;">
                        <!-- <p class="fs-5 fw-medium text-primary">Our Team</p> -->
                        <h1 class="display-5 mb-5">Our Expert People Ready to Help You</h1>
                    </div>
                    <div class="owl-carousel project-carousel wow fadeInUp" data-wow-delay="0.1s">

                        <div class="project-item mb-5">
                                <div class="position-relative">
                                    <img class="img-fluid" src="{{URL('public/assets/img/team/ceo1.jpg')}}" alt="">
                                </div>
                                <div class="p-4">
                                    <a class="d-block h5 text-center" href="">Manikandan Ak <br>
                                    <span class="text-warning">CEO</span></a>
                                </div>
                            </div>
                      
                            <div class="project-item mb-5">
                                <div class="position-relative">
                                    <img class="img-fluid h-100" src="{{URL('public/assets/img/team/team-5.jpg')}}" alt="" >
                                    
                                </div>
                                <div class="p-4">
                                    <a class="d-block h5 text-center" href="">Arun Chandrasekaran <br>
                                    <span class="text-warning">COO</span></a>
                                </div>
                            </div>
                            <div class="project-item mb-5">
                                <div class="position-relative">
                                    <img class="img-fluid" src="{{URL('public/assets/img/team/team-5.jpg')}}" alt="">
                                    
                                </div>
                                <div class="p-4">
                                    <a class="d-block h5 text-center" href="">Jijo Louis <br>
                                    <span class="text-warning">HR's Head</span></a>
                                </div>
                            </div>
                            <div class="project-item mb-5">
                                <div class="position-relative">
                                    <img class="img-fluid" src="{{URL('public/assets/img/team/account.jpg')}}" alt="">
                                    
                                </div>
                                <div class="p-4">
                                    <a class="d-block h5 text-center" href="">Stephin Jose <br>
                                    <span class="text-warning">Account's Head</span></a>
                                </div>
                            </div>
                            <div class="project-item mb-5">
                                <div class="position-relative">
                                    <img class="img-fluid" src="{{URL('public/assets/img/team/team-5.jpg')}}" alt="">
                                    
                                </div>
                                <div class="p-4">
                                    <a class="d-block h5 text-center" href="">Josen Kuriakose <br>
                                        <span class="text-warning">Operation's Head</span></a>
                                </div>
                            </div> 
                       
                       
                        
                    </div>
                </div>
            </div>
            <!-- Project End -->
        </section>
    <!--------------------------------------------------- Team  end ------------------------------------->
    <!--------------------------------------------------- Testimonial  Start ------------------------------------->
    <section id="testimonial">
        <div class="container-xxl pt-5">
            <div class="container">
                <div class="text-center text-md-start pb-5 pb-md-0 wow fadeInUp" data-wow-delay="0.1s"
                    style="max-width: 500px;">
                    <h1 class="display-5 mb-5">What Clients Say About Our Services!</h1>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                    <div class="testimonial-item rounded p-4 p-lg-5 mb-5">
                                <h5 class="text-center">SUJITH E R</h5>
                                <p class="mb-3 limitline">I recently had the opportunity to avail myself the service provided by DAFY,
                                    and I am pleased to share my experience. One aspect that impressed me was their responsiveness.
                                    Whenever I needed assistance or had a question, their customer service team was readily available 
                                    and provided prompt and helpful responses. Their efficiency in handling these matters was truly commendable. 
                                    I highly recommend DAFY's service based on my positive experience. I am grateful for their assistance 
                                    and would not hesitate to utilize their services again if needed in the future.</p>
                                    <input type="checkbox" class="expand-btn" id="expand-btn-1">
                                

                            </div>
                      
                            <div class="testimonial-item rounded p-4 p-lg-5 mb-5">
                                <h5 class="text-center">ANANDHU S </h5>
                                <p class="mb-3 limitline">The efficiency and punctuality of the drivers provided by
                                    DAFY had impressed me a lot. Whenever I needed a driver to take me and my friends to 
                                    various lounge and venues, they were always prompt and ready to assist. Their knowledge of 
                                    the local area and the best routes ensured that we reached our destinations without any hassle. 
                                    The drivers were polite, courteous, and made us feel safe throughout the journey.</p>
                                    <input type="checkbox" class="expand-btn" id="expand-btn">
                                
                            </div>
                            <div class="testimonial-item rounded p-4 p-lg-5 mb-5">
                                <h5 class="text-center">JAYESH K</h5>
                                <p class="mb-3 limitline">I recently had the pleasure of using the professional driver assistant service provided by DAFY for my family.
                                    One aspect that stood out to me was the professionalism and expertise of the driver provided by DAFY.
                                    He was not only skilled behind the wheel but also displayed a warm and friendly approach towards us. 
                                    We felt safe on the road, and the driver consistently exhibited the highest standards of professionalism and respect.
                                    I would not hesitate to engage their services again in the future.</p>
                                    <input type="checkbox" class="expand-btn" id="expand-btn">
                                
                            </div>
                            <div class="testimonial-item rounded p-4 p-lg-5 mb-5">
                                <h5 class="text-center">SAJESH V</h5>
                                <p class="mb-4 limitline">Using the driver service was a truly amazing experience for me. 
                                    It blew me away with its excellent service, The driver was attentive and professional, 
                                    ensuring that I had a comfortable and safe journey. 
                                    I was truly impressed with the level of service and would
                                    definitely use it again.</p>
                                    <input type="checkbox" class="expand-btn" id="expand-btn">
                                
                            </div>
                            <div class="testimonial-item rounded p-4 p-lg-5 mb-5">
                                <h5 class="text-center">ARCHIT M D</h5>
                                <p class="mb-4 limitline">I've been using DAFY's driver/caretaker service for several months now, 
                                    and I must say they have exceeded my expectations in every way!Considering the exceptional service 
                                    and professionalism they provide; their charges are more than fair. They strike a perfect balance 
                                    between affordability and quality, making them a top choice in the industry.
                                    I highly recommend DAFY for anyone seeking a driver/caretaker service.
                                    Their drivers are polite, friendly, and go the extra mile to ensure customer satisfaction.
                                    With their availability, punctuality, reasonable prices, and commitment to safety, 
                                    DAFY has set a new standard in the industry. 
                                    I am grateful for their outstanding service and will continue relying on them for all my transportation needs.</p>
                                    <input type="checkbox" class="expand-btn" id="expand-btn">
                                
                            </div>
                            <div class="testimonial-item rounded p-4 p-lg-5 mb-5 limitext">
                                <h5 class="text-center">NANCY JACOB</h5>
                                <p class="mb-4 limitline">I recently experienced the professional driver assistance service provided by DAFY,
                                    and I am delighted to share my positive experience. 
                                    From the moment I engaged with the driver, he displayed a high level of professionalism, 
                                    attentiveness, and respect, making me feel safe and comfortable throughout my journeys. 
                                    He is very attentive to my specific needs and preferences like adjusting the temperature, 
                                    ensuring privacy, or helping with luggage, thus made my journey comfortable and stress-free.</p>
                                <input type="checkbox" class="expand-btn" id="expand-btn">
                               </div>
                       
                            <!-- <?php
                                // Fetch data from the 'review' table
                                // $sql = "SELECT * FROM `testimonial` ORDER BY date DESC LIMIT 6";
                                // $result = $conn->query($sql); 
                                //     if ($result->num_rows > 0) {
                                //         while ($row = $result->fetch_assoc()) {
                                //             // Display each row of data
                                //         ?>
                                 <div class="testimonial-item rounded p-4 p-lg-5 mb-5 limitext">
                                     <h5 class="text-center"><?php //echo  $row['name'];  ?></h5>
                                     <p class="mb-4 limitline"><?php //echo  $row['review'];  ?></p>
                                     <input type="checkbox" class="expand-btn" id="expand-btn">
                                 </div> 
                                 <?php
                                // }
                                // } else {
                                // echo '<tr><td colspan="12">No data found.</td></tr>';}
                            ?> -->
                      
                    </div>
                </div>
            </div>
            
            <style>
                .limitline {
                        --max-lines: 5;
                    --line-height: 1.4;
                    max-height: calc(var(--max-lines)*1em*var(--line-height));
                        line-height: var(--line-height);
                        display: flex;
                        overflow: hidden;
                        position: relative;
                        justify-content: center;
                        align-content: stretch;
                            }
                        .limitext::before{
                            content: "";
                            position: absolute;
                            height: calc(1em * var(--line-height));
                            width: 100%;
                            bottom: 0;
                            pointer-events: none;
                            background: linear-gradient(to bottom,transparent,white);
                        }
                
                    .expand-btn{
                        appearance: none;
                        border: transparent;
                        padding: .5em;
                        border-radius: .25em;
                        cursor: pointer;
                        margin-top: 1rem;
                        
                    }
                    .expand-btn:hover{
                        background-color:none;
                        grid-auto-rows: auto;
                        
                    }
                    .expand-btn::before{
                        content: "Read more";
                    }
                    .expand-btn:checked::before{
                        content: "Read less";
                    }
                    .limitline:has(+ .expand-btn:checked){
                        max-height: none;
                    }
                    
                </style>
        </section>
    <!--------------------------------------------------- Testimonial  end ------------------------------------->
    <!-- ====================================== Footer Start ================================================ -->  
    <div class="container-fluid bg-dark footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s" id="contact">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Our Office</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i> 34/192C
                        PNRA – 67
                        Melthara Lane, Padivattom
                        Edappally, Kochi
                        Kerala - 682024</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+91 7592933933</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>www.dafy.in</p>
                    <div class="d-flex pt-3"> 
                        <a class="btn btn-square btn-light rounded-circle me-1" href="https://twitter.com/dafyindia">
                            <i class="fab fa-twitter"></i></a>
                       <a class="btn btn-square btn-light rounded-circle me-1" href="https://www.facebook.com/dafyonline">
                           <i class="fab fa-facebook-f"></i></a>
                       <a class="btn btn-square btn-light rounded-circle me-1" href="https://www.youtube.com/channel/UCaDXn5tA7A4-pX1srS1fJ6A"><i
                               class="fab fa-youtube"></i></a>
                       <a class="btn btn-square btn-light rounded-circle me-1" href="https://www.instagram.com/dafyonline/">
                       <i class='fab fa-instagram'></i></a>
                       <a class="btn btn-square btn-light rounded-circle me-2" href="https://api.whatsapp.com/send?phone=91 7592933933">
                       <i class='fab fa-whatsapp'></i></a>
                       <a class="btn btn-square btn-light rounded-circle me-1" href="https://www.linkedin.com/company/dafyonline/">
                       <i class='fab fa-linkedin-in'></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="#about">About Us</a>
                    <a class="btn btn-link" href="#expert">Contact Us</a>
                    <a class="btn btn-link" href="#service">Our Services</a>
                    <a class="btn btn-link" href="notes/term.php">Terms & Condition</a>
                    <a class="btn btn-link" href="">Support</a>
                    <a class="btn btn-link" href="notes/policy.php">Policy</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Business Hours</h4>
                    <p class="mb-1">Monday - Sunday</p>
                    <p class="mb-4">Any Time</p>
                    <p class="mb-4">Ready for Services</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Subscribe</h4>
                    <p>Need Asisstance for drive home, anytime anywhere we are here to help</p>
                    <div class="position-relative w-100">
                        <a type="button" class="btn btn-warning py-2 position-absolute top-0 end-0 mt-2 me-2" data-bs-toggle="modal" data-bs-target="#book4"
                         href="">Subscribe Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================================= Footer End ================================================== -->
    <!-- ======================================= Copyright Start ================================================== -->
    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="fw-medium text-light" href="#">2023</a>, All Right Reserved.
                </div>
                <!--div class="col-md-6 text-center text-md-end">

                    Designed By <a class="fw-medium text-light" href="#team">Dafy Team</a>
                    Distributed By <a class="fw-medium text-light" href="#about">Dafy Online pvt. ltd.</a>
                </div-->
            </div>
        </div>
    </div>
    <!-- ======================================== Copyright End =============================================== -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-warning btn-square rounded-circle back-to-top"  style="display: inline;">
    <i class="fa fa-angle-up"></i></a>
    <!-- ===========================Modal Pop-Up ==============================================================-->
             <!---------------------------- One-Ride Modal ----------------------------------->
    <!-- <div class="modal fade right" id="book1" tabindex="-1" aria-labelledby="exampleModalLabel"   aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="booking" class="section-center bg-dark">
                    <div class="booking-form">
                            <div class="form-header text-center text-primary">
                                <h2 class="text-primary">Booking Service</h2>
                            </div>
                        <div class="modal-body">
                            <form method="POST" action="database/insert_data.php" onsubmit="showConfirmation()">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="form-group">
                                            <label class="form-label">Name</label><br>
                                            <input class="form-control" type="text" name="name3" pattern="[A-Z\sa-z]{3,20}" required>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">

                                            <label class="form-label">E-mail</label><br>
                                            <input class="form-control" type="text" name="email3" required>
                                            
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                            
                                            <label class="form-label">Phone</label><br>
                                            <input class="form-control" type="tel"  name="phone3" pattern=(\d{3})?\s?(\d{3})?\s?(\d{4}) maxlength="10" required>
                                            
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                                <label class="form-label">Type</label><br>
                                            <select class="form-control"  name="ride-type3" id="b-type3" required >
                                            <option  selected>Select</option>
                                            <option value="one_trip">One-Side-Trip</option>
                                            <option value="round_trip">Round-Trip</option>
                                            <option value="hospital_trip">Hospital-Assistance</option>
                                            </select>
                                            <span class="select-arrow"></span>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-10" id="round">
                                    <div class="form-group">
                                        <label class="form-label" for="select">Choose Ride</label><br>
                                        <select class="form-control" name="t-type3" id="t-type3"  required >
                                        <option active>Select</option>
                                        <option value="schedule_trip">Schedule Ride</option>
                                        <option value="immediate_trip">Immediate Pick-up</option>
                                        </select>
                                        <span class="select-arrow"></span>
                                        
                                    </div>
                                </div>
                                </div>


                                <div class="data1">
                                    <div class="row">	
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label class="form-label">Pick-up Location</label><br>
                                            <input class="form-control" type="text" name="pick_up3" id="pick3" required>

                                            </div>
                                        </div>							
                                        <div class="col-md-6" >
                                            <div class="form-group" >
                                                <label class="form-label">Drop location</label><br>
                                            <input class="form-control" type="text" name="drop_in3" id="drop3" required>
                                            </div>
                                    </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="form-label">Vechile Name</label><br>
                                                <select class="form-control" id="v-name3" name="v_name3"required>
                                                <option active>Select</option>
                                                <option>HatchBack</option>
                                                <option>Sedan</option>
                                                <option>SUV</option>
                                                <option>Luxury</option>
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="form-label">Vechile Type</label><br>
                                            <select class="form-control" id="v-type3" name="v_type3" required>
                                                    <option active>Select</option>
                                                    <option >Automatic</option>
                                                    <option>Manaual</option>
                                                    <option>Both</option>
                                                </select>
                                                <span class="select-arrow"></span>

                                            </div>
                                        </div>
                                        <div class="col-10 "id="date3">
                                            <div class="form-group">
                                                <label class="form-label"> Date </label><br>
                                                <input class="form-control" type="date"  name="date_in3"  min="<?=date('Y-m-d\TH:i');?>" >
                                            </div>
                                        </div>
                                        <div class="col-10 " id="time3">
                                            <div class="form-group">
                                                <label class="form-label"> Time</label><br>
                                                <input class="form-control" type="time" name="time_in3" >
                                            </div>
                                        </div>
                                        </div>
                            
                                        <div class="row" id="book">
                                            <div class="col-10">
                                                <div class="form-btn">
                                                <button type="submit" class="submit-btn rounded-pill text-center py-2 px-3 w-100" name="addData">Book </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row data2">
                                                <div class="col-10">
                                                    <div class="form-btn">
                                                    <button type="submit" class="submit-btn rounded-pill text-center py-2 px-3 w-100" name="addDataWithDateTime">book</button>
                                                    </div>
                                                </div>
                                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

        
    <!----   Subscribe US button          ---->
        <!-- <div class="modal fade" id="book4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="booking-form">
                            <div class="form-header">
                                <h2 class="text-primary">Subscribe Us</h2>
                            </div>
                        <div class="modal-body ">
                            <form action="database/subcribe-add.php" method="POST">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="text" placeholder="Enter your name" id="name1" pattern=[A-Z\sa-z]{3,20} required>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="email" placeholder="Enter your email" id="email1" required>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="tel" placeholder="Enter your phone number" id="phone1" pattern=(\d{3})?\s?(\d{3})?\s?(\d{4}) required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 justify-content-center">
                                    <div class="form-btn validate">
                                    <button class="submit-btn w-100 py-3 px-3 rounded-pill" id="subscribe-submit" name="subscribe-submit">Subscribe Us</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    <!--- join US driver --->
            <!-- <div class="modal fade" id="book5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="booking-form">
                            <div class="form-header">
                                <h2 class="text-primary">Join Us</h2>
                            </div>
                        <div class="modal-body">
                            <form action="database/cv_add.php" method="post">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="text" placeholder="Enter your name" id="name2" name="name2" pattern=[A-Z\sa-z]{3,20} required>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="email" placeholder="Enter your email" id="email2" name="email2" required>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="tel" placeholder="Enter your phone number" name="phone2" id="phone2" pattern=(\d{3})?\s?(\d{3})?\s?(\d{4}) required>
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input class="form-control validate" type="file"id="file-cv1" name="cv-file1" required placeholder="upload your cv/resume">
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <div class="form-btn validate">
                                            <button class="submit-btn py-3 px-3 w-100 rounded-pill" id="join-submit"name="join-submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div> -->


        <!--- ============================ Booking form control js =================================== -->
                        <!-- <script>
                            // function showConfirmation() {
                            //     alert("Confirm booking");
                            //     return true; // Allow the form submission to proceed
                            // }
                        </script>
                        <style>
                            /* .data1, .data2{
                                display: none;
                            } */
                        </style> -->
                        <!-- <script src="assert/js/jquery.min.js"></script>
                        <script>// Booking Button option 
                            // $(document).ready(function() {
                            //             // Initially hide all data sections
                            //             $(".data1").hide();
                            //             $(".data2").hide();
                            //             // Show the appropriate data section based on the selected value in the "Type" dropdown
                            //             $("#b-type3,#t-type3").change(function() {
                            //                 var selectedType = $("#b-type3").val();
                            //                 var selectedRide = $("#t-type3").val();
                            //                 if (selectedType === "one_trip"){ 
                            //                     if( selectedRide ==="schedule_trip"){
                            //                     $(".data2").hide();
                            //                     $(".data1").show();
                            //                 }else if (selectedRide === "immediate_trip") {
                            //                     $(".data2, .data1").show();
                            //                     $('#date3, #time3, #book').hide();
                            //                 }
                            //                 }else if (selectedType === "round_trip"){ if (selectedRide ==="schedule_trip"){
                            //                     $(".data2").hide();
                            //                     $(".data1").show();
                            //                 } else if (selectedRide === "immediate_trip") {
                            //                     $(".data2, .data1").show();
                            //                     $('#date3, #time3, #book').hide();

                            //                 }
                            //                 } else if (selectedType === "hospital_trip"){ if( selectedRide ==="schedule_trip"){
                                                
                            //                     $(".data1").show();
                            //                     $(".data2").hide();
                            //                 }else if (selectedRide === "immediate_trip") {
                            //                     $(".data2, .data1").show();
                            //                     $('#date3, #time3, #book').hide();
                                            
                            //                 }
                            //                 }else{
                            //                     $(".data2").hide();
                            //                     $(".data1").hide();
                            //                 }
                            //             });
                                    });
                            
                        </script>
                     -->
                    <!-- ================ form control ================ -->
                        <!-- <script>
                            // $('.form-control').each(function () {
                            //     floatedLabel($(this));
                            //     });
                    
                            //     $('.form-control').on('input', function () {
                            //         floatedLabel($(this));
                            //     });
                    
                            //     function floatedLabel(input) {
                            //         var $field = input.closest('.form-group');
                            //         if (input.val()) {
                            //             $field.addClass('input-not-empty');
                            //         } else {
                            //             $field.removeClass('input-not-empty');
                            //         }
                            // }
                        </script> -->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

                        <!--- ============================ Booking form control js End =================================== -->
    <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


        <script src="{{URL('public/assets/lib/wow/wow.min.js')}}"></script>
        <script src="{{URL('public/assets/lib/easing/easing.min.js')}}"></script>
        <script src="{{URL('public/assets/lib/waypoints/waypoints.min.js')}}"></script>
        <script src="{{URL('public/assets/lib/owlcarousel/owl.carousel.min.js')}}"></script>
        <script src="{{URL('public/assets/lib/lightbox/js/lightbox.min.js')}}"></script>
        <!-- Template Javascript -->
        <script src="{{URL('public/assets/js/video.js')}}"></script>
        <script src="{{URL('public/assets/js/main.js')}}"></script>
    <!-- JavaScript Libraries End -->    
</body>
</html>