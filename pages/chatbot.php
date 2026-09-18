<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user'])) {
    $_SESSION['open_modal'] = "login";
    $_SESSION['login_notice'] = "Please sign in to access this feature.";
    header("Location: home.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCOD360</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

        :root {
            --subtle: #E2CEF3;
            --lilac: #CCAAE6;
            --lavender-clr: #A788DC;
            --wisteria: #9673D2;
            --thistle: #7D45C6;
            --mauve: #7E42AC;
            --orchid: #6B297C;
            --amethyst: #4F1176;
            --plum: #3B165C;

            --primary: var(--thistle);
            --deep-plum: var(--plum);
            --soft-purple: var(--wisteria);
            --light-purple: #f8f3fd;
            --lavender: var(--subtle);
            --white: #ffffff;
            --text: #2a1042;
            --muted: #6b5a82;
            --border: #dfd1ef;

            --shadow: 0 15px 40px rgba(59, 22, 92, 0.04);
            --transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);

            --btn-gradient: linear-gradient(135deg, var(--thistle) 0%, var(--mauve) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, var(--subtle) 0%, transparent 40%),
                radial-gradient(circle at bottom right, #eadfff 0%, transparent 30%),
                #faf9ff;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        /* Custom Health Status Card styling inside chat bubbles */
.health-status-card {
    background: #ffffff;
    border-left: 4px solid #3b82f6; /* Blue bar indicator */
    border-radius: 8px;
    padding: 12px 16px;
    margin-top: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
}

.health-status-card.status-high {
    border-left-color: #ef4444; /* Red bar indicator for High Risk */
}

.health-status-card.status-low {
    border-left-color: #10b981; /* Green bar indicator for Low Risk */
}

.card-header-title {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 6px;
    font-size: 0.95rem;
}

.card-header-title i {
    margin-right: 8px;
}

.card-metrics-grid {
    font-size: 0.88rem;
    color: #4b5563;
    line-height: 1.5;
}

.badge-status {
    display: inline-block;
    padding: 2px 8px;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 4px;
    text-transform: uppercase;
}

.badge-status.bg-low {
    background-color: #d1fae5;
    color: #065f46;
}

.badge-status.bg-high {
    background-color: #fee2e2;
    color: #991b1b;
}

        /* --- MOBILE TOGGLE NAVIGATION CONTAINER --- */
        .mobile-nav-controls {
            display: none;
            align-items: center;
            gap: 12px;
        }

        .mobile-history-toggle {
            background: none;
            border: none;
            color: var(--deep-plum);
            font-size: 1.4rem;
            cursor: pointer;
            padding: 5px;
            transition: var(--transition);
        }

        /* --- NAVIGATION --- */
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto 15px auto;
            padding: 15px 20px;
            position: relative;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            gap: 34px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--muted);
            font-weight: 500;
            position: relative;
            transition: var(--transition);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0%;
            height: 2px;
            background: var(--primary);
            transition: 0.3s;
            border-radius: 20px;
        }

        .nav-links a:hover { color: var(--plum); }
        .nav-links a:hover::after { width: 100%; }

        .nav-toggle-icon {
            display: none; /* Hidden on Desktop */
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--plum);
            cursor: pointer;
        }

        /* Responsive Media Layout Breakthrough Rules */
        @media (max-width: 992px) {
            .mobile-nav-controls {
                display: flex !important;
                order: -1;
                margin-right: 15px;
                z-index: 1001;
            }

            .nav-toggle-icon {
                display: block !important;
            }

            .logo {
                margin-right: auto; /* Keeps logo aligned next to hamburger button */
            }

            .nav-links {
                display: none; /* Hide default inline menu items on mobile */
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #ffffff;
                padding: 24px;
                gap: 20px;
                box-shadow: 0 15px 35px rgba(59, 22, 92, 0.12);
                border-bottom: 2px solid var(--border);
                z-index: 1000;
            }

            /* Responsive class added via JS to reveal the menu items vertically */
            .nav.responsive .nav-links {
                display: flex !important;
            }
        }

        @media (max-width: 768px) {
            .hero-cove {
                padding: 24px;
                min-height: 85vh; 
                border-radius: 28px;
                margin: 10px;
                background-position: center top;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
            }

            .hero-content {
                background: rgba(255, 255, 255, 0.88);
                padding: 24px;
                border-radius: 22px;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                box-shadow: 0 12px 35px rgba(59, 22, 92, 0.1);
            }

            .hero-content h1 { font-size: 2.1rem !important; margin-bottom: 12px; }
            .hero-content p { font-size: 1.0rem; margin-bottom: 25px; }
            .cta-btn { width: 100%; }
        }

        /* =========================
           NAV AUTH BUTTON & USER PROFILE DROPDOWN
           ========================= */
        .nav-auth {
            display: flex;
            align-items: center;
            position: relative; /* Anchor for the absolute profile dropdown */
        }

        .signin-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px;
            border: none;
            border-radius: 40px;
            background: var(--btn-gradient);
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 10px 25px rgba(125, 69, 198, 0.18);
            white-space: nowrap; /* Prevents text from breaking into two lines */
        }

        .signin-btn i {
            font-size: 0.9rem;
        }

        .signin-btn:hover {
            transform: translateY(-2px);
            background: var(--deep-plum);
            box-shadow: 0 14px 30px rgba(59, 22, 92, 0.25);
        }

        /* User Account Interface Dropdown parameters */
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 12px); /* Perfectly spaces it right below the button */
            right: 0;
            width: 240px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(59, 22, 92, 0.15);
            padding: 15px;
            display: none;
            flex-direction: column;
            gap: 8px;
            z-index: 10000;
        }

        .profile-dropdown.show {
            display: flex;
        }

        .dropdown-header {
            display: flex;
            flex-direction: column;
            font-size: 0.85rem;
            color: var(--muted);
            padding-bottom: 5px;
        }

        .dropdown-header strong {
            color: var(--deep-plum);
            font-size: 0.95rem;
            word-break: break-word; /* Prevents long names from breaking layout */
        }

        .dropdown-header span {
            word-break: break-all; /* Prevents long emails from breaking container */
        }

        .profile-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.92rem;
            font-weight: 500;
            padding: 8px 10px;
            border-radius: 10px;
            transition: var(--transition);
        }

        .profile-dropdown a:hover {
            background: #f5effd;
            color: var(--primary);
        }

        /* ==========================================================================
           LOGIN / SIGNUP / FORGOT MODAL OVERLAYS
           ========================================================================== */
        .login-overlay {
            position: fixed;
            inset: 0;
            background: rgba(28, 16, 43, 0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px); /* Safari support */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px; /* Essential safety spacing on mobile */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        .login-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .login-box {
            width: 100%;
            max-width: 430px;
            max-height: calc(100vh - 40px); /* Restricts height so it never cuts off on mobile viewports */
            overflow-y: auto; /* Adds a clean scrollable track inside if screen is small */
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(223, 209, 239, 0.5);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 34px;
            padding: 40px;
            box-shadow: 0 30px 80px rgba(59, 22, 92, 0.18);
            position: relative;
        }

        /* Custom subtle scrollbar layout metrics for small screens */
        .login-box::-webkit-scrollbar {
            width: 5px;
        }
        .login-box::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -80px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(167, 136, 220, 0.18), transparent 70%);
            pointer-events: none; /* Stops it from blocking clicks */
        }

        .close-login {
            position: absolute;
            top: 18px;
            right: 20px;
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            background: #f5effd;
            color: var(--deep-plum);
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s ease;
            z-index: 10; /* Keeps exit cross on top layer */
        }

        .close-login:hover {
            background: var(--thistle);
            color: white;
            transform: rotate(90deg);
        }

        .login-header {
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
            padding-right: 25px; /* Leaves room so text doesn't slide under close button */
        }

        .login-header h2 {
            font-size: 1.85rem;
            font-family: 'Lexend', sans-serif;
            color: var(--deep-plum);
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .login-header p {
            color: var(--muted);
            line-height: 1.5;
            font-size: 0.92rem;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--deep-plum);
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #ffffff;
            outline: none;
            font-size: 0.96rem;
            transition: 0.3s ease;
            font-family: 'Outfit', sans-serif;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: var(--thistle);
            box-shadow: 0 0 0 4px rgba(167, 136, 220, 0.15);
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 50px; /* Stops text from overlapping eye icon */
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #7D45C6;
            cursor: pointer;
            font-size: 0.95rem;
            transition: 0.3s ease;
            z-index: 5;
        }

        .password-toggle:hover {
            color: #3B165C;
        }

        .error {
            display: block;
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 4px;
            font-weight: 500;
        }

        .input-error {
            border: 1.5px solid #e74c3c !important;
        }

        .login-submit {
            margin-top: 5px;
            padding: 15px;
            border: none;
            border-radius: 18px;
            background: var(--btn-gradient);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 14px 30px rgba(125, 69, 198, 0.2);
        }

        .login-submit:hover {
            background: var(--deep-plum);
            transform: translateY(-2px);
        }

        .login-extra {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .login-extra span {
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            margin-left: 4px;
        }

        .login-extra span:hover {
            text-decoration: underline;
        }

        /* Status alerts configuration styling */
        .status-msg {
            margin-top: 15px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            line-height: 1.4;
            text-align: left;
        }

        .success-msg {
            background-color: #e6f9f3;
            color: #107c41;
            border: 1px solid #a3ebd0;
        }

        .error-msg {
            background-color: #fdebee;
            color: #a80000;
            border: 1px solid #f3b6b7;
        }

        /* ==========================================================================
           RESPONSIVE RE-ALIGNMENT MEDIA BREAKPOINT (768px and below)
           ========================================================================== */
        @media (max-width: 768px) {
            .signin-btn {
                padding: 10px 18px;
                font-size: 0.88rem;
            }

            .login-box {
                padding: 30px 24px;
                border-radius: 28px;
            }

            .login-header h2 {
                font-size: 1.6rem;
            }
            
            .profile-dropdown {
                width: 220px;
                right: -10px; /* Adjust dropdown position slightly on mobile */
            }
        }

        /* ==========================================================================
           STRUCTURAL INTERFACE SYSTEMS
           ========================================================================== */
        .app-body-container {
            display: flex;
            flex: 1;
            width: 100%;
            height: calc(100vh - 70px);
            overflow: hidden;
            position: relative;
        }

        /* SIDEBAR PANEL MATCHES FULL SCREEN HEIGHT */
        .chat-sidebar {
            width: 280px;
            background-color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(223, 209, 239, 0.5);
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-shrink: 0;
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1);
            z-index: 1000;
        }

        /* SEAMLESS SIDEBAR HEADER CONTAINING ONLY NEW CHAT BUTTON WITH NAVBAR COLOR MATCH */
        .sidebar-header {
            padding: 15px;
        }

        .sidebar-new-chat-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: transparent;
            color: var(--deep-plum);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-new-chat-btn:hover {
            background: rgba(125, 69, 198, 0.08);
            border-color: var(--primary);
            color: var(--primary);
        }

        .sidebar-history-list {
            flex: 1;
            overflow-y: auto;
            padding: 5px 10px 15px 10px;
        }

        .history-item {
            display: flex;
            align-items: center;
            padding: 11px 12px;
            margin-bottom: 6px;
            border-radius: 12px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid transparent;
            transition: var(--transition);
        }

        .history-item:hover, .history-item.active-thread {
            background: #ffffff;
            border-color: var(--border);
        }

        .history-item.active-thread {
            background: #f5effd;
            border-color: var(--primary);
        }

        .history-icon {
            font-size: 0.95rem;
            margin-right: 12px;
            color: var(--lavender-clr);
        }

        .history-details { flex: 1; min-width: 0; }

        .msg-snippet {
            font-size: 0.88rem;
            color: var(--text);
            margin: 0;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .time-stamp {
            font-size: 0.72rem;
            color: var(--muted);
            display: block;
            margin-top: 1px;
        }

        .no-history {
            font-size: 0.85rem;
            color: var(--muted);
            text-align: center;
            padding: 30px 10px;
        }

        .sidebar-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100%;
            background: rgba(42, 16, 66, 0.2);
            backdrop-filter: blur(2px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* MAIN WORKSPACE CANVAS */
        .workspace-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding: 0 24px 30px 24px;
            overflow: hidden;
            height: 100%;
            width: 100%;
        }

        /* SHIFTED UPSIDE PERFECTLY BALANCED GREETING SPLASH */
        .welcome-center-hero {
            position: absolute;
            top: 38%; 
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
            max-width: 650px;
            padding: 0 20px;
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 1;
        }

        .welcome-center-hero h1 {
            font-size: 3.2rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--deep-plum) 30%, var(--lavender-clr) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .welcome-center-hero p { font-size: 1.1rem; color: var(--muted); line-height: 1.5; }

        .chat-scroll-canvas {
            width: 100%;
            max-width: 760px;
            flex: 1;
            overflow-y: auto;
            padding: 20px 5px 120px 5px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            scroll-behavior: smooth;
        }

        .chat-scroll-canvas::-webkit-scrollbar { width: 5px; }
        .chat-scroll-canvas::-webkit-scrollbar-thumb { background: #dfd5f0; border-radius: 10px; }

        .chat-row { display: flex; width: 100%; animation: fadeIn 0.35s ease-out forwards; }
        .chat-row.user-row { justify-content: flex-end; }
        .chat-row.bot-row { justify-content: flex-start; }

        .bubble { max-width: 82%; font-size: 1.02rem; line-height: 1.55; word-wrap: break-word; }

        .user-bubble {
            background-color: #f0e6fc;
            color: var(--deep-plum);
            padding: 12px 20px;
            border-radius: 22px;
            border-bottom-right-radius: 6px;
            box-shadow: 0 4px 12px rgba(126, 66, 172, 0.02);
        }

        .bot-bubble { color: var(--text); padding: 4px 0; width: 100%; }

        .control-pill-wrapper {
            position: absolute;
            bottom: 25px;
            width: calc(100% - 48px);
            max-width: 760px;
            background: #ffffff;
            border: 1px solid rgba(204, 170, 230, 0.5);
            border-radius: 32px;
            padding: 6px 10px 6px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(59, 22, 92, 0.05);
            transition: var(--transition);
            z-index: 5;
        }

        .control-pill-wrapper:focus-within {
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(125, 69, 198, 0.08);
        }

        .pill-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1.02rem;
            font-family: 'Outfit', sans-serif;
            color: var(--text);
            background: transparent;
            padding: 10px 0;
        }

        .pill-input::placeholder { color: #a192b8; }

        .pill-send-btn {
            background: var(--btn-gradient);
            color: var(--white);
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .pill-send-btn:hover { transform: scale(1.03); }

        .typing-loader { display: none; align-self: flex-start; padding: 6px 0; }
        .loader-dot { display: inline-block; width: 6px; height: 6px; background: var(--lavender-clr); border-radius: 50%; margin-right: 4px; animation: bounceWave 1.3s linear infinite; }
        .loader-dot:nth-child(2) { animation-delay: -1.1s; }
        .loader-dot:nth-child(3) { animation-delay: -0.9s; }

        @keyframes bounceWave {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==========================================================================
           RESPONSIVE RE-ENGINEERING BREAKPOINTS
           ========================================================================== */
        @media (max-width: 992px) {
            .nav-links { gap: 20px; }
            .welcome-center-hero h1 { font-size: 2.5rem; }
        }

        @media(max-width: 768px) {
            .nav { 
                padding: 12px 15px; 
            }

            .logo img {
                width: 140px !important;
                height: auto;
            }
            
            .nav-links { 
                display: none; 
            } 

            .signin-btn {
                padding: 8px 14px;
                font-size: 0.85rem;
                gap: 6px;
            }
            
            .app-body-container {
                height: calc(100vh - 64px);
            }

            .chat-sidebar {
                position: fixed;
                top: 64px;
                left: 0;
                height: calc(100vh - 64px);
                width: 280px;
                transform: translateX(-100%); 
                box-shadow: 15px 0 30px rgba(59, 22, 92, 0.1);
                background: #ffffff;
                z-index: 2000;
            }

            .chat-sidebar.sidebar-open {
                transform: translateX(0); 
            }

            .sidebar-overlay {
                position: fixed;
                top: 64px;
                height: calc(100vh - 64px);
                z-index: 1999;
            }

            .workspace-main { padding: 0 15px 20px 15px; }
            
            .welcome-center-hero {
                padding: 0 15px;
                top: 35%;
            }
            .welcome-center-hero h1 { 
                font-size: 1.85rem; 
                line-height: 1.2;
                margin-bottom: 12px;
            }
            .welcome-center-hero p { 
                font-size: 0.9rem; 
                line-height: 1.4;
            }

            .control-pill-wrapper {
                width: calc(100% - 30px);
                bottom: 15px;
                padding: 4px 8px 4px 18px;
            }
            .pill-input { font-size: 0.95rem; }
            .bubble { max-width: 90%; font-size: 0.95rem; }
            .user-bubble { padding: 10px 16px; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav" id="myTopnav">
        <!-- Unified mobile controls frame -->
        <div class="mobile-nav-controls">
            <button class="nav-toggle-icon" onclick="toggleMenu()">
                <i class="fas fa-bars" id="hamburgerIcon"></i>
            </button>
            <button class="mobile-history-toggle" onclick="toggleMobileSidebar(event)">
                <i class="fas fa-history"></i>
            </button>
        </div>

        <div class="logo">
            <a href="home.php"><img src="../assets/images/PCOD360 (4)-Photoroom.png" alt="PCOD360 Logo" style="width: 200px; height: auto;"></a>
        </div>

        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="screening.php">Screening</a></li>
            <li><a href="track.php">Track</a></li>
            <li><a href="chatbot.php">Chatbot</a></li>
            <li><a href="about.php">About PCOD</a></li>
        </ul>

        <div class="nav-auth">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="profile-container" style="position: relative; display: inline-block;">
                    <button class="signin-btn" onclick="toggleProfileDropdown(event)">
                        <i class="fas fa-user-circle"></i> Hi, <?php echo htmlspecialchars(explode(' ', $_SESSION['user']['name'])[0]); ?>
                    </button>
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="dropdown-header">
                            <strong><?php echo htmlspecialchars($_SESSION['user']['name']); ?></strong>
                            <span><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                        </div>
                        <hr style="border: 0; border-top: 1px solid var(--border); margin: 8px 0;">
                        <a href="?logout=1" style="color: #d9534f;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <button class="signin-btn" onclick="openLogin()">
                    <i class="fas fa-user"></i> Sign In
                </button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="app-body-container">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>
        
        <!-- Sidebar -->
        <div class="chat-sidebar" id="chatSidebar">
            <div class="sidebar-header">
                <button class="sidebar-new-chat-btn" onclick="startNewChat()">
                    <i class="fas fa-plus"></i> New Chat
                </button>
            </div>
            <div class="sidebar-history-list" id="sidebarList">
                <!-- Chat history will be loaded here via JS -->
            </div>
        </div>

        <!-- Main Chat -->
        <main class="workspace-main">
            <div class="welcome-center-hero" id="welcomeHero">
                <h1>Hi, <?php echo htmlspecialchars(explode(' ', $_SESSION['user']['name'])[0]); ?></h1>
                <p>How can I assist you with your screening or tracking analytics today?</p>
            </div>

            <div class="chat-scroll-canvas" id="chatCanvas">
                <div class="typing-loader" id="typingLoader">
                    <span class="loader-dot"></span>
                    <span class="loader-dot"></span>
                    <span class="loader-dot"></span>
                </div>
            </div>

            <div class="control-pill-wrapper">
                <input type="text" id="userInput" class="pill-input" placeholder="Ask PCOD360..." autocomplete="off">
                <button onclick="sendMessage()" class="pill-send-btn" aria-label="Send Message">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleMenu() {
            var navbar = document.getElementById("myTopnav");
            var icon = document.getElementById("hamburgerIcon");
            
            // Close sidebar if menu is expanding to prevent layouts fighting
            if(chatSidebar.classList.contains("sidebar-open")) {
                closeMobileSidebar();
            }

            if (navbar.className === "nav") {
                navbar.className += " responsive";
                icon.className = "fas fa-times"; 
            } else {
                navbar.className = "nav";
                icon.className = "fas fa-bars";  
            }
        }

        const profileDropdown = document.getElementById("profileDropdown");
        const chatSidebar = document.getElementById("chatSidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        
        let currentSessionId = ""; 

        document.addEventListener("DOMContentLoaded", () => {
            loadChatHistory();
        });

        function toggleProfileDropdown(e) {
            e.stopPropagation();
            if (profileDropdown) profileDropdown.classList.toggle("show");
        }

        function toggleMobileSidebar(e) {
            e.stopPropagation();
            
            // Close nav links if they are open to prevent overlapping
            var navbar = document.getElementById("myTopnav");
            var icon = document.getElementById("hamburgerIcon");
            if (navbar.classList.contains("responsive")) {
                navbar.className = "nav";
                icon.className = "fas fa-bars";
            }

            chatSidebar.classList.toggle("sidebar-open");
            if (chatSidebar.classList.contains("sidebar-open")) {
                sidebarOverlay.style.display = "block";
                setTimeout(() => sidebarOverlay.style.opacity = "1", 10);
            } else {
                closeMobileSidebar();
            }
        }

        function closeMobileSidebar() {
            chatSidebar.classList.remove("sidebar-open");
            sidebarOverlay.style.opacity = "0";
            setTimeout(() => sidebarOverlay.style.display = "none", 300);
        }

        window.addEventListener("click", function(e) {
            if (profileDropdown && !profileDropdown.contains(e.target) && !e.target.closest('.signin-btn')) {
                profileDropdown.classList.remove("show");
            }
        });

        document.getElementById('userInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        document.addEventListener("DOMContentLoaded", () => {
    loadChatHistory();
    // Trigger initial polite welcome message on page load
    triggerWelcomeMessage();
});

function triggerWelcomeMessage() {
    if (!currentSessionId) {
        currentSessionId = "sess_" + Math.random().toString(36).substring(2, 11) + "_" + Date.now();
    }
    
    const canvas = document.getElementById('chatCanvas');
    const loader = document.getElementById('typingLoader');
    const hero = document.getElementById('welcomeHero');

    // Hide background static title text when welcoming
    if (hero) {
        hero.style.display = "none";
    }

    loader.style.display = 'block';

    const formData = new FormData();
    formData.append('message', 'INIT_CHAT');
    formData.append('session_id', currentSessionId);

    fetch('../api/chatbot_backend.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(replyText => {
        const rowBot = document.createElement('div');
        rowBot.className = 'chat-row bot-row';
        const botBubble = document.createElement('div');
        botBubble.className = 'bubble bot-bubble';
        botBubble.innerHTML = replyText;
        rowBot.appendChild(botBubble);
        canvas.insertBefore(rowBot, loader);
    })
    .catch(err => console.error("Error fetching welcome message:", err))
    .finally(() => {
        loader.style.display = 'none';
        canvas.scrollTop = canvas.scrollHeight;
    });
}

function startNewChat() {
    currentSessionId = "sess_" + Math.random().toString(36).substring(2, 11) + "_" + Date.now(); 
    const canvas = document.getElementById('chatCanvas');
    
    // Clear current canvas bubbles
    const oldRows = canvas.querySelectorAll('.chat-row');
    oldRows.forEach(row => row.remove());
    
    document.querySelectorAll('.history-item').forEach(item => item.classList.remove('active-thread'));
    document.getElementById('userInput').value = "";
    
    if (window.innerWidth <= 768) closeMobileSidebar();

    // Trigger welcoming intro options for the new chat thread
    triggerWelcomeMessage();
}

        async function loadChatSession(sessionId, element) {
            currentSessionId = sessionId;
            
            document.querySelectorAll('.history-item').forEach(item => item.classList.remove('active-thread'));
            if (element) element.classList.add('active-thread');
            
            const canvas = document.getElementById('chatCanvas');
            const loader = document.getElementById('typingLoader');
            const hero = document.getElementById('welcomeHero');
            
            const oldRows = canvas.querySelectorAll('.chat-row');
            oldRows.forEach(row => row.remove());
            
            if (hero) {
                hero.style.display = "none";
                hero.style.opacity = "0";
            }

            if (window.innerWidth <= 768) closeMobileSidebar();

            try {
                const response = await fetch(`../api/get_chat_session.php?session_id=${sessionId}`);
                if (!response.ok) throw new Error();
                const messages = await response.json();
                
                messages.forEach(msg => {
                    const row = document.createElement('div');
                    row.className = msg.sender === 'user' ? 'chat-row user-row' : 'chat-row bot-row';
                    
                    const bubble = document.createElement('div');
                    bubble.className = msg.sender === 'user' ? 'bubble user-bubble' : 'bubble bot-bubble';
                    if (msg.sender === "bot") {
    bubble.innerHTML = msg.message;
} else {
    bubble.textContent = msg.message;
}
                    
                    row.appendChild(bubble);
                    canvas.insertBefore(row, loader);
                });
                
                canvas.scrollTop = canvas.scrollHeight;
            } catch(e) {
                console.error("Critical session history rendering error:", e);
            }
        }

async function sendMessage() {
    const inputField = document.getElementById('userInput');
    const canvas = document.getElementById('chatCanvas');
    const loader = document.getElementById('typingLoader');
    const hero = document.getElementById('welcomeHero');
    
    const messageText = String(inputField.value).trim();
    if (!messageText) return;

    if (hero && hero.style.opacity !== "0") {
        hero.style.opacity = "0";
        hero.style.transform = "translate(-50%, -60%)";
        setTimeout(() => hero.style.display = "none", 500);
    }

    // 1. Render User Bubble
    const rowUser = document.createElement('div');
    rowUser.className = 'chat-row user-row';
    const userBubble = document.createElement('div');
    userBubble.className = 'bubble user-bubble';
    userBubble.innerText = messageText;
    rowUser.appendChild(userBubble);
    canvas.insertBefore(rowUser, loader);

    inputField.value = '';
    canvas.scrollTop = canvas.scrollHeight;
    
    // Show 3-dots loader animation
    loader.style.display = 'block';

    if (!currentSessionId) {
        currentSessionId = "sess_" + Math.random().toString(36).substring(2, 11) + "_" + Date.now();
    }

    try {
        const formData = new FormData();
        formData.append('message', messageText);
        formData.append('session_id', currentSessionId);

        const response = await fetch('../api/chatbot_backend.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error();
        const replyText = await response.text();

        // 2. Render Bot Bubble with .innerHTML to display UI cards immediately
        const rowBot = document.createElement('div');
        rowBot.className = 'chat-row bot-row';
        const botBubble = document.createElement('div');
        botBubble.className = 'bubble bot-bubble';
        
        //  CORRECTED LINE: Changed from .innerText to .innerHTML
        botBubble.innerHTML = replyText; 
        
        rowBot.appendChild(botBubble);
        canvas.insertBefore(rowBot, loader);

        // 3. Refresh history log files securely
        // Note: Make sure the loop inside your loadChatHistory() also uses .innerHTML!
        loadChatHistory();

    } catch (error) {
        const rowErr = document.createElement('div');
        rowErr.className = 'chat-row bot-row';
        const errorBubble = document.createElement('div');
        errorBubble.className = 'bubble bot-bubble';
        errorBubble.style.color = '#dc2626';
        errorBubble.innerText = "System was unable to complete your response pipeline.";
        rowErr.appendChild(errorBubble);
        canvas.insertBefore(rowErr, loader);
    } finally {
        // Turning off loader animation blocks cleanly
        loader.style.display = 'none';
        canvas.scrollTop = canvas.scrollHeight;
    }
}
async function loadChatHistory() {
    try {
        const response = await fetch(`../api/get_chat_session.php`);
        if (!response.ok) throw new Error("Network response was not ok");
        const sessions = await response.json();

        const sidebarList = document.getElementById("sidebarList");
        if (!sidebarList) return;
        sidebarList.innerHTML = "";

        if (!sessions || sessions.length === 0) {
            sidebarList.innerHTML = "<div class='no-history' style='padding:15px; text-align:center; color:#888;'>No past sessions found.</div>";
            return;
        }

        sessions.forEach(session => {
            const item = document.createElement("div");
            item.classList.add("history-item");
            
            // Safe fallback check: Prevents crashing if currentSessionId is undefined
            if (typeof currentSessionId !== 'undefined' && currentSessionId === session.session_id) {
                item.classList.add('active-thread');
            }

            const iconClass = session.sender === 'bot' ? 'fas fa-robot' : 'far fa-comments';
            const timeValue = session.created_at || session.timestamp || "";

            // Strip out dashboard HTML tags so the sidebar preview stays clean text
            const cleanSnippet = session.message ? String(session.message).replace(/<[^>]*>/g, '') : "";

            item.innerHTML = `
                <div class="history-icon"><i class="${iconClass}"></i></div>
                <div class="history-details">
                    <p class="msg-snippet">${cleanSnippet}</p>
                    <span class="time-stamp">${timeValue}</span>
                </div>
            `;
            
            // Bind click event handler
            item.onclick = () => loadChatSession(session.session_id, item);
            sidebarList.appendChild(item);
        });
    } catch (e) {
        console.error("Sidebar history load error:", e);
    }
}
    function escapeHtml(text) {
        if (!text) return "";
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    
</script>
</body>
</html>
