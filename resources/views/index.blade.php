<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperFresco — Tu supermercado en línea</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #f5f7f5; color: #1a202c; overflow-x: hidden; }

        /* ─────────────── NAVBAR ─────────────── */
        .navbar {
            background: #fff;
            padding: 0 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
            box-shadow: 0 1px 0 #e5e7eb, 0 4px 16px rgba(0,0,0,.06);
            position: sticky; top: 0; z-index: 200;
        }
        .navbar-brand { display: flex; align-items: center; gap: .55rem; text-decoration: none; }
        .brand-logo { font-size: 1.7rem; line-height: 1; }
        .brand-name { font-size: 1.25rem; font-weight: 400; color: #1a202c; letter-spacing: -.3px; }
        .brand-name b { color: #16a34a; font-weight: 800; }
        .navbar-links { display: flex; align-items: center; gap: .25rem; list-style: none; }
        .navbar-links a {
            text-decoration: none; color: #4b5563; font-size: .9rem; font-weight: 500;
            padding: .45rem .9rem; border-radius: 8px; transition: all .2s;
        }
        .navbar-links a:hover { color: #16a34a; background: #f0fdf4; }
        .navbar-links a.active { color: #16a34a; font-weight: 600; }
        .navbar-right { display: flex; align-items: center; gap: .8rem; }
        .btn-search {
            background: #f3f4f6; border: none; width: 38px; height: 38px; border-radius: 10px;
            font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .btn-search:hover { background: #e5e7eb; }
        .btn-cuenta {
            background: #16a34a; color: #fff; border: none;
            padding: .52rem 1.2rem; border-radius: 22px; font-size: .88rem;
            font-weight: 600; cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: .4rem;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 2px 8px rgba(22,163,74,.35);
        }
        .btn-cuenta:hover { background: #15803d; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(22,163,74,.4); }

        /* ─────────────── HERO SLIDER ─────────────── */
        .hero-slider { position: relative; overflow: hidden; height: 500px; background: #0a0a0a; }
        .slide { position: absolute; inset: 0; opacity: 0; transition: opacity .9s cubic-bezier(.4,0,.2,1); }
        .slide.active { opacity: 1; }
        .slide img {
            width: 100%; height: 100%; object-fit: cover;
            filter: brightness(.45) saturate(1.1);
            transform: scale(1.03);
            transition: transform 6s ease;
        }
        .slide.active img { transform: scale(1); }
        .slide-content {
            position: absolute; inset: 0;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; color: #fff; padding: 2rem;
        }
        .slide-badge {
            display: inline-block; background: rgba(22,163,74,.85);
            border: 1px solid rgba(255,255,255,.3);
            backdrop-filter: blur(8px);
            color: #fff; font-size: .78rem; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            padding: .35rem .9rem; border-radius: 20px; margin-bottom: 1.2rem;
        }
        .slide-content h1 {
            font-size: 3.4rem; font-weight: 900; line-height: 1.1;
            margin-bottom: 1rem; letter-spacing: -.5px;
            text-shadow: 0 2px 20px rgba(0,0,0,.4);
        }
        .slide-content p {
            font-size: 1.1rem; opacity: .88; max-width: 500px;
            line-height: 1.65; margin-bottom: 2rem;
        }
        .slide-cta {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #16a34a; color: #fff;
            padding: .8rem 2rem; border-radius: 30px;
            font-size: .95rem; font-weight: 700; text-decoration: none;
            box-shadow: 0 4px 20px rgba(22,163,74,.5);
            transition: background .2s, transform .15s;
        }
        .slide-cta:hover { background: #15803d; transform: translateY(-2px); }
        .slider-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,.12); border: 1.5px solid rgba(255,255,255,.3);
            backdrop-filter: blur(10px);
            color: #fff; width: 50px; height: 50px; border-radius: 50%;
            font-size: 1.4rem; cursor: pointer; display: flex;
            align-items: center; justify-content: center;
            transition: all .2s; z-index: 10;
        }
        .slider-btn:hover { background: rgba(255,255,255,.25); border-color: rgba(255,255,255,.6); }
        .slider-btn.prev { left: 2rem; }
        .slider-btn.next { right: 2rem; }
        .slider-dots {
            position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
            display: flex; gap: .5rem; z-index: 10;
        }
        .dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,.4); cursor: pointer;
            transition: all .3s; border: none;
        }
        .dot.active { background: #fff; width: 24px; border-radius: 4px; }

        /* ─────────────── BENEFICIOS ─────────────── */
        .benefits-wrap { padding: 0 4rem; margin-top: -52px; position: relative; z-index: 10; }
        .benefits {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,.1);
            display: grid; grid-template-columns: repeat(4,1fr);
            overflow: hidden; border: 1px solid #f0f0f0;
        }
        .benefit {
            text-align: center; padding: 1.8rem 1.2rem;
            border-right: 1px solid #f0f0f0;
            transition: background .2s;
        }
        .benefit:last-child { border-right: none; }
        .benefit:hover { background: #f9fffe; }
        .benefit-icon-wrap {
            width: 52px; height: 52px; background: #f0fdf4; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin: 0 auto .8rem;
        }
        .benefit h3 { font-size: .92rem; font-weight: 700; color: #111827; margin-bottom: .25rem; }
        .benefit p  { font-size: .8rem; color: #9ca3af; }

        /* ─────────────── LAYOUT ─────────────── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 4rem; }
        .section { padding: 4rem 0; }
        .section-white { background: #fff; }
        .section-gray  { background: #f5f7f5; }

        /* ─────────────── TÍTULOS ─────────────── */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .section-title-wrap h2 {
            font-size: 1.65rem; font-weight: 800; color: #111827;
            padding-bottom: .55rem; position: relative; display: inline-block;
        }
        .section-title-wrap h2::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 36px; height: 3px; background: #16a34a; border-radius: 2px;
        }
        .section-header .link-all {
            color: #16a34a; text-decoration: none; font-size: .88rem;
            font-weight: 600; display: flex; align-items: center; gap: .3rem;
            transition: gap .2s;
        }
        .section-header .link-all:hover { gap: .6rem; }

        .section-center-title { text-align: center; margin-bottom: 2.5rem; }
        .section-center-title h2 { font-size: 1.9rem; font-weight: 800; color: #111827; margin-bottom: .5rem; }
        .section-center-title .underline-red {
            display: block; width: 44px; height: 3px; background: #ef4444;
            border-radius: 2px; margin: .5rem auto 0;
        }
        .section-center-title p { color: #6b7280; font-size: .95rem; margin-top: .9rem; }

        /* ─────────────── CATEGORÍAS ─────────────── */
        .categories-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; }
        .cat-card {
            position: relative; border-radius: 16px; overflow: hidden;
            height: 220px; cursor: pointer;
            box-shadow: 0 4px 16px rgba(0,0,0,.08);
        }
        .cat-card img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .4s cubic-bezier(.4,0,.2,1);
        }
        .cat-card:hover img { transform: scale(1.08); }
        .cat-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(160deg, transparent 30%, rgba(0,0,0,.72));
            transition: opacity .3s;
        }
        .cat-card:hover .cat-overlay { opacity: .85; }
        .cat-label {
            position: absolute; bottom: 0; left: 0; right: 0;
            color: #fff; padding: 1.2rem 1rem .9rem;
            font-size: 1rem; font-weight: 700; letter-spacing: -.2px;
        }
        .cat-label span { font-size: .78rem; font-weight: 400; opacity: .75; display: block; margin-top: .15rem; }

        /* ─────────────── PRODUCTOS ─────────────── */
        .products-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.4rem; }
        .product-card {
            background: #fff; border-radius: 16px;
            overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: 1px solid #f0f0f0;
            transition: transform .25s, box-shadow .25s;
            display: flex; flex-direction: column;
        }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 32px rgba(0,0,0,.11); }
        .product-img { position: relative; height: 190px; overflow: hidden; background: #f9fafb; }
        .product-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
        .product-card:hover .product-img img { transform: scale(1.06); }
        .badge-discount {
            position: absolute; top: .75rem; left: .75rem;
            background: #ef4444; color: #fff;
            font-size: .72rem; font-weight: 800;
            padding: .22rem .55rem; border-radius: 6px;
            letter-spacing: .03em;
        }
        .btn-wishlist {
            position: absolute; top: .7rem; right: .7rem;
            background: rgba(255,255,255,.9); border: none;
            width: 32px; height: 32px; border-radius: 50%;
            font-size: .9rem; cursor: pointer; display: flex;
            align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            transition: background .2s, transform .2s;
        }
        .btn-wishlist:hover { background: #fff; transform: scale(1.1); }
        .product-info { padding: 1rem 1rem .9rem; flex: 1; display: flex; flex-direction: column; }
        .product-category { font-size: .72rem; font-weight: 600; color: #16a34a; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .35rem; }
        .product-info h3 { font-size: .93rem; font-weight: 600; color: #111827; margin-bottom: .6rem; line-height: 1.4; flex: 1; }
        .product-footer { margin-top: auto; }
        .product-prices { display: flex; align-items: center; gap: .5rem; margin-bottom: .75rem; }
        .price-new { font-size: 1.2rem; font-weight: 800; color: #16a34a; }
        .price-old { font-size: .82rem; color: #d1d5db; text-decoration: line-through; }
        .btn-agregar {
            width: 100%; padding: .62rem;
            background: #f0fdf4; color: #16a34a;
            border: 1.5px solid #bbf7d0; border-radius: 10px;
            font-size: .87rem; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: .4rem;
            transition: all .2s; font-family: inherit;
        }
        .btn-agregar:hover { background: #16a34a; color: #fff; border-color: #16a34a; transform: translateY(-1px); }

        /* ─────────────── BANNER PROMO ─────────────── */
        .promo-banner {
            background: linear-gradient(120deg, #052e16 0%, #14532d 50%, #16a34a 100%);
            border-radius: 20px; padding: 3rem 3.5rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 2rem; overflow: hidden; position: relative;
        }
        .promo-banner::before {
            content: ''; position: absolute;
            width: 400px; height: 400px; border-radius: 50%;
            background: rgba(255,255,255,.04);
            top: -150px; right: -100px;
        }
        .promo-text .label {
            font-size: .78rem; font-weight: 700; color: #86efac;
            text-transform: uppercase; letter-spacing: .1em; margin-bottom: .5rem;
        }
        .promo-text h2 { font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: .6rem; line-height: 1.2; }
        .promo-text p  { color: rgba(255,255,255,.75); font-size: .95rem; max-width: 380px; line-height: 1.6; }
        .promo-cta {
            display: inline-flex; align-items: center; gap: .5rem; margin-top: 1.5rem;
            background: #fff; color: #16a34a;
            padding: .75rem 1.8rem; border-radius: 25px;
            font-size: .92rem; font-weight: 700; text-decoration: none;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
        }
        .promo-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.25); }
        .promo-image { font-size: 8rem; line-height: 1; flex-shrink: 0; }

        /* ─────────────── FOOTER ─────────────── */
        .footer { background: #0f172a; color: #94a3b8; padding: 4rem 4rem 0; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid #1e293b; }
        .footer-brand-logo { display: flex; align-items: center; gap: .5rem; margin-bottom: 1rem; }
        .footer-brand-logo .brand-name { font-size: 1.25rem; color: #f1f5f9; }
        .footer-brand p { font-size: .85rem; line-height: 1.7; color: #64748b; margin-bottom: 1.4rem; max-width: 230px; }
        .social-links { display: flex; gap: .6rem; }
        .social-btn {
            width: 38px; height: 38px; border-radius: 10px;
            background: #1e293b; color: #94a3b8;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; font-size: .85rem; font-weight: 700;
            transition: all .2s;
        }
        .social-btn:hover { background: #16a34a; color: #fff; transform: translateY(-2px); }
        .footer-col h4 { font-size: .88rem; font-weight: 700; color: #f1f5f9; margin-bottom: 1.2rem; text-transform: uppercase; letter-spacing: .06em; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: .55rem; }
        .footer-col ul li a { color: #64748b; text-decoration: none; font-size: .85rem; transition: color .2s; display: flex; align-items: center; gap: .4rem; }
        .footer-col ul li a:hover { color: #16a34a; }
        .footer-col ul li a::before { content: '›'; font-size: 1rem; color: #334155; }
        .contact-item { display: flex; align-items: flex-start; gap: .6rem; margin-bottom: .7rem; font-size: .85rem; color: #64748b; }
        .contact-item .ci { font-size: 1rem; flex-shrink: 0; margin-top: .05rem; }
        .footer-bottom { padding: 1.5rem 0; display: flex; align-items: center; justify-content: space-between; }
        .footer-bottom p { font-size: .8rem; color: #475569; }
        .payment-icons { display: flex; gap: .5rem; }
        .pay-icon { background: #1e293b; color: #64748b; padding: .3rem .75rem; border-radius: 6px; font-size: .72rem; font-weight: 800; letter-spacing: .04em; }
    </style>
</head>
<body>

<!-- ═══════════════ NAVBAR ═══════════════ -->
<nav class="navbar">
    <a href="{{ route('index') }}" class="navbar-brand">
        <span class="brand-logo">🛒</span>
        <span class="brand-name">Super<b>Fresco</b></span>
    </a>

    <ul class="navbar-links">
        <li><a href="{{ route('index') }}" class="active">Inicio</a></li>
        <li><a href="#">Ofertas</a></li>
        <li><a href="#">Categorías</a></li>
        <li><a href="#">Nosotros</a></li>
    </ul>

    <div class="navbar-right">
        <button class="btn-search" title="Buscar">🔍</button>
        @auth
            <a href="{{ route('dashboard') }}" class="btn-cuenta">👤 {{ Auth::user()->nombres }}</a>
        @else
            <a href="{{ route('register') }}" style="color:#16a34a;text-decoration:none;font-size:.88rem;font-weight:600;padding:.45rem .9rem;border-radius:8px;transition:background .2s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='none'">Registrarse</a>
            <a href="{{ route('login') }}" class="btn-cuenta">👤 Mi Cuenta</a>
        @endauth
    </div>
</nav>

<!-- ═══════════════ HERO SLIDER ═══════════════ -->
<div class="hero-slider" id="heroSlider">
    <div class="slide active">
        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1600&q=85" alt="Ofertas de frutas">
        <div class="slide-content">
            <span class="slide-badge">🔥 Oferta Especial</span>
            <h1>Ofertas de Fin<br>de Semana</h1>
            <p>Hasta 50% de descuento en frutas y verduras seleccionadas. ¡Aprovecha antes de que se agoten!</p>
            <a href="#" class="slide-cta">Ver ofertas →</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?w=1600&q=85" alt="Productos frescos">
        <div class="slide-content">
            <span class="slide-badge">🌿 Calidad Premium</span>
            <h1>Productos<br>100% Frescos</h1>
            <p>Del campo directo a tu mesa. Garantía de frescura en cada producto que llevamos a tu hogar.</p>
            <a href="#" class="slide-cta">Explorar →</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1600&q=85" alt="Carnes y lácteos">
        <div class="slide-content">
            <span class="slide-badge">🥩 Sección Premium</span>
            <h1>Carnes y Lácteos<br>de Calidad</h1>
            <p>Los mejores cortes y productos lácteos artesanales. Frescura garantizada cada día.</p>
            <a href="#" class="slide-cta">Descubrir →</a>
        </div>
    </div>
    <button class="slider-btn prev" onclick="changeSlide(-1)">&#8249;</button>
    <button class="slider-btn next" onclick="changeSlide(1)">&#8250;</button>
    <div class="slider-dots" id="sliderDots">
        <button class="dot active" onclick="goToSlide(0)"></button>
        <button class="dot" onclick="goToSlide(1)"></button>
        <button class="dot" onclick="goToSlide(2)"></button>
    </div>
</div>

<!-- ═══════════════ BENEFICIOS ═══════════════ -->
<div class="benefits-wrap">
    <div class="benefits">
        <div class="benefit">
            <div class="benefit-icon-wrap">🚚</div>
            <h3>Envío Gratis</h3>
            <p>En pedidos sobre $50</p>
        </div>
        <div class="benefit">
            <div class="benefit-icon-wrap">🌿</div>
            <h3>100% Fresco</h3>
            <p>Garantía de calidad</p>
        </div>
        <div class="benefit">
            <div class="benefit-icon-wrap">🏷️</div>
            <h3>Mejores Precios</h3>
            <p>Ofertas garantizadas</p>
        </div>
        <div class="benefit">
            <div class="benefit-icon-wrap">🎧</div>
            <h3>Soporte 24/7</h3>
            <p>Estamos para ayudarte</p>
        </div>
    </div>
</div>

<!-- ═══════════════ CATEGORÍAS ═══════════════ -->
<div class="section-white">
    <div class="container">
        <div class="section" style="padding-top:3rem;">
            <div class="section-header">
                <div class="section-title-wrap"><h2>Compra por Categorías</h2></div>
                <a href="#" class="link-all">Ver todas →</a>
            </div>
            <div class="categories-grid">
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&q=80" alt="Frutas y Verduras">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">Frutas y Verduras <span>Productos frescos del campo</span></div>
                </div>
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600&q=80" alt="Carnes y Aves">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">Carnes y Aves <span>Res, cerdo y pollo</span></div>
                </div>
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&q=80" alt="Lácteos y Huevos">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">Lácteos y Huevos <span>Leche, queso y yogur</span></div>
                </div>
                <div class="cat-card">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&q=80" alt="Panadería">
                    <div class="cat-overlay"></div>
                    <div class="cat-label">Panadería <span>Pan fresco cada día</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ OFERTAS DESTACADAS ═══════════════ -->
<div class="section-gray">
    <div class="container">
        <div class="section">
            <div class="section-center-title">
                <h2>Ofertas Destacadas <span class="underline-red"></span></h2>
                <p>Aprovecha nuestros descuentos de temporada</p>
            </div>
            <div class="products-grid">

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=400&q=80" alt="Plátanos">
                        <span class="badge-discount">-20%</span>
                        <button class="btn-wishlist">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Frutas</div>
                        <h3>Plátanos Frescos (1kg)</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$1.20</span>
                                <span class="price-old">$1.50</span>
                            </div>
                            <button class="btn-agregar">🛒 Agregar</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1582979512210-99b6a53386f9?w=400&q=80" alt="Naranjas">
                        <button class="btn-wishlist">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Frutas</div>
                        <h3>Naranjas Jugosas (Malla 2kg)</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$3.50</span>
                            </div>
                            <button class="btn-agregar">🛒 Agregar</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400&q=80" alt="Leche">
                        <span class="badge-discount">-15%</span>
                        <button class="btn-wishlist">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Lácteos</div>
                        <h3>Leche Entera Pasteurizada (1L)</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$1.10</span>
                                <span class="price-old">$1.30</span>
                            </div>
                            <button class="btn-agregar">🛒 Agregar</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&q=80" alt="Pan">
                        <button class="btn-wishlist">♡</button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Panadería</div>
                        <h3>Pan de Molde Integral</h3>
                        <div class="product-footer">
                            <div class="product-prices">
                                <span class="price-new">$2.20</span>
                            </div>
                            <button class="btn-agregar">🛒 Agregar</button>
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
        <div style="padding: 0 0 4rem;">
            <div class="promo-banner">
                <div class="promo-text">
                    <div class="label">Oferta exclusiva</div>
                    <h2>Descarga nuestra App<br>y obtén 10% OFF</h2>
                    <p>Compra desde donde estés. Disponible para iOS y Android. Primeras 3 compras con descuento especial.</p>
                    <a href="#" class="promo-cta">Descargar App →</a>
                </div>
                <div class="promo-image">📱</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ FOOTER ═══════════════ -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="footer-brand-logo">
                <span style="font-size:1.6rem;">🛒</span>
                <span class="brand-name">Super<b style="color:#16a34a;">Fresco</b></span>
            </div>
            <p>Tu supermercado de confianza en línea. Calidad, frescura y los mejores precios entregados directamente a tu puerta.</p>
            <div class="social-links">
                <a href="#" class="social-btn">f</a>
                <a href="#" class="social-btn">in</a>
                <a href="#" class="social-btn">tw</a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Enlaces Rápidos</h4>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Categorías</a></li>
                <li><a href="#">Sobre Nosotros</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Ayuda</h4>
            <ul>
                <li><a href="#">Preguntas Frecuentes</a></li>
                <li><a href="#">Políticas de Envío</a></li>
                <li><a href="#">Términos y Condiciones</a></li>
                <li><a href="#">Política de Privacidad</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contacto</h4>
            <div class="contact-item"><span class="ci">📍</span> Av. Principal 123, Ciudad</div>
            <div class="contact-item"><span class="ci">✉️</span> info@superfresco.com</div>
            <div class="contact-item"><span class="ci">📞</span> +1 234 567 890</div>
            <div class="contact-item"><span class="ci">🕐</span> Lun–Sáb: 7am – 9pm</div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2026 SuperFresco. Todos los derechos reservados.</p>
        <div class="payment-icons">
            <span class="pay-icon">VISA</span>
            <span class="pay-icon">MC</span>
            <span class="pay-icon">PayPal</span>
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

    // Auto-avance cada 5 s
    setInterval(() => changeSlide(1), 5000);
</script>
</body>
</html>
