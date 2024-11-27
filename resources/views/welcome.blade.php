<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('placeholder.png') }}" type="image/png">
    <title>PlaceIt - Monetize Your Empty Spaces</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-nmkRI2k3l2GKtWo8ZxLpW2VfZHRlXYWnPbm2LFl9hAL5ZtntF7D1h6jcNcdEHOo5AC5f5E3i6fq4+Qkv3DdOdw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Ensure Tailwind is loaded -->
    
</head>
<body class="bg-white font-sans antialiased">
    <!-- Navbar -->
    <nav class="p-4 bg-white shadow md:flex md:justify-between md:items-center border-b-2 border-orange-500">
        <!-- Logo Section -->
        <div class="flex justify-between items-center">
            <a href="{{ url('/') }}" class="hover:text-red-800 text-orange-600 text-3xl" style="font-family: 'Fredoka', sans-serif;">place.it</a>
            <!-- Hamburger Icon for Mobile -->
            <div class="md:hidden">
                <button class="text-gray-700 focus:outline-none" id="menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex md:flex-grow justify-center space-y-4 md:space-y-0 md:space-x-8 mt-4 md:mt-0" id="menu">
            <a href="#top" class="text-red-600 hover:text-red-800 uppercase block md:inline-block font-bold">HOME</a>
            <a href="#about-us" class="text-red-600 hover:text-red-800 uppercase block md:inline-block font-bold">ABOUT</a>
            <a href="#contacts" class="text-red-600 hover:text-red-800 uppercase block md:inline-block font-bold">CONTACT</a>

            <!-- Mobile Login and Get Started Buttons -->
            <div class="block md:hidden space-y-4">
                @if (Route::has('login') && Auth::check())
                    <a href="{{ url('/login') }}" class="text-red-600 hover:text-gray-800 block">Dashboard</a>
                @elseif (Route::has('login') && !Auth::check())
                    <a href="{{ url('/login') }}" class="text-red-600 text-center border-2 block rounded-lg py-2 px-4 hover:text-blue-800">Login</a>
                    <a href="{{ url('/register') }}" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md block text-center">Get Started</a>
                @endif
            </div>
        </div>

        <!-- Desktop Login and Get Started Buttons -->
        <div class="hidden md:flex items-center space-x-4 mt-4 md:mt-0">
            @if (Route::has('login') && Auth::check())
                <a href="{{ url('/dashboard') }}" class="text-red-600  hover:text-gray-800 ">Dashboard</a>
            @elseif (Route::has('login') && !Auth::check())
                <a href="{{ url('/login') }}" class="font-semibold bg-red-500 hover:bg-sky-600 text-white py-2 px-4 rounded-md">Login</a>
                <a href="{{ url('/register') }}" class="font-semibold bg-red-500 hover:bg-sky-600 text-white py-2 px-4 rounded-md">Get Started</a>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-white py-16 px-6 lg:px-8">
        <div class="container mx-auto flex flex-col-reverse lg:flex-row items-center -mt-9">
            <!-- Left Content -->
            <div class="lg:w-1/2 text-center lg:text-left mt-8 lg:mt-0">
                <!--h1 class="text-9xl md:text-9xl font-bold text-orange-600 leading-tight mb-4" style="font-family: 'Fredoka', sans-serif;">PlaceIt</h1-->
                <h1 class="text-2xl md:text-6xl font-bold text-red-600 mb-4" style="font-family: 'Fredoka', sans-serif;">Monetize Your Empty Spaces with PlaceIt!</h1>
                <h2 class="text-gray-600 text-base md:text-2xl mb-6 ml-3">Transform spaces, maximizing potential — where opportunities meet visibility.</>
                <h3 class="text-gray-600 text-base md:text-lg mb-6 ml-3">Welcome to the ultimate platform for turning your unused spaces into a source of revenue. Whether it’s a blank wall, a billboard, or any available area, we connect space owners with businesses looking to advertise. Let your empty spaces work for you!</h3>
                <a href="{{url(path: '/register') }}" class="bg-red-500 hover:bg-red-700 text-white py-3 px-6 rounded-md text-lg">Learn More</a>
            </div>

            <!-- Carousel Section -->
            <div class="lg:w-1/2 relative">
                <div id="carousel" class="relative w-full overflow-hidden rounded-md shadow-lg">
                    <div id="carousel-images" class="relative w-full h-full flex transition-all duration-500">
                    <img src="{{ asset('storage/images/p1.jpg') }}" alt="Image 1" class="w-full h-67 hidden">
                        <img src="{{ asset('storage/images/p2.jpg') }}" alt="Image 2" class="w-full h-67 hidden">
                        <img src="{{ asset('storage/images/p3.png') }}" alt="Image 3" class="w-full h-67 hidden">
                        <img src="{{ asset('storage/images/p4.jpg') }}" alt="Image 4" class="w-full h-67 hidden">
                        <img src="{{ asset('storage/images/p5.jpg') }}" alt="Image 5" class="w-full h-67 hidden">
                        <img src="{{ asset('storage/images/p6.jpg') }}" alt="Image 6" class="w-full h-67">

                    </div>

                    <!-- Previous/Next Buttons -->
                    <button id="prev" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 opacity-50 hover:opacity-100">
                        &#10094;
                    </button>
                    <button id="next" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 opacity-50 hover:opacity-100">
                        &#10095;
                    </button>
                </div>
            </div>

        </div>
    </section>

<!-- About Us Section -->
<section id="about-us" class="bg-gray-100 py-12">

    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold text-red-600 mb-4">Our Team</h2>
        <p class="text-gray-600 text-lg mb-6">Meet the PlaceIt Team.</p>

        <!-- Team Members Grid with 3 on top and 2 on the bottom -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Top Row - 3 Team Members -->
            <div class="text-center">
                <img src="{{ asset('team/perod.png') }}" alt="Team Member 1" class="w-40 h-40 mx-auto rounded-full mb-4 border-2 border-orange-600">
                <h3 class="text-xl font-bold">JASPER ELDRICH BALLESTEROS</h3>
                <p class="text-orange-600 mb-2 font-bold">SOFTWARE ENGINEER</p>
                <div class="flex justify-center space-x-3 text-gray-500">
                    <a href="https://www.facebook.com/JahIsGood"><img src="{{ asset('storage/images/facebook-app-symbol.png') }}" alt="Description of icon" style="width: 18px; height: auto;"/></a>
                    <a href="https://www.instagram.com/jahsspear/"><img src="{{ asset('storage/images/instagram.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></i></a>
                    <a href="404 | Not Found"><img src="{{ asset('storage/images/twitter.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="#"><img src="{{ asset('storage/images/linkedin-logo.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                </div>
            </div>

            <div class="text-center">
                <img src="{{ asset('team/eyac.png') }}" alt="Team Member 2" class="w-40 h-40 mx-auto rounded-full mb-4 border-2 border-orange-600">
                <h3 class="text-xl font-bold">LEMARC EYAC</h3>
                <p class="text-orange-600 mb-2 font-bold">PROJECT MANAGER</p>
                <div class="flex justify-center space-x-3 text-gray-500">
                    <a href="https://www.facebook.com/lemarc.eyac"><img src="{{ asset('storage/images/facebook-app-symbol.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="https://www.instagram.com/eyaczoldyck/"><img src="{{ asset('storage/images/instagram.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></i></a>
                    <a href="404 | Not Found"><img src="{{ asset('storage/images/twitter.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="https://www.linkedin.com/in/eyac-lemarc-099935327/"><img src="{{ asset('storage/images/linkedin-logo.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                </div>
            </div>

            <div class="text-center">
                <img src="{{ asset('team/cinco.png') }}" alt="Team Member 3" class="w-40 h-40 mx-auto rounded-full mb-4 border-2 border-orange-600">
                <h3 class="text-xl font-bold">JOHN BENEDICT CINCO</h3>
                <p class="text-orange-600 mb-2 font-bold">QUALITY ASSURANCE</p>
                <div class="flex justify-center space-x-3 text-gray-500">
                    <a href="https://www.facebook.com/jboycinco62"><img src="{{ asset('storage/images/facebook-app-symbol.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="https://www.instagram.com/jboy_cinco/"><img src="{{ asset('storage/images/instagram.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></i></a>
                    <a href="404 | Not Found"><img src="{{ asset('storage/images/twitter.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="#"><img src="{{ asset('storage/images/linkedin-logo.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                </div>
            </div>
        </div>

        <!-- Bottom Row - 2 Team Members -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <div class="text-center">
                <img src="{{ asset('team/yani.png') }}" alt="Team Member 4" class="w-40 h-40 mx-auto rounded-full mb-4 border-2 border-orange-600">
                <h3 class="text-xl font-bold">FRANCES DIANA N. CARSIDO</h3>
                <p class="text-orange-600 mb-2 font-bold">DATABASE MANAGER</p>
                <div class="flex justify-center space-x-3 text-gray-500">
                    <a href="https://www.facebook.com/yani.mcphee"><img src="{{ asset('storage/images/facebook-app-symbol.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="https://www.instagram.com/yani.mcphee/"><img src="{{ asset('storage/images/instagram.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></i></a>
                    <a href="#"><img src="{{ asset('storage/images/twitter.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="https://www.linkedin.com/in/frances-diana-carsido-987286207/?originalSubdomain=ph"><img src="{{ asset('storage/images/linkedin-logo.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                </div>
            </div>

            <div class="text-center">
                <img src="{{ asset('team/shy.jpg') }}" alt="Team Member 5" class="w-40 h-40 mx-auto rounded-full mb-4 border-2 border-orange-600">
                <h3 class="text-xl font-bold">SHYRELLE SHINE L. MANAGAYTAY</h3>
                <p class="text-orange-600 mb-2 font-bold">UI/UX DESIGNER</p>
                <div class="flex justify-center space-x-3 text-gray-500">
                    <a href="https://www.facebook.com/shyrelle.managaytay"><img src="{{ asset('storage/images/facebook-app-symbol.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="https://www.instagram.com/__s12u/"><img src="{{ asset('storage/images/instagram.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="#"><img src="{{ asset('storage/images/twitter.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                    <a href="linkedin.com/in/shyrelle-managaytay-398864283"><img src="{{ asset('storage/images/linkedin-logo.png') }}" alt="Description of icon" style="width: 18px; height: auto;" /></a>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Contacts Section -->
    <!-- Contact Us Section -->
<section id="contacts" class="bg-white py-12">
    <div class="container mx-auto text-center">
        <!-- Title -->
        <h2 class="text-3xl font-bold text-red-600 mb-4">Contact Us</h2>
        <p class="text-gray-500 mb-8 font-semibold">Feel free to get in touch with us for any queries or assistance.</p>

        <!-- Contact Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <!-- Address -->
            <div class="flex flex-col items-center">
            <img src="{{ asset('storage/images/location.png') }}" alt="Description of icon" class="mb-4" style="width: auto; height: 30px;" />
                <h3 class="text-xl font-bold mb-2">ADDRESS</h3>
                <p class="text-gray-600 font-medium">Alaska Duljo Cebu City</p>
            </div>

            <!-- Phone -->
            <div class="flex flex-col items-center font-bold">
            <img src="{{ asset('storage/images/phone-call.png') }}" alt="Description of icon" class="mb-4" style="width: auto; height: 30px;" />
                <h3 class="text-xl font-bold mb-2">PHONE</h3>
                <p class="text-gray-600 font-medium">+63 919 988 4880</p>
            </div>

            <!-- Email -->
            <div class="flex flex-col items-center">
            <img src="{{ asset('storage/images/email.png') }}" alt="Description of icon" class="mb-4" style="width: auto; height: 30px;" />
                <h3 class="text-xl font-bold mb-2">EMAIL</h3>
                <p class="text-gray-600 font-medium">placeit13@gmail.com</p>
            </div>
        </div>
    </div>
</section>


    <!-- Footer Section -->
    <footer class="bg-red-500 py-6 text-center text-white">
        <p class="text-sm">Copyright &copy; <a href="#top">PlaceIt</a>, All Rights Reserved.</p>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="fixed bottom-5 right-5 bg-red-500 text-white rounded-full p-4 shadow-lg hover:bg-red-600">
        <img src="{{ asset('arrow-up.png') }}" alt="Scroll Up" style="width: 18px; height: auto;" />
    </button>
</body>
<script>
        const menuBtn = document.getElementById('menu-btn');
        const menu = document.getElementById('menu');

        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // Smooth scrolling when clicking on nav links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Smooth scroll to top for "PlaceIt" in the footer
        const scrollToTop = document.querySelector('a[href="#top"]');
        scrollToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        //carousel-images
        const images = document.querySelectorAll('#carousel-images img');
        let currentIndex = 0;

        function showImage(index) {
            images.forEach((img, i) => {
                img.classList.add('hidden');
                if (i === index) {
                    img.classList.remove('hidden');
                }
            });
        }

        document.getElementById('prev').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(currentIndex);
        });

        document.getElementById('next').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
        });

        // Auto-rotate images every 3 seconds
        setInterval(() => {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
        }, 3000);

        // Show the first image initially
        showImage(currentIndex);
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Show the button when scrolling down
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // When the button is clicked, scroll to the top
        scrollToTopBtn.onclick = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
</html>
