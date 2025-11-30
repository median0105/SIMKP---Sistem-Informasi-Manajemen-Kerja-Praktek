<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMKP - Sistem Informasi Manajemen Kerja Praktek</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Unib.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Library for Scroll Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom CSS untuk animasi dan efek tambahan */
        :root {
            --unib-blue: #1e40af;
            --teknik-orange: #f97316;
            --unib-blue-light: #3b82f6;
            --unib-blue-dark: #1e3a8a;
        }
        
        /* Hero Section Styles dari kode pertama */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
            overflow-x: hidden;
            line-height: 1.6;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            z-index: -2;
        }

        .hero-content {
            text-align: center;
            max-width: 900px;
            z-index: 10;
            position: relative;
            padding-top: 60px; /* Ditambahkan untuk menurunkan seluruh konten */
        }

        .logo-container {
            margin-bottom: 3rem; /* Diperbesar dari 2rem */
            animation: fadeInDown 1s ease-out;
            transform: translateY(30px); /* Ditambahkan untuk lebih menurunkan logo */
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        .logo img {
            width: 80px;
            height: 80px;
        }

        .title-container {
            margin-bottom: 2rem;
        }

        .main-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.1;
            opacity: 0;
            animation: fadeInUp 1s ease-out 0.5s forwards;
        }

        .gradient-text {
            background: linear-gradient(135deg, #f97316, #ea580c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }

        .subtitle {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0;
            animation: fadeInUp 1s ease-out 0.8s forwards;
        }

        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 3rem;
            opacity: 0;
            animation: fadeInUp 1s ease-out 1.1s forwards;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.6);
        }

        .btn-secondary {
            background: white;
            color: var(--unib-blue);
            border: 2px solid white;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.5);
        }

        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
        }

        .btn:hover:before {
            left: 100%;
        }

        .scroll-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            animation: fadeIn 1s ease-out 1.4s forwards;
        }

        .scroll-text {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .scroll-arrow {
            width: 30px;
            height: 50px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            position: relative;
        }

        .scroll-arrow:before {
            content: '';
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 10px;
            background: white;
            border-radius: 2px;
            animation: scroll 2s infinite;
        }

        /* Floating elements */
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 10%;
            animation-delay: 1s;
        }

        .floating-element:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 10%;
            right: 20%;
            animation-delay: 3s;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        @keyframes scroll {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(0);
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
        }

        /* Particle effect */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particle-float 15s linear infinite;
        }

        /* Additional effects */
        .glow {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.3) 0%, transparent 70%);
            filter: blur(40px);
            z-index: -1;
            animation: glow-move 10s ease-in-out infinite alternate;
        }

        .glow-1 {
            top: 10%;
            left: 10%;
        }

        .glow-2 {
            bottom: 10%;
            right: 10%;
            background: radial-gradient(circle, rgba(30, 64, 175, 0.3) 0%, transparent 70%);
        }

        @keyframes glow-move {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(20px, 20px);
            }
        }

        /* Text animation */
        .typing-text {
            overflow: hidden;
            border-right: 2px solid #f97316;
            white-space: nowrap;
            margin: 0 auto;
            animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #f97316 }
        }

        /* Responsive design untuk hero section */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }
            
            .subtitle {
                font-size: 1.2rem;
            }
            
            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
            }
            
            .button-container {
                flex-direction: column;
                align-items: center;
            }
            
            .hero-content {
                padding-top: 40px; /* Disesuaikan untuk tablet */
            }
            
            .logo-container {
                margin-bottom: 2.5rem;
                transform: translateY(20px);
            }
        }

        @media (max-width: 480px) {
            .main-title {
                font-size: 2rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
            
            .logo {
                width: 100px;
                height: 100px;
            }
            
            .logo img {
                width: 60px;
                height: 60px;
            }
            
            .hero-content {
                padding-top: 30px; /* Disesuaikan untuk mobile */
            }
            
            .logo-container {
                margin-bottom: 2rem;
                transform: translateY(15px);
            }
        }

        /* CSS untuk bagian lainnya dari kode kedua */
        /* Loading Animation dengan Lottie */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.8s ease-out;
        }
        
        .loading-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            z-index: 10000;
        }
        
        .loading-text {
            font-size: 1.25rem;
            color: white;
            margin-top: 20px;
            font-weight: 500;
            z-index: 10000;
        }
        
        /* Sticky Header - IMPROVED DESIGN */
        .sticky-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.4s ease;
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .sticky-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
            z-index: -1;
        }
        
        .sticky-header.scrolled {
            background: linear-gradient(135deg, var(--unib-blue), var(--unib-blue-dark));
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .sticky-header.scrolled::before {
            background: linear-gradient(135deg, var(--unib-blue), var(--unib-blue-dark));
        }
        
        .sticky-header.scrolled .header-logo-text h1 {
            color: white;
        }
        
        .sticky-header.scrolled .header-logo-text p {
            color: #bfdbfe;
        }
        
        .sticky-header.scrolled .header-logo-text .university-badge {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .header-logo-text h1 {
            color: var(--unib-blue);
            transition: color 0.3s ease;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }
        
        .header-logo-text p {
            color: #6b7280;
            transition: color 0.3s ease;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .university-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(30, 64, 175, 0.08);
            color: var(--unib-blue);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid rgba(30, 64, 175, 0.1);
            transition: all 0.3s ease;
            margin-top: 2px;
        }
        
        /* Progress Bar Animation */
        .progress-container {
            margin-top: 12px;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        
        .progress-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }
        
        .progress-percentage {
            font-size: 0.875rem;
            color: var(--unib-blue);
            font-weight: 600;
        }
        
        .progress-bar {
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--unib-blue), var(--teknik-orange));
            border-radius: 4px;
            transition: width 1.2s ease-in-out;
            width: 0%;
        }
        
        /* Section Background Patterns */
        .pattern-dots {
            background-image: radial-gradient(rgba(30, 64, 175, 0.08) 1.5px, transparent 1.5px);
            background-size: 25px 25px;
        }
        
        .pattern-grid {
            background-image: 
                linear-gradient(rgba(30, 64, 175, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 64, 175, 0.03) 1px, transparent 1px);
            background-size: 25px 25px;
        }
        
        /* Full Page Scroll Sections */
        .fullpage-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 0;
        }
        
        /* Enhanced Card Styles */
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 32px 28px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .magang-card {
            background: white;
            border-radius: 16px;
            padding: 28px 24px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .magang-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
        }
        
        /* Improved Typography */
        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            color: var(--unib-blue-dark);
        }
        
        .section-subtitle {
            font-size: 1.3rem;
            color: #6b7280;
            margin-bottom: 3rem;
            line-height: 1.5;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Enhanced Footer - MODIFIED */
        .footer {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            z-index: 0;
        }
        
        .footer-content {
            position: relative;
            z-index: 1;
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Container Custom */
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
        }
        
        /* Better Font Sizes */
        .text-lead {
            font-size: 1.25rem;
            line-height: 1.6;
        }
        
        .text-body {
            font-size: 1.05rem;
            line-height: 1.6;
        }
        
        /* Card Content Layout */
        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .card-footer {
            margin-top: auto;
            padding-top: 20px;
        }
        
        /* Improved Header */
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        /* Tag Styles */
        .tag {
            display: inline-flex;
            align-items: center;
            background: #eff6ff;
            color: var(--unib-blue);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .tag i {
            margin-right: 4px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            background: #dcfce7;
            color: #166534;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Icon Container */
        .icon-container {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        /* Button Styles untuk Header yang Diperbaiki */
        .header-btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            border: none;
            outline: none;
            position: relative;
            overflow: hidden;
        }

        .header-btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
            background: linear-gradient(135deg, #ea580c, #dc2626);
        }

        .header-btn-secondary {
            background: transparent;
            color: var(--unib-blue);
            border: 2px solid var(--unib-blue);
            font-weight: 600;
        }

        .header-btn-secondary:hover {
            background: rgba(30, 64, 175, 0.05);
            transform: translateY(-2px);
        }

        .sticky-header.scrolled .header-btn-secondary {
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .sticky-header.scrolled .header-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.7);
        }

        .header-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
        }

        .header-btn:hover:before {
            left: 100%;
        }

        /* Icon styles */
        .header-btn i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .header-btn:hover i {
            transform: scale(1.1);
        }

        /* Utility Classes untuk Header */
        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .duration-300 {
            transition-duration: 300ms;
        }

        .ml-4 {
            margin-left: 1rem;
        }

        .space-x-4 > * + * {
            margin-left: 1rem;
        }

        .text-white {
            color: white;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-blue-200 {
            color: #bfdbfe;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .h-10 {
            height: 2.5rem;
        }

        .w-auto {
            width: auto;
        }

        /* Responsive Adjustments untuk Header */
        @media (max-width: 768px) {
            .section-title {
                font-size: 2.25rem;
            }
            
            .section-subtitle {
                font-size: 1.1rem;
            }
            
            .feature-card, .magang-card {
                padding: 24px 20px;
            }

            .header-content {
                padding: 0 16px;
            }
            
            .header-btn {
                padding: 8px 16px;
                font-size: 0.875rem;
            }
            
            .space-x-4 > * + * {
                margin-left: 0.75rem;
            }
            
            .header-logo-text h1 {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 480px) {
            .section-title {
                font-size: 1.875rem;
            }
            
            .container-custom {
                padding: 0 16px;
            }

            .header-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .header-btn i {
                font-size: 0.875rem;
            }
            
            .space-x-4 > * + * {
                margin-left: 0.5rem;
            }
            
            .header-logo-text h1 {
                font-size: 1.2rem;
            }
            
            .university-badge {
                font-size: 0.65rem;
                padding: 3px 8px;
            }
        }

        /* NEW: Scroll Progress Indicator - MODIFIED untuk header tengah */
        .header-scroll-indicator {
            display: flex;
            align-items: center;
            gap: 32px; /* Jarak yang lebih renggang antara bulatan */
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .header-scroll-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--unib-blue);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .header-scroll-dot.active {
            background-color: var(--unib-blue);
            transform: scale(1.4);
            box-shadow: 0 0 10px rgba(30, 64, 175, 0.5);
        }

        .header-scroll-dot:hover {
            transform: scale(1.5);
            background-color: var(--unib-blue-dark);
        }

        .header-scroll-dot:after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            border: 1px solid var(--unib-blue);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .header-scroll-dot.active:after,
        .header-scroll-dot:hover:after {
            opacity: 1;
        }

        .header-scroll-label {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            font-weight: 500;
        }

        .header-scroll-dot:hover .header-scroll-label {
            opacity: 1;
        }

        /* Sticky header scrolled state untuk scroll indicator */
        .sticky-header.scrolled .header-scroll-dot {
            background-color: rgba(255, 255, 255, 0.7);
        }

        .sticky-header.scrolled .header-scroll-dot.active {
            background-color: white;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .sticky-header.scrolled .header-scroll-dot:hover {
            background-color: white;
        }

        .sticky-header.scrolled .header-scroll-dot:after {
            border-color: rgba(255, 255, 255, 0.8);
        }

        /* Layout header untuk menempatkan scroll indicator di tengah */
        .header-nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: relative;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        /* Responsive untuk header scroll indicator */
        @media (max-width: 1024px) {
            .header-scroll-indicator {
                gap: 24px;
            }
        }

        @media (max-width: 768px) {
            .header-scroll-indicator {
                display: none; /* Sembunyikan di tablet dan mobile */
            }
            
            .header-nav-container {
                justify-content: space-between;
            }
        }

        /* NEW: University Badge tanpa ikon */
        .university-badge {
            display: inline-block;
            background: rgba(30, 64, 175, 0.08);
            color: var(--unib-blue);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid rgba(30, 64, 175, 0.1);
            transition: all 0.3s ease;
            margin-top: 2px;
            text-align: left;
        }

        .sticky-header.scrolled .university-badge {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Loading Screen dengan Lottie Animation -->
    <div class="loading-screen" id="loadingScreen">
        <img class="loading-logo" src="{{ asset('images/Logo Unib.png') }}" alt="UNIB Logo">
        <dotlottie-wc src="https://lottie.host/988719f4-7725-45a6-a99a-12109d30bc0b/YWP4doZdm6.lottie" style="width: 280px; height: 280px;" autoplay loop></dotlottie-wc>
        <div class="loading-text">Memuat Sistem Informasi...</div>
    </div>

    <!-- Header - IMPROVED DESIGN -->
    <header class="sticky-header shadow-lg" id="mainHeader">
        <nav class="header-content">
            <div class="header-nav-container py-4 transition-all duration-300">
                <div class="header-left">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center bg-white rounded-full p-1 shadow-md">
                            <img class="h-10 w-auto" src="{{ asset('images/Logo Unib.png') }}" alt="UNIB Logo">
                        </div>
                        <div class="ml-4 header-logo-text">
                            <h1>SIMKP</h1>
                            <div class="university-badge">
                                Universitas Bengkulu
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- NEW: Scroll Indicator di Tengah Header -->
                <div class="header-scroll-indicator">
                    <div class="header-scroll-dot active" data-section="home">
                        <span class="header-scroll-label">Beranda</span>
                    </div>
                    <div class="header-scroll-dot" data-section="magang">
                        <span class="header-scroll-label">Tempat Magang</span>
                    </div>
                    <div class="header-scroll-dot" data-section="features">
                        <span class="header-scroll-label">Fitur Utama</span>
                    </div>
                    <div class="header-scroll-dot" data-section="roles">
                        <span class="header-scroll-label">Peran Pengguna</span>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="header-btn header-btn-primary">
                                    <i class="fas fa-home"></i>
                                    Beranda
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="header-btn header-btn-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="header-btn header-btn-secondary">
                                        <i class="fas fa-user-plus"></i>
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section dari kode pertama -->
    <section class="hero-section" id="home">
        <div class="hero-background"></div>
        
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>
        
        <div class="particles" id="particles"></div>
        
        <div class="hero-content">
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('images/Logo Unib.png') }}" alt="UNIB Logo">
                </div>
            </div>
            
            <div class="title-container">
                <h1 class="main-title">
                    Sistem Informasi<br>
                    <span class="gradient-text">Manajemen Kerja Praktek</span>
                </h1>
                <p class="subtitle">
                    Platform digital untuk mengelola seluruh proses kerja praktek mahasiswa Sistem Informasi 
                    Fakultas Teknik Universitas Bengkulu secara efisien dan terintegrasi.
                </p>
            </div>
            
            <div class="button-container">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke SIMKP
                </a>
                <a href="#features" class="btn btn-secondary">
                    <i class="fas fa-info-circle"></i>
                    Pelajari Lebih Lanjut
                </a>
            </div>
            
            <div class="scroll-indicator">
                <span class="scroll-text">Scroll untuk menjelajahi</span>
                <div class="scroll-arrow"></div>
            </div>
        </div>
    </section>

    <!-- Tempat Magang Section -->
    <section class="fullpage-section bg-white pattern-dots" id="magang">
        <div class="container-custom">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="section-title">
                    Tempat Magang Tersedia
                </h2>
                <p class="section-subtitle">
                    Daftar tempat magang yang telah bekerja sama dengan Prodi Sistem Informasi Universitas Bengkulu
                </p>
            </div>

            @if($tempatMagang->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($tempatMagang as $tempat)
                        <div class="magang-card card-hover" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="card-content">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold text-unib-blue-800">{{ $tempat->nama_perusahaan }}</h3>
                                    <span class="status-badge">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                </div>
                                
                                @if($tempat->deskripsi)
                                    <p class="text-gray-600 mb-4 text-body">{{ Str::limit($tempat->deskripsi, 120) }}</p>
                                @endif
                                
                                <div class="mb-4">
                                    <p class="text-gray-600 flex items-start text-body">
                                        <i class="fas fa-map-marker-alt text-unib-blue-600 mr-2 mt-1 flex-shrink-0"></i>
                                        <span>{{ Str::limit($tempat->alamat, 100) }}</span>
                                    </p>
                                </div>
                                
                                <div class="mb-4">
                                    <span class="tag">
                                        <i class="fas fa-briefcase"></i>{{ Str::limit($tempat->bidang_usaha, 20) }}
                                    </span>
                                    <span class="tag">
                                        <i class="fas fa-users"></i>{{ $tempat->kuota_mahasiswa - $tempat->terpakai_count }}/{{ $tempat->kuota_mahasiswa }} tersedia
                                    </span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="card-footer">
                                    <div class="progress-container">
                                        <div class="progress-info">
                                            <span class="progress-label">Kuota Tersedia</span>
                                            <span class="progress-percentage">{{ round((($tempat->kuota_mahasiswa - $tempat->terpakai_count) / $tempat->kuota_mahasiswa) * 100) }}%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" data-width="{{ (($tempat->kuota_mahasiswa - $tempat->terpakai_count) / $tempat->kuota_mahasiswa) * 100 }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12" data-aos="fade-up">
                    <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Tempat Magang</h3>
                    <p class="text-gray-600 text-body">Saat ini belum ada tempat magang yang tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="fullpage-section bg-gray-50 pattern-grid" id="features">
        <div class="container-custom">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="section-title">
                    Fitur Utama SIMKP
                </h2>
                <p class="section-subtitle">
                    Solusi lengkap untuk mengelola proses kerja praktek dari pengajuan hingga evaluasi
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card card-hover" data-aos="fade-up" data-aos-delay="0">
                    <div class="icon-container bg-unib-blue-600">
                        <i class="fas fa-file-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Pengajuan KP Online</h3>
                    <p class="text-gray-600 text-body">Mahasiswa dapat mengajukan judul dan tempat kerja praktek secara online dengan mudah dan cepat.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-container bg-teknik-orange-500">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Database Tempat Magang</h3>
                    <p class="text-gray-600 text-body">Akses ke database lengkap tempat magang yang telah bekerja sama dengan Prodi.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-container bg-green-500">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Sistem Bimbingan</h3>
                    <p class="text-gray-600 text-body">Platform bimbingan terintegrasi antara mahasiswa, dosen Pembimbing/Penguji, dan pengawas lapangan.</p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-container bg-purple-500">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Monitoring Kegiatan</h3>
                    <p class="text-gray-600 text-body">Pencatatan dan monitoring kegiatan harian mahasiswa selama kerja praktek.</p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon-container bg-red-500">
                        <i class="fas fa-file-upload text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Upload Laporan</h3>
                    <p class="text-gray-600 text-body">Sistem upload dan verifikasi laporan kerja praktek secara digital.</p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card card-hover" data-aos="fade-up" data-aos-delay="500">
                    <div class="icon-container bg-yellow-500">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Evaluasi & Kuisioner</h3>
                    <p class="text-gray-600 text-body">Sistem evaluasi dan feedback untuk perbaikan proses kerja praktek berkelanjutan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Roles Section -->
    <section class="fullpage-section bg-gradient-to-br from-unib-blue-50 to-white" id="roles">
        <div class="container-custom">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="section-title">
                    Multi-User System
                </h2>
                <p class="section-subtitle">
                    Sistem dengan 4 tingkat akses untuk mengelola seluruh stakeholder
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Mahasiswa -->
                <div class="feature-card card-hover text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="icon-container bg-blue-500 mx-auto">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Mahasiswa</h3>
                    <p class="text-gray-600 text-body">Pengajuan KP, upload kegiatan, bimbingan, dan laporan</p>
                </div>

                <!-- Dosen -->
                <div class="feature-card card-hover text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-container bg-green-500 mx-auto">
                        <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Dosen</h3>
                    <p class="text-gray-600 text-body">Verifikasi pengajuan dan pembimbingan dan Pengujian Seminar untuk mahasiswa</p>
                </div>

                <!-- Super Admin -->
                <div class="feature-card card-hover text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-container bg-red-500 mx-auto">
                        <i class="fas fa-user-shield text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Super Admin/Prodi</h3>
                    <p class="text-gray-600 text-body">Kelola seluruh sistem dan data master</p>
                </div>

                <!-- Pengawas Lapangan -->
                <div class="feature-card card-hover text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-container bg-orange-500 mx-auto">
                        <i class="fas fa-clipboard-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Pengawas Lapangan</h3>
                    <p class="text-gray-600 text-body">Monitoring dan evaluasi di tempat kerja praktek</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - MODIFIED -->
    <footer class="footer text-white py-12">
        <div class="container-custom footer-content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-aos="fade-up">
                    <div class="flex items-center mb-4">
                        <img class="h-8 w-auto" src="{{ asset('images/Logo Unib.png') }}" alt="UNIB Logo">
                        <div class="ml-3">
                            <div class="text-white font-bold text-lg">SIMKP</div>
                            <div class="text-blue-200 text-sm">Universitas Bengkulu</div>
                        </div>
                    </div>
                    <p class="text-blue-200 text-body">
                        Sistem Informasi Manajemen Kerja Praktek Fakultas Teknik Universitas Bengkulu.
                    </p>
                </div>
                
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="space-y-2 text-blue-200">
                        <p class="flex items-start"><i class="fas fa-map-marker-alt mr-2 mt-1 flex-shrink-0"></i> Jl. WR Supratman, Bengkulu</p>
                        <p class="flex items-start"><i class="fas fa-phone mr-2 mt-1 flex-shrink-0"></i> (0736) 344087</p>
                        <p class="flex items-start"><i class="fas fa-envelope mr-2 mt-1 flex-shrink-0"></i> sisteminformasi@unib.ac.id</p>
                    </div>
                </div>
                
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="#" class="block text-blue-200 hover:text-white transition text-body">Panduan Penggunaan</a>
                        <a href="#" class="block text-blue-200 hover:text-white transition text-body">FAQ</a>
                        <a href="#" class="block text-blue-200 hover:text-white transition text-body">Dukungan Teknis</a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-blue-700 mt-8 pt-8 text-center text-blue-200" data-aos="fade-up">
                <p class="text-body">Copyright &copy; {{ date('Y') }} Prodi Sistem Informasi Fakultas Teknik Universitas Bengkulu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Library Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Hide loading screen when page is loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingScreen').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 800);
            }, 2500);
        });

        // Sticky header on scroll - MODIFIED
        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Progress bar animation on scroll
        function animateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width + '%';
                }, 200);
            });
        }

        // Intersection Observer for progress bars
        const progressSection = document.querySelector('#magang');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(animateProgressBars, 500);
                }
            });
        }, { threshold: 0.3 });

        if (progressSection) {
            observer.observe(progressSection);
        }

        // Create particle effect untuk hero section
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random properties
                const size = Math.random() * 5 + 2;
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                const delay = Math.random() * 15;
                const duration = Math.random() * 10 + 10;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.top = `${posY}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Initialize particles
        document.addEventListener('DOMContentLoaded', createParticles);
        
        // Add hover effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add hover effect to header buttons
        document.querySelectorAll('.header-btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Add scroll functionality untuk hero section
        document.querySelector('.scroll-indicator').addEventListener('click', function() {
            window.scrollTo({
                top: window.innerHeight,
                behavior: 'smooth'
            });
        });

        // Full page scroll effect
        let currentSection = 0;
        const sections = document.querySelectorAll('.fullpage-section, .hero-section');
        
        function scrollToSection(index) {
            if (index >= 0 && index < sections.length) {
                sections[index].scrollIntoView({ behavior: 'smooth' });
                currentSection = index;
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                scrollToSection(currentSection + 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                scrollToSection(currentSection - 1);
            }
        });
        
        // Update current section on scroll
        window.addEventListener('scroll', function() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });
            
            // Update current section index
            sections.forEach((section, index) => {
                if (section.getAttribute('id') === current) {
                    currentSection = index;
                }
            });
        });

        // NEW: Header scroll indicator functionality
        const headerScrollDots = document.querySelectorAll('.header-scroll-dot');
        const sectionsForIndicator = ['home', 'magang', 'features', 'roles'];
        
        // Update active dot based on scroll position
        function updateHeaderScrollIndicator() {
            const scrollPosition = window.scrollY + 100;
            
            sectionsForIndicator.forEach((sectionId, index) => {
                const section = document.getElementById(sectionId);
                if (section) {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        headerScrollDots.forEach(dot => dot.classList.remove('active'));
                        headerScrollDots[index].classList.add('active');
                    }
                }
            });
        }
        
        // Click on dot to scroll to section
        headerScrollDots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                const sectionId = sectionsForIndicator[index];
                const section = document.getElementById(sectionId);
                if (section) {
                    window.scrollTo({
                        top: section.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Update header scroll indicator on scroll
        window.addEventListener('scroll', updateHeaderScrollIndicator);
        
        // Initialize header scroll indicator
        updateHeaderScrollIndicator();
    </script>
</body>
</html>