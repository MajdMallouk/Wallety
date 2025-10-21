<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Wallety') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
              integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
              crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
        <script src="https://unpkg.com/alpinejs" defer></script>
        <link rel="shortcut icon" href="{{ asset('/img/logo-white.svg') }}" type="image/x-icon">

        <!-- Styles / Scripts -->
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="{{ asset('style.css') }}">
    </head>
    <body class="bg-base-bgLight text-white" x-data="{open: false}">
    <x-section class="flex justify-between bg-base-dark">
        <div class="hidden lg:flex gap-8">
            <p><i class="far fa-envelope"></i> <a href="mailto:majdmallouk365@gmail.com" target="_blank">majdmallouk365@gmail.com</a></p>
            <p><i class="fas fa-phone"></i> +1234567890</p>
        </div>
        <div class="hidden lg:flex list-none gap-4">
            <li>Contact Links:</li>
            <li><i class="fab fa-facebook"></i></li>
            <li><i class="fab fa-x-twitter"></i></li>
            <li><i class="fab fa-linkedin"></i></li>
            <li><i class="fab fa-pinterest"></i></li>
        </div>



        <!-- Mobile View -->

        <a href="{{ route('welcome') }}" class="flex lg:hidden gap-2 items-center">
            <x-application-logo class="w-12"/>
            <div>
                <h2 class="text-2xl font-semibold uppercase">{{ config('app.name') }}</h2>
                <p class="text-neutral-300 text-[10px]">The fastest thing ever</p>
            </div>
        </a>


        <button @click="open = ! open" class="inline-flex lg:hidden items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </x-section>

        <!-- Hamburger Menu -->

        <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-base-dark">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link class="text-neutral-50" href="/#">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-neutral-50" href="/#about">
                    {{ __('About') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-neutral-50" href="/#services">
                    {{ __('Services') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-neutral-50" href="/#features">
                    {{ __('Features') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-neutral-50" href="/#other">
                    {{ __('Other') }}
                </x-responsive-nav-link>
            </div>
        </div>

    <x-section class="relative w-full h-[60vh] lg:h-screen overflow-hidden">
        <video
            class="absolute inset-0 w-full h-full object-cover"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
        >
            <source src="{{ asset('img/backgroundVideo.mp4') }}" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
        <nav class="relative flex justify-between z-10">
            <a href="{{ route('welcome') }}" class="hidden lg:flex gap-2 items-center">
                <x-application-logo class="w-16"/>
                <div>
                    <h2 class="text-4xl font-semibold uppercase">{{ config('app.name') }}</h2>
                    <p class="text-neutral-300">The fastest thing ever</p>
                </div>
            </a>
            <div class="hidden lg:flex items-center gap-8">
                <ul class="flex-none space-x-6">
                    <a href="{{ route('welcome') }}" class="text-accent-400 underline">Home</a>
                    <a href="#about">About</a>
                    <a href="#services">Services</a>
                    <a href="#features">Features</a>
                    <a href="#other">Other</a>
                </ul>
                <x-primary-link href="{{ route('login') }}">Login / Signup</x-primary-link>
            </div>
        </nav>
        <div class="relative z-10 w-full lg:min-h-screen flex justify-center items-start lg:items-center text-center">
            <div class="max-w-3xl space-y-6 p-1 lg:p-4 rounded">
                <div class="backdrop-blur-sm">
                    <div class="font-semibold">
                        <p class="text-accent-400">{{ config('app.name') }}</p>
                        <h2 class="text-3xl lg:text-6xl">The best whatever in the whateverness that ever existed! (so far)</h2>
                    </div>
                    <div>
                        <p class="text-neutral-300">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam deserunt excepturi hic iusto
                            libero nesciunt porro rerum sapiente soluta veritatis.
                        </p>
                    </div>
                </div>
                <div>
                    <x-primary-link href="{{ route('login')}}" class="!w-fit">Start Today</x-primary-link>
                </div>
            </div>
        </div>
    </x-section>
    <x-section class="bg-base-dark">
        <div class="flex flex-col lg:flex-row justify-around gap-4 items-center p-4">
            <h2 class="text-xl lg:text-2xl text-center">We have +50 strategical partners</h2>
            <div class="flex gap-2 lg:gap-4">
                <img class="w-20 lg:w-36" src="{{ asset('img/Dell_Logo.png') }}" alt="Dell Logo">
                <img class="w-20 lg:w-36" src="{{ asset('img/oxford.png') }}" alt="Oxford Logo">
                <img class="w-20 lg:w-36" src="{{ asset('img/Dell_Logo.png') }}" alt="Dell Logo">
                <img class="w-20 lg:w-36" src="{{ asset('img/oxford.png') }}" alt="Oxford Logo">
            </div>
        </div>
    </x-section>
    <x-section id="about" class="lg:grid lg:grid-cols-12 gap-12 my-10 lg:my-40 lg:!px-24 text-black">
        <div class="mb-6 lg:mb-0 lg:col-span-6">
            <img class="rounded-3xl" src="{{ asset('img/15773.jpg') }}" alt="meeting">
        </div>
        <div class="lg:col-span-6 flex flex-col justify-between gap-6">
            <div>
                <p class="text-2xl mb-2">Who are we?</p>
                <h2 class="text-5xl">
                    We are the best of the best
                </h2>
                <p>(for no clear reason)</p>
            </div>
            <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad, assumenda atque beatae consectetur
                cupiditate dolorum ducimus eius esse est exercitationem expedita illum itaque minus natus officia
                officiis quae quaerat qui quibusdam quo rem reprehenderit saepe sed tempore ut voluptatem voluptatibus.
                Aliquid, itaque tenetur? Adipisci consequatur eligendi incidunt ipsa iure labore modi non perferendis,
                praesentium tempora! Architecto aut doloremque doloribus eius explicabo in iusto magnam maiores nobis
                provident quaerat quia quibusdam, repudiandae sit voluptates! Aliquam maiores minima nisi repudiandae
                vero. Aut dicta dolorum error fugit impedit nam non optio provident quaerat quasi, quod repellat tempora
                ullam veniam veritatis! Animi, consequatur cumque cupiditate ducimus eveniet harum inventore iusto
                labore laboriosam modi numquam officia officiis perspiciatis quam quidem quisquam repellendus
                reprehenderit, ullam volup</p>
            <x-primary-link href="{{ route('login') }}" class="!w-fit">Let's goo!</x-primary-link>
        </div>
    </x-section>
    <x-section id="services" class="relative bg-[#002046] pt-44 lg:pt-72 pb-32" x-data="{ shown: false }" x-intersect.threshold.100="shown = true">
        <div x-show="shown" x-transition>
            <div>
                <img class="absolute top-0 lg:-top-32 left-0 w-full z-0 opacity-50 " src="{{ asset('img/curvey-lines-shape.svg') }}" alt="curvey lines shape">
            </div>
            <div class="relative z-10 space-y-20 flex flex-col justify-center items-center">
                <div class="backdrop-blur-sm p-1 rounded-xl">
                    <h2 class="w-fit text-5xl font-semibold text-center">Some of our great services</h2>
                </div>
                <div class="lg:grid lg:grid-cols-12 gap-12 space-y-8 lg:space-y-0">
                    <x-service-card>
                        <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                        <h2 class="text-3xl font-semibold text-center mb-5">
                            The Ultimate Solution
                        </h2>
                        <p>
                            numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                            omnis pariatur recusandae repellat rerum, tenetur.
                        </p>
                    </x-service-card>
                    <x-service-card>
                        <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                        <h2 class="text-3xl font-semibold text-center mb-5">
                            The Ultimate Solution
                        </h2>
                        <p>
                            numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                            omnis pariatur recusandae repellat rerum, tenetur.
                        </p>
                    </x-service-card>
                    <x-service-card>
                        <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                        <h2 class="text-3xl font-semibold text-center mb-5">
                            The Ultimate Solution
                        </h2>
                        <p>
                            numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                            omnis pariatur recusandae repellat rerum, tenetur.
                        </p>
                    </x-service-card>
                    <x-service-card>
                        <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                        <h2 class="text-3xl font-semibold text-center mb-5">
                            The Ultimate Solution
                        </h2>
                        <p>
                            numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                            omnis pariatur recusandae repellat rerum, tenetur.
                        </p>
                    </x-service-card>
                    <x-service-card>
                        <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                        <h2 class="text-3xl font-semibold text-center mb-5">
                            The Ultimate Solution
                        </h2>
                        <p>
                            numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                            omnis pariatur recusandae repellat rerum, tenetur.
                        </p>
                    </x-service-card>
                    <x-service-card>
                        <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                        <h2 class="text-3xl font-semibold text-center mb-5">
                            The Ultimate Solution
                        </h2>
                        <p>
                            numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                            omnis pariatur recusandae repellat rerum, tenetur.
                        </p>
                    </x-service-card>
                </div>
            </div>
        </div>
    </x-section>
    <x-section id="features" class="text-black space-y-20 py-32">
        <div class="max-w-3xl flex flex-col justify-center items-center gap-6 mx-auto">
            <h2 class="text-5xl text-center">Why you're a loser if you don't join {{ config('app.name') }} right this moment!</h2>
            <p class="border-neutral-600 px-8 text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci asperiores
                delectus doloremque ducimus facilis iure labore maxime nam nemo, neque nulla perferendis quae, quam
                repellat voluptat</p>
        </div>
        <div class="lg:px-48 lg:grid lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 flex flex-col justify-between p-8 shadow-md !bg-gray-50">
                    <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                    <h2 class="text-3xl font-semibold text-center mb-5">
                        The Ultimate Solution
                    </h2>
                    <p>
                        numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                        omnis pariatur recusandae repellat rerum, tenetur.
                    </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8 shadow-md !bg-gray-50">
                    <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                    <h2 class="text-3xl font-semibold text-center mb-5">
                        The Ultimate Solution
                    </h2>
                    <p>
                        numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                        omnis pariatur recusandae repellat rerum, tenetur.
                    </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8 shadow-md !bg-gray-50">
                    <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                    <h2 class="text-3xl font-semibold text-center mb-5">
                        The Ultimate Solution
                    </h2>
                    <p>
                        numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                        omnis pariatur recusandae repellat rerum, tenetur.
                    </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8 shadow-md !bg-gray-50">
                    <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                    <h2 class="text-3xl font-semibold text-center mb-5">
                        The Ultimate Solution
                    </h2>
                    <p>
                        numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                        omnis pariatur recusandae repellat rerum, tenetur.
                    </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8 shadow-md !bg-gray-50">
                    <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                    <h2 class="text-3xl font-semibold text-center mb-5">
                        The Ultimate Solution
                    </h2>
                    <p>
                        numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                        omnis pariatur recusandae repellat rerum, tenetur.
                    </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8 shadow-md !bg-gray-50">
                    <img class="mx-auto" src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                    <h2 class="text-3xl font-semibold text-center mb-5">
                        The Ultimate Solution
                    </h2>
                    <p>
                        numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                        omnis pariatur recusandae repellat rerum, tenetur.
                    </p>
            </div>
        </div>
    </x-section>
    <x-section id="other" class="dealWithTrustSection relative bg-[#002046] pb-16">
        <div class="lg:grid lg:grid-cols-12 gap-24 lg:px-48 pt-72 pb-32">
            <div class="lg:col-span-6 mb-6 lg:mb-0">
                <img class="w-full rounded-xl" src="{{ asset('img/deal-with-trust.jpg') }}" alt="deal with trust">
            </div>
            <div class="lg:col-span-6 flex flex-col justify-center gap-6">
                <h2 class="text-5xl font-semibold">Deal With Trust</h2>
                <p class="text-neutral-200">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A dolorem ducimus et explicabo facilis,
                    fugiat, impedit itaque iusto necessitatibus officia optio placeat quos rem sint suscipit totam
                    ullam? Accusamus cum doloremque nem</p>
            </div>
        </div>
        <div class="lg:grid lg:grid-cols-12">
            <div class="lg:col-span-4 flex flex-col justify-between p-8">
                <img src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                <h2 class="text-3xl font-semibold mb-5">
                    The Ultimate Solution
                </h2>
                <p>
                    numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                    omnis pariatur recusandae repellat rerum, tenetur.
                </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8">
                <img src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                <h2 class="text-3xl font-semibold mb-5">
                    The Ultimate Solution
                </h2>
                <p>
                    numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                    omnis pariatur recusandae repellat rerum, tenetur.
                </p>
            </div>

            <div class="lg:col-span-4 flex flex-col justify-between p-8">
                <img src="{{ asset('img/icons/wallet-46.svg') }}" width="75px" alt="">
                <h2 class="text-3xl font-semibold mb-5">
                    The Ultimate Solution
                </h2>
                <p>
                    numquam, perspiciatis qui recusandae sit? A aperiam consectetur, dolore in laudantium maiores mollitia
                    omnis pariatur recusandae repellat rerum, tenetur.
                </p>
            </div>
        </div>
        <ul class="items-animating">
            <li class="absolute top-20 right-20">
                <i class="fas fa-plus fa-2xl opacity-30"></i>
            </li>
            <li class="absolute top-52 lg:top-20 left-16 lg:left-32">
                <i class="fas fa-plus fa-2xl opacity-30"></i>
            </li>
            <li class="absolute right-12 top-60 lg:top-1/2 lg:-translate-y-1/2">
                <i class="fa-solid fa-user-lock fa-2xl opacity-30"></i>
            </li>
            <li class="absolute top-12 right-1/2 -translate-x-1/2">
                <i class="fa-solid fa-money-check-dollar fa-2xl opacity-30"></i>
            </li>
            <li class="absolute right-1/2 -translate-x-1/2 bottom-20 lg:top-3/4 lg:-translate-y-3/4">
                <i class="fa-solid fa-money-bill-trend-up fa-2xl opacity-30"></i>
            </li>
        </ul>
    </x-section>
    <x-section class="bg-[#002046] !p-0">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-16 items-center">
            <div class="flex-1 px-6 lg:px-0 pt-24 lg:py-32 flex justify-end">
                <div class="max-w-xl space-y-2 ml-0 md:ml-8">
                    <h2 class="text-2xl lg:text-4xl font-semibold">Subscribe to our news letter</h2>
                    <p>if you want us to advertise you things you don't need for money you don't have, then this is the right place for you.</p>
                </div>
            </div>
            <div class="newsletterBox flex-1 w-full py-36 px-4 lg:px-16">
                <form action="">
                    <div class="relative">
                        <input class="w-full py-3 lg:py-6 px-4 rounded-full bg-base-bg border-[5px] border-accent-400 lg:placeholder:text-xl" type="email" name="email" id="email" placeholder="Your Email Address" required>
                        <x-primary-button class="absolute right-3 lg:right-5 top-1/2 -translate-y-1/2 text-lg lg:!text-xl !rounded-full py-1 lg:!py-2 px-2 lg:!px-4 !w-fit" type="submit">Subscribe &nbsp<i class="fa-solid fa-paper-plane"></i></x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </x-section>
    <x-section class="lg:!p-32 bg-base-dark">
        <div class="lg:grid lg:grid-cols-12 gap-12 divide-y-2 divide-neutral-50/20 lg:divide-y-0">
            <ul class="lg:col-span-4 space-y-4 py-8 lg:py-0">
                <li>
                    <a href="{{ route('welcome') }}" class="flex gap-2 items-center">
                        <x-application-logo class="w-16"/>
                        <div>
                            <h2 class="text-4xl font-semibold uppercase">{{ config('app.name') }}</h2>
                            <p class="text-neutral-300">The fastest thing ever</p>
                        </div>
                    </a>
                </li>
                <li>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus asperiores commodi, culpa
                        cupiditate dolorem ducimus maiores quaerat quas repudiandae rerum sapiente sint ullam? Fugit
                        necessitatibus neque quam rerum soluta venia</p>
                </li>
            </ul>
            <ul class="lg:col-span-4 space-y-4 py-8 lg:py-0">
                <li>Quick Menu</li>
                <li>About us</li>
                <li>Devs Section</li>
                <li>Something else</li>
                <li>Something elser</li>
            </ul>
            <ul class="lg:col-span-4 space-y-4 py-8 lg:py-0">
                <li>Get Started</li>
                <li>Open the door</li>
                <li>Step in</li>
                <li>Say hello</li>
                <li>And leave immediately</li>
            </ul>
            <ul class="lg:col-span-4 space-y-4 py-8 lg:py-0">
                <li>Legal and Megal</li>
                <li>Law is law</li>
                <li>Rules are rules</li>
                <li>Tho, Do what you like</li>
                <li>And like what you do</li>
            </ul>
        </div>
    </x-section>
    <footer class="bg-base-dark py-6">
        <p class="text-center">All right reserved &copy;Be Happy Be Dumb 2025</p>
    </footer>
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
