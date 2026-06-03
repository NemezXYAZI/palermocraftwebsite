@extends('layouts.app')

@section('title', 'PalermoCraft - Головна')

@section('content')
<main class="flex-grow">
    <!-- Hero Section (Animated Carousel) -->
    <section class="relative h-[500px] overflow-hidden bg-palermo-dark">
        <!-- Slides Container -->
        <div id="hero-carousel" class="relative w-full h-full">

            <!-- Slide 1 -->
            <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-100 z-10">
                <div class="absolute inset-0">
                   <img src="{{ asset('images/HeroSlide1.webp') }}" alt="Minecraft World" class="w-full h-full object-cover opacity-60" onerror="this.src='https://i.ibb.co/hb1HMYK/MSPromotional-Background.webp'">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-palermo-dark"></div>
                </div>
                <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4">
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-4 uppercase tracking-wide shadow-black drop-shadow-lg">
                        Унікальний світ пригод
                    </h1>
                    <p class="text-gray-300 md:text-lg max-w-2xl mx-auto drop-shadow-md">
                        Долучайся до найкращого Minecraft-сервера з сюжетними квестами та дружньою спільнотою
                    </p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-0 z-0">
                <div class="absolute inset-0">
                    <img src="{{ asset('images/HeroSlide2.avif') }}" alt="Minecraft Combat" class="w-full h-full object-cover opacity-60" onerror="this.src='https://i.ibb.co/8WPzn5t/photo-1587573089734-09cb69c0f2b4.avif'">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-palermo-dark"></div>
                </div>
                <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4">
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-4 uppercase tracking-wide shadow-black drop-shadow-lg">
                        Твори свою історію
                    </h1>
                    <p class="text-gray-300 md:text-lg max-w-2xl mx-auto drop-shadow-md">
                        Розвивай свого персонажа, будуй міста та знаходь нових друзів у безмежному світі
                    </p>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-0 z-0">
                <div class="absolute inset-0">
                    <img src="{{ asset('images/HeroSlide3.avif') }}" alt="Minecraft Building" class="w-full h-full object-cover opacity-60" onerror="this.src='https://i.ibb.co/Rk68hb3z/photo-1550745165-9bc0b252726f.avif'">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-palermo-dark"></div>
                </div>
                <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4">
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-4 uppercase tracking-wide shadow-black drop-shadow-lg">
                        Стань легендою
                    </h1>
                    <p class="text-gray-300 md:text-lg max-w-2xl mx-auto drop-shadow-md">
                        Бери участь у масштабних івентах та битвах за звання найкращого гравця PalermoCraft
                    </p>
                </div>
            </div>

        </div>

        <!-- Pagination Dots -->
        <div class="absolute bottom-8 left-0 right-0 z-30 flex justify-center space-x-3">
            <button onclick="goToSlide(0)" class="carousel-dot w-3 h-3 rounded-full bg-palermo-green transition-all duration-300"></button>
            <button onclick="goToSlide(1)" class="carousel-dot w-3 h-3 rounded-full bg-gray-500 transition-all duration-300"></button>
            <button onclick="goToSlide(2)" class="carousel-dot w-3 h-3 rounded-full bg-gray-500 transition-all duration-300"></button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-palermo-green mb-12">
            Чому обирають нас?
        </h2>

        <div class="flex flex-col md:flex-row flex-wrap justify-center gap-6 max-w-5xl mx-auto">
            <!-- Card 1 -->
            <div class="bg-palermo-card w-full md:w-72 h-80 rounded-3xl relative overflow-hidden group shadow-lg flex-shrink-0">
                <img src="{{ asset('images/CarouselFirstImage.jpg') }}" alt="Сюжетні Квести" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://via.placeholder.com/400x600?text=Quest'">
                <div class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition-colors"></div>
                <div class="relative z-10 w-full h-full flex items-center justify-center p-6 text-center">
                    <h3 class="font-bold text-xl text-white drop-shadow-md">Сюжетні Квести</h3>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-palermo-card w-full md:w-72 h-80 rounded-3xl relative overflow-hidden group shadow-lg flex-shrink-0">
                <img src="{{ asset('images/CarouselSecondImage.jpg') }}" alt="Економіка" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://via.placeholder.com/400x600?text=Economy'">
                <div class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition-colors"></div>
                <div class="relative z-10 w-full h-full flex items-center justify-center p-6 text-center">
                    <h3 class="font-bold text-xl text-white drop-shadow-md">Економіка</h3>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-palermo-card w-full md:w-72 h-80 rounded-3xl relative overflow-hidden group shadow-lg flex-shrink-0">
                <img src="{{ asset('images/CarouselThirdImage.jpg') }}" alt="PvP Арени" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://via.placeholder.com/400x600?text=PvP'">
                <div class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition-colors"></div>
                <div class="relative z-10 w-full h-full flex items-center justify-center p-6 text-center">
                    <h3 class="font-bold text-xl text-white drop-shadow-md">PvP Арени</h3>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('javascripts')
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const totalSlides = slides.length;

    function updateSlides() {
        slides.forEach((slide, index) => {
            if (index === currentSlide) {
                slide.classList.remove('opacity-0', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
                dots[index].classList.remove('bg-gray-500');
                dots[index].classList.add('bg-palermo-green');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0');
                dots[index].classList.remove('bg-palermo-green');
                dots[index].classList.add('bg-gray-500');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlides();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlides();
        resetTimer();
    }

    let slideTimer = setInterval(nextSlide, 5000);

    function resetTimer() {
        clearInterval(slideTimer);
        slideTimer = setInterval(nextSlide, 5000);
    }
</script>
@endpush
