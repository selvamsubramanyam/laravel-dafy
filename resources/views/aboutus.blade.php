<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="images/favicon.png" type="image/gif" sizes="16x16">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <title>Dafy</title>

    <style>

html body {
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
    margin: 0;
}

        .container {
    /* width: 100%; */
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

.navBar-section .navbar {
    
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    background-color: #fff !important;
    padding: 10px 30px;
    height: 6rem;
    margin-top: 3rem;
    z-index: 1;
}

.navBar-section .navbar img {
    /* width: 65px!important; */
    height: 100%;
}

.terms-conditions-section {
    margin-top: -20rem;
    position: relative;
    margin-bottom: 5rem;
}

.terms-conditions-section .terms-conditions h1 {
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 1.5rem;
}

.terms-conditions-section .terms-conditions p {
    font-size: 15px;
    font-weight: 500;
    color: #666666;
    line-height: 1.7;
    margin-bottom: 2rem;
    text-align: justify;
}

.terms-conditions-section .img-2 {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0.035;
    width: 100%;
}

.terms-conditions-section .terms-conditions {
    margin-top: 23rem;
}
.terms-conditions-section .terms-conditions h2 {
    color: #e6243c;
}
@media (max-width: 991.98px){
    .navBar-section .navbar {
    padding: 10px 10px;
    height: 100px;
}
.navBar-section .navbar .navbar-brand {
    margin-left: 15px;
}
}

@media (min-width: 576px){
    .container {
    max-width: 540px;
}
}
@media (min-width: 768px){
    .container {
    max-width: 720px;
}
}

@media (min-width: 992px){
    .container {
    max-width: 960px;
}

}


@media (min-width: 1200px){
    .container {
    max-width: 1140px;
}
}



    </style>


</head>
<body>



<section class="navBar-section" id="myHeader">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" >
                <img src="{{URL('public/images/logo.png')}}" alt="">             
            </nav>
        </div>
    </section>



<section class="terms-conditions-section">
    <img class="img-2" src="{{URL('public/images/favicon.png')}}" alt="">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="terms-conditions">
                    <h1>About Us</h1>
                    <p>Dafy was started with a vision to integrate the most important things you do with your
                        smartphones into a single application. We bring together ‘Business Exploring’, ‘Messaging
                        services’ and ‘Media streaming’ into a single platform. With our app, you can easily keep up
                        with the pace of this technology-driven world where you will usually have to switch between
                        various apps. The app which is catered to businessmen and others alike provides you innovative
                        features that even leading applications do not offer.</p>
                </div>
            </div>
        </div>
    </div>
</section>

