<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca Central - UNASAM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    dropShadow: {
                        // Efecto de resplandor personalizado
                        'glow-white': '0 0 10px rgba(255, 255, 255, 0.7)',
                        'glow-gold': '0 0 10px rgba(252, 191, 73, 0.6)',
                        'text-outline': '0 2px 4px rgba(0,0,0,0.8)',
                    }
                }
            }
        }
    </script>

    <style>
        /* Animaciones personalizadas */
        .fade-enter { opacity: 0; transform: scale(1.05); }
        .fade-enter-active {
            opacity: 1; transform: scale(1);
            transition: opacity 1000ms ease-in-out, transform 8000ms ease-out;
        }
        .slide-content-enter { opacity: 0; transform: translateY(20px); }
        .slide-content-active {
            opacity: 1; transform: translateY(0);
            transition: opacity 800ms ease-out 300ms, transform 800ms ease-out 300ms;
        }

        /* Sombra de texto para mayor legibilidad */
        .text-shadow-strong {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }
        /* Borde sutil alrededor del texto (efecto stroke simulado) */
        .text-stroke-light {
            -webkit-text-stroke: 0.5px rgba(255,255,255,0.1);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-900 text-white overflow-x-hidden">

    {{-- NAVBAR FLOTANTE --}}
    {{-- ========================================================= --}}
    {{-- SECCIÓN HEADER: Navegación y Logo --}}
    {{-- ========================================================= --}}
    <header class="fixed w-full z-50 top-0 transition-all duration-300 bg-gradient-to-b from-black/90 via-black/60 to-transparent p-4">
        <div class="w-full px-4 md:px-12 flex items-center justify-between">

            {{-- ========================================================= --}}
            {{-- SECCIÓN LOGO: Borde Dorado + Resplandor (Glow) --}}
            {{-- ========================================================= --}}
            <div class="flex items-center gap-3
                        p-3 rounded-xl
                        border-2 border-[#FCBF49]
                        shadow-[0_0_20px_rgba(252,191,73,0.6)]
                        bg-black/20 backdrop-blur-sm
                        transition-transform hover:scale-105 duration-300">

                {{-- LOGO IMAGEN --}}
                {{-- filter brightness-150 ayuda a que las letras oscuras del logo brillen un poco más sobre oscuro --}}
                <img src="https://sga.unasam.edu.pe/images/logo_frase.png" alt="Logo UNASAM"
                     class="h-16 md:h-20 w-auto object-contain filter brightness-125 drop-shadow-lg">

            </div>

            {{-- Navegación --}}
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/admin') }}"
                           class="px-6 py-2 bg-[#2D529F] hover:bg-[#1a3b7a] text-white font-semibold rounded-full shadow-lg shadow-[#2D529F]/40 transition-all duration-300 transform hover:-translate-y-0.5 border border-white/20">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}"
                           class="hidden sm:inline-block text-white font-bold transition-colors text-lg tracking-wide drop-shadow-md hover:text-[#FCBF49] hover:scale-105 transform duration-200">
                            Iniciar Sesión
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- SLIDER A PANTALLA COMPLETA --}}
    <main class="relative h-screen w-full overflow-hidden">

        {{-- Slide 1 --}}
        <div class="absolute inset-0 slide opacity-0 transition-opacity duration-1000" data-index="0">
            <div class="absolute inset-0 bg-black/50 z-10"></div>
            <img src="https://cdn.pixabay.com/photo/2024/02/28/11/07/woman-8601818_1280.jpg"
                 class="absolute inset-0 w-full h-full object-cover slide-img" alt="Tesis">

            <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4 max-w-6xl mx-auto slide-content">

                {{-- ========================================================= --}}
                {{-- SECCIÓN TEXTO (Slide 1): Títulos y descripciones --}}
                {{-- ========================================================= --}}
                <span class="inline-block py-1.5 px-4 rounded-full bg-[#FCBF49] text-gray-900 text-sm font-extrabold tracking-widest uppercase mb-6 shadow-lg shadow-yellow-500/30">
                    Investigación
                </span>

                <h1 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight text-shadow-strong drop-shadow-glow-white">
                    Repositorio de <br/>
                    <span class="text-[#FCBF49] drop-shadow-glow-gold">Tesis Académicas</span>
                </h1>

                <p class="text-sm md:text-base text-gray-100 max-w-3xl mb-8 font-medium drop-shadow-md text-shadow-strong">
                    La herramienta oficial para la catalogación, preservación y consulta de los trabajos de investigación de la UNASAM.
                </p>

                <div class="flex gap-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="px-6 py-2.5 bg-[#2D529F] hover:bg-[#234082] text-white font-bold text-lg rounded-xl shadow-xl hover:shadow-[#2D529F]/60 transition-all duration-300 transform hover:-translate-y-1 border border-white/20 backdrop-blur-sm">
                        Acceder al Sistema
                    </a>
                </div>
            </div>
        </div>

        {{-- Slide 2 --}}
        <div class="absolute inset-0 slide opacity-0 transition-opacity duration-1000" data-index="1">
            <div class="absolute inset-0 bg-black/50 z-10"></div>
            <img src="https://cdn.pixabay.com/photo/2015/02/02/11/09/office-620822_1280.jpg"
                 class="absolute inset-0 w-full h-full object-cover slide-img" alt="Tablets">

            <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4 max-w-6xl mx-auto slide-content">
                <span class="inline-block py-1.5 px-4 rounded-full bg-blue-600 text-white text-sm font-extrabold tracking-widest uppercase mb-6 shadow-lg shadow-blue-500/30">
                    Tecnología
                </span>

                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight text-shadow-strong drop-shadow-glow-white">
                    Gestión de Activos <br/> y
                    <span class="text-blue-400 drop-shadow-glow-white">Equipos Tecnológicos</span>
                </h1>

                <p class="text-sm md:text-base text-gray-100 max-w-3xl mb-8 font-medium drop-shadow-md text-shadow-strong">
                    Control preciso de inventario, préstamos y devoluciones de tablets y equipos para la comunidad universitaria.
                </p>

                <a href="{{ route('filament.admin.auth.login') }}" class="px-6 py-2.5 bg-white text-gray-900 hover:bg-gray-100 font-bold text-lg rounded-xl shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    Gestionar Inventario
                </a>
            </div>
        </div>

        {{-- Slide 3 --}}
        <div class="absolute inset-0 slide opacity-0 transition-opacity duration-1000" data-index="2">
            <div class="absolute inset-0 bg-black/40 z-10"></div>
            <img src="https://cdn.pixabay.com/photo/2016/02/16/21/07/books-1204029_1280.jpg"
                 class="absolute inset-0 w-full h-full object-cover slide-img" alt="Biblioteca">

            <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4 max-w-6xl mx-auto slide-content">
                <div class="w-32 h-2 bg-[#FCBF49] mb-8 shadow-[0_0_15px_#FCBF49]"></div>

                <h1 class="text-3xl md:text-5xl lg:text-7xl font-extrabold mb-6 leading-tight text-shadow-strong drop-shadow-glow-white">
                    Biblioteca Central <br/> UNASAM
                </h1>

                <p class="text-sm md:text-base text-gray-100 max-w-3xl mb-8 font-medium drop-shadow-md text-shadow-strong">
                    Innovación y orden al servicio del conocimiento. Un sistema integral para la administración eficiente.
                </p>
            </div>
        </div>

        {{-- Indicadores --}}
        <div class="absolute bottom-12 left-0 right-0 z-30 flex justify-center gap-3">
            <button class="indicator w-3 h-3 rounded-full bg-white/30 hover:bg-white transition-all shadow-[0_0_8px_rgba(255,255,255,0.8)]" data-index="0"></button>
            <button class="indicator w-3 h-3 rounded-full bg-white/30 hover:bg-white transition-all shadow-[0_0_8px_rgba(255,255,255,0.8)]" data-index="1"></button>
            <button class="indicator w-3 h-3 rounded-full bg-white/30 hover:bg-white transition-all shadow-[0_0_8px_rgba(255,255,255,0.8)]" data-index="2"></button>
        </div>

    </main>

    <footer class="absolute bottom-0 w-full z-40 p-3 text-center text-[10px] sm:text-xs text-white/80 border-t border-white/10 bg-black/60 backdrop-blur-md font-medium tracking-wide">
        &copy; {{ date('Y') }} Biblioteca Central de la UNASAM. Todos los derechos reservados.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.slide');
            const indicators = document.querySelectorAll('.indicator');
            let currentSlide = 0;
            const intervalTime = 6000;

            function showSlide(index) {
                slides.forEach(slide => {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'z-0');
                    const img = slide.querySelector('.slide-img');
                    const content = slide.querySelector('.slide-content');
                    if(img) img.classList.remove('fade-enter-active');
                    if(content) content.classList.remove('slide-content-active');
                });

                indicators.forEach(ind => {
                    ind.classList.remove('bg-white', 'scale-125');
                    ind.classList.add('bg-white/30');
                });

                const activeSlide = slides[index];
                activeSlide.classList.remove('opacity-0', 'z-0');
                activeSlide.classList.add('opacity-100', 'z-10');

                setTimeout(() => {
                    const img = activeSlide.querySelector('.slide-img');
                    const content = activeSlide.querySelector('.slide-content');
                    if(img) img.classList.add('fade-enter-active');
                    if(content) content.classList.add('slide-content-active');
                }, 50);

                indicators[index].classList.remove('bg-white/30');
                indicators[index].classList.add('bg-white', 'scale-125');

                currentSlide = index;
            }

            function nextSlide() {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }

            showSlide(0);
            const slideInterval = setInterval(nextSlide, intervalTime);

            indicators.forEach((ind, i) => {
                ind.addEventListener('click', () => {
                    clearInterval(slideInterval);
                    showSlide(i);
                });
            });
        });
    </script>
</body>
</html>
