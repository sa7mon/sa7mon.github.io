<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Dan Salmon</title>

        <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/freelancer.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

        <!--Github-activity -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/octicons/2.0.2/octicons.min.css">
        <link rel="stylesheet" href="css/github-activity-0.1.0.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body id="page-top" class="index">
        <!-- Header -->
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <img class="img-responsive" src="img/profile.png" alt="">
                        <div class="intro-text">
                            <span class="name">Dan Salmon</span>
                            <hr class="star-opaque">
                            <span class="skills">
                                [<a href="http://southcentral.edu" target="_blank">student</a>] 
                                [<a href="http://github.com/sa7mon" target="_blank">programmer</a>] 
                                [<a href="http://themanchesterorchestra.com/" target="_blank">music&nbsp;lover</a>]
                            </span>
                            <br />
                        </div>
                        <div class="col-lg-8 col-lg-offset-2 text-center page-scroll" id="buttons">
                            <a href="#blog" class="btn btn-primary btn-outline">
                                <i class="fa fa-external-link"></i> Blog
                            </a>
                            <a href="#about" class="btn btn-primary btn-outline">
                                <i class="fa fa-male"></i> About
                            </a>
                            <a href="#github" class="btn btn-primary btn-outline">
                                <i class="fa fa-code"></i> Git
                            </a>
                            <a href="#resume" class="btn btn-primary btn-outline">
                                <i class="fa fa-briefcase"></i> Resume
                            </a>
                            <a href="#contact" class="btn btn-primary btn-outline">
                                <i class="fa fa-comment"></i> Contact
                            </a>
                            <a href="#site" class="btn btn-primary btn-outline">
                                <i class="fa fa-comment"></i> Site
                            </a>
                        </div>
                        <div id="bottomspacer"></div>
                    </div>
                </div>
            </div>
        </header>

        <div class="spacer"></div>

        <!-- Navigation -->

            <nav id="nav" class="navbar navbar-default">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#page-top">HOME</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right" role="navigation">
                            <li class="hidden">
                                <a href="#page-top"></a>
                            </li>
                            <li class="page-scroll">
                                <a href="#blog">Blog</a>
                            </li>
                            <li class="page-scroll">
                                <a href="#about">About</a>
                            </li>
                            <li class="page-scroll">
                                <a href="#github">Git</a>
                            </li>
                            <li class="page-scroll">
                                <a href="#resume">Resume</a>
                            </li>
                            <li class="page-scroll">
                                <a href="#contact">Contact</a>
                            </li>
                            <li class="page-scroll">
                                <a href="#site">Site</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </div>
                <!-- /.container-fluid -->
            </nav>

        <!-- Blog Section -->
        <section id="blog" class="clear">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Blog</h2>
                        <hr class="star-clear">
                    </div>
                </div>
                <div class="row">
                <?php
                    include 'blog/wp-recent-posts.php';
                ?>    
                </div>
                <div class="col-lg-12 text-center">
                    <a href="./blog/" target="_blank" class="btn btn-outline">
                        <i class="fa fa-coffee"></i> Visit Blog
                    </a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="success" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>About</h2>
                        <hr class="star-opaque">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- PESONAL -->
                        <p>Hi! My name is Dan and I'm a college student in Minnesota who is currently pursing multiple degrees in Computer Science. </p>
                        <p>In my free time I love to build computers, see live music, and code. I also collect vinyl, watch tons of movies, and eat lots of Chinese food. In the future, I hope to be able to travel the world more.</p>
                    </div>
                    <!-- TECHNICAL -->
                    <div class="col-md-6">
                        <p>I have experience using many technologies in various areas including desktop imaging and repair, web design, web backend programming, and networking. I love learning new things and discovering better ways to do things.</p>
                        <p>I have experience programming in HTML5, CSS3, PHP, JS, VB.NET, and Java. I also utilize many frameworks and tools such as Bootstrap, jQuery, and LESS.</p>
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                        <a href="rfnqyt:ifs.xf7rts@lrfnq.htr" id="secretEmail1" class="btn btn-outline">
                            <i class="fa fa-envelope"></i> Email Me
                        </a>
                    </div>
            </div>
        </section>

        <!--GitHub Section-->
        <section id="github" class="clear">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>GitHub</h2>
                        <hr class="star-clear">
                    </div>
                </div>
                <div id="feed" class="col-lg-12">
                </div>
                <div class="col-lg-12 text-center">
                    <a href="https://github.com/sa7mon" target="_blank" class="btn btn-outline text-center">
                        <i class="fa fa-github"></i> View on GitHub
                    </a>
                </div>
            </div>
        </section>

        <!--Resume Section -->
        <section id="resume" class="success opaque">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Resume</h2>
                        <hr class="star-opaque">
                    </div>
                    <div class="col-lg-10 col-lg-offset-1">
                        <table>
                            <!--Work Experience-->
                            <tr>
                                <td colspan="2">
                                    <h3><i class="fa fa-briefcase"></i> Work Experience<h3>
                                </td>
                            </tr>
                            <tr class="subhead">
                                <td class="subheadtd1">
                                    <strong>2014 - Present</strong>
                                </td>
                                <td class="subheadtd2">
                                    <strong>Computer Technology Solutions - Service Technician</strong>
                                </td>
                            </tr>
                            <tr class="info">
                                <td colspan="2">At CTS, I perform bench work including virus removal, OS re-installation, HP Warranty work, and Apple repairs. I also go onsite to customers and provide a variety of solutions including network troubleshooting, system installation, and sales discovery.</td>
                            </tr>
                            <tr class="subhead">
                                <td class="subheadtd1"><strong>2013 - 2014</strong></td>
                                <td class="subheadtd2"><strong>South Central College - Web Programming Assistant</strong></td>
                            </tr>
                            <tr class="info">
                                <td colspan="2">Working for SCC, I was the assistant to the head web developer for the school's website.  I was in charge of creating and editing content with Joomla!. I was also tasked with creating standalone PHP/MySQL apps for internal employee use and various forms for the school.  In addition, I was on the team assigned to the complete re-design of the school's site. For this project, I wrote custom PHP, JavaScript, and CSS code. </td>
                            </tr>

                            <!----------Education---------->
                            <tr class="header">
                                <td colspan="2"><h3><i class="fa fa-graduation-cap"></i> Education</h3></td>
                            </tr>
                            <tr class="subhead">
                                <td class="subheadtd1">2014 - Present</td>
                                <td class="subheadtd2">South Central College - A.A. Degree</td>
                            </tr>
                            <tr class="info">
                                <td colspan="2">Currently, I'm pursuing my A.A. Degree. I'm planning on transferring it to MSU and getting my B.S. degree in Computer Information Technology.</td>
                            </tr>
                            <tr class="subhead">
                                <td class="subheadtd1">2012 - 2014</td>
                                <td class="subheadtd2">South Central College - Networking Services A.A.S. Degree</td>
                            </tr>
                            <tr class="info">
                                <td colspan="2">This degree fulfilled training that prepared me to do the following tasks: design and administer computer networks, maintain and repair personal computer systems, support common business application software, and support current computer operating systems.</td>
                            </tr>

                            <!--Certifications-->
                            <tr class="header">
                                <td colspan="2"><h3><i class="fa fa-file-text-o"></i> Certificates</h3></td>
                            </tr>
                            <tr class="subhead">
                                <td class="subheadtd1">Apple</td>
                                <td class="subheadtd2">Apple Certified Macintosh Technician</td>
                            </tr>
                            <tr class="info">
                                <td colspan="2">Apple Certified Macintosh Technician (ACMT) certification verifies the ability to perform basic troubleshooting and repair of both desktop and portable Macintosh systems, such as iMac and MacBook Pro. ACMT certification exams emphasize identifying and resolving common Mac OS X problems, and using Apple Service and Support products and practices to effectively repair Apple hardware.</td>
                            </tr>
                            <tr class="subhead">
                                <td class="subheadtd1">WatchGuard</td>
                                <td class="subheadtd2">Certified System Professional</td>
                            </tr>
                            <tr class="info">
                                <td colspan="2">The WatchGuard Certified System Professional (WCSP) program certifies that individuals who pass the test are competent in the installation, configuration, management, and monitoring of a WatchGuard Firebox with WFS appliance software.  The courseware and exam reference WatchGuard System Manager management software.</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a href="https://www.linkedin.com/profile/view?id=184402988" target="_blank" class="btn btn-outline btn">
                            <i class="fa fa-linkedin-square"></i> Connect on LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Contact Me</h2>
                        <hr class="star-clear">
                    </div>
                </div>
                <div class="row text-center">
                    <ul class="list-inline">
                        <li>
                            <a href="https://github.com/sa7mon" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-github"></i></a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/profile/view?id=184402988" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                        </li>
                        <li>
                            <a href="rfnqyt:ifs.xf7rts@lrfnq.htr" id="secretEmail2" class="btn-social btn-outline"><i class="fa fa-fw fa-envelope-o"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center" id="site">
            <div class="footer-above">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2>About This Site</h2>
                            <hr class="star-opaque">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p> This site is built on the Bootstrap Framework. The site focuses heavily on being fully responsive and clean-looking. All of the code used is free and open-source; the site itself is <a href="https://github.com/sa7mon/personal-site">available on GitHub</a>.
                            </p>
                        </div>
                        <div class="col-md-6" id="right">
                            <p>The following open-source projects are being utilized: 
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-code"></i><a href="https://github.com/caseyscarborough/github-activity">caseyscarborough/github-activity</a></li>
                                    <li><i class="fa-li fa fa-code"></i><A href="http://startbootstrap.com/template-overviews/freelancer/">Freelancer Theme</a> from <a href="http://startbootstrap.com/">Start Bootstrap</a></li>
                                    <li><i class="fa-li fa fa-code"></i><a href="https://jquery.com/">jQuery</a></li>
                                    <li><i class="fa-li fa fa-code"></i><a href="http://getbootstrap.com/">Bootstrap Framework</a></li>
                                    <li><i class="fa-li fa fa-code"></i><a href="http://lesscss.org/">LESS</a></li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-below">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            Copyright &copy; Dan Salmon&nbsp;<?php echo date("Y") ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
        <div class="scroll-top page-scroll visible-xs visble-sm">
            <a class="btn btn-primary" href="#page-top">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>

        <!-- Begin Scripts -->

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script> 

        <!-- Bootstrap Core JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
        <script src="js/jquery.easing.min.js"></script><!-- Plugin JavaScript for jQuery Easing -->

        <!--script src="js/classie.js"></script-->
        <!--script src="js/cbpAnimatedHeader.js"></script-->
        <script src="js/freelancer.js"></script>  <!-- Custom Theme JavaScript -->

        <!--GitHub-activity -->
        <script type="text/javascript" src="js/mustache_0.7.2.min.js"></script>
        <script type="text/javascript" src="js/github-activity-0.1.1.min.js"></script>
       
        <!-- Email masking javascript -->       
        <script type="text/javascript" src="js/crypt.js"></script>

        <script>
        $( document ).ready(function() {
            // Generate GitHub activity section
            GitHubActivity.feed({
                username: "sa7mon",
                selector: "#feed",
                limit: 10 // optional
            });

            // New sticky navbar function
            // http://jsfiddle.net/yeco/4EcFf/
            var menu = $('.navbar');
            var origOffsetY = menu.offset().top;

            function scroll() {
                if ($(window).scrollTop() >= origOffsetY) {
                    $('.navbar').addClass('navbar-fixed-top');
                    $('#blog').addClass('paddedblog');
                } else {
                    $('.navbar').removeClass('navbar-fixed-top');
                    $('#blog').removeClass('paddedblog');
                }
            }
            document.onscroll = scroll;
        });
        //Because the btn-social or btn-outline class breaks the javascript
        $("#secretEmail1").hover (
            function() {
                //On hover enter
                doCrypt(true,'secretEmail1');
            }, function() {
                //ON hover leave
                doCrypt(false,'secretEmail1');
            }
        );
        $("#secretEmail2").hover (
            function() {
                //On hover enter
                doCrypt(true,'secretEmail2');
            }, function() {
                //ON hover leave
                doCrypt(false,'secretEmail2');
            }
        );
        </script>
    </body>
</html>