{{-- Solo aplicar estilos si estamos en la página de Login --}}
@if(request()->routeIs('filament.admin.auth.login'))
    <style>
        /* 1. IMAGEN DE FONDO */
        body {
            background-image: url('https://cdn.pixabay.com/photo/2016/02/16/21/07/books-1204029_1280.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            /* SOLUCIÓN AL BORDE BLANCO: Quitamos márgenes y scroll horizontal */
            margin: 0;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* 2. CAPA OSCURA SOBRE EL FONDO */
        body::before {
            content: '';
            /* SOLUCIÓN: Usamos 'fixed' y anclamos a los 4 lados */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(17, 24, 39, 0.7);
            z-index: -1;
            /* Aseguramos que no genere scroll */
            margin: 0;
            padding: 0;
        }

        /* 3. MEJORAR LA TARJETA DE LOGIN (Igual que antes) */
        .fi-login-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Asegura altura completa */
            padding: 1rem; /* Un poco de espacio en móviles */
        }

        .fi-login-card,
        .fi-simple-page-section {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid #FCBF49;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5) !important;
            width: 100%;
            max-width: 28rem; /* Ancho máximo para la tarjeta */
        }

        .fi-simple-page-heading {
            color: #1A3E6C !important;
            text-transform: uppercase;
            font-weight: 800;
            text-align: center;
        }

        .fi-btn-primary {
            background-color: #2D529F !important;
            border: none !important;
            transition: all 0.3s ease;
        }
        .fi-btn-primary:hover {
            background-color: #1A3E6C !important;
            transform: scale(1.02);
        }
    </style>
@endif
