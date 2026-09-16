<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperFresco — Mercado Gourmet & Orgánico</title>
    <meta name="description" content="Productos frescos del campo a tu mesa. Frutas, verduras, lácteos y carnes con la máxima calidad y frescura garantizada.">
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-900: #042217;
            --primary-800: #063826;
            --primary-700: #0b4e37;
            --primary-600: #106f4e;
            --primary-500: #159c6c;
            --primary-400: #22c584;
            --primary-100: #d1fae5;
            --primary-50: #ecfdf5;

            --accent-gold: #d97706;
            --accent-gold-light: #fef3c7;
            --accent-coral: #f43f5e;
            --accent-coral-light: #fff1f2;

            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;

            --bg-canvas: #f8fafc;
            --surface-card: #ffffff;
            --border-subtle: #e2e8f0;
            --border-light: rgba(226, 232, 240, 0.8);

            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
            --shadow-md: 0 4px 16px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            --shadow-lg: 0 16px 32px -8px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
            --shadow-glow: 0 8px 24px -4px rgba(16, 111, 78, 0.28);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-canvas);
            color: var(--text-dark);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-name, .price-new, .slide-badge {
            font-family: 'Outfit', sans-serif;
        }

        /* ─────────────── NAVBAR GLASSMORPHISM ─────────────── */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 0 3.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
            border-bottom: 1px solid var(--border-light);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            position: sticky; top: 0; z-index: 200;
            transition: all 0.3s ease;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }
        .brand-logo-wrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
            box-shadow: 0 4px 12px rgba(16, 111, 78, 0.25);
        }
        .brand-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .brand-name b {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        .brand-tagline {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--accent-gold);
            margin-top: 2px;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            list-style: none;
        }
        .navbar-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.92rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            position: relative;
        }
        .navbar-links a:hover {
            color: var(--primary-700);
            background: var(--primary-50);
        }
        .navbar-links a.active {
            color: var(--primary-700);
            background: var(--primary-50);
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .search-container {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 20px;
            padding: 0.4rem 0.9rem;
            transition: all 0.2s;
        }
        .search-container:focus-within {
            background: #fff;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(21, 156, 108, 0.15);
        }
        .search-container input {
            border: none;
            background: transparent;
            outline: none;
            font-family: inherit;
            font-size: 0.85rem;
            color: var(--text-dark);
            width: 140px;
            transition: width 0.3s ease;
        }
        .search-container input:focus {
            width: 190px;
        }
        .search-icon {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-right: 0.4rem;
        }

        .btn-register-link {
            color: var(--primary-700);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .btn-register-link:hover {
            background: var(--primary-50);
        }

        .btn-cuenta {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            color: #fff;
            border: none;
            padding: 0.55rem 1.35rem;
            border-radius: 24px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.25s ease;
            box-shadow: var(--shadow-glow);
        }
        .btn-cuenta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 26px -4px rgba(16, 111, 78, 0.4);
            filter: brightness(1.05);
        }

        /* ─────────────── HERO SLIDER CINEMATOGRÁFICO ─────────────── */
        .hero-slider {
            position: relative;
            overflow: hidden;
            height: 520px;
            background: var(--primary-900);
        }
        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .slide.active { opacity: 1; }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.42) saturate(1.15);
            transform: scale(1.04);
            transition: transform 7s ease-out;
        }
        .slide.active img { transform: scale(1); }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(4, 34, 23, 0.3) 0%, rgba(4, 34, 23, 0.82) 100%);
        }

        .slide-content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            padding: 2.5rem;
            z-index: 5;
        }
        .slide-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.4rem 1.1rem;
            border-radius: 30px;
            margin-bottom: 1.2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .slide-badge.gold {
            background: rgba(217, 119, 6, 0.25);
            border-color: rgba(245, 158, 11, 0.5);
            color: #fef3c7;
        }
        .slide-content h1 {
            font-size: 3.6rem;
            font-weight: 900;
            line-height: 1.12;
            margin-bottom: 1.1rem;
            letter-spacing: -0.03em;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.45);
            max-width: 780px;
        }
        .slide-content p {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 540px;
            line-height: 1.65;
            margin-bottom: 2.2rem;
            font-weight: 400;
        }
        .slide-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: #fff;
            padding: 0.9rem 2.2rem;
            border-radius: 30px;
            font-size: 0.98rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 6px 24px rgba(21, 156, 108, 0.45);
            transition: all 0.25s ease;
        }
        .slide-cta:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 30px rgba(21, 156, 108, 0.6);
            filter: brightness(1.1);
        }

        .slider-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.15);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #fff; width: 48px; height: 48px; border-radius: 50%;
            font-size: 1.5rem; cursor: pointer; display: flex;
            align-items: center; justify-content: center;
            transition: all 0.2s; z-index: 10;
        }
        .slider-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: #fff;
            transform: translateY(-50%) scale(1.08);
        }
        .slider-btn.prev { left: 2rem; }
        .slider-btn.next { right: 2rem; }
        .slider-dots {
            position: absolute; bottom: 1.8rem; left: 50%; transform: translateX(-50%);
            display: flex; gap: 0.6rem; z-index: 10;
        }
        .dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: rgba(255, 255, 255, 0.4); cursor: pointer;
            transition: all 0.3s ease; border: none;
        }
        .dot.active {
            background: #fff;
            width: 28px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
        }

        /* ─────────────── BENEFICIOS PRÉMIUM ─────────────── */
        .benefits-wrap {
            padding: 0 3.5rem;
            margin-top: -55px;
            position: relative;
            z-index: 15;
        }
        .benefits {
            background: var(--surface-card);
            border-radius: 22px;
            box-shadow: var(--shadow-lg);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            overflow: hidden;
            border: 1px solid var(--border-light);
        }
        .benefit {
            text-align: center;
            padding: 2rem 1.4rem;
            border-right: 1px solid var(--border-light);
            transition: all 0.25s ease;
        }
        .benefit:last-child { border-right: none; }
        .benefit:hover {
            background: #fafcfb;
            transform: translateY(-2px);
        }
        .benefit-icon-wrap {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-50) 0%, #e6f9f0 100%);
            border: 1px solid var(--primary-100);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            margin: 0 auto 0.9rem;
            transition: transform 0.2s;
        }
        .benefit:hover .benefit-icon-wrap {
            transform: scale(1.1) rotate(4deg);
        }
        .benefit h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
        }
        .benefit p {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ─────────────── CONTENEDOR Y SECCIONES ─────────────── */
        .container { max-width: 1240px; margin: 0 auto; padding: 0 3.5rem; }
        .section { padding: 4.5rem 0; }
        .section-white { background: #ffffff; }
        .section-gray  { background: var(--bg-canvas); }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2.2rem;
        }
        .section-title-wrap h2 {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            position: relative;
            display: inline-block;
            padding-bottom: 0.6rem;
        }
        .section-title-wrap h2::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 44px; height: 3.5px;
            background: linear-gradient(90deg, var(--primary-500) 0%, var(--accent-gold) 100%);
            border-radius: 4px;
        }
        .section-header .link-all {
            color: var(--primary-600);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s;
        }
        .section-header .link-all:hover {
            color: var(--primary-700);
            gap: 0.65rem;
        }

        .section-center-title { text-align: center; margin-bottom: 2.8rem; }
        .section-center-title h2 {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }
        .section-center-title .underline-accent {
            display: block;
            width: 50px;
            height: 3.5px;
            background: linear-gradient(90deg, var(--accent-coral) 0%, var(--accent-gold) 100%);
            border-radius: 3px;
            margin: 0.6rem auto 0;
        }
        .section-center-title p {
            color: var(--text-muted);
            font-size: 1rem;
            margin-top: 0.8rem;
            font-weight: 500;
        }

        /* ─────────────── CATEGORÍAS ─────────────── */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.4rem;
        }
        .cat-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 240px;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cat-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }
        .cat-card:hover img {
            transform: scale(1.1);
        }
        .cat-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(4, 34, 23, 0.05) 30%, rgba(4, 34, 23, 0.88) 100%);
            transition: opacity 0.3s;
        }
        .cat-label {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            color: #fff;
            padding: 1.4rem 1.2rem 1.1rem;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            z-index: 2;
        }
        .cat-label span {
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            display: block;
            margin-top: 0.25rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ─────────────── TARJETAS DE PRODUCTO ─────────────── */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.6rem;
        }
        .product-card {
            background: var(--surface-card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(16, 111, 78, 0.25);
        }
        .product-img {
            position: relative;
            height: 205px;
            overflow: hidden;
            background: #f8fafc;
        }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-img img {
            transform: scale(1.08);
        }
        .badge-discount {
            position: absolute;
            top: 0.85rem; left: 0.85rem;
            background: var(--accent-coral);
            color: #fff;
            font-size: 0.74rem;
            font-weight: 800;
            padding: 0.28rem 0.65rem;
            border-radius: 8px;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 10px rgba(244, 63, 94, 0.35);
        }
        .badge-organic {
            position: absolute;
            top: 0.85rem; left: 0.85rem;
            background: var(--primary-700);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
        }
        .btn-wishlist {
            position: absolute;
            top: 0.8rem; right: 0.8rem;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            width: 36px; height: 36px;
            border-radius: 50%;
            font-size: 1rem;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
        .btn-wishlist:hover {
            background: #fff;
            color: var(--accent-coral);
            transform: scale(1.15);
            border-color: var(--accent-coral-light);
        }
        .product-info {
            padding: 1.2rem 1.1rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-category {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary-600);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.4rem;
        }
        .product-info h3 {
            font-size: 1.02rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.8rem;
            line-height: 1.35;
            flex: 1;
        }
        .product-footer { margin-top: auto; }
        .product-prices {
            display: flex;
            align-items: baseline;
            gap: 0.55rem;
            margin-bottom: 0.85rem;
        }
        .price-new {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary-700);
            letter-spacing: -0.5px;
        }
        .price-old {
            font-size: 0.86rem;
            color: var(--text-light);
            text-decoration: line-through;
            font-weight: 500;
        }
        .btn-agregar {
            width: 100%;
            padding: 0.7rem;
            background: var(--primary-50);
            color: var(--primary-700);
            border: 1.5px solid var(--primary-100);
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.25s ease;
            font-family: inherit;
        }
        .btn-agregar:hover {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            color: #fff;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 111, 78, 0.3);
        }

        /* ─────────────── BANNER PROMO EXCLUSIVO ─────────────── */
        .promo-banner {
            background: linear-gradient(135deg, var(--primary-900) 0%, var(--primary-700) 55%, var(--primary-600) 100%);
            border-radius: 24px;
            padding: 3.5rem 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
            overflow: hidden;
            position: relative;
            box-shadow: var(--shadow-lg);
        }
        .promo-banner::before {
            content: '';
            position: absolute;
            width: 460px; height: 460px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 197, 132, 0.15) 0%, rgba(255,255,255,0) 70%);
            top: -180px; right: -80px;
            pointer-events: none;
        }
        .promo-text .label {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--accent-gold-light);
            background: rgba(217, 119, 6, 0.25);
            border: 1px solid rgba(245, 158, 11, 0.4);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.9rem;
        }
        .promo-text h2 {
            font-size: 2.3rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 0.8rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        .promo-text p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.02rem;
            max-width: 420px;
            line-height: 1.65;
        }
        .promo-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-top: 1.8rem;
            background: #ffffff;
            color: var(--primary-800);
            padding: 0.85rem 2.2rem;
            border-radius: 30px;
            font-size: 0.96rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }
        .promo-cta:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
            background: #f8fafc;
        }
        .promo-image {
            font-size: 7.5rem;
            line-height: 1;
            flex-shrink: 0;
            filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.4));
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* ─────────────── FOOTER PRÉMIUM ─────────────── */
        .footer {
            background: #08121e;
            color: #94a3b8;
            padding: 4.5rem 3.5rem 0;
            border-top: 1px solid #1e293b;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.3fr;
            gap: 3.5rem;
            padding-bottom: 3.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .footer-brand-logo {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 1.2rem;
        }
        .footer-brand p {
            font-size: 0.9rem;
            line-height: 1.75;
            color: #64748b;
            margin-bottom: 1.6rem;
            max-width: 280px;
        }
        .social-links { display: flex; gap: 0.65rem; }
        .social-btn {
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; font-size: 0.9rem; font-weight: 700;
            transition: all 0.25s ease;
        }
        .social-btn:hover {
            background: var(--primary-600);
            color: #fff;
            border-color: var(--primary-500);
            transform: translateY(-3px);
            box-shadow: 0 4px 14px rgba(16, 111, 78, 0.35);
        }
        .footer-col h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 0.65rem; }
        .footer-col ul li a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .footer-col ul li a:hover {
            color: var(--primary-400);
            transform: translateX(3px);
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            margin-bottom: 0.8rem;
            font-size: 0.88rem;
            color: #64748b;
        }
        .contact-item .ci {
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 0.05rem;
        }
        .footer-bottom {
            padding: 1.8rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-bottom p {
            font-size: 0.85rem;
            color: #475569;
        }
        .payment-icons { display: flex; gap: 0.5rem; }
        .pay-icon {
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.35rem 0.85rem;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.05em;
        }

        /* ─────────────── RESPONSIVE ─────────────── */
        @media (max-width: 1024px) {
            .navbar { padding: 0 1.5rem; }
            .container { padding: 0 1.5rem; }
            .benefits-wrap { padding: 0 1.5rem; }
            .benefits { grid-template-columns: repeat(2, 1fr); }
            .categories-grid, .products-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 2.5rem; }
            .slide-content h1 { font-size: 2.6rem; }
        }
        @media (max-width: 640px) {
            .navbar-links { display: none; }
            .search-container input { width: 90px; }
            .benefits { grid-template-columns: 1fr; }
            .categories-grid, .products-grid { grid-template-columns: 1fr; }
            .promo-banner { flex-direction: column; text-align: center; padding: 2.5rem 1.5rem; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
            .slide-content h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>

<!-- ═══════════════ NAVBAR ═══════════════ -->
<nav class="navbar">
    <a href="{{ route('index') }}" class="navbar-brand">
        <div class="brand-logo-wrap">🛒</div>
        <div>
            <div class="brand-name">Super<b>Fresco</b></div>
            <span class="brand-tagline">Mercado Gourmet & Orgánico</span>
        </div>
    </a>

    <ul class="navbar-links">
        <li><a href="{{ route('index') }}" class="active">Inicio</a></li>
        <li><a href="#ofertas">Ofertas</a></li>
        <li><a href="#categorias">Categorías</a></li>
        <li><a href="#beneficios">Nosotros</a></li>
    </ul>

    <div class="navbar-right">
        <div class="search-container">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Buscar producto..." aria-label="Buscar producto">
        </div>
        @auth
            <a href="{{ route('dashboard') }}" class="btn-cuenta">
                <span>👤</span>
                <span>{{ Auth::user()->nombres }}</span>
            </a>
        @else
            <a href="{{ route('register') }}" class="btn-register-link">Crear cuenta</a>
            <a href="{{ route('login') }}" class="btn-cuenta">
                <span>👤</span>
                <span>Ingresar</span>
            </a>
        @endauth
    </div>
</nav>

<!-- ═══════════════ HERO SLIDER ═══════════════ -->
<div class="hero-slider" id="heroSlider">
    <div class="slide active">
        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1600&q=85" alt="Ofertas de frutas y verduras frescas">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <span class="slide-badge gold">✨ Selección Gourmet</span>
            <h1>Cosechas Frescas y Orgánicas de Temporada</h1>
            <p>Directo de los mejores campos a tu mesa, garantizando la máxima frescura y nutrición para tu familia.</p>
            <a href="#ofertas" class="slide-cta">Descubrir Ofertas →</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?w=1600&q=85" alt="Frutas y vegetales premium">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <span class="slide-badge">🌿 100% Calidad Garantizada</span>
            <h1>Alimentos Naturales sin Intermediarios</h1>
            <p>Apoyamos a productores locales para entregarte alimentos sustentables con sabor auténtico.</p>
            <a href="#categorias" class="slide-cta">Explorar Catálogo →</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1600&q=85" alt="Cortes selectos y quesos artesanales">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <span class="slide-badge gold">🥩 Especialidades del Chef</span>
            <h1>Cortes Finos y Lácteos Artesanales</h1>
            <p>La mejor selección de productos curados y refrigerados con entrega exprés el mismo día.</p>
            <a href="#ofertas" class="slide-cta">Ver Especialidades →</a>
        </div>
    </div>

    <button class="slider-btn prev" onclick="changeSlide(-1)" aria-label="Diapositiva anterior">&#8249;</button>
    <button class="slider-btn next" onclick="changeSlide(1)" aria-label="Diapositiva siguiente">&#8250;</button>

    <div class="slider-dots" id="sliderDots">
        <button class="dot active" onclick="goToSlide(0)" aria-label="Ir a diapositiva 1"></button>
        <button class="dot" onclick="goToSlide(1)" aria-label="Ir a diapositiva 2"></button>
        <button class="dot" onclick="goToSlide(2)" aria-label="Ir a diapositiva 3"></button>
    </div>
</div>

<!-- ═══════════════ BENEFICIOS ═══════════════ -->
<div class="benefits-wrap" id="beneficios">
    <div class="benefits">
        <div class="benefit">
            <div class="benefit-icon-wrap">⚡</div>
            <h3>Envío Rápido & Seguro</h3>
            <p>Gratis en compras superiores a $50</p>
        </div>
        <div class="benefit">
            <div class="benefit-icon-wrap">🌱</div>
            <h3>Origen Orgánico</h3>
            <p>Certificación de calidad sustentable</p>
        </div>
        <div class="benefit">
            <div class="benefit-icon-wrap">🏷️</div>
            <h3>Precios Transparentes</h3>
            <p>Ofertas exclusivas cada semana</p>
        </div>
        <div class="benefit">
            <div class="benefit-icon-wrap">✨</div>
            <h3>Atención Personalizada</h3>
            <p>Soporte y asesoría los 7 días</p>
        </div>
    </div>
</div>

<!-- ═══════════════ CATEGORÍAS ═══════════════ -->
<div class="section-white" id="categorias">
    <div class="container">
        <div class="section" style="padding-top: 3.5rem;">
            <div class="section-header">
                <div class="section-title-wrap">
                    <h2>Categorías Destacadas</h2>
                </div>
                <a href="#" class="link-all">Ver todas las categorías →</a>
            </div>
            <div class="categories-grid">
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&q=80" alt="Frutas y Verduras">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">
                        Frutas & Vegetales
                        <span>Recién cosechados del huerto</span>
                    </div>
                </div>
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600&q=80" alt="Carnes y Aves">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">
                        Carnes & Aves Selectas
                        <span>Cortes madurados y frescos</span>
                    </div>
                </div>
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&q=80" alt="Lácteos y Huevos">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">
                        Lácteos Artesanales
                        <span>Quesos, yogures y leche pura</span>
                    </div>
                </div>
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&q=80" alt="Panadería">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">
                        Panadería & Granos
                        <span>Masa madre horneada al día</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ OFERTAS DESTACADAS ═══════════════ -->
<div class="section-gray" id="ofertas">
    <div class="container">
        <div class="section">
            <div class="section-center-title">
                <h2>Ofertas & Favoritos de la Semana</h2>
                <span class="underline-accent"></span>
                <p>Aprovecha precios especiales en productos seleccionados de alta demanda</p>
            </div>
            <div class="products-grid">

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=400&q=80" alt="Plátanos Frescos">
                        <span class="badge-discount">-20% OFF</span>
                        <button class="btn-wishlist" title="Guardar en favoritos">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Frutas Orgánicas</div>
                        <h3>Plátanos Cavendish Seleccionados (1kg)</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$1.20</span>
                                <span class="price-old">$1.50</span>
                            </div>
                            <button class="btn-agregar">
                                <span>🛒</span>
                                <span>Agregar al Carrito</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1582979512210-99b6a53386f9?w=400&q=80" alt="Naranjas Dulces">
                        <span class="badge-organic">🌿 Orgánico</span>
                        <button class="btn-wishlist" title="Guardar en favoritos">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Cítricos del Valle</div>
                        <h3>Naranjas Valencia Dulces (Malla 2kg)</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$3.50</span>
                            </div>
                            <button class="btn-agregar">
                                <span>🛒</span>
                                <span>Agregar al Carrito</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400&q=80" alt="Leche Entera">
                        <span class="badge-discount">-15% OFF</span>
                        <button class="btn-wishlist" title="Guardar en favoritos">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Lácteos Frescos</div>
                        <h3>Leche Entera Pasteurizada Botella Vidrio (1L)</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$1.10</span>
                                <span class="price-old">$1.30</span>
                            </div>
                            <button class="btn-agregar">
                                <span>🛒</span>
                                <span>Agregar al Carrito</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&q=80" alt="Pan Artesanal">
                        <span class="badge-organic">✨ Artesanal</span>
                        <button class="btn-wishlist" title="Guardar en favoritos">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Panadería Rústica</div>
                        <h3>Pan de Molde Multigrano y Semillas</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$2.20</span>
                            </div>
                            <button class="btn-agregar">
                                <span>🛒</span>
                                <span>Agregar al Carrito</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ BANNER PROMO ═══════════════ -->
<div class="section-white">
    <div class="container">
        <div style="padding: 0 0 4.5rem;">
            <div class="promo-banner">
                <div class="promo-text">
                    <div class="label">✨ Beneficio Exclusivo</div>
                    <h2>Descarga la App SuperFresco<br>y recibe 10% de Descuento</h2>
                    <p>Haz tus compras desde tu móvil, programa tu entrega a la hora exacta y disfruta de promociones exclusivas para miembros.</p>
                    <a href="#" class="promo-cta">Descargar Gratis →</a>
                </div>
                <div class="promo-image">📲</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ FOOTER ═══════════════ -->
<footer class="footer">
    <div class="container" style="padding: 0;">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-brand-logo">
                    <div class="brand-logo-wrap" style="width:36px;height:36px;font-size:1.1rem;">🛒</div>
                    <div class="brand-name" style="color:#fff;font-size:1.25rem;">Super<b>Fresco</b></div>
                </div>
                <p>Tu supermercado gourmet y orgánico de confianza. Calidad insuperable, frescura verificada y entregas puntuales directo a tu hogar.</p>
                <div class="social-links">
                    <a href="#" class="social-btn" aria-label="Facebook">f</a>
                    <a href="#" class="social-btn" aria-label="Instagram">ig</a>
                    <a href="#" class="social-btn" aria-label="Twitter">tw</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navegación</h4>
                <ul>
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="#ofertas">Ofertas de la Semana</a></li>
                    <li><a href="#categorias">Categorías</a></li>
                    <li><a href="#beneficios">Sobre Nosotros</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Ayuda & Políticas</h4>
                <ul>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                    <li><a href="#">Tiempos de Entrega</a></li>
                    <li><a href="#">Términos del Servicio</a></li>
                    <li><a href="#">Privacidad y Seguridad</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contacto</h4>
                <div class="contact-item"><span class="ci">📍</span> Sector Gourmet, Centro Comercial Principal</div>
                <div class="contact-item"><span class="ci">✉️</span> atencion@superfresco.com</div>
                <div class="contact-item"><span class="ci">📞</span> +1 (800) 456-FRESCO</div>
                <div class="contact-item"><span class="ci">🕐</span> Lunes a Domingo: 7:00 AM – 9:30 PM</div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2026 SuperFresco. Todos los derechos reservados. Mercado Gourmet & Orgánico.</p>
            <div class="payment-icons">
                <span class="pay-icon">VISA</span>
                <span class="pay-icon">MASTERCARD</span>
                <span class="pay-icon">PAYPAL</span>
                <span class="pay-icon">APPLE PAY</span>
            </div>
        </div>
    </div>
</footer>

<script>
    let current = 0;
    const slides = document.querySelectorAll('.slide');
    const dots   = document.querySelectorAll('.dot');

    function updateDots() {
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    function goToSlide(n) {
        slides[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        updateDots();
    }

    function changeSlide(dir) { goToSlide(current + dir); }

    // Auto-avance suave cada 6 segundos
    setInterval(() => changeSlide(1), 6000);
</script>
</body>
</html>
