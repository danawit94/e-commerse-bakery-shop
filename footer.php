<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Orange Bakery Footer</title>
    <!-- Bootstrap Icons CDN for clean social elements -->
    <link rel="stylesheet" href="https://jsdelivr.net">
    
    <style>
        /* --- Premium Bakery Theme Config --- */
        :root {
            --bakery-orange: #D9531E;    /* Signature burnt orange */
            --bakery-dark: #231109;      /* Ultra-deep dark chocolate/espresso */
            --bakery-cream: #FFFBF7;     /* Soft warm white pastry flour color */
            --bakery-accent: #FFA048;    /* Bright golden-orange highlight */
            --bakery-text-muted: #D1BFA7;/* Readable warm gray-beige */
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #fcf8f5;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* --- Custom SVG Pastry Wave Divider --- */
       
 /* Closes tiny gaps on mobile */
        

        /* --- Footer Wrappers --- */
        footer {
            background-color: var(--orange);
            color: var(--bakery-cream);
            padding: 60px 0 25px 0;
            position: relative;
            overflow: hidden;
        }

        /* JavaScript Animated Ambient Backdrop */
        #flour-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            opacity: 0.25;
        }

        .inner-footer {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* --- Card Styles --- */
        .card {
            background: transparent;
            border: none;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card h3 {
            font-size: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--bakery-dark);
            margin-bottom: 25px;
            font-weight: 700;
            position: relative;
            display: inline-block;
        }

        /* Decorative underline styling */
        .card h3::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 30px;
            height: 3px;
            background-color: var(--bakery-cream);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .card:hover h3::after {
            width: 55px;
        }

        .card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .card ul li {
            margin-bottom: 12px;
            color: var(--bakery-cream);
            opacity: 0.85;
            text-transform: capitalize;
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }

        /* Slide-out bullet point */
        .card ul li::before {
            content: '➔';
            margin-right: 0;
            width: 0;
            opacity: 0;
            transition: all 0.25s ease;
            font-size: 0.8rem;
            color: var(--bakery-dark);
        }

        .card ul li:hover {
            opacity: 1;
            color: var(--bakery-dark);
            padding-left: 5px;
        }

        .card ul li:hover::before {
            width: 15px;
            opacity: 1;
            margin-right: 5px;
        }

        /* --- Newsletter Form Elements --- */
        .card p {
            color: var(--bakery-cream);
            opacity: 0.9;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .input-field {
            position: relative;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .input-field input {
            width: 100%;
            padding: 12px 45px 12px 18px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            color: var(--bakery-cream);
            outline: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }

        .input-field input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-field input:focus {
            background: var(--bakery-cream);
            color: var(--bakery-dark);
            border-color: var(--bakery-dark);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .input-field i {
            position: absolute;
            right: 18px;
            color: var(--bakery-cream);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .input-field input:focus + i {
            color: var(--bakery-orange);
            transform: scale(1.1);
        }

        /* --- Social Platform Layouts --- */
        .social-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .social-links i {
            width: 40px;
            height: 40px;
            background: var(--bakery-dark);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--bakery-cream);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 1.1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .social-links i:hover {
            background: var(--bakery-cream);
            color: var(--bakery-orange);
            transform: translateY(-5px) scale(1.05);
        }

        /* --- Base Bottom Banner --- */
        .bottom-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin-top: 50px;
            padding-top: 25px;
            text-align: center;
            position: relative;
            z-index: 2;

            width: 100%;
        }

        .bottom-footer p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--bakery-dark);
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 1px;
        }

        /* --- Responsive Screen Adaptations --- */
        @media (max-width: 768px) {
            .inner-footer {
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }
        }
        @media (max-width: 480px) {
            .inner-footer {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .card h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
            .card ul li {
                justify-content: center;
            }
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Wave divider on top -->
    <div class="line4"></div>
    
    <footer>
        <!-- Canvas animation layer -->
        <canvas id="flour-canvas"></canvas>

        <div class="inner-footer">
            <div class="card">
                <h3>about us</h3>
                <ul>
                    <li>about us</li>
                    <li>our difference</li>
                    <li>community matters</li>
                    <li>press</li>
                    <li>bouqs video</li>
                    <li>blog</li>
                </ul>
            </div>
            
            <div class="card">
                <h3>services</h3>
                <ul>
                    <li>orders</li>
                    <li>help center</li>
                    <li>shipping</li>
                    <li>term of use</li>
                    <li>account detail</li>
                    <li>my account</li>
                </ul>
            </div>
            
            <div class="card">
                <h3>local</h3>
                <ul>
                    <li>mumbai</li>
                    <li>new delhi</li>
                    <li>san francisco</li>
                    <li>los angeles</li>
                    <li>chicago</li>
                    <li>new york city</li>
                </ul>
            </div>
            
            <div class="card">
                <h3>newsletter</h3>
                <p>Sign up for the latest sweet offers and exclusive updates.</p>
                <div class="input-field">
                    <input type="email" placeholder="email address...">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="social-links">
                     <i class="bi bi-instagram" ></i>
                      <i class="bi bi-youtube" ></i> 
                      <i class="bi bi-twitter" ></i> 
                       
                      <i class="bi bi-whatsapp" ></i> 
                      <i class="bi bi-telegram" ></i>
                </div> 
                
            </div> 
            <div class="bottom-footer"> 
                    <p>all right reserved- by danawit</p> 
            </div>
    </footer>
            


         
     </body> 
    </html>
