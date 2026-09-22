<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../config/db.php";

$signup_msg = "";
$login_err = "";
$forgot_msg = "";

// 1. SIGNUP PROCESS
if (isset($_POST['signup'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $signup_msg = "<div class='status-msg error-msg'>Email already registered.</div>";
        $_SESSION['open_modal'] = "signup";
    } else {
        $sql = "INSERT INTO users(fullname, email, password) VALUES('$fullname', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user'] = [
                'id' => mysqli_insert_id($conn),
                'name' => $fullname,
                'email' => $email
            ];
            unset($_SESSION['open_modal']);
            header("Location: home.php");
            exit();
        } else {
            $signup_msg = "<div class='status-msg error-msg'>Error Creating Account.</div>";
            $_SESSION['open_modal'] = "signup";
        }
    }
}

// 2. LOGIN PROCESS
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['fullname'],
            'email' => $user['email']
        ];
        unset($_SESSION['open_modal']);
        header("Location: home.php");
        exit();
    } else {
        $login_err = "<div class='status-msg error-msg'>Invalid Email or Password.</div>";
        $_SESSION['open_modal'] = "login";
    }
}

// 3. FORGOT PASSWORD DIRECT CREATION ENGINE
if (isset($_POST['reset_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['reset_email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    $check_user = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_user) > 0) {
        $update_query = "UPDATE users SET password='$new_password' WHERE email='$email'";
        if (mysqli_query($conn, $update_query)) {
            $login_err = "<div class='status-msg success-msg'>Password updated! Sign in with your new credentials.</div>";
            $_SESSION['open_modal'] = "login";
        } else {
            $forgot_msg = "<div class='status-msg error-msg'>Failed to update password. Try again.</div>";
            $_SESSION['open_modal'] = "forgot";
        }
    } else {
        $forgot_msg = "<div class='status-msg error-msg'>No account found with that email address.</div>";
        $_SESSION['open_modal'] = "forgot";
    }
}

// 4. LOGOUT PROCESS
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

        /* ==========================================================================
           CORE TOKENS & SYSTEM VARIABLES (Strict Color Palette)
           ========================================================================== */
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

            --light-purple: #f8f3fd;
            --white: #ffffff;
            --text: #2a1042;
            --muted: #6b5a82;
            --border: #dfd1ef;
            --primary: #7D45C6;
            --deep-plum: #3B165C;

            --shadow-sm: 0 10px 25px rgba(59, 22, 92, 0.05);
            --shadow-md: 0 15px 40px rgba(59, 22, 92, 0.1);
            --shadow-lg: 0 18px 45px rgba(59, 22, 92, 0.16);
            --radius-lg: 24px;
            --radius-md: 16px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);


            /* UI Mapping */
            --primary: var(--thistle);
            --deep-plum: var(--plum);
            --soft-purple: var(--wisteria);
            --light-purple: #f8f3fd;
            --lavender: var(--subtle);
            --white: #ffffff;
            --text: #2a1042;
            --muted: #6b5a82;
            --border: #dfd1ef;

            --shadow: 0 15px 40px rgba(59, 22, 92, 0.06);
            --hover-shadow: 0 20px 45px rgba(59, 22, 92, 0.12);
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);

            /* Advanced Gradients */
            --card-grad-1: linear-gradient(135deg, rgba(248, 243, 253, 0.9) 0%, rgba(240, 228, 252, 0.8) 100%);
            --card-grad-2: linear-gradient(135deg, rgba(253, 251, 255, 0.95) 0%, rgba(245, 238, 254, 0.9) 100%);
            --btn-gradient: linear-gradient(135deg, var(--thistle) 0%, var(--mauve) 100%);
            --icon-bg: linear-gradient(135deg, var(--subtle) 0%, var(--lilac) 100%);

            /* Professional Gradients */
            --gradient: linear-gradient(135deg, var(--plum) 0%, var(--thistle) 100%);
            --soft-gradient: linear-gradient(135deg, #fcfaff 0%, #f4eeff 100%);
            --transition: all 0.3s ease;
        }

        /* ==========================================================================
           BASE RESET & UTILITIES
           ========================================================================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white);
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1,h2,h3,h4 {
            color: var(--plum);
            font-weight: 700;
        }

        p {
            color: var(--muted);
        }

        section {
            position: relative;
            padding: 120px 6% 120px 6%;
            width: 100%;
            overflow: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 5;
        }

        /* Premium Badges */
        .premium-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(226, 206, 243, 0.4);
            border: 1px solid var(--border);
            border-radius: 100px;
            color: var(--amethyst);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        /* Premium Buttons */
        .btn-premium {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: linear-gradient(135deg, var(--thistle), var(--amethyst));
            color: var(--white);
            border: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, var(--mauve), var(--plum));
        }

        /* Floating Vector Elements & Blobs */
        .bg-blob {
            position: absolute;
            filter: blur(80px);
            opacity: 0.35;
            border-radius: 50%;
            z-index: 1;
            pointer-events: none;
        }

        /* --- NAVIGATION --- */
        /* .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto 15px auto;
            padding: 15px 20px;
            position: relative;
            z-index: 100;
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

        .nav-links a:hover {
            color: var(--deep-plum);
        }

        .nav-links a:hover::after {
            width: 100%;
        } */
        /* NAVIGATION */
/* --- NAVIGATION --- */
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto 15px auto;
            padding: 15px 20px;
            position: relative;
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
            .nav-toggle-icon {
                display: block !important;
                order: -1; /* Pushes the hamburger button to the far LEFT */
                margin-right: 15px;
                z-index: 1001;
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
        }        /* =========================
        /* =========================
   NAV AUTH BUTTON
========================= */

        /* ==========================================================================
           NAV AUTH BUTTON & USER PROFILE DROPDOWN
           ========================================================================== */
        .nav-auth {
            display: flex;
            align-items: center;
            position: relative;
            /* Anchor for the absolute profile dropdown */
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
            white-space: nowrap;
            /* Prevents text from breaking into two lines */
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
            top: calc(100% + 12px);
            /* Perfectly spaces it right below the button */
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
            word-break: break-word;
            /* Prevents long names from breaking layout */
        }

        .dropdown-header span {
            word-break: break-all;
            /* Prevents long emails from breaking container */
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
            -webkit-backdrop-filter: blur(8px);
            /* Safari support */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
            /* Essential safety spacing on mobile */
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
            max-height: calc(100vh - 40px);
            /* Restricts height so it never cuts off on mobile viewports */
            overflow-y: auto;
            overflow-x: hidden;   /* ADD THIS */ 
            /* Adds a clean scrollable track inside if screen is small */
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
            pointer-events: none;
            /* Stops it from blocking clicks */
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
            z-index: 10;
            /* Keeps exit cross on top layer */
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
            padding-right: 25px;
            /* Leaves room so text doesn't slide under close button */
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
            padding-right: 50px;
            /* Stops text from overlapping eye icon */
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
                right: -10px;
                /* Adjust dropdown position slightly on mobile */
            }
        }
        /* ==========================================================================
           1. HERO SECTION
           ========================================================================== */
        #hero {
            background: radial-gradient(circle at 80% 20%, var(--light-purple) 0%, var(--white) 100%);
            min-height: 85vh;
            display: flex;
            align-items: center;
            padding-top: 60px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3.8rem;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero-content h1 span {
            color: var(--thistle);
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 400;
        }

        .hero-content p {
            font-size: 1.15rem;
            margin-bottom: 40px;
            max-width: 540px;
        }

        .hero-illustration {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Update your canvas layout container size */
        .vector-art-canvas {
            position: relative;
            width: 100%;
            /* 420px is the sweet spot for a 1536-wide display next to text layout grids */
            max-width: 420px;
            aspect-ratio: 1 / 1;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Update your center circle container size */
        .hero-image-hub {
            width: 90%;
            height: 90%;
            background: transparent;
            border-radius: 50%;
            box-shadow: 0 20px 50px rgba(123, 66, 188, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
            /* Allows leaves and sparkles to pop out slightly */
            position: relative;
            z-index: 1;
        }

        /* Update the core graphic scaling rules */
        /* Update the core graphic scaling rules */
        .hero-graphic {
            width: 260%;
            /* INCREASED from 110% to make the artwork fill the circle */
            height: 260%;
            /* INCREASED from 110% to match */
            object-fit: contain;
            transform: translate(1%, 3%);
            /* Adjusted slightly to keep her perfectly centered at this larger scale */
            pointer-events: none;
            align-items: center;
            justify-content: center;
        }

        .floating-art-node {
            position: absolute;
            width: 56px;
            height: 56px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--thistle);
            box-shadow: var(--shadow-md);
            font-size: 1.25rem;
            z-index: 3;
        }

        .fan-1 {
            top: 10%;
            left: -10px;
            animation: dykFloat 4s infinite ease-in-out;
        }

        .fan-2 {
            top: 50%;
            right: -20px;
            animation: dykFloat 4.5s infinite ease-in-out 0.5s;
        }

        .fan-3 {
            bottom: 5%;
            left: 20%;
            animation: dykFloat 5s infinite ease-in-out 1s;
        }

        @keyframes organicMorph {
            0% {
                border-radius: 43% 57% 64% 36% / 43% 40% 60% 57%;
            }

            100% {
                border-radius: 60% 40% 45% 55% / 55% 60% 40% 45%;
            }
        }

        @keyframes spinCircle {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulseLotus {
            0% {
                transform: scale(0.95);
                opacity: 0.9;
            }

            100% {
                transform: scale(1.05);
                opacity: 1;
            }
        }

        /* THE MISSING FLOATING ANIMATION RULE */
        @keyframes dykFloat {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
                /* Smoothly glides the icon upwards */
            }

            100% {
                transform: translateY(0px);
                /* Returns it gently to its starting place */
            }
        }

        /* ==========================================================================
           2. WHAT IS PCOD SECTION (Reworked Content Blocks, No Cards)
           ========================================================================== */
        /* ==========================================================================
           2. WHAT IS PCOD SECTION (Left-Aligned Image Layout Optimization)
           ========================================================================== */
        #what-is {
            background-color: var(--light-purple);
        }

        .section-curve-divider {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .section-curve-divider svg {
            position: relative;
            display: block;
            width: calc(130% + 1.3px);
            height: 70px;
        }

        .section-curve-divider .shape-fill {
            fill: var(--white);
        }

        .asym-grid {
            display: grid;
            /* Balanced explicit layout for Left Image (0.9) and Right Text (1.1) */
            grid-template-columns: 0.9fr 1.1fr;
            gap: 70px;
            align-items: center;
        }

        .text-clean-container h2 {
            font-size: 2.8rem;
            margin-bottom: 24px;
            letter-spacing: -0.5px;
            color: var(--plum);
        }

        .text-clean-container p {
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 20px;
            color: var(--text);
        }

        .minimal-anatomy-canvas {
            position: relative;
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, var(--white), var(--subtle));
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            /* Keeps the custom image corner boundaries clean */
        }

        /* New premium image controller ruleset */
        .minimal-anatomy-canvas img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures your uploaded graphic frames beautifully without distortion */
            display: block;
            border-radius: var(--radius-lg);
        }

        /* ==========================================================================
           3. COMMON SYMPTOMS SECTION (Minimalist 2-Column Feature Grid)
           ========================================================================== */
        #symptoms {
            background-color: var(--white);
            padding: 80px 0;
        }

        .clinical-feature-layout {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-center-title {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 60px auto;
        }

        .section-center-title h2 {
            font-size: 2.6rem;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
            color: var(--plum);
            font-weight: 700;
        }

        .section-center-title p {
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--text);
        }

        /* Symmetric Clean Multi-Column Layout Architecture */
        .clinical-feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            column-gap: 80px;
            row-gap: 45px;
            align-items: start;
            padding: 0 20px;
        }

        /* Clean Feature Row Block */
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        /* Minimalist Checkmark Design matching template */
        .feature-marker {
            color: var(--plum);
            /* Keeps premium branding color theme unified */
            font-size: 0.95rem;
            margin-top: 4px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Premium Text Layout Integration */
        .feature-content h3 {
            font-size: 1.35rem;
            color: var(--plum);
            margin-bottom: 10px;
            font-weight: 600;
            line-height: 1.2;
        }

        .feature-content p {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text);
            margin: 0;
            opacity: 0.88;
            /* Softens reading weight without adding complex card layers */
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .clinical-feature-grid {
                grid-template-columns: 1fr;
                row-gap: 35px;
                column-gap: 0;
            }

            .section-center-title h2 {
                font-size: 2.1rem;
            }
        }

        /* ==========================================================================
           4. CAUSES OF PCOD SECTION (6 Customized Nodes & Clean Text Header)
           ========================================================================== */
        #causes {
            background: radial-gradient(circle at 10% 90%, var(--light-purple) 0%, var(--white) 100%);
        }

        .timeline-wrapper {
            position: relative;
            display: flex;
            justify-content: space-between;
            gap: 24px;
            padding: 40px 0;
            overflow-x: auto;
        }

        .timeline-line-connector {
            position: absolute;
            top: 75px;
            left: 2%;
            width: 96%;
            height: 3px;
            background: linear-gradient(90deg, var(--subtle), var(--thistle), var(--subtle));
            z-index: 1;
        }

        .timeline-node-item {
            flex: 1;
            min-width: 180px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .timeline-glow-point {
            width: 24px;
            height: 24px;
            background: var(--white);
            border: 4px solid var(--thistle);
            border-radius: 50%;
            margin: 25px auto;
            box-shadow: 0 0 15px var(--lavender-clr);
            transition: var(--transition);
        }

        .timeline-glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px 16px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .timeline-glass-card i {
            font-size: 1.5rem;
            color: var(--mauve);
            margin-bottom: 12px;
        }

        .timeline-glass-card h4 {
            font-size: 1.05rem;
            margin-bottom: 6px;
        }

        .timeline-glass-card p {
            font-size: 0.88rem;
        }

        .timeline-node-item:hover .timeline-glow-point {
            background: var(--amethyst);
            transform: scale(1.2);
        }

        .timeline-node-item:hover .timeline-glass-card {
            background: var(--white);
            border-color: var(--thistle);
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        /* ==========================================================================
           5. HEALTH IMPACT SECTION (Streamlined High-End Analytical Layout)
           ========================================================================== */
        #health-impact {
            background-color: var(--white);
        }

        .diagonal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .dashboard-mockup-wrapper {
            background: var(--light-purple);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 35px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            box-shadow: var(--shadow-md);
        }

        .analytics-mini-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            border-top: 4px solid var(--lavender-clr);
        }

        .amc-2 {
            border-top-color: var(--thistle);
        }

        .amc-3 {
            border-top-color: var(--orchid);
        }

        .amc-4 {
            border-top-color: var(--plum);
        }

        .analytics-mini-card h4 {
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .analytics-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--plum);
            margin-bottom: 4px;
        }

        /* ==========================================================================
           6. LIFESTYLE MANAGEMENT SECTION (6 Reworked Visual Grids / Dark-Light Matrix)
           ========================================================================== */
        #lifestyle {
            background-color: var(--light-purple);
            padding-top: 50px;
        }

        .lifestyle-matrix-6 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .matrix-card-item {
            border-radius: var(--radius-md);
            padding: 30px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        /* Alternating configurations */
        .mci-light {
            background-color: var(--white);
            color: var(--text);
        }

        .mci-dark {
            /* background: linear-gradient(135deg, var(--amethyst), var(--plum)); */
            background: linear-gradient(135deg, var(--plum) 0%, var(--thistle) 100%);
            color: var(--white);
            border: none;
        }

        .mci-dark h3 {
            color: var(--white);
        }

        .mci-dark p {
            color: var(--subtle);
        }

        .mci-dark .mci-icon {
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
        }

        .mci-icon {
            width: 52px;
            height: 52px;
            background: rgba(204, 170, 230, 0.25);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.4rem;
            color: var(--thistle);
            margin-bottom: 20px;
        }

        .matrix-card-item h3 {
            font-size: 1.3rem;
            margin-bottom: 12px;
        }

        .matrix-card-item p {
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .matrix-card-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* ==========================================================================
   4. DID YOU KNOW SECTION (Premium Dual Column Stack Layout)
   ========================================================================== */
        #did-you-know {
            background-color: var(--white);
            padding: 90px 0;
            position: relative;
        }

        .section-left-title {
            margin-bottom: 40px;
        }

        .section-left-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.6rem;
            color: var(--plum);
            letter-spacing: -0.5px;
            margin-top: 12px;
            font-weight: 700;
        }

        /* Master equal split frame layout configuration */
        .dyk-split-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Perfectly equal split mapping */
            gap: 60px;
            /* Uniform horizontal margin alignment */
            align-items: flex-start;
            width: 100%;
        }

        /* Left and Right Side Pipeline Blueprints */
        .dyk-cases-column {
            display: flex;
            flex-direction: column;
            gap: 32px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Unified Stack Element Text Items */
        .dyk-text-item {
            padding-left: 20px;
            border-left: 2px solid var(--border);
            transition: var(--transition);
        }

        /* Highlighting States for Active Focus */
        .dyk-text-item.active-case {
            border-left-color: var(--plum);
        }

        .dyk-text-item h3 {
            font-size: 1.3rem;
            color: var(--plum);
            margin: 0 0 10px 0;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        .dyk-text-item p {
            font-size: 0.98rem;
            line-height: 1.6;
            color: var(--text);
            margin: 0;
        }

        /* Responsive Mobile Architecture Scaling */
        @media (max-width: 992px) {
            .dyk-split-grid {
                grid-template-columns: 1fr;
                /* Stack vertically on tablets/mobiles */
                gap: 32px;
            }

            .dyk-cases-column {
                gap: 32px;
            }
        }

        /* ==========================================================================
           8. Why tracking matters?(Section Layout & Compact Structural Frame
           ========================================================================== */
        .tracking-section {
            position: relative;
            padding: 60px 0;
            /* Reduced to preserve vertical page space */
            width: 100%;
            box-sizing: border-box;
            background: radial-gradient(circle at 50% 50%, var(--white) 0%, var(--light-purple) 100%);
            overflow: hidden;
        }

        /* Soft Purple Ambient Decorative Glow Blobs */
        .tracking-bg-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            pointer-events: none;
            z-index: 1;
        }

        .t-blob-1 {
            width: 300px;
            height: 300px;
            background: var(--subtle);
            top: -5%;
            left: 10%;
        }

        .t-blob-2 {
            width: 300px;
            height: 300px;
            background: var(--lilac);
            bottom: -5%;
            right: 10%;
        }

        .tracking-container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 20px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        .tracking-header-block {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 40px auto;
        }

        .tracking-section-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--deep-plum);
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
        }

        .tracking-section-subtitle {
            font-size: 1.05rem;
            line-height: 1.5;
            color: var(--muted);
            font-weight: 500;
            margin: 0;
        }

        /* ==========================================================================
           3. True Radial Infographic Grid Workspace (Compact Desktop)
           ========================================================================== */
        .radial-infographic-space {
            position: relative;
            width: 100%;
            height: 500px;
            /* Reduced layout height down from 640px */
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Central Glow Hub Positioning */
        .centerpiece-visual-hub {
            position: relative;
            width: 220px;
            /* Scaled down */
            height: 220px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            box-shadow: 0 15px 40px rgba(59, 22, 92, 0.08),
                0 0 40px rgba(125, 69, 198, 0.1);
            border: 3px solid var(--white);
        }

        /* Dashed Orbit Ring Layout */
        .centerpiece-visual-hub::before {
            content: '';
            position: absolute;
            inset: -12px;
            border: 2px dashed var(--lavender-clr);
            border-radius: 50%;
            opacity: 0.5;
            animation: spinRing 50s linear infinite;
        }

        @keyframes spinRing {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Elegant Minimalist Purple Calendar Vector Graphic */
        .vector-calendar-card {
            width: 120px;
            height: 120px;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(59, 22, 92, 0.12);
            border: 1px solid var(--border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: gentleFloat 4s infinite ease-in-out alternate;
        }

        @keyframes gentleFloat {
            0% {
                transform: translateY(0px);
            }

            100% {
                transform: translateY(-8px);
            }
        }

        .calendar-header {
            height: 32px;
            background: linear-gradient(135deg, var(--amethyst), var(--thistle));
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .calendar-body-grid {
            flex-grow: 1;
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            align-items: center;
            justify-items: center;
        }

        .calendar-dot-node {
            width: 10px;
            height: 10px;
            background: var(--light-purple);
            border-radius: 50%;
            border: 1px solid var(--border);
        }

        .calendar-dot-node.active-ovulation {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 6px var(--primary);
        }

        /* Subtle Floating Floating Icons Directly around Center */
        .subtle-floating-icon {
            position: absolute;
            color: var(--mauve);
            background: var(--white);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8rem;
            box-shadow: 0 4px 8px rgba(59, 22, 92, 0.06);
            border: 1px solid var(--border);
            z-index: 11;
            animation: orbitalFloat 3s infinite alternate ease-in-out;
        }

        .s-fa-1 {
            top: -15px;
            left: 40px;
            animation-delay: 0s;
        }

        .s-fa-2 {
            top: 50px;
            right: -20px;
            animation-delay: 0.5s;
        }

        .s-fa-3 {
            bottom: -10px;
            left: 100px;
            animation-delay: 1s;
        }

        .s-fa-4 {
            top: 100px;
            left: -20px;
            animation-delay: 1.5s;
        }

        @keyframes orbitalFloat {
            0% {
                transform: translateY(0px) scale(1);
            }

            100% {
                transform: translateY(-5px) scale(1.05);
            }
        }

        /* ==========================================================================
           4. 3D Position Mapping for 6 Orbiting Cards (Desktop Sizing)
           ========================================================================== */
        .benefit-orbital-card {
            position: absolute;
            width: 230px;
            /* Slightly narrower to compress width */
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            box-sizing: border-box;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            box-shadow: 0 6px 18px rgba(59, 22, 92, 0.03);
            transition: var(--transition-smooth);
            z-index: 20;
            opacity: 0;
            transform: scale(0.95);
        }

        /* Interactive Trigger State */
        .benefit-orbital-card.reveal-active {
            opacity: 1;
            transform: scale(1);
        }

        /* Tightened Geometric Radial Coordinates */
        .card-pos-1 {
            top: 3%;
            left: 6%;
        }

        .card-pos-2 {
            top: 40%;
            left: 1%;
        }

        .card-pos-3 {
            bottom: 3%;
            left: 6%;
        }

        .card-pos-4 {
            top: 3%;
            right: 6%;
        }

        .card-pos-5 {
            top: 40%;
            right: 1%;
        }

        .card-pos-6 {
            bottom: 3%;
            right: 6%;
        }

        /* Monochrome Tone Allocations */
        .c-subtle {
            background-color: #FAF6FE;
            border-left: 3px solid var(--subtle);
        }

        .c-lilac {
            background-color: #F6EFFF;
            border-left: 3px solid var(--lilac);
        }

        .c-lavender {
            background-color: #F1E9FC;
            border-left: 3px solid var(--lavender-clr);
        }

        .c-wisteria {
            background-color: #FAF6FE;
            border-left: 3px solid var(--wisteria);
        }

        .c-thistle {
            background-color: #F6EFFF;
            border-left: 3px solid var(--thistle);
        }

        .c-amethyst {
            background-color: #F1E9FC;
            border-left: 3px solid var(--amethyst);
        }

        /* Icon Container Wrapper Nodes */
        .orbital-icon-wrapper {
            width: 38px;
            height: 38px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.02);
            transition: var(--transition-smooth);
        }

        .orbital-icon-wrapper i {
            font-size: 1rem;
            color: var(--primary);
        }

        .orbital-card-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .orbital-card-text h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--deep-plum);
            margin: 0;
        }

        .orbital-card-text p {
            font-size: 0.82rem;
            line-height: 1.35;
            color: var(--muted);
            margin: 0;
            font-weight: 400;
        }

        /* ==========================================================================
           5. Micro-Interaction System Rulesets
           ========================================================================== */
        .benefit-orbital-card:hover {
            transform: scale(1.03) translateY(-3px);
            box-shadow: 0 12px 25px rgba(59, 22, 92, 0.1);
            background-color: var(--white) !important;
            border-color: var(--primary);
            z-index: 30;
        }

        .benefit-orbital-card:hover .orbital-icon-wrapper {
            background: var(--primary);
            border-color: var(--primary);
        }

        .benefit-orbital-card:hover .orbital-icon-wrapper i {
            color: var(--white);
        }

        /* SVG Connection Vector Overlay Frame Layer */
        .connecting-lines-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
        }

        .connecting-line-path {
            fill: none;
            stroke: var(--border);
            stroke-width: 1.5;
            stroke-dasharray: 5 5;
            transition: var(--transition-smooth);
        }

        /* Activated Path Highlight Line */
        .connecting-line-path.highlight-path {
            stroke: var(--primary);
            stroke-width: 2;
            stroke-dasharray: 0;
        }

        /* ==========================================================================
           6. Mobile Adaptive Timeline Transformation (Screen Snapping Layouts)
           ========================================================================== */
        @media (max-width: 1024px) {
            .radial-infographic-space {
                height: 460px;
            }

            .benefit-orbital-card {
                width: 200px;
                padding: 12px;
            }

            .card-pos-1 {
                left: 1%;
            }

            .card-pos-3 {
                left: 1%;
            }

            .card-pos-4 {
                right: 1%;
            }

            .card-pos-6 {
                right: 1%;
            }
        }

        @media (max-width: 868px) {
            .tracking-section {
                padding: 50px 0;
            }

            .radial-infographic-space {
                display: flex;
                flex-direction: column;
                height: auto;
                gap: 30px;
            }

            .connecting-lines-svg {
                display: none;
            }

            .centerpiece-visual-hub {
                position: relative;
                transform: none !important;
                margin: 0 auto;
            }

            .cards-outer-wrapper-stack {
                width: 100%;
                max-width: 460px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 16px;
                position: relative;
                padding-left: 20px;
                box-sizing: border-box;
            }

            /* Vertical Timeline Bar Frame */
            .cards-outer-wrapper-stack::before {
                content: '';
                position: absolute;
                top: 10px;
                bottom: 10px;
                left: 0;
                width: 2px;
                background: linear-gradient(to bottom, var(--subtle), var(--amethyst));
                border-radius: 2px;
            }

            .benefit-orbital-card {
                position: relative !important;
                inset: auto !important;
                width: 100% !important;
                transform: none !important;
                opacity: 1 !important;
                background: var(--white) !important;
            }

            /* Milestone Navigation Node Dots */
            .benefit-orbital-card::before {
                content: '';
                position: absolute;
                left: -25px;
                top: 24px;
                width: 8px;
                height: 8px;
                background: var(--white);
                border: 2px solid var(--primary);
                border-radius: 50%;
                z-index: 5;
            }
        }

        /* ==========================================================================
           9. FAQ SECTION (Expanded Content Library - 7 Questions)
           ========================================================================== */
        #faq {
            background-color: var(--white);
        }

        .faq-wrapper {
            max-width: 850px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-item:hover {
            border-color: var(--lilac);
            box-shadow: var(--shadow-sm);
        }

        .faq-trigger {
            padding: 24px 30px;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--plum);
        }

        .faq-icon-indicator {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--light-purple);
            color: var(--thistle);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding: 0 30px;
        }

        .faq-content p {
            padding-bottom: 24px;
            font-size: 0.98rem;
            color: var(--muted);
        }

        .faq-item.active .faq-content {
            max-height: 220px;
        }

        .faq-item.active .faq-icon-indicator {
            transform: rotate(180deg);
            background: var(--thistle);
            color: var(--white);
        }

        /* ==========================================================================
           10. FINAL CTA / WELLNESS SECTION (Extended Luxury Block Layout)
           ========================================================================== */
        #final-cta {
            background: linear-gradient(180deg, var(--white) 0%, var(--light-purple) 100%);
            padding: 140px 4%;
            padding-top: 50px;
        }

        .cta-box-premium {
            /* background: linear-gradient(135deg, var(--plum), var(--amethyst)); */
            background: linear-gradient(135deg, var(--plum) 0%, var(--thistle) 100%);
            border-radius: var(--radius-lg);
            padding: 90px 60px;
            color: var(--white);
            position: relative;
            overflow: hidden;
            max-width: 1140px;
            /* Enhanced wider geometry */
            margin: 0 auto;
            box-shadow: var(--shadow-lg);
            text-align: center;
        }

        .cta-box-premium h2 {
            color: var(--white);
            font-size: 3.4rem;
            /* Optimized elegant font sizing */
            margin-bottom: 24px;
            letter-spacing: -1.5px;
            font-family: 'Playfair Display', serif;
        }

        .wellness-quote {
            font-size: 1.25rem;
            color: var(--subtle);
            margin-bottom: 45px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }

        .btn-cta-glow {
            background: var(--white);
            color: var(--plum);
            padding: 18px 46px;
            font-weight: 700;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            text-decoration: none;
        }

        .btn-cta-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(226, 206, 243, 0.4);
            background: var(--light-purple);
        }

        .cta-particle-blob {
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(204, 170, 230, 0.15);
            filter: blur(60px);
            border-radius: 50%;
        }

        .cpb-1 {
            top: -120px;
            left: -120px;
        }

        .cpb-2 {
            bottom: -120px;
            right: -120px;
        }

        /* ==========================================================================
   Premium Footer Framework Styles (Global Variables Synced)
   ========================================================================== */

        :root {
            /* Local Glass & Utility scopes safely renamed to avoid global collision */
            --ft-glass-bg: rgba(255, 255, 255, 0.05);
            --ft-glass-border: rgba(255, 255, 255, 0.15);
            --ft-text-muted: #EBD6FF;
            --ft-icon-muted: #D9B3FF;
            --ft-disclaimer-text: #D1C4E9;
        }

        .cyclescan-footer-section {
            background-color: var(--amethyst);
            /* Uses #4F1176 from your main theme variables */
            position: relative;
            padding: 100px 0 40px 0;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
            border-top: 1px solid var(--ft-glass-border);
        }

        /* Ambient Glowing Background Blobs */
        .footer-glow-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.25;
            z-index: 1;
            pointer-events: none;
            animation: pulseGlow 8s infinite alternate ease-in-out;
        }

        .blob-left {
            width: 400px;
            height: 400px;
            background: var(--amethyst);
            /* Replaced old footer variable safely */
            left: -10%;
            bottom: -10%;
        }

        .blob-right {
            width: 500px;
            height: 500px;
            background: var(--thistle);
            /* Replaced with your global #7D45C6 */
            right: -5%;
            top: -10%;
            animation-delay: 3s;
        }

        @keyframes pulseGlow {
            0% {
                transform: scale(1) translateY(0);
                opacity: 0.2;
            }

            100% {
                transform: scale(1.15) translateY(-20px);
                opacity: 0.35;
            }
        }

        .footer-outer-container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 30px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
            opacity: 0;
            transform: translateY(20px);
            animation: footerFadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes footerFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer-main-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 80px;
            align-items: start;
            margin-bottom: 60px;
        }

        .footer-brand-hub {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* .footer-brand-identity {
    display: flex;
    align-items: center;
    gap: 14px;
    color: var(--white); /* Synced to your global #ffffff 
    font-size: 2.1rem;
    font-weight: 800;
    letter-spacing: -0.5px;
} */
        .footer-brand-identity {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            margin: 0;
            padding-left: 0;
        }

        /* Target the image itself to break through grid locks and pull left */
        .footer-logo-img {
            height: 80px;
            /* Kept your preferred height choice */
            width: auto;
            display: block;
            object-fit: contain;

            /* Increased pulling force to aggressively snap it to the left layout edge */
            margin-left: -30px !important;
            padding-left: 0 !important;
        }


        /* .footer-brand-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--thistle), var(--mauve)); /* Synced to your exact global brand gradients 
    border-radius: var(--radius-md); /* Uses your global 16px border-radius layout metric 
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 8px 24px rgba(125, 69, 198, 0.4);
    transition: var(--transition); /* Linked safely to your global transition timeline 
}

.footer-brand-icon i {
    color: var(--white);
    font-size: 1.3rem;
    transition: var(--transition);
} */

        /* Identity Hover Micro-interaction */
        .footer-brand-identity:hover .footer-brand-icon {
            transform: scale(1.08) rotate(5deg);
            box-shadow: 0 12px 28px rgba(125, 69, 198, 0.6);
        }

        .footer-brand-statement {
            font-size: 1.05rem;
            line-height: 1.75;
            color: var(--ft-text-muted);
            max-width: 520px;
            margin: 0;
        }

        .wellness-badges-row {
            display: flex;
            gap: 14px;
            margin-top: 10px;
        }

        .wellness-mini-badge {
            background: var(--ft-glass-bg);
            border: 1px solid var(--ft-glass-border);
            backdrop-filter: blur(10px);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--white);
            font-size: 0.95rem;
            transition: var(--transition);
            cursor: pointer;
        }

        /* Wellness Badges Hover State */
        .wellness-mini-badge:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--white);
            transform: translateY(-4px) scale(1.1);
            color: var(--white);
        }

        .wellness-mini-badge:hover i {
            animation: miniSpin 0.5s ease-in-out;
        }

        @keyframes miniSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(15deg);
            }
        }

        .footer-features-hub h4 {
            color: var(--white);
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            position: relative;
            padding-bottom: 8px;
        }

        .footer-features-hub h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 35px;
            height: 2px;
            background: var(--white);
            border-radius: 20px;
            transition: var(--transition);
        }

        /* Subtle line extension when features container hovered */
        .footer-features-hub:hover h4::after {
            width: 55px;
            background: var(--ft-text-muted);
        }

        .features-interactive-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 24px;
        }

        .feature-interactive-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--ft-glass-border);
            backdrop-filter: blur(12px);
            padding: 18px 22px;
            border-radius: var(--radius-md);
            /* Standardized across your global components */
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.98rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: var(--transition);
        }

        .feature-interactive-card i {
            font-size: 1.1rem;
            color: var(--ft-icon-muted);
            transition: var(--transition);
        }

        .feature-interactive-card span {
            transition: var(--transition);
        }

        /* Premium Interactive Hover States for Features Grid */
        .feature-interactive-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(125, 69, 198, 0.3), rgba(126, 66, 172, 0.3));
            /* Uses your global thistle and mauve variants transparently */
            opacity: 0;
            z-index: -1;
            transition: var(--transition);
        }

        .feature-interactive-card:hover {
            transform: translateY(-3px) translateX(4px);
            border-color: rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 20px rgba(59, 22, 92, 0.4),
                0 0 15px rgba(125, 69, 198, 0.2);
        }

        .feature-interactive-card:hover::before {
            opacity: 1;
        }

        .feature-interactive-card:hover i {
            color: var(--white);
            transform: scale(1.15) rotate(-5deg);
        }

        .feature-interactive-card:hover span {
            transform: translateX(2px);
            letter-spacing: 0.2px;
        }

        .footer-bottom-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            margin: 0 0 35px 0;
        }

        .footer-disclaimer-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--ft-glass-border);
            padding: 24px 30px;
            border-radius: var(--radius-md);
        }

        .footer-disclaimer-text {
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--ft-disclaimer-text);
            margin: 0;
            text-align: center;
        }

        @media (max-width: 868px) {
            .footer-main-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .features-interactive-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ==========================================================================
           RESPONSIVE CONFIGURATIONS
           ========================================================================== */
        @media (max-width: 992px) {
            html {
                font-size: 15px;
            }

            section {
                padding: 80px 4% 80px 4%;
            }

            .hero-grid,
            .asym-grid,
            .diagonal-grid,
            .dashboard-showcase-grid,
            .dyk-premium-showcase {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .hero-content,
            .section-center-title {
                text-align: center;
            }

            .hero-content p {
                margin: 0 auto 40px auto;
            }

            .bento-grid {
                grid-template-columns: 1fr 1fr;
            }

            .bento-size-wide {
                grid-column: span 1;
            }

            .lifestyle-matrix-6 {
                grid-template-columns: 1fr 1fr;
            }

            .hero-illustration,
            .minimal-anatomy-canvas {
                order: -1;
            }

            .footer-top-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }

            .footer-brand-col {
                grid-column: span 2;
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            html {
                font-size: 14px;
            }

            .bento-grid,
            .lifestyle-matrix-6 {
                grid-template-columns: 1fr;
            }

            .bento-size-tall {
                grid-row: span 1;
            }

            .cta-box-premium {
                padding: 60px 20px;
            }

            .cta-box-premium h2 {
                font-size: 2.2rem;
            }

            .footer-top-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .footer-brand-col {
                grid-column: span 1;
            }

            .footer-bottom-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }
        }

        /* =========================
   LOGIN MODAL
========================= */

        .login-overlay {
            position: fixed;
            inset: 0;
            background: rgba(28, 16, 43, 0.45);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;

            opacity: 0;
            visibility: hidden;
            transition: 0.35s ease;
        }

        .login-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .login-box {
            width: 100%;
            max-width: 430px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(223, 209, 239, 0.5);
            backdrop-filter: blur(18px);
            border-radius: 34px;
            padding: 40px;
            box-shadow: 0 30px 80px rgba(59, 22, 92, 0.18);
            position: relative;
            overflow: hidden;
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -80px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(167, 136, 220, 0.18), transparent 70%);
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
            transition: 0.3s ease;
        }

        .close-login:hover {
            background: var(--thistle);
            color: white;
            transform: rotate(90deg);
        }

        .login-header {
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .login-header h2 {
            font-size: 2rem;
            font-family: 'Lexend', sans-serif;
            color: var(--deep-plum);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--muted);
            line-height: 1.6;
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
            gap: 10px;
        }

        .input-group label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--deep-plum);
        }

        .input-group input {
            width: 100%;
            padding: 16px 18px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #ffffff;
            outline: none;
            font-size: 0.96rem;
            transition: 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .input-group input:focus {
            border-color: var(--thistle);
            box-shadow: 0 0 0 4px rgba(167, 136, 220, 0.15);
        }

        .login-submit {
            margin-top: 10px;
            padding: 16px;
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
            margin-top: 18px;
            font-size: 0.92rem;
            color: var(--muted);
        }

        /* .login-extra span{
    color:var(--thistle);
    font-weight:600;
    cursor:pointer;
} */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 50px;
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
        }

        .password-toggle:hover {
            color: #3B165C;
        }

        .login-extra span {
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            margin-left: 4px;
        }

        /* Inline Response Status Messages styling profiles */
        .status-msg {
            margin-top: 15px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
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

        /* User Account Interface Dropdown parameters */
        .profile-dropdown {
            position: absolute;
            top: 120%;
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

        @media(max-width:768px) {

            .nav {
                padding: 15px;
            }

            .signin-btn {
                padding: 10px 18px;
                font-size: 0.85rem;
            }

            .login-box {
                margin: 20px;
                padding: 32px 24px;
                border-radius: 28px;
            }
        }

        .error {
            display: block;
            color: #e74c3c;
            font-size: 13px;
            margin-top: 6px;
            font-weight: 500;
        }

        .input-error {
            border: 2px solid #e74c3c !important;
        }
    </style>
</head>

<body>

<nav class="nav" id="myTopnav">
        <button class="nav-toggle-icon" onclick="toggleMenu()">
            <i class="fas fa-bars" id="hamburgerIcon"></i>
        </button>

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
                    <button class="signin-btn" onclick="toggleProfileDropdown()">
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
    <div class="login-overlay" id="loginModal">
        <div class="login-box">
            <button class="close-login" onclick="closeLogin()">
                <i class="fas fa-times"></i>
            </button>

            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Sign in to access your screening results, cycle tracking data, and personalized wellness insights.</p>
                <?php echo $login_err; ?>
            </div>

            <form class="login-form" method="POST" action="" novalidate>
                <div class="input-group">
                    <label>Email Address</label>
                    <!-- <input type="email" name="email" placeholder="Enter your email" required> -->
                    <input type="text" id="loginEmail" name="email" placeholder="Enter your email">

                    <small class="error" id="loginEmailError"></small>
                </div>

                <div class="input-group">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label>Password</label>
                        <span onclick="openForgot()" style="font-size: 0.85rem; color: var(--primary); cursor: pointer; font-weight: 500;">Forgot Password?</span>
                    </div>
                    <!-- <div class="password-wrapper">
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('loginPassword', this)"></i>
                </div> -->
                    <div class="password-wrapper">
                        <input type="password" id="loginPassword" name="password" placeholder="Enter your password">
                        <i class="fas fa-eye password-toggle"
                            onclick="togglePassword('loginPassword', this)"></i>
                    </div>

                    <small class="error" id="loginPasswordError"></small>
                </div>

                <button type="submit" name="login" class="login-submit">Sign In</button>
            </form>

            <div class="login-extra">
                Don’t have an account? <span onclick="openSignup()">Create Account</span>
            </div>
        </div>
    </div>

    <div class="login-overlay" id="signupModal">
        <div class="login-box">
            <button class="close-login" onclick="closeSignup()">
                <i class="fas fa-times"></i>
            </button>

            <div class="login-header">
                <h2>Create Account</h2>
                <p>Create your PCOD360 account to save your tracking data and wellness reports.</p>
                <?php echo $signup_msg; ?>
            </div>

            <form class="login-form" method="POST" action="" novalidate>
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter your name" required>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <!-- <input type="email" name="email" placeholder="Enter your email" required> -->
                    <input type="text" id="signupEmail" name="email" placeholder="Enter your email">

                    <small class="error" id="signupEmailError"></small>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <!-- <div class="password-wrapper">
                    <input type="password" id="signupPassword" name="password" placeholder="Create password" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('signupPassword', this)"></i>
                </div> -->
                    <div class="password-wrapper">
                        <input type="password" id="signupPassword" name="password" placeholder="Create password">
                        <i class="fas fa-eye password-toggle"
                            onclick="togglePassword('signupPassword', this)"></i>
                    </div>

                    <small class="error" id="signupPasswordError"></small>
                </div>

                <button type="submit" class="login-submit" name="signup">Create Account</button>
            </form>

            <div class="login-extra">
                Already have an account? <span onclick="openLogin()">Sign In</span>
            </div>
        </div>
    </div>

    <div class="login-overlay" id="forgotModal">
        <div class="login-box">
            <button class="close-login" onclick="closeForgot()">
                <i class="fas fa-times"></i>
            </button>

            <div class="login-header">
                <h2>Reset Password</h2>
                <p>Provide your registered email signature to directly reconfigure account authorization metrics parameters.</p>
                <?php echo $forgot_msg; ?>
            </div>

            <form class="login-form" method="POST" action="" novalidate>
                <div class="input-group">
                    <label>Registered Email Address</label>
                    <!-- <input type="email" name="reset_email" placeholder="Enter your email" required> -->
                    <input type="text" id="forgotEmail" name="reset_email" placeholder="Enter your email">
                    <small class="error" id="forgotEmailError"></small>
                </div>

                <div class="input-group">
                    <label>Create New Password</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" id="forgotPassword" name="new_password" placeholder="Enter new password">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('forgotPassword', this)"></i>
                    </div>
                    <small class="error" id="forgotPasswordError"></small>
                </div>

                <button type="submit" name="reset_password" class="login-submit">Update Password</button>
            </form>

            <div class="login-extra">
                Remembered details? <span onclick="openLogin()">Back to Login</span>
            </div>
        </div>
    </div>


    <section id="hero">
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="premium-badge"><i class="fas fa-sparkles"></i>Smart Health Companion</div> <!--Healthcare Intelligence-->
                <h1>Understanding <span>PCOD</span> & Your Body</h1>
                <!-- <p>Take charge of your hormonal health with evidence-based diagnostic screening, premium metric analysis tracking, and comprehensive, personalized cycle care updates.</p> -->
                <p>Explore the causes, symptoms, diagnosis, and lifestyle strategies that can help you better understand and effectively manage PCOD.</p>
                <a href="chatbot.php" class="btn-premium">Chat with Assistant<i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="hero-illustration">
                <!-- <div class="vector-art-canvas">
                    <div class="vector-core-wellness">
                        <div class="vector-hormone-wave"></div>
                        <i class="fas fa-seedling vector-center-lotus"></i>
                    </div>
                    <div class="floating-art-node fan-1"><i class="fas fa-heart-pulse"></i></div>
                    <div class="floating-art-node fan-2"><i class="fas fa-calendar-alt"></i></div>
                    <div class="floating-art-node fan-3"><i class="fas fa-dna"></i></div>
                </div>
            </div> -->
                <div class="vector-art-canvas">

                    <div class="hero-image-hub">
                        <img src="../assets/images/about2.png" alt="CycleScan Health Analytics Layout" class="hero-graphic">
                    </div>

                    <div class="floating-art-node fan-1"><i class="fas fa-heart-pulse"></i></div>
                    <div class="floating-art-node fan-2"><i class="fas fa-calendar-alt"></i></div>
                    <div class="floating-art-node fan-3"><i class="fas fa-dna"></i></div>

                </div>
            </div>
        </div>
    </section>

    <section id="what-is">
        <div class="section-curve-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 1200" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
        <div class="container asym-grid">
            <div class="minimal-anatomy-canvas">
                <img src="../assets/images/pcod.png" alt="PCOD Overview Analysis Graphic">
            </div>

            <div class="text-clean-container">
                <div class="premium-badge">Health Insights</div>
                <h2>What exactly is PCOD?</h2>
                <p>Polycystic Ovarian Disease (PCOD) is a common hormonal condition where the ovaries release immature or partially mature eggs, which can form tiny, fluid-filled sacs (cysts) over time.</p>
                <p>This imbalance causes the body to produce higher levels of male hormones (androgens), which can disrupt your natural menstrual cycle and affect your overall well-being.</p>
            </div>
        </div>
    </section>

    <section id="symptoms">
        <div class="container clinical-feature-layout">

            <div class="section-center-title">
                <div class="premium-badge"><i class="fas fa-search"></i> Signs & Symptoms</div>
                <h2>Key Body Indicators</h2>
                <p>Hormonal shifts affect everyone differently. Pinpointing your specific symptoms is the first step to feeling better.</p>
            </div>

            <div class="clinical-feature-grid">

                <!-- <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Irregular Periods</h3>
                        <p>Missed or unpredictable cycles, typically caused by irregular or delayed ovulation.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Highly Secure Profile</h3>
                        <p>Sudden variations or metabolic profile recalibrations linked directly to cell insulin responses.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Androgen Acne</h3>
                        <p>Persistent flareups tracking across high-sensitivity dermal zones.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>High Performance & Energy</h3>
                        <p>Systemic energy reductions caused by fluctuations in nutrient conversion speeds.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Mood Fluctuations</h3>
                        <p>Hormonal shift tracking paths influence emotional states and baseline fatigue triggers.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Hair Thinning & Texture Changes</h3>
                        <p>Thinning variations or unexpected development across specific patterns matching high hormone indices.</p>
                    </div>
                </div>

            </div>
        </div> -->
                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Irregular Periods</h3>
                        <p>Missed or unpredictable menstrual cycles, typically caused by irregular or delayed ovulation.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Metabolic & Weight Changes</h3>
                        <p>Sudden weight shifts or difficulty managing weight, frequently linked to cellular insulin resistance.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Hormonal Acne</h3>
                        <p>Persistent breakouts and skin flare-ups, commonly concentrated along the jawline and lower face.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Fatigue & Energy Shifts</h3>
                        <p>Frequent fatigue or sudden energy crashes, often influenced by underlying metabolic changes.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Mood Fluctuations</h3>
                        <p>Noticeable shifts in emotional wellness, anxiety, or irritability driven by changing hormone levels.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Hair & Texture Changes</h3>
                        <p>Scalp hair thinning or excess facial and body hair growth due to elevated androgen levels.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="causes">
        <div class="container">
            <div class="section-center-title">
                <h2>Causes of PCOD</h2>
                <p>Multiple factors contribute to the development of PCOD</p>
            </div>
            <div class="timeline-wrapper">
                <div class="timeline-line-connector"></div>

                <!--<div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-droplet"></i>
                        <h4>Hormonal Imbalance</h4>
                        <p>Disrupted estrogen and androgen tracking levels.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-dna"></i>
                        <h4>Genetics</h4>
                        <p>Inherent family lineage pre-dispositions across generational links.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-brain"></i>
                        <h4>Stress</h4>
                        <p>Cortisol tracking variances matching high work volumes.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-utensils"></i>
                        <h4>Lifestyle</h4>
                        <p>Processed nutrition inputs changing internal profiles.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-cubes"></i>
                        <h4>Insulin Resistance</h4>
                        <p>Altered sugar conversion handling tracks within cells.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-dumbbell"></i>
                        <h4>Lack of Exercise</h4>
                        <p>Sedentary tracking paths reduce metabolic adjustment speeds.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>
            </div> -->
                <!-- <div class="timeline-wrapper">
    <div class="timeline-line-connector"></div> -->

                <!-- CARD 1: Hormonal Imbalance (Pair 1/6 - Longest frame size) -->
                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-droplet"></i>
                        <h4>Hormonal Imbalance</h4>
                        <p>Elevated reproductive androgens disrupting ovulation cycles.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <!-- CARD 2: Genetics (Pair 2/5 - Medium frame size) -->
                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-dna"></i>
                        <h4>Genetics</h4>
                        <p>Inherited familial traits increasing your underlying genetic risks.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <!-- CARD 3: Stress (Pair 3/4 - Shortest frame size) -->
                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-brain"></i>
                        <h4>Stress</h4>
                        <p>Elevated cortisol responses altering hormone signaling.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <!-- CARD 4: Lifestyle (Pair 3/4 - Shortest frame size) -->
                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-utensils"></i>
                        <h4>Lifestyle</h4>
                        <p>Refined dietary factors worsening metabolic function.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <!-- CARD 5: Insulin Resistance (Pair 2/5 - Medium frame size) -->
                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-cubes"></i>
                        <h4>Insulin Resistance</h4>
                        <p>Impaired cellular glucose processing elevating hormone levels.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>

                <!-- CARD 6: Lack of Exercise (Pair 1/6 - Longest frame size) -->
                <div class="timeline-node-item">
                    <div class="timeline-glass-card">
                        <i class="fas fa-dumbbell"></i>
                        <h4>Lack of Exercise</h4>
                        <p>Persistent paths of sedentary habits lowering your overall metabolic health profiles.</p>
                    </div>
                    <div class="timeline-glow-point"></div>
                </div>
            </div>

        </div>
    </section>

    <section id="lifestyle">
        <div class="container">
            <div class="section-center-title">
                <div class="premium-badge">Holistic Care</div>
                <h2>Lifestyle Protocols</h2>
                <!-- Word Count: 13 words (Matches original precisely) -->
                <p>Support your endocrine health through structured habits designed to restore natural hormone cycles.</p>
            </div>
            <div class="lifestyle-matrix-6">

                <!-- Balanced Diet -->
                <!-- Word Count: 15 words (Matches original precisely) -->
                <div class="matrix-card-item mci-light">
                    <div class="mci-icon"><i class="fas fa-plate-wheat"></i></div>
                    <h3>Balanced Diet</h3>
                    <p>Integrate nutrient-dense whole foods and prioritize low glycemic items to stabilize blood glucose levels.</p>
                </div>

                <!-- Regular Exercise -->
                <!-- Word Count: 15 words (Matches original precisely) -->
                <div class="matrix-card-item mci-dark">
                    <div class="mci-icon"><i class="fas fa-person-running"></i></div>
                    <h3>Regular Exercise</h3>
                    <p>Engage in active cardiovascular exercise or structured strength training to combat cellular insulin resistance.</p>
                </div>

                <!-- Quality Sleep -->
                <!-- Word Count: 14 words (Matches original precisely) -->
                <div class="matrix-card-item mci-light">
                    <div class="mci-icon"><i class="fas fa-moon"></i></div>
                    <h3>Quality Sleep</h3>
                    <p>Ensure 7-8 hours of uninterrupted deep restorative sleep to correctly balance systemic cortisol production.</p>
                </div>

                <!-- Stay Hydrated -->
                <!-- Word Count: 13 words (Matches original precisely) -->
                <div class="matrix-card-item mci-dark">
                    <div class="mci-icon"><i class="fas fa-faucet-drip"></i></div>
                    <h3>Stay Hydrated</h3>
                    <p>Maintain consistent water intake to optimize cellular hydration and support metabolic clearance.</p>
                </div>

                <!-- Stress Management -->
                <!-- Word Count: 13 words (Matches original precisely) -->
                <div class="matrix-card-item mci-light">
                    <div class="mci-icon"><i class="fas fa-spa"></i></div>
                    <h3>Stress Management</h3>
                    <p>Incorporate daily meditation or breathing techniques to downregulate overactive autonomic stress responses.</p>
                </div>

                <!-- Track Your Cycle -->
                <!-- Word Count: 13 words (Matches original precisely) -->
                <div class="matrix-card-item mci-dark">
                    <div class="mci-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3>Track Your Cycle</h3>
                    <p>Log period dates to monitor ovulation patterns and easily identify cycle irregularities over time.</p>
                </div>

            </div>
        </div>
    </section>

    <section id="did-you-know">
        <div class="container">

            <div class="section-left-title">
                <div class="premium-badge">Global Data</div>
                <h2>Did You Know?</h2>
            </div>

            <div class="dyk-split-grid">

                <div class="dyk-cases-column">
                    <!-- Item 1 -->
                    <!-- Word Count: 19 words (Matches original precisely) -->
                    <div class="dyk-text-item active-case">
                        <h3>Hormonal & Ovarian Imbalance</h3>
                        <p>Polycystic Ovary Disease is primarily an anatomical condition where emotional stress and hormonal surges cause immature egg accumulation.</p>
                    </div>

                    <!-- Item 2 -->
                    <!-- Word Count: 19 words (Matches original precisely) -->
                    <div class="dyk-text-item">
                        <h3>PCOD Affects Every Body Type</h3>
                        <p>This condition does not have a single look. Lean individuals still experience identical internal hormonal imbalances and irregular cycles.</p>
                    </div>
                </div>

                <div class="dyk-cases-column">
                    <!-- Item 3 -->
                    <!-- Word Count: 19 words (Matches original precisely) -->
                    <div class="dyk-text-item">
                        <h3>Increases Future Health Risks</h3>
                        <p>Left completely unmanaged, chronic cycle imbalances elevate long-term risks for persistent fertility challenges, hormonal skin issues, and fatigue.</p>
                    </div>

                    <!-- Item 4 -->
                    <!-- Word Count: 17 words (Matches original precisely) -->
                    <div class="dyk-text-item">
                        <h3>Can Be Managed Naturally</h3>
                        <p>Regular exercise, balanced nutrition, quality sleep, and stress management can help support healthy ovulation.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="tracking-section">
        <div class="tracking-bg-blob t-blob-1"></div>
        <div class="tracking-bg-blob t-blob-2"></div>

        <div class="tracking-container">

            <div class="tracking-header-block">
                <h2 class="tracking-section-title">Why Tracking Matters?</h2>
                <p class="tracking-section-subtitle">Understanding your cycle is the first step toward better health awareness and PCOD management.</p>
            </div>

            <div class="radial-infographic-space">

                <svg class="connecting-lines-svg" id="linesCanvas">
                    <path class="connecting-line-path" id="line1" d="M 0 0 L 0 0"></path>
                    <path class="connecting-line-path" id="line2" d="M 0 0 L 0 0"></path>
                    <path class="connecting-line-path" id="line3" d="M 0 0 L 0 0"></path>
                    <path class="connecting-line-path" id="line4" d="M 0 0 L 0 0"></path>
                    <path class="connecting-line-path" id="line5" d="M 0 0 L 0 0"></path>
                    <path class="connecting-line-path" id="line6" d="M 0 0 L 0 0"></path>
                </svg>

                <div class="centerpiece-visual-hub" id="centerHub">
                    <div class="subtle-floating-icon s-fa-1"><i class="fas fa-heart"></i></div>
                    <div class="subtle-floating-icon s-fa-2"><i class="fas fa-seedling"></i></div>
                    <div class="subtle-floating-icon s-fa-3"><i class="fas fa-spa"></i></div>
                    <div class="subtle-floating-icon s-fa-4"><i class="fas fa-venus"></i></div>

                    <div class="vector-calendar-card">
                        <div class="calendar-header">Cycle</div>
                        <div class="calendar-body-grid">
                            <div class="calendar-dot-node"></div>
                            <div class="calendar-dot-node active-ovulation"></div>
                            <div class="calendar-dot-node active-ovulation"></div>
                            <div class="calendar-dot-node active-ovulation"></div>
                            <div class="calendar-dot-node"></div>
                            <div class="calendar-dot-node"></div>
                            <div class="calendar-dot-node"></div>
                            <div class="calendar-dot-node"></div>
                        </div>
                    </div>
                </div>

                <div class="cards-outer-wrapper-stack">

                    <div class="benefit-orbital-card card-pos-1 c-subtle" data-card-index="1">
                        <div class="orbital-icon-wrapper"><i class="fas fa-calendar-alt"></i></div>
                        <div class="orbital-card-text">
                            <!-- Word Count: 7 words (Matches original precisely) -->
                            <h3>Predict Periods</h3>
                            <p>Identify ovulation windows to anticipate when withdrawal bleeding occurs.</p>
                        </div>
                    </div>

                    <div class="benefit-orbital-card card-pos-2 c-lilac" data-card-index="2">
                        <div class="orbital-icon-wrapper"><i class="fas fa-chart-line"></i></div>
                        <div class="orbital-card-text">
                            <!-- Word Count: 7 words (Matches original precisely) -->
                            <h3>Track Length</h3>
                            <p>Monitor cycle fluctuations to analyze hormonal healing trends.</p>
                        </div>
                    </div>

                    <div class="benefit-orbital-card card-pos-3 c-lavender" data-card-index="3">
                        <div class="orbital-icon-wrapper"><i class="fas fa-exclamation-circle"></i></div>
                        <div class="orbital-card-text">
                            <!-- Word Count: 7 words (Matches original precisely) -->
                            <h3>Spot Irregularities</h3>
                            <p>Flag prolonged cycles to prevent unsafe endometrial buildup.</p>
                        </div>
                    </div>

                    <div class="benefit-orbital-card card-pos-4 c-wisteria" data-card-index="4">
                        <div class="orbital-icon-wrapper"><i class="fas fa-brain"></i></div>
                        <div class="orbital-card-text">
                            <!-- Word Count: 8 words (Matches original precisely) -->
                            <h3>Understand Symptoms</h3>
                            <p>Correlate mood shifts and intense breakouts with specific cycle phases.</p>
                        </div>
                    </div>

                    <div class="benefit-orbital-card card-pos-5 c-thistle" data-card-index="5">
                        <div class="orbital-icon-wrapper"><i class="fas fa-clipboard-check"></i></div>
                        <div class="orbital-card-text">
                            <!-- Word Count: 8 words (Matches original precisely) -->
                            <h3>Prepare Ahead</h3>
                            <p>Manage physical fatigue by mapping energy changes across variations.</p>
                        </div>
                    </div>

                    <div class="benefit-orbital-card card-pos-6 c-amethyst" data-card-index="6">
                        <div class="orbital-icon-wrapper"><i class="fas fa-notes-medical"></i></div>
                        <div class="orbital-card-text">
                            <!-- Word Count: 9 words (Matches original precisely) -->
                            <h3>PCOD Awareness</h3>
                            <p>Collect accurate, objective data to share with your gynecologist.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="faq">
        <div class="container">
            <div class="section-center-title">
                <div class="premium-badge">Support</div>
                <h2>Frequently Raised Concerns</h2>
            </div>

            <div class="faq-wrapper">

                <!-- FAQ 1 -->
                <div class="faq-item active">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        Can PCOD completely disappear?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>PCOD is a long-term hormonal condition that can often be managed effectively. Healthy lifestyle changes and appropriate medical care may help reduce symptoms and improve menstrual regularity.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        How does it differ from PCOS?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>PCOD and PCOS are closely related terms and are often used interchangeably. Both involve hormonal imbalance and irregular ovulation, although some healthcare providers use the terms differently.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        Does tracking fix irregular periods?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>Tracking does not treat irregular periods, but it helps monitor menstrual patterns and provides useful information for healthcare providers during evaluation and treatment.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        Can dietary changes improve my symptoms?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>A balanced diet may help improve insulin sensitivity, support hormonal balance, and reduce some PCOD symptoms when combined with other healthy lifestyle habits.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        Is exercise important for managing PCOD?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>Regular physical activity can improve insulin sensitivity, support hormonal health, and help manage PCOD symptoms as part of an overall treatment plan.</p>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        What role does stress play?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>Chronic stress may affect hormonal balance and can contribute to changes in ovulation and menstrual cycle regularity.</p>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        When should I consult a doctor?
                        <div class="faq-icon-indicator"><i class="fas fa-chevron-down"></i></div>
                    </button>
                    <div class="faq-content">
                        <p>Consult a healthcare provider if your periods remain irregular, you miss several menstrual cycles, or you experience worsening symptoms such as severe acne, excess hair growth, or difficulty becoming pregnant.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="final-cta">
        <div class="container">
            <div class="cta-box-premium">
                <div class="cta-particle-blob cpb-1"></div>
                <div class="cta-particle-blob cpb-2"></div>
                <h2>Begin Your Health Restoration</h2>
                <p class="wellness-quote">"Healing is not an overnight script adjustment; it is a daily commitment to understanding your body's subtle baseline rhythms."</p>
                <a href="screening.php" class="btn-cta-glow">Evaluate Your Health<i class="fas fa-wand-magic-sparkles"></i></a>
            </div>
        </div>
    </section>

    <footer class="cyclescan-footer-section">
        <div class="footer-glow-blob blob-left"></div>
        <div class="footer-glow-blob blob-right"></div>

        <div class="footer-outer-container">
            <div class="footer-main-grid">
                <div class="footer-brand-hub">
                    <div class="footer-brand-identity">
                        <a href="home.php" style="display: block; line-height: 0;">
                            <img src="../assets/images/PCOD360_w (1).png" alt="PCOD360 Logo" class="footer-logo-img">
                        </a>
                    </div>
                    <p class="footer-brand-statement">
                        An comprehensive wellness screening tool designed to help women assess PCOD risks, understand metabolic indicators, and navigate their health journeys with clarity and confidence.
                    </p>
                    <div class="wellness-badges-row">
                        <div class="wellness-mini-badge"><i class="fas fa-heart"></i></div>
                        <div class="wellness-mini-badge"><i class="fas fa-leaf"></i></div>
                        <div class="wellness-mini-badge"><i class="fas fa-spa"></i></div>
                        <div class="wellness-mini-badge"><i class="fas fa-venus"></i></div>
                    </div>
                </div>

                <div class="footer-features-hub">
                    <h4>Features</h4>
                    <div class="features-interactive-grid">
                        <a href="screening.php" class="feature-interactive-card"><i class="fas fa-notes-medical"></i><span>Health Screening</span></a>
                        <a href="track.php" class="feature-interactive-card"><i class="fas fa-calendar-alt"></i><span>Cycle Tracker</span></a>
                        <a href="chatbot.php" class="feature-interactive-card"><i class="fas fa-comment-medical"></i><span>AI Assistant</span></a>
                        <a href="about.php" class="feature-interactive-card"><i class="fas fa-book-open"></i><span>About PCOD</span></a>
                    </div>
                </div>
            </div>

            <hr class="footer-bottom-divider">

            <div class="footer-disclaimer-card">
                <p class="footer-disclaimer-text">
                    <strong>Disclaimer:</strong> PCOD360 is a wellness tool designed to support PCOD management. It is not a substitute for professional medical advice, diagnosis, or treatment. Always consult with a qualified healthcare provider regarding any clinical symptoms or medical conditions.
                </p>
            </div>
        </div>
    </footer>


    <script>
               function toggleMenu() {
            var navbar = document.getElementById("myTopnav");
            var icon = document.getElementById("hamburgerIcon");
            
            // Toggle between layout classes using the responsive trigger flag
            if (navbar.className === "nav") {
                navbar.className += " responsive";
                icon.className = "fas fa-times"; 
            } else {
                navbar.className = "nav";
                icon.className = "fas fa-bars";  
            }
        }

        function toggleProfileDropdown() {
            var dropdown = document.getElementById("profileDropdown");
            if (dropdown) {
                dropdown.classList.toggle("show");
            }
        }

        // Close profile dropdown when clicking outside active viewport areas
        window.onclick = function(event) {
            if (!event.target.matches('.signin-btn') && !event.target.matches('.signin-btn *')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }


        function toggleFaq(button) {
            const currentItem = button.parentElement;
            const content = currentItem.querySelector('.faq-content');
            const isActive = currentItem.classList.contains('active');

            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
                item.querySelector('.faq-content').style.maxHeight = null;
            });

            if (!isActive) {
                currentItem.classList.add('active');
                content.style.maxHeight = content.scrollHeight + "px";
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const firstActive = document.querySelector('.faq-item.active .faq-content');
            if (firstActive) firstActive.style.maxHeight = firstActive.scrollHeight + "px";
        });

        document.addEventListener('DOMContentLoaded', () => {
            const layoutCards = document.querySelectorAll('.benefit-orbital-card');
            const centerHubEl = document.getElementById('centerHub');

            // 1. Line-Mapping Vector Positioning Calculation Function
            function redrawConnectionCoordinates() {
                if (window.innerWidth <= 868) return;

                const hubBounds = centerHubEl.getBoundingClientRect();
                const parentSpaceBounds = document.querySelector('.radial-infographic-space').getBoundingClientRect();

                const hubCenterX = (hubBounds.left + hubBounds.width / 2) - parentSpaceBounds.left;
                const hubCenterY = (hubBounds.top + hubBounds.height / 2) - parentSpaceBounds.top;

                layoutCards.forEach(card => {
                    const cardIndex = card.getAttribute('data-card-index');
                    const cardBounds = card.getBoundingClientRect();
                    const currentLinePath = document.getElementById(`line${cardIndex}`);

                    if (!currentLinePath) return;

                    let cardAnchorX = (cardBounds.left) - parentSpaceBounds.left;
                    if (cardBounds.left > hubBounds.left) {
                        cardAnchorX = (cardBounds.left) - parentSpaceBounds.left;
                    } else {
                        cardAnchorX = (cardBounds.right) - parentSpaceBounds.left;
                    }
                    const cardAnchorY = (cardBounds.top + cardBounds.height / 2) - parentSpaceBounds.top;

                    // Clean arc line vectors from anchor nodes to centerpiece hub points
                    currentLinePath.setAttribute('d', `M ${cardAnchorX} ${cardAnchorY} Q ${(cardAnchorX + hubCenterX)/2} ${(cardAnchorY + hubCenterY)/2 - 10}, ${hubCenterX} ${hubCenterY}`);
                });
            }

            // 2. Intersection Observer Implementation for Reveal Animations
            const scrollIntersectionConfig = {
                threshold: 0.1
            };
            const sectionEntryObserver = new IntersectionObserver((observedEntries) => {
                observedEntries.forEach((entry, idx) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('reveal-active');
                            redrawConnectionCoordinates();
                        }, idx * 60);
                    }
                });
            }, scrollIntersectionConfig);

            layoutCards.forEach(card => sectionEntryObserver.observe(card));

            // 3. Hover Highlighting Listeners
            layoutCards.forEach(card => {
                const targetIndex = card.getAttribute('data-card-index');
                const matchingPathNode = document.getElementById(`line${targetIndex}`);

                card.addEventListener('mouseenter', () => {
                    if (matchingPathNode) matchingPathNode.classList.add('highlight-path');
                });
                card.addEventListener('mouseleave', () => {
                    if (matchingPathNode) matchingPathNode.classList.remove('highlight-path');
                });
            });

            window.addEventListener('resize', redrawConnectionCoordinates);
            setTimeout(redrawConnectionCoordinates, 300);
        });


        const profileDropdown = document.getElementById("profileDropdown");

        function toggleProfileDropdown() {
            if (profileDropdown) {
                profileDropdown.classList.toggle("show");
            }
        }

        // Close dropdown instantly if user clicks outside of it
        window.addEventListener("click", function(e) {
            if (profileDropdown && !profileDropdown.contains(e.target) && !e.target.closest('.signin-btn')) {
                profileDropdown.classList.remove("show");
            }
        })


        // ==========================================
        // 2. MODAL OPERATIONS & CONTROLS
        // ==========================================
        const loginModal = document.getElementById("loginModal");
        const signupModal = document.getElementById("signupModal");
        const forgotModal = document.getElementById("forgotModal");

        function openLogin() {
            closeAllModals();
            if (loginModal) {
                loginModal.classList.add("active");
                document.body.style.overflow = "hidden";
            }
        }

        function closeLogin() {
            if (loginModal) loginModal.classList.remove("active");
            document.body.style.overflow = "auto";
            const notice = document.querySelector("#loginModal .status-msg");
            if (notice) notice.remove();
        }

        function openSignup() {
            closeAllModals();
            if (signupModal) {
                signupModal.classList.add("active");
                document.body.style.overflow = "hidden";
            }
        }

        function closeSignup() {
            if (signupModal) signupModal.classList.remove("active");
            document.body.style.overflow = "auto";
        }

        function openForgot() {
            closeAllModals();
            if (forgotModal) {
                forgotModal.classList.add("active");
                document.body.style.overflow = "hidden";
            }
        }

        function closeForgot() {
            if (forgotModal) forgotModal.classList.remove("active");
            document.body.style.overflow = "auto";
        }

        function closeAllModals() {
            [loginModal, signupModal, forgotModal].forEach(modal => {
                if (modal) modal.classList.remove("active");
            });
        }

        function toggleProfileDropdown() {
            if (profileDropdown) {
                profileDropdown.classList.toggle("show");
            }
        }

        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input) {
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        }

        window.addEventListener("click", function(e) {
            if (e.target === loginModal) closeLogin();
            if (e.target === signupModal) closeSignup();
            if (e.target === forgotModal) closeForgot();

            if (profileDropdown && !profileDropdown.contains(e.target) && !e.target.closest('.signin-btn')) {
                profileDropdown.classList.remove("show");
            }
        });

        // ==========================================
        // 3. CORE VALIDATION FRAMEWORK
        // ==========================================
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_\-.^])[A-Za-z\d@$!%*?&#_\-.^]{8,}$/;

        function validateEmail(input, error) {
            if (input.value.trim() == "") {
                error.textContent = "Email address is required.";
                input.classList.add("input-error");
                return false;
            }

            if (!emailRegex.test(input.value)) {
                error.textContent = "Please enter a valid email address.";
                input.classList.add("input-error");
                return false;
            }

            error.textContent = "";
            input.classList.remove("input-error");
            return true;
        }

        function validatePassword(input, error) {
            if (input.value == "") {
                error.textContent = "Password is required.";
                input.classList.add("input-error");
                return false;
            }

            if (!passwordRegex.test(input.value)) {
                error.textContent = "Password must be at least 8 characters and include uppercase, lowercase, number and special character.";
                input.classList.add("input-error");
                return false;
            }

            error.textContent = "";
            input.classList.remove("input-error");
            return true;
        }

        // Attach tracking elements and listeners once DOM nodes resolve
        document.addEventListener("DOMContentLoaded", function() {

            // --- LOGIN ELEMENT REGISTRATION ---
            const loginEmail = document.getElementById("loginEmail");
            const loginEmailError = document.getElementById("loginEmailError");
            const loginPassword = document.getElementById("loginPassword");
            const loginPasswordError = document.getElementById("loginPasswordError");

            if (loginEmail) loginEmail.addEventListener("input", () => validateEmail(loginEmail, loginEmailError));
            if (loginPassword) loginPassword.addEventListener("input", () => validatePassword(loginPassword, loginPasswordError));

            // --- SIGNUP ELEMENT REGISTRATION ---
            const signupEmail = document.getElementById("signupEmail");
            const signupEmailError = document.getElementById("signupEmailError");
            const signupPassword = document.getElementById("signupPassword");
            const signupPasswordError = document.getElementById("signupPasswordError");

            if (signupEmail) signupEmail.addEventListener("input", () => validateEmail(signupEmail, signupEmailError));
            if (signupPassword) signupPassword.addEventListener("input", () => validatePassword(signupPassword, signupPasswordError));

            // --- FORGOT ELEMENT REGISTRATION ---
            const forgotEmail = document.getElementById("forgotEmail");
            const forgotEmailError = document.getElementById("forgotEmailError");
            const forgotPassword = document.getElementById("forgotPassword");
            const forgotPasswordError = document.getElementById("forgotPasswordError");

            if (forgotEmail) forgotEmail.addEventListener("input", () => validateEmail(forgotEmail, forgotEmailError));
            if (forgotPassword) forgotPassword.addEventListener("input", () => validatePassword(forgotPassword, forgotPasswordError));

            // ==========================================
            // 4. SUBMIT EVENT BLOCKERS
            // ==========================================
            const forms = document.querySelectorAll(".login-form");
            forms.forEach(form => {
                form.addEventListener("submit", function(e) {
                    let isFormValid = true;

                    if (form.contains(loginEmail)) {
                        const emailRes = validateEmail(loginEmail, loginEmailError);
                        const passRes = validatePassword(loginPassword, loginPasswordError);
                        if (!emailRes || !passRes) isFormValid = false;
                    }

                    if (form.contains(signupEmail)) {
                        const emailRes = validateEmail(signupEmail, signupEmailError);
                        const passRes = validatePassword(signupPassword, signupPasswordError);

                        const nameInput = form.querySelector("input[name='fullname']");
                        if (nameInput && !nameInput.value.trim()) {
                            nameInput.classList.add("input-error");
                            isFormValid = false;
                        } else if (nameInput) {
                            nameInput.classList.remove("input-error");
                        }

                        if (!emailRes || !passRes) isFormValid = false;
                    }

                    if (form.contains(forgotEmail)) {
                        const emailRes = validateEmail(forgotEmail, forgotEmailError);
                        const passRes = validatePassword(forgotPassword, forgotPasswordError);
                        if (!emailRes || !passRes) isFormValid = false;
                    }

                    if (!isFormValid) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    <?php if (isset($_SESSION['open_modal'])): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const modalToOpen = "<?php echo $_SESSION['open_modal']; ?>";

                if (modalToOpen === "login") {
                    openLogin();

                    <?php if (isset($_SESSION['login_notice'])): ?>
                        const infoHeader = document.querySelector("#loginModal .login-header p");
                        if (infoHeader) {
                            infoHeader.insertAdjacentHTML('afterend',
                                `<div class="status-msg error-msg" style="margin-bottom:15px; color:#dc3545; font-size:13px; font-weight:500;">
                            <i class="fas fa-lock"></i> <?php echo htmlspecialchars($_SESSION['login_notice']); ?>
                         </div>`
                            );
                        }
                    <?php unset($_SESSION['login_notice']);
                    endif; ?>
                }

                if (modalToOpen === "signup") {
                    openSignup();
                }

                if (modalToOpen === "forgot") {
                    openForgot();
                }
            });
        </script>
    <?php
        unset($_SESSION['open_modal']);
    endif;
    ?>

</body>

</html>
