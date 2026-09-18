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
    <!-- <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;500;600;700;800&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;500;600;700;800&display=swap" rel="stylesheet">



    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

        :root {
            /* Theme Color Palette */
            --subtle: #E2CEF3;
            --lilac: #CCAAE6;
            --lavender-clr: #A788DC;
            --wisteria: #9673D2;
            --thistle: #7D45C6;
            --mauve: #7E42AC;
            --orchid: #6B297C;
            --amethyst: #4F1176;
            --plum: #3B165C;

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

            --shadow-sm: 0 10px 25px rgba(59, 22, 92, 0.05);
            --shadow-md: 0 15px 40px rgba(59, 22, 92, 0.1);
            --shadow-lg: 0 18px 45px rgba(59, 22, 92, 0.16);
            --radius-lg: 24px;
            --radius-md: 16px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);


        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        /* body {
            font-family: 'Outfit', sans-serif;
            background:
                radial-gradient(circle at top left, var(--subtle) 0%, transparent 40%),
                radial-gradient(circle at bottom right, #f2eaff 0%, transparent 35%),
                #fbfaff;
            color: var(--text);
            padding: 10px 0;
            overflow-x: hidden;
        } */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: radial-gradient(circle at top left, var(--subtle) 0%, transparent 40%),
                radial-gradient(circle at bottom right, #f2eaff 0%, transparent 35%),
                #fbfaff;
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* --- NAVIGATION --- */
        /* .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto 15px auto;
            padding: 15px 20px;
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

        .nav-links a:hover {
            color: var(--plum);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-toggle-icon {
            display: none;
            /* Hidden on Desktop */
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
                order: -1;
                /* Pushes the hamburger button to the far LEFT */
                margin-right: 15px;
                z-index: 1001;
            }

            .logo {
                margin-right: auto;
                /* Keeps logo aligned next to hamburger button */
            }

            .nav-links {
                display: none;
                /* Hide default inline menu items on mobile */
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

            .hero-content h1 {
                font-size: 2.1rem !important;
                margin-bottom: 12px;
            }

            .hero-content p {
                font-size: 1.0rem;
                margin-bottom: 25px;
            }

            .cta-btn {
                width: 100%;
            }
        }

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

        /* Hamburger icon — hidden on desktop */

        /* Mobile nav styles */

        .hero-cove,
        .hero-content,
        .cta-btn {
            box-sizing: border-box;
        }

        .hero-cove {
            max-width: 1400px;
            margin: 0 auto 50px auto;
            position: relative;
            min-height: 75vh;
            border-radius: 40px;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 80px;
            background: linear-gradient(rgba(226, 206, 243, 0.2), rgba(248, 243, 253, 0.15));
        }

        /* Forces the real HTML image to act exactly as a full-size cover background */
        .hero-cover-bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Keeps image sharp and fully stretched across the whole container */
            object-position: center;
            z-index: 1;
            /* Sets image safely behind your text content */
            pointer-events: none;
            /* Prevents users from accidentally grabbing/dragging the asset */
        }

        /* Darker mobile gradient overlay shield to ensure text remains easily readable */
        .hero-cove::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.4) 30%, rgba(255, 255, 255, 0) 100%);
            z-index: 2;
            pointer-events: none;
        }

        .hero-content {
            max-width: 750px;
            z-index: 3;
            /* Elevated layer index directly on top of everything */
            position: relative;
            width: 100%;
        }

        .hero-content h1 {
            font-size: clamp(2.5rem, 5.5vw, 4.4rem);
            font-family: "Lexend", sans-serif;
            line-height: 1.15;
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: 700;
            color: #000000;
            letter-spacing: -1px;
        }

        .hero-content p {
            font-size: 1.25rem;
            font-family: "Lexend", sans-serif;
            font-weight: 300;
            margin-bottom: 45px;
            max-width: 550px;
            color: #504c57;
            line-height: 1.6;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 18px 45px;
            border-radius: 50px;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            background: #000000;
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(59, 22, 92, 0.15);
            white-space: nowrap;
            transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease, background 0.3s ease, color 0.3s ease;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 22, 92, 0.25);
            background: #fdfbff;
            color: #000000;
        }

        .cta-btn:hover .btn-icon {
            filter: none;
            transform: translate(3px, -3px);
        }

        .btn-icon {
            width: 18px;
            height: 18px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            transition: transform 0.3s ease;
        }

        /* ==========================================================================
   MOBILE DESIGN (TEXT VISUALLY OVERLAYING THE LOWER HALF OF THE IMAGE)
   ========================================================================== */
        @media (max-width: 768px) {
            .hero-cove {
                min-height: 80vh;
                padding: 40px 24px;
                border-radius: 30px;
                margin: 0 12px 40px 12px;
                display: flex;
                align-items: flex-end;
                /* Shifts elements down beautifully toward the layout base */
            }

            .hero-cover-bg-img {
                object-position: 65% center;
                /* Adjusts framing focus on mobile viewports */
            }

            .hero-cove::after {
                background: linear-gradient(to top, rgba(255, 255, 255, 0.9) 40%, rgba(255, 255, 255, 0.1) 100%);
            }

            .hero-content {
                text-align: center;
                margin-top: auto;
                padding-bottom: 10px;
            }

            .hero-content h1 {
                font-size: clamp(1.9rem, 7vw, 2.5rem);
                margin-bottom: 16px;
            }

            .hero-content h1 br {
                display: none;
            }

            .hero-content p {
                font-size: 1.05rem;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 35px;
                max-width: 100%;
            }

            .cta-btn {
                width: 100%;
                max-width: 320px;
                padding: 16px 32px;
                font-size: 1.05rem;
            }
        }

        /* --- ASYMMETRIC WELLNESS FEATURES GRAPHIC LAYOUT --- */
        .options-section {
            max-width: 1400px;
            margin: 0 auto 100px auto;
            padding: 0 20px;
        }

        .section-header-centered {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header-centered h2 {
            font-family: 'Lexend', sans-serif;
            font-size: 2.8rem;
            color: var(--mauve);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .section-header-centered p {
            font-size: 1.1rem;
            color: var(--muted);
            font-weight: 400;
        }

        /* Complex Grid Structural Core */
        .asymmetric-layout {
            display: grid;
            grid-template-columns: 1.1fr 1.9fr;
            gap: 30px;
            align-items: stretch;
        }

        /* Column Component Configuration */
        .layout-col-left {
            display: flex;
        }

        .layout-col-right {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .sub-grid-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* Base Unified Premium Card Architecture */
        .premium-card {
            background: var(--card-grad-2);
            border-radius: 36px;
            border: 1px solid rgba(223, 209, 239, 0.45);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }

        .premium-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
            border-color: var(--lilac);
            background: var(--white);
        }

        /* Component Details Styling */
        .card-icon-badge {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            background: var(--icon-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(150, 115, 210, 0.15);
        }

        .card-icon-badge img {
            width: 26px;
            height: 26px;
            object-fit: contain;
        }

        .premium-card h3 {
            font-family: 'Lexend', sans-serif;
            font-size: 1.6rem;
            color: var(--deep-plum);
            margin-bottom: 15px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .premium-card p {
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 30px;
            font-weight: 400;
        }

        /* 1. Large Vertical Diagnostic Card Style 
        .vertical-hero-card {
            background: var(--card-grad-1);
            border-color: rgba(167, 136, 220, 0.3);
        }

        .vertical-hero-card p {
            margin-bottom: auto;
            /* Pushes content down ensuring CTA anchors tightly 
        }

        .card-illustration-box {
            width: 100%;
            height: 220px;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.6);
            box-shadow: inset 0 0 20px rgba(59, 22, 92, 0.02);
        }

        .card-illustration-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .premium-card:hover .card-illustration-box img {
            transform: scale(1.04);
        }*/

        /* .card-action-block-btn {
            width: 100%;
            padding: 18px;
            border-radius: 20px;
            background: var(--btn-gradient);
            color: var(--white);
            text-align: center;
            font-weight: 600;
            font-size: 1.05rem;
            box-shadow: 0 10px 25px rgba(125, 69, 198, 0.2);
            transition: var(--transition);
            margin-top: 20px;
        } */
        .card-action-block-btn {
            width: 100%;
            padding: 18px;
            border-radius: 20px;
            text-align: center;
            font-weight: 700;
            font-size: 1.05rem;
            transition: var(--transition);
            margin-top: 20px;
            display: block;
            text-decoration: none;

            /* THE CHANGED PROPERTIES */
            background: rgba(255, 255, 255, 0.45);
            /* Soft semi-transparent backdrop */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 2px solid var(--thistle);
            /* Elegant #7D45C6 brand border outline */
            color: var(--text);
            /* Safe, readable dark purple text color (#2a1042) */
            box-shadow: 0 8px 25px rgba(42, 16, 66, 0.05);
        }

        /* Hover effect to make it interactive and playful */
        .card-action-block-btn:hover {
            background: var(--btn-gradient);
            /* Flips cleanly to your purple brand gradient on hover */
            color: var(--white);
            /* Text turns white on interactive hover */
            box-shadow: 0 12px 30px rgba(125, 69, 198, 0.25);
            transform: translateY(-2px);
        }

        .vertical-hero-card {
            position: relative;
            overflow: hidden;
            min-height: 720px;
            display: flex;
            justify-content: space-between;
            border-color: rgba(167, 136, 220, 0.3);
        }

        /* Full Background Image */
        .card-bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
            z-index: 1;
            transition: 0.5s ease;
        }

        /* Soft overlay for readability */
        /* .card-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to bottom,
                    rgba(248, 243, 253, 0.95) 0%,
                    rgba(248, 243, 253, 0.75) 30%,
                    rgba(248, 243, 253, 0.35) 60%,
                    rgba(248, 243, 253, 0.15) 100%);
            z-index: 2;
        } */

        /* Content stays above image */
        .card-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Hover zoom */
        /* .vertical-hero-card:hover .card-bg-image {
            transform: scale(1.04);
        } */

        /* .premium-card:hover .card-action-block-btn {
            background: var(--deep-plum);
            box-shadow: 0 12px 28px rgba(59, 22, 92, 0.25);
        } */

        /* 2. Wide Landscape Block Card Configuration */
        /* .landscape-block-card {
            flex-direction: row;
            align-items: center;
            gap: 40px;
        }

        .landscape-content {
            flex: 1.2;
        }

        .landscape-media {
            flex: 0.8;
            height: 180px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(59, 22, 92, 0.05);
        }

        .landscape-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        } */
        .landscape-block-card {
            position: relative;
            overflow: hidden;
            min-height: 320px;
            padding: 40px;
            display: flex;
            align-items: flex-start;
        }

        /* Full background image */
        .landscape-media {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border-radius: 32px;
            overflow: hidden;
            z-index: 1;
        }

        /* Image full cover */
        .landscape-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dark overlay for readability */
        .landscape-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    rgba(255, 255, 255, 0.92) 20%,
                    rgba(255, 255, 255, 0.55) 55%,
                    rgba(255, 255, 255, 0.15) 100%);
        }

        /* Content above image */
        .landscape-content {
            position: relative;
            z-index: 2;
            max-width: 55%;
        }

        .card-inline-action-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 30px;
            border: 1px solid var(--border);
            background: var(--white);
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--thistle);
            transition: var(--transition);
        }

        .premium-card:hover .card-inline-action-link {
            background: var(--thistle);
            color: var(--white);
            border-color: var(--thistle);
        }

        /* Floating Absolute UI Component Details */
        /* .floating-action-arrow {
            position: absolute;
            bottom: 35px;
            right: 35px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--light-purple);
            color: var(--deep-plum);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            transition: var(--transition);
            border: 1px solid rgba(223, 209, 239, 0.3);
        }

        .premium-card:hover .floating-action-arrow {
            background: var(--deep-plum);
            color: var(--white);
            transform: rotate(45deg);
        } */
        /* Ensure the outer anchor blocks establish a concrete absolute positioning root */
        /* STEP 1: Turn ALL cards into relative anchor parents so absolute positioning works perfectly */
        .premium-card,
        .landscape-block-card,
        .vertical-hero-card,
        #test-card,
        #chat-card {
            position: relative !important;
        }

        /* STEP 2: The Core Absolute Floating Arrow Configuration */
        .floating-action-arrow {
            position: absolute !important;
            bottom: 35px !important;
            right: 35px !important;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--light-purple);
            color: var(--deep-plum);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            transition: var(--transition);
            border: 1px solid rgba(223, 209, 239, 0.3);
            z-index: 20 !important;
            /* Forces the arrow above background images and card overlays */
        }

        /* STEP 3: Special layout adjustments for the wide chatbot card */
        #chat-card .landscape-content {
            position: static !important;
            /* Allows the arrow inside to read the main card dimensions instead of the 55% text box */
        }

        /* STEP 4: Global hover rotation animation for all card types */
        .premium-card:hover .floating-action-arrow,
        #test-card:hover .floating-action-arrow,
        #chat-card:hover .floating-action-arrow {
            background: var(--deep-plum);
            color: var(--white);
            transform: rotate(45deg);
        }

        #chat-card .floating-action-arrow {
            right: auto !important;
            /* Clear the previous right setting */
            left: 35px !important;
            /* Snap it precisely to the bottom-left corner */
            bottom: 25px !important;
            /* Keep it perfectly aligned with the lower grid lines */
        }

        /* Target only the arrow inside the screening card */
        #test-card .floating-action-arrow {
            bottom: 5px !important;
            /* Decreased from 35px to pull it lower */
            right: 15px !important;
            /* Decreased from 35px to push it further right */
        }

        /* Target the arrow background ONLY inside the Cycle Tracker and About PCOD cards */
        .layout-col-right .premium-card:not(#chat-card) .floating-action-arrow {
            background: var(--light-purple) !important;
            /* Changes arrow circle background */
            color: var(--deep-plum) !important;
            /* Changes arrow icon color inside */
            border: 1px solid rgba(125, 69, 198, 0.2);
            /* Softens the border line */
        }

        /* Ensure the hover state still looks great and animates cleanly */
        .layout-col-right .premium-card:not(#chat-card):hover .floating-action-arrow {
            background: var(--deep-plum) !important;
            color: var(--white) !important;
        }
        

        /* ==========================================================================
       How It Works Section Styles (Card-Free Open Architecture with SVG Zig-Zag)
       ========================================================================== */
        /* @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'); */

        .how-it-works-section {
            padding: 100px 4% 120px 4%;
            background: radial-gradient(circle at 10% 20%, rgba(243, 231, 255, 0.4) 0%, rgba(255, 255, 255, 0) 80%);
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: #3b165c;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .header-line {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #7d45c6, #a788dc);
            margin: 0 auto;
            border-radius: 2px;
        }

        /* Pipeline Flow Layout Container */
        .pipeline-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* SVG Container for the custom fluid minimal zig-zag connection path */
        .pipeline-svg-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 220px;
            z-index: 1;
            pointer-events: none;
        }

        .zigzag-line {
            stroke: url(#pipeline-gradient);
            stroke-width: 4;
            stroke-dasharray: 4 1;
            /* Creates a clean textured link line */
            fill: none;
            filter: drop-shadow(0px 0px 8px rgba(167, 136, 220, 0.4));
        }

        /* Individual Flow Nodes */
        .pipeline-node {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
            padding: 0 20px;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .pipeline-node:hover {
            transform: translateY(-6px);
        }

        /* Floating Step Indicators */
        .node-number {
            position: absolute;
            top: -25px;
            font-size: 4.5rem;
            font-weight: 900;
            color: rgba(125, 69, 198, 0.07);
            line-height: 1;
            z-index: -1;
            user-select: none;
            transition: color 0.3s ease;
        }

        .pipeline-node:hover .node-number {
            color: rgba(125, 69, 198, 0.13);
        }

        /* Graphical Core Area Wrapper */
        .node-visual {
            width: 220px;
            height: 220px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 25px;
        }

        /* ==========================================================================
       Pure CSS Claymorphic UI Mock Devices & Elements
       ========================================================================== */

        /* Universal Device Properties */
        .mock-device {
            background: #ffffff;
            border: 5px solid #3b165c;
            box-shadow: 0 20px 40px rgba(59, 22, 92, 0.08),
                inset 0 0 15px rgba(125, 69, 198, 0.03);
            position: relative;
            transition: all 0.4s ease;
            box-sizing: border-box;
        }

        /* Step 1: Tablet Asset (Start) */
        .mock-tablet {
            width: 115px;
            height: 155px;
            border-radius: 14px;
            padding: 8px;
            transform: rotate(-6deg);
        }

        .tablet-header {
            width: 4px;
            height: 4px;
            background: #3b165c;
            border-radius: 50%;
            margin: 0 auto 6px auto;
        }

        .quiz-ui .avatar-circle {
            width: 32px;
            height: 32px;
            background: #e5daf5;
            border: 2px solid #a788dc;
            border-radius: 50%;
            margin: 0 auto 6px auto;
        }

        .quiz-ui .ui-bar {
            width: 100%;
            height: 5px;
            background: #f1ebf9;
            border-radius: 3px;
            margin-bottom: 5px;
        }

        .quiz-ui .ui-device-text {
            font-size: 9px;
            font-weight: 700;
            color: #7d45c6;
            text-align: center;
            margin: 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .quiz-ui .ui-progress-pill {
            width: 85%;
            height: 7px;
            background: linear-gradient(90deg, #7d45c6, #a788dc);
            border-radius: 4px;
            margin: 8px auto 8px auto;
        }

        .quiz-ui .ui-btn-pill {
            width: 50px;
            height: 14px;
            background: #3b165c;
            border-radius: 6px;
            margin: 0 auto;
        }

        /* Step 2: Biological Cellular Analysis Cluster Core (Process) */
        .bio-analysis-cluster {
            position: relative;
            width: 110px;
            height: 110px;
        }

        .bio-hub {
            width: 74px;
            height: 74px;
            background: linear-gradient(135deg, #a788dc 0%, #7d45c6 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            font-size: 1.6rem;
            box-shadow: 0 10px 25px rgba(125, 69, 198, 0.35);
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 3;
        }

        .pulse-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 2px solid #a788dc;
            border-radius: 50%;
            animation: hubPulse 2.5s infinite ease-out;
            top: 0;
            left: 0;
            z-index: -1;
        }

        /* Swirling Micro Hormone/DNA Cells */
        .cell-dot {
            position: absolute;
            width: 32px;
            height: 32px;
            background: #ffffff;
            border: 2.5px solid #7d45c6;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8rem;
            color: #3b165c;
            box-shadow: 0 4px 12px rgba(59, 22, 92, 0.1);
        }

        .cd-1 {
            top: -18px;
            left: 39px;
            animation: floatAnim 3.2s infinite ease-in-out;
        }

        .cd-2 {
            bottom: -12px;
            left: -8px;
            animation: floatAnim 3.2s infinite ease-in-out 0.8s;
        }

        .cd-3 {
            bottom: -12px;
            right: -8px;
            animation: floatAnim 3.2s infinite ease-in-out 1.6s;
        }


        /* Step 3: Desktop Analytics Metric Dashboard UI Mock (Analyze) */
        .mock-dashboard {
            width: 160px;
            height: 110px;
            border-radius: 10px;
            display: flex;
            overflow: hidden;
            padding: 0;
        }

        .dash-sidebar {
            width: 25px;
            background: #f1ebf9;
            border-right: 2px solid #3b165c;
            height: 100%;
        }

        .dash-body {
            flex: 1;
            padding: 6px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dash-row {
            display: flex;
            gap: 4px;
        }

        .dash-stat {
            flex: 1;
            height: 24px;
            background: #fdfaff;
            border: 1.5px solid #a788dc;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dash-risk-banner {
            width: 100%;
            background: #fff0f0;
            border: 1px solid #ffbaba;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            color: #d93838;
            text-align: center;
            padding: 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.1px;
        }

        .dash-chart {
            height: 42px;
            background: #f1ebf9;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }

        .chart-line-wave {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60%;
            background: linear-gradient(180deg, rgba(167, 136, 220, 0.4) 0%, rgba(167, 136, 220, 0) 100%);
            border-top: 2px solid #7d45c6;
            border-radius: 8px 8px 0 0;
        }


        /* Step 4: Mobile Smartphone Device UI Component (Track) */
        .mock-mobile {
            width: 85px;
            height: 155px;
            border-radius: 16px;
            padding: 10px 6px;
            transform: rotate(4deg);
        }

        .phone-speaker {
            width: 20px;
            height: 3px;
            background: #3b165c;
            border-radius: 2px;
            margin: 0 auto 8px auto;
        }

        .calendar-ui .cal-header {
            width: 100%;
            height: 12px;
            background: #e5daf5;
            border-radius: 3px;
            margin-bottom: 6px;
        }

        .calendar-ui .cal-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3px;
            justify-content: center;
            margin-bottom: 6px;
        }

        .calendar-ui .cal-grid span {
            width: 11px;
            height: 11px;
            background: #f7f3fd;
            border-radius: 2px;
            display: inline-block;
        }

        .calendar-ui .cal-grid span.active-day {
            background: #7d45c6;
            box-shadow: 0 0 6px #7d45c6;
        }

        .calendar-ui .cal-text-data {
            font-size: 8px;
            font-weight: 600;
            color: #3b165c;
            text-align: center;
            background: #f1ebf9;
            padding: 3px 0;
            border-radius: 4px;
            margin-top: 5px;
        }

        .calendar-ui .toggle-row {
            width: 100%;
            height: 8px;
            background: #fdfaff;
            border: 1px solid #e5daf5;
            border-radius: 4px;
            margin-top: 5px;
        }

        /* Floating Accessory Micro Badges Interaction Overrides */
        .floating-badge {
            position: absolute;
            width: 38px;
            height: 38px;
            background: #ffffff;
            border-radius: 50%;
            box-shadow: 0 6px 15px rgba(59, 22, 92, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #7d45c6;
            font-size: 0.9rem;
            z-index: 4;
        }

        .fb-1 {
            top: 15px;
            right: 25px;
            animation: floatAnim 4s infinite ease-in-out;
        }

        .fb-2 {
            top: 20px;
            left: 10px;
            animation: floatAnim 4s infinite ease-in-out 0.5s;
        }

        .fb-3 {
            bottom: 30px;
            left: 15px;
            animation: floatAnim 4s infinite ease-in-out 1s;
        }

        /* Node Typography Content Descriptions */
        .node-content h3 {
            font-size: 1.35rem;
            color: #3b165c;
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .node-content strong {
            display: block;
            font-size: 0.85rem;
            color: #7d45c6;
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .node-content p {
            font-size: 0.92rem;
            color: #6b587e;
            line-height: 1.5;
            margin: 0;
        }

        /* Hover Zoom Actions For Devices */
        .pipeline-node:hover .mock-device {
            transform: scale(1.06) rotate(0deg);
            border-color: #7d45c6;
            box-shadow: 0 25px 50px rgba(125, 69, 198, 0.14);
        }

        .pipeline-node:hover .bio-hub {
            transform: scale(1.06);
            box-shadow: 0 14px 30px rgba(125, 69, 198, 0.5);
        }

        /* ==========================================================================
       Animation Timelines
       ========================================================================== */
        @keyframes floatAnim {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes hubPulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.35);
                opacity: 0;
            }
        }

        /* Responsive Structural Breakdown Controls */
        @media (max-width: 992px) {
            .pipeline-container {
                flex-direction: column;
                align-items: center;
                gap: 60px;
            }

            .pipeline-svg-container {
                display: none;
                /* Hide horizontal zig zag svg layout line on responsive stack */
            }

            .pipeline-node {
                width: 100%;
                max-width: 450px;
                flex-direction: row;
                text-align: left;
                align-items: center;
            }

            .node-visual {
                margin-bottom: 0;
                margin-right: 25px;
                width: 160px;
                height: 160px;
                flex-shrink: 0;
            }

            .node-number {
                left: -10px;
                top: 40px;
            }

            .fb-1,
            .fb-2,
            .fb-3 {
                top: auto;
                bottom: 10px;
                right: 10px;
                left: auto;
            }
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
            color: var(--white);
            /* Synced to your global #ffffff 
            font-size: 2.1rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        } */
        /* Container fix to ensure clean vertical center alignment */
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
            background: linear-gradient(135deg, var(--thistle), var(--mauve));
            /* Synced to your exact global brand gradients 
            border-radius: var(--radius-md);
            /* Uses your global 16px border-radius layout metric
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 24px rgba(125, 69, 198, 0.4);
            transition: var(--transition);
            /* Linked safely to your global transition timeline 
        } */

        .footer-brand-icon i {
            color: var(--white);
            font-size: 1.3rem;
            transition: var(--transition);
        }

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


        /* --- RESPONSIVE LAYOUT BREAKPOINTS --- */
        @media (max-width: 1100px) {
            .asymmetric-layout {
                grid-template-columns: 1fr;
            }

            .vertical-hero-card {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .hero-cove {
                padding: 40px 25px;
                border-radius: 30px;
                min-height: 60vh;
            }

            .nav-links {
                display: none;
            }

            .landscape-block-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .landscape-media {
                width: 100%;
                height: 180px;
            }

            .sub-grid-row {
                grid-template-columns: 1fr;
            }

            .section-header-centered h2 {
                font-size: 2.2rem;
            }
        }
        @media (max-width: 768px) {
            /* .asymmetric-layout,
            .layout-col-right,
            .sub-grid-row {
                display: flex !important;
                flex-direction: column !important;
                gap: 24px !important;
            }

            .layout-col-left {
                display: block !important;
            }

            .premium-card {
                padding: 30px 24px 80px 24px !important;
                min-height: auto !important;
                height: auto !important;
            }

            .vertical-hero-card {
                min-height: 480px !important;
            }
 */
            .landscape-content {
                max-width: 100% !important;
            }

            .floating-action-arrow {
                right: 24px !important;
                bottom: 24px !important;
            }

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
            }

            .hero-cove {
                min-height: 80vh;
                padding: 40px 24px;
                border-radius: 30px;
                margin: 0 12px 40px 12px;
                display: flex;
                align-items: flex-end;
            }

            .hero-cover-bg-img {
                object-position: 75% center;
            }

            .hero-cove::after {
                background: linear-gradient(to top, rgba(255, 255, 255, 0.9) 40%, rgba(255, 255, 255, 0.1) 100%);
            }

            .hero-content {
                text-align: center;
                margin-top: auto;
                padding-bottom: 10px;
            }

            .hero-content h1 {
                font-size: clamp(1.9rem, 7vw, 2.5rem);
                margin-bottom: 16px;
            }

            .hero-content h1 br {
                display: none;
            }

            .hero-content p {
                font-size: 1.05rem;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 35px;
                max-width: 100%;
            }

            .cta-btn {
                width: 100%;
                max-width: 320px;
                padding: 16px 32px;
                font-size: 1.05rem;
            }
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

    <section class="hero-cove">
        <img src="../assets/images/Home2.png" alt="PCOD Management Cover" class="hero-cover-bg-img">

        <div class="hero-content">
            <h1>The Beautiful Way<br>To Manage PCOD</h1>
            <p>Understand your body, track symptoms, and find balance with our all-in-one health companion.</p>
            <button class="cta-btn" onclick="goToTest()">
                Take the test
                <img src="../assets/images/right-up.png" alt="arrow" class="btn-icon">
            </button>
        </div>
    </section>

    <section class="how-it-works-section">
        <div class="section-header">
            <h2>How PCOD360 Works</h2>
            <div class="header-line"></div>
        </div>

        <div class="pipeline-container">
            <svg class="pipeline-svg-container" preserveAspectRatio="none" viewBox="0 0 1200 220" version="1.1"
                xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="pipeline-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="rgba(125, 69, 198, 0.15)" />
                        <stop offset="30%" stop-color="rgba(167, 136, 220, 0.85)" />
                        <stop offset="70%" stop-color="rgba(125, 69, 198, 0.85)" />
                        <stop offset="100%" stop-color="rgba(167, 136, 220, 0.15)" />
                    </linearGradient>
                </defs>
                <path d="M 150,110 Q 300,40 450,110 T 750,110 T 1050,110" class="zigzag-line" />
            </svg>

            <div class="pipeline-node">
                <div class="node-number">1</div>
                <div class="node-visual">
                    <div class="mock-device mock-tablet">
                        <div class="tablet-header"></div>
                        <div class="quiz-ui">
                            <div class="avatar-circle"></div>
                            <div class="ui-device-text">Quiz Profile</div>
                            <div class="ui-bar short"></div>
                            <div class="ui-progress-pill"></div>
                            <div class="ui-btn-pill"></div>
                        </div>
                    </div>
                    <div class="floating-badge fb-1"><i class="fas fa-file-alt"></i></div>
                </div>
                <div class="node-content">
                    <h3>Start</h3>
                    <strong>Personalized Health Screening</strong>
                    <p>Begin by answering a series of wellness questions about your lifestyle, symptoms, and menstrual history.</p>
                </div>
            </div>

            <div class="pipeline-node">
                <div class="node-number">2</div>
                <div class="node-visual">
                    <div class="bio-analysis-cluster">
                        <div class="bio-hub">
                            <div class="pulse-ring"></div>
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="cell-dot cd-1"><i class="fas fa-dna"></i></div>
                        <div class="cell-dot cd-2"><i class="fas fa-seedling"></i></div>
                        <div class="cell-dot cd-3"><i class="fas fa-shield-virus"></i></div>
                    </div>
                    <div class="floating-badge fb-2" style="top:20px; left:10px;"><i class="fas fa-cog fa-spin"
                            style="font-size:0.85rem; color:#7d45c6;"></i></div>
                </div>
                <div class="node-content">
                    <h3>Process</h3>
                    <strong>Analyzing Responses</strong>
                    <p>The engine immediately goes to work, processing your inputs to calculate underlying hormonal risk indicators.</p>
                </div>
            </div>

            <div class="pipeline-node">
                <div class="node-number">3</div>
                <div class="node-visual">
                    <div class="mock-device mock-dashboard">
                        <div class="dash-sidebar"></div>
                        <div class="dash-body">
                            <div class="dash-row">
                                <div class="dash-risk-banner">Risk</div>
                            </div>
                            <div class="dash-chart">
                                <div class="chart-line-wave"></div>
                            </div>
                        </div>
                    </div>
                    <div class="floating-badge fb-2"><i class="fas fa-chart-line"></i></div>
                </div>
                <div class="node-content">
                    <h3>Result</h3> <!-- Fixed sequence naming collision -->
                    <strong>Risk Level Assessment</strong>
                    <!-- Word Count: 17 words (Matches original precisely) -->
                    <p>View your instant results page to find your estimated risk status, clearly categorized from low to high.</p>
                </div>
            </div>

            <div class="pipeline-node">
                <div class="node-number">4</div>
                <div class="node-visual">
                    <div class="mock-device mock-mobile">
                        <div class="phone-speaker"></div>
                        <div class="calendar-ui">
                            <div class="cal-header"></div>
                            <div class="cal-grid">
                                <span></span><span></span><span></span><span></span>
                                <span></span><span class="active-day"></span><span></span><span></span>
                                <span></span><span></span><span></span><span></span>
                            </div>
                            <div class="cal-text-data">Cycle: 28 Days</div>
                            <div class="toggle-row"></div>
                        </div>
                    </div>
                    <div class="floating-badge fb-3"><i class="fas fa-calendar-check"></i></div>
                </div>
                <div class="node-content">
                    <!-- <h3>Track</h3>
                    <strong>Cycle Length & Forecasting</strong>
                    <p>Log your past period dates to automatically calculate your cycle length and instantly predict
                        your next period date.</p> -->
                    <h3>Track</h3>
                    <strong>Cycle Length & Forecasting</strong>
                    <p>Log your past period dates to automatically calculate your cycle length and instantly forecast future trends.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="options-section" id="wellness-tools">
        <div class="section-header-centered">
            <h2>Wellness Features</h2>
            <p>Comprehensive tools designed to support your unique health journey</p>
        </div>

        <div class="asymmetric-layout">

            <!-- <div class="layout-col-left">
                <a href="final_test.html" class="premium-card vertical-hero-card" id="test-card">
                    <div>
                        <div class="card-icon-badge">
                            <img src="https://cdn-icons-png.flaticon.com/512/3022/3022215.png" alt="Pulse icon">
                        </div>
                        <h3>Screening & Testing</h3>
                        <p>Comprehensive health assessments designed specifically for PCOD management. Get personalized
                            insights and track your progress metrics safely over time.</p>
                    </div>
                    <div>
                        <div class="card-illustration-box">
                            <img src="assets/images/test4.png" alt="Medical professional explaining data metrics" id="testimage">
                        </div>
                        <div class="card-action-block-btn">Start Assessment</div>
                    </div>
                </a>
            </div> -->
            <div class="layout-col-left">
                <a href="screening.php" class="premium-card vertical-hero-card" id="test-card">

                    <img src="../assets/images/test11.png" class="card-bg-image">

                    <div class="card-overlay"></div>

                    <div class="card-content">

                        <div>
                            <div class="card-icon-badge">
                                <img src="https://cdn-icons-png.flaticon.com/512/3022/3022215.png" alt="Pulse icon">
                            </div>

                            <h3>Screening & Testing</h3>
                            <p>
                                An online wellness questionnaire designed to help assess your risk indicators.
                                Complete the evaluation to get immediate, instant risk status results.
                            </p>
                        </div>

                        <div>
                            <!-- <div class="card-action-block-btn">
                                Start Assessment
                            </div> -->
                            <!-- <div class="card-action-block-btn">Start Assessment</div> -->
                            <div class="floating-action-arrow">↗</div>
                        </div>

                    </div>

                </a>
            </div>


            <div class="layout-col-right">

                <a href="chatbot.php" class="premium-card landscape-block-card" id="chat-card">
                    <div class="landscape-content">
                        <div class="card-icon-badge">
                            <img src="https://cdn-icons-png.flaticon.com/512/2040/2040946.png" alt="Chat icon">
                        </div>
                        <h3>AI Health Assistant</h3>
                        <p>
                            24/7 interactive chat support to address your general health questions.
                            Receive reliable wellness information and compassionate guidance instantly.
                        </p>
                        <!-- <div class="card-inline-action-link">Chat Now</div> -->
                        <div class="floating-action-arrow">↗</div>
                    </div>
                    <div class="landscape-media">
                        <img src="../assets/images/chatbot.png" alt="Serene tracking lifestyle illustration">
                    </div>
                </a>

                <div class="sub-grid-row">

                    <a href="track.php" class="premium-card" id="tracker-card">
                        <div>
                            <div class="card-icon-badge">
                                <img src="https://cdn-icons-png.flaticon.com/512/3652/3652191.png" alt="Calendar icon">
                            </div>
                            <h3>Cycle Tracker</h3>
                            <p>
                                Easily record your cycle history to determine your average length and get clear, automatic predictions for your upcoming period date.</p>
                        </div>
                        <div class="floating-action-arrow">↗</div>
                    </a>

                    <a href="about.php" class="premium-card" id="about-card">
                        <div>
                            <div class="card-icon-badge">
                                <img src="https://cdn-icons-png.flaticon.com/512/3588/3588613.png"
                                    alt="Information icon">
                            </div>
                            <h3>About PCOD</h3>
                            <p>
                                Educational resources and curated health insights to empower your
                                overall lifestyle awareness, personal wellness journey, and daily choices.
                            </p>
                        </div>
                        <div class="floating-action-arrow">↗</div>
                    </a>

                </div>

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
                    <!-- Updated with safe, non-liability wellness wording for PCOD metrics -->
                    <p class="footer-brand-statement">
                        An informative wellness screening tool designed to help women assess PCOD risks, understand hormonal variations, and navigate their health journeys with clarity and confidence.
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
        // ==========================================
        // 1. UTILITIES & HERO ANIMATION
        // ==========================================
        function goToTest() {
            window.location.href = "screening.php";
        }

        window.addEventListener('DOMContentLoaded', () => {
            const hero = document.querySelector('.hero-cove');
            if (hero) {
                hero.style.opacity = '0';
                hero.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    hero.style.transition = 'all 1.2s cubic-bezier(0.25, 1, 0.5, 1)';
                    hero.style.opacity = '1';
                    hero.style.transform = 'translateY(0)';
                }, 150);
            }
        });

        function toggleMenu() {
            var navbar = document.getElementById("myTopnav");
            var icon = document.getElementById("hamburgerIcon");

            // Toggle between layout classes using the responsive trigger flag
            if (navbar.className === "nav") {
                navbar.className += " responsive";
                icon.className = "fas fa-times"; // Changes icon to an 'X' close button
            } else {
                navbar.className = "nav";
                icon.className = "fas fa-bars"; // Resets icon back to standard bars
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

        // ==========================================
        // 2. MODAL OPERATIONS & CONTROLS
        // ==========================================
        const loginModal = document.getElementById("loginModal");
        const signupModal = document.getElementById("signupModal");
        const forgotModal = document.getElementById("forgotModal");
        const profileDropdown = document.getElementById("profileDropdown");

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
