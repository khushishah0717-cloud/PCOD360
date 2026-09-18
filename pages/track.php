<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../config/db.php";

// If not logged in, bounce them back to home with an alert flag
if (!isset($_SESSION['user'])) {
    $_SESSION['open_modal'] = "login"; 
    $_SESSION['login_notice'] = "Please sign in to access this feature."; 
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$status_action_msg = "";
$status_action_type = "error"; 

// Handle dynamic logout actions straight from the internal page navbar dropdown
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}

// 1. ADD NEW LOG ACTION (Left Side Panel Action)
if (isset($_POST['action']) && $_POST['action'] === 'add_log') {
    $selected_date_raw = mysqli_real_escape_string($conn, $_POST['selected_date']);
    if (!empty($selected_date_raw)) {
        $period_date = $selected_date_raw; 
        
        $cycle_length = 0;
        $next_period_date = '0000-00-00';
        
        $stmt = mysqli_prepare($conn, "INSERT INTO track (user_id, period_date, cycle_length, next_period_date) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isis", $user_id, $period_date, $cycle_length, $next_period_date);
        mysqli_stmt_execute($stmt);
        
        // Pass a tracking flag in the session to show the warning message style post-redirect if coming from a warning save
        if (isset($_POST['is_warning_save']) && $_POST['is_warning_save'] === '1') {
            $_SESSION['warning_save_msg'] = "This entry is very close to your previous cycle log. Please verify the date before continuing.";
        } else {
            $_SESSION['success_save_msg'] = "Period data logged successfully";
        }
        
        header("Location: track.php");
        exit();
    } else {
        $status_action_msg = "No operational date selection targets received.";
    }
}

// Check for session messages after standard redirects
if (isset($_SESSION['success_save_msg'])) {
    $status_action_msg = $_SESSION['success_save_msg'];
    $status_action_type = "success";
    unset($_SESSION['success_save_msg']);
} elseif (isset($_SESSION['warning_save_msg'])) {
    $status_action_msg = $_SESSION['warning_save_msg'];
    $status_action_type = "warning";
    unset($_SESSION['warning_save_msg']);
}

// 2. RUN PREDICTION ENGINE METRICS PACKAGES (Right Side Panel Action)
if (isset($_POST['action']) && $_POST['action'] === 'calculate_prediction') {
    $mode_chosen = mysqli_real_escape_string($conn, $_POST['mode']);
    
    if ($mode_chosen === 'manual') {
        $raw_last_date = mysqli_real_escape_string($conn, $_POST['manual_last_date']);
        $cycle_length = intval($_POST['manual_cycle']);
        
        if (!empty($raw_last_date) && $cycle_length > 0) {
            $date_parts = explode('-', $raw_last_date);
            $period_date = "{$date_parts[2]}-{$date_parts[1]}-{$date_parts[0]}";
            
            $calc_next = new DateTime($period_date);
            $calc_next->modify("+$cycle_length days");
            $next_period_date = $calc_next->format('Y-m-d');
            
            $check_exist = mysqli_query($conn, "SELECT id FROM track WHERE user_id = '$user_id' AND period_date = '$period_date'");
            if (mysqli_num_rows($check_exist) > 0) {
                mysqli_query($conn, "UPDATE track SET cycle_length = '$cycle_length', next_period_date = '$next_period_date' WHERE user_id = '$user_id' AND period_date = '$period_date'");
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO track (user_id, period_date, cycle_length, next_period_date) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "isis", $user_id, $period_date, $cycle_length, $next_period_date);
                mysqli_stmt_execute($stmt);
            }
            
            $status_action_msg = "Prediction metrics compiled and synchronized.";
            $status_action_type = "success";
        } else {
            $status_action_msg = "Configuration inputs for manual engine are missing.";
        }
    } else {
        $hist_query = mysqli_query($conn, "SELECT id, period_date FROM track WHERE user_id = '$user_id' ORDER BY period_date DESC");
        $historical_dates = [];
        $historical_ids = [];
        while ($row = mysqli_fetch_assoc($hist_query)) {
            $historical_dates[] = $row['period_date'];
            $historical_ids[] = $row['id'];
        }
        
        if (count($historical_dates) >= 1) {
            $period_date = $historical_dates[0]; 
            $cycle_length = 28; 
            $total_logs = count($historical_dates);
            
            if ($total_logs >= 2) {
                $gaps = [];
                $limit = min($total_logs, 5); 
                for ($i = 0; $i < $limit - 1; $i++) {
                    $diff = strtotime($historical_dates[$i]) - strtotime($historical_dates[$i+1]);
                    $gaps[] = round($diff / 86400);
                }
                if (count($gaps) > 0) {
                    $cycle_length = (int)round(array_sum($gaps) / count($gaps));
                }
            }
            
            $calc_next = new DateTime($period_date);
            $calc_next->modify("+$cycle_length days");
            $next_period_date = $calc_next->format('Y-m-d');
            
            $target_id = $historical_ids[0];
            mysqli_query($conn, "UPDATE track SET cycle_length = '$cycle_length', next_period_date = '$next_period_date' WHERE id = '$target_id'");
            
            $status_action_msg = "Predictions completed and analytics saved.";
            $status_action_type = "success";
        } else {
            $status_action_msg = "Log at least one period record inside the left panel first.";
        }
    }
}

// 3. DELETE SINGLE RECORDS PIPELINE SUBENGINE
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM track WHERE id = '$delete_id' AND user_id = '$user_id'");
    
    header("Location: track.php?deleted_success=1");
    exit();
}

if (isset($_GET['deleted_success'])) {
    $status_action_msg = "Period record deleted successfully";
    $status_action_type = "success";
}

// 4. PULL DATASET
$logs_query = mysqli_query($conn, "SELECT * FROM track WHERE user_id = '$user_id' ORDER BY period_date DESC");
$user_db_logs = [];
while ($row = mysqli_fetch_assoc($logs_query)) {
    $user_db_logs[] = $row;
}

$js_logged_array = array_map(function($item) {
    return $item['period_date'];
}, $user_db_logs);

$calculated_record = null;
foreach($user_db_logs as $log_check) {
    if (intval($log_check['cycle_length']) > 0 && $log_check['next_period_date'] !== '0000-00-00') {
        $calculated_record = $log_check;
        break;
    }
}

$dashboard_display_cycle = $calculated_record ? $calculated_record['cycle_length'] : '--';
$dashboard_display_next = $calculated_record ? date('d-m-Y', strtotime($calculated_record['next_period_date'])) : '--';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>PCOD360</title>

    <!-- <link href="https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;500;600;700;800&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            
            --shadow: 0 15px 40px rgba(59, 22, 92, 0.06);
            --hover-shadow: 0 20px 45px rgba(59, 22, 92, 0.12);
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);

            --card-grad-1: linear-gradient(135deg, rgba(248, 243, 253, 0.9) 0%, rgba(240, 228, 252, 0.8) 100%);
            --card-grad-2: linear-gradient(135deg, rgba(253, 251, 255, 0.95) 0%, rgba(245, 238, 254, 0.9) 100%);
            --btn-gradient: linear-gradient(135deg, var(--thistle) 0%, var(--mauve) 100%);
            --icon-bg: linear-gradient(135deg, var(--subtle) 0%, var(--lilac) 100%);
            
            --gradient: linear-gradient(135deg, var(--plum) 0%, var(--thistle) 100%);
            --soft-gradient: linear-gradient(135deg, #fcfaff 0%, #f4eeff 100%);
        }

        *{ margin:0; padding:0; box-sizing:border-box; }

        body{
            /* font-family:'Outfit',sans-serif; */
                        font-family: 'Plus Jakarta Sans', sans-serif;

            background: 
                radial-gradient(circle at top left, var(--subtle) 0%, transparent 35%),
                radial-gradient(circle at bottom right, #eadfff 0%, transparent 25%),
                #faf9ff;
            color:var(--text);
             /* min-height:100vh; 
            padding:24px 40px;  */
            line-height: 1.6;

    /* REMOVED: Padding here completely squishes full-width navigation bars. 
       We zero it out for the viewport edges and handle content margins inside container wraps. */
    margin: 0;
    padding: 0;
            
        }

        /* NAVIGATION
        .nav { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto 15px auto; padding: 15px 20px; }
        .logo { display: flex; align-items: center; }
        .logo img { height: 40px; width: auto; display: block; object-fit: contain; }
        .nav-links { display: flex; gap: 34px; list-style: none; }
        .nav-links a { text-decoration: none; color: var(--muted); font-weight: 500; position: relative; transition: var(--transition); }
        .nav-links a::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 0%; height: 2px; background: var(--primary); transition: 0.3s; border-radius: 20px; }
        .nav-links a:hover { color: var(--plum); }
        .nav-links a:hover::after { width: 100%; }
        .nav-auth { display: flex; align-items: center; }

        .signin-btn {
            display: flex; align-items: center; gap: 10px; padding: 12px 24px; border: none; border-radius: 40px;
            background: var(--btn-gradient); color: white; font-family: 'Outfit', sans-serif; font-size: 0.95rem;
            font-weight: 600; cursor: pointer; transition: var(--transition); box-shadow: 0 10px 25px rgba(125, 69, 198, 0.18);
        }
        .signin-btn:hover { transform: translateY(-2px); background: var(--deep-plum); box-shadow: 0 14px 30px rgba(59, 22, 92, 0.25); }
    
        .profile-dropdown {
            position: absolute; top: 120%; right: 0; width: 240px; background: #ffffff; border: 1px solid var(--border);
            border-radius: 20px; box-shadow: 0 10px 30px rgba(59, 22, 92, 0.15); padding: 15px; display: none;
            flex-direction: column; gap: 8px; z-index: 10000;
        }
        .profile-dropdown.show { display: flex; }
        .dropdown-header { display: flex; flex-direction: column; font-size: 0.85rem; color: var(--muted); padding-bottom: 5px; }
        .dropdown-header strong { color: var(--deep-plum); font-size: 0.95rem; }
        .profile-dropdown a { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--muted); font-size: 0.92rem; font-weight: 500; padding: 8px 10px; border-radius: 10px; transition: var(--transition); }
        .profile-dropdown a:hover { background: #f5effd; color: var(--primary); } */
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
   NAV AUTH BUTTON
========================= */

/* ==========================================================================
           NAV AUTH BUTTON & USER PROFILE DROPDOWN
           ========================================================================== */
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
   DASHBOARD STRUCTURAL GRID MATRIX
   ========================================================================== */
.dashboard { 
    max-width: 1400px; 
    margin: 0 auto; 
    display: grid; 
    grid-template-columns: repeat(2, minmax(0, 1fr)); /* Fixes flex child overflows */
    gap: 28px; 
    width: 100%;
}

/* ==========================================================================
   HERO / BANNER BLOCK
   ========================================================================== */
.banner {
    grid-column: span 2; 
    background: var(--gradient); 
    border-radius: 30px; 
    padding: 35px 40px;
    position: relative; 
    overflow: hidden; 
    box-shadow: var(--shadow); 
    min-height: 200px; 
    display: flex; 
    align-items: center;
}

.banner::before { 
    content: ''; 
    position: absolute; 
    width: 280px; 
    height: 280px; 
    border-radius: 50%; 
    background: rgba(255, 255, 255, 0.08); 
    top: -120px; 
    right: -60px; 
    pointer-events: none;
}

.banner-content { 
    position: relative; 
    z-index: 2; 
    width: 100%;
}

.banner h3 { 
    color: white; 
    font-size: 2rem; 
    margin-bottom: 12px; 
    font-weight: 700; 
    line-height: 1.2;
}

.banner p { 
    color: rgba(255, 255, 255, 0.9); 
    line-height: 1.6; 
    max-width: 800px; 
    font-size: 1rem; 
}

.precision-tag { 
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    margin-top: 18px; 
    background: rgba(255, 255, 255, 0.18); 
    border: 1px solid rgba(255, 255, 255, 0.2); 
    color: white; 
    padding: 8px 16px; 
    border-radius: 40px; 
    font-size: 0.85rem; 
    font-weight: 600; 
    backdrop-filter: blur(8px); 
    -webkit-backdrop-filter: blur(8px);
}

/* ==========================================================================
   GLASSMORPHISM CONTENT PANELS
   ========================================================================== */
.panel { 
    background: rgba(255, 255, 255, 0.8); 
    backdrop-filter: blur(20px); 
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--white); 
    border-radius: 36px; 
    padding: 34px; 
    box-shadow: var(--shadow); 
    transition: var(--transition); 
    min-height: 650px; 
    display: flex; 
    flex-direction: column; 
    overflow: hidden; /* Guards layout against internal text overflow ruptures */
}

/* ==========================================================================
   CALENDAR SYSTEM ENGINE
   ========================================================================== */
.calendar-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 28px; 
    gap: 12px;
}

.month-btn { 
    width: 42px; 
    height: 42px; 
    border: none; 
    border-radius: 14px; 
    background: var(--subtle); 
    color: var(--plum); 
    cursor: pointer; 
    font-size: 1.1rem; 
    transition: var(--transition); 
    flex-shrink: 0;
}

.month-btn:hover { 
    background: var(--plum); 
    color: white; 
}

#monthDisplay { 
    font-size: 1.3rem; 
    font-weight: 700; 
    color: var(--plum); 
    text-align: center;
}

.calendar-grid { 
    display: grid; 
    grid-template-columns: repeat(7, minmax(0, 1fr)); 
    gap: 10px; 
    text-align: center; 
    width: 100%;
}

.cal-day { 
    font-size: 0.85rem; 
    font-weight: 700; 
    color: var(--wisteria); 
    padding-bottom: 12px; 
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

.cal-date { 
    padding: 14px 0; 
    border-radius: 18px; 
    cursor: pointer; 
    transition: var(--transition); 
    font-weight: 500; 
    border: 2px solid transparent; 
}

.cal-date:hover { 
    background: var(--subtle); 
    color: var(--plum); 
}

.cal-date.logged { 
    background-color: var(--light-purple); 
    color: var(--thistle); 
    border-color: var(--lilac); 
    font-weight: 700; 
}

.cal-date.selected { 
    background: var(--gradient) !important; 
    color: white !important; 
    box-shadow: 0 10px 20px rgba(59, 22, 92, 0.2); 
    border-color: transparent; 
}

/* ==========================================================================
   INTERACTIVE USER CONTROLS, LISTS & ROW ITEMS
   ========================================================================== */
.inline-alert { 
    display: none; 
    background: #ffebe6; 
    color: #de350b; 
    border: 1px solid #ffbdad; 
    padding: 12px 16px; 
    border-radius: 16px; 
    font-size: 0.9rem; 
    font-weight: 600; 
    margin-bottom: 16px; 
    text-align: center; 
    animation: fadeIn 0.3s ease-in-out; 
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.track-btn { 
    width: 100%; 
    padding: 18px; 
    border: none; 
    border-radius: 60px; 
    background: var(--gradient); 
    color: white; 
    font-family: inherit; 
    font-size: 1rem; 
    font-weight: 600; 
    cursor: pointer; 
    margin-top: 24px; 
    transition: var(--transition); 
    box-shadow: 0 8px 20px rgba(59, 22, 92, 0.2); 
}

.track-btn:hover { 
    transform: translateY(-2px); 
    filter: brightness(1.1); 
}

#logHistory { 
    margin-top: 28px; 
    flex-grow: 1; 
    overflow-y: auto; 
    padding-right: 4px;
}

#logHistory::-webkit-scrollbar { width: 6px; }
#logHistory::-webkit-scrollbar-thumb { background: var(--lilac); border-radius: 20px; }

.log-row { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 16px 18px; 
    margin-bottom: 10px; 
    background: var(--soft-gradient); 
    border-radius: 18px; 
    border: 1px solid var(--border); 
    transition: var(--transition); 
    gap: 15px; /* Keeps text and delete action split cleanly on small viewports */
}

.delete-btn { 
    color: #e11d48; 
    cursor: pointer; 
    font-weight: 600; 
    font-size: 0.82rem; 
    text-decoration: none; 
    flex-shrink: 0;
}

/* ==========================================================================
   METRIC SUMMARY SQUARE CARDS
   ========================================================================== */
.card-wrap { 
    display: flex; 
    gap: 18px; 
    margin-bottom: 30px; 
    width: 100%;
}

.square-card { 
    flex: 1; 
    border-radius: 28px; 
    padding: 24px 15px; 
    background: var(--white); 
    border: 1px solid var(--border); 
    display: flex; 
    flex-direction: column; 
    justify-content: center; 
    align-items: center; 
    min-height: 130px; 
    box-shadow: 0 8px 24px rgba(59, 22, 92, 0.05); 
    text-align: center;
}

.square-card.highlight { background: var(--gradient); border: none; }
.square-card.highlight .num, .square-card.highlight .lab { color: white; }

.num { font-size: 1.8rem; font-weight: 800; color: var(--plum); line-height: 1.2; word-break: break-word; }
.lab { margin-top: 8px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); }

/* ==========================================================================
   TOGGLE ACTIONS & DATA INPUT COMPONENT FIELDSETS
   ========================================================================== */
.toggle-container { 
    display: flex; 
    gap: 8px; 
    background: var(--light-purple); 
    padding: 8px; 
    border-radius: 22px; 
    margin-bottom: 28px; 
    width: 100%;
}

.toggle-btn { 
    flex: 1; 
    border: none; 
    padding: 14px 10px; 
    border-radius: 16px; 
    background: transparent; 
    font-family: inherit; 
    font-weight: 600; 
    cursor: pointer; 
    color: var(--muted); 
    transition: var(--transition); 
    font-size: 0.95rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.toggle-btn.active { 
    background: white; 
    color: var(--plum); 
    box-shadow: 0 8px 18px rgba(59, 22, 92, 0.1); 
}

label { display: block; margin-bottom: 10px; font-size: 0.9rem; font-weight: 600; color: var(--wisteria); }
input { width: 100%; padding: 16px; border-radius: 18px; border: 1px solid var(--border); background:#fbfaff; color:var(--text); transition:var(--transition); font-size:0.95rem; font-family: inherit; }
input:focus { outline:none; border-color:var(--thistle); box-shadow:0 0 0 4px rgba(125, 69, 198, 0.1); }
.status-msg { margin-top:12px; background:var(--soft-gradient); border:1px solid var(--border); padding:14px 18px; border-radius:18px; color:var(--plum); font-size:0.9rem; font-weight:600; text-align: center; }

/* ==========================================================================
   MEDIA QUERY BREAKPOINTS (LAPTOPS, TABLETS, SMARTPHONES)
   ========================================================================== */

/* 1. Laptop & Mid-size Display Bounds Scaling */
@media (max-width: 1050px) { 
    body { padding: 20px; } 
    .dashboard { grid-template-columns: 1fr; gap: 24px; } 
    .banner { grid-column: span 1; } 
}

/* 2. Tablet Viewports & Handheld Framework Scaling */
@media (max-width: 768px) {
    .banner { padding: 25px 30px; min-height: auto; }
    .banner h3 { font-size: 1.7rem; }
    .panel { padding: 24px; border-radius: 28px; min-height: auto; }
    .calendar-grid { gap: 6px; }
    .cal-date { padding: 10px 0; border-radius: 12px; font-size: 0.9rem; }
}

/* 3. Standard Smartphone Breakpoints */
@media (max-width: 480px) {
    body { padding: 12px; }
    .banner { padding: 20px; border-radius: 22px; }
    .banner h3 { font-size: 1.45rem; margin-bottom: 8px; }
    .banner p { font-size: 0.9rem; }
    .precision-tag { margin-top: 14px; padding: 6px 12px; font-size: 0.8rem; }
    
    .panel { padding: 16px; border-radius: 24px; }
    
    /* Converts summary grid blocks into clean mobile grid systems */
    .card-wrap { gap: 12px; }
    .square-card { padding: 16px 10px; border-radius: 20px; min-height: 105px; }
    .num { font-size: 1.45rem; }
    .lab { font-size: 0.65rem; letter-spacing: 0.5px; margin-top: 6px; }
    
    /* Prevents navigation title clipping by truncating single letter labels */
    .cal-day { font-size: 0.75rem; padding-bottom: 8px; }
    .cal-date { padding: 8px 0; border-radius: 10px; font-size: 0.85rem; }
    
    .toggle-container { padding: 6px; border-radius: 18px; margin-bottom: 20px; }
    .toggle-btn { padding: 10px 6px; font-size: 0.85rem; border-radius: 12px; }
    
    .track-btn { padding: 15px; font-size: 0.95rem; margin-top: 16px; }
    .log-row { padding: 14px; border-radius: 14px; font-size: 0.9rem; }
}

/* 4. Support for Ultra-Compact Devices (e.g., iPhone SE / Galaxy Z Fold Cover) */
@media (max-width: 360px) {
    .calendar-grid { gap: 4px; }
    .cal-date { font-size: 0.8rem; }
    .card-wrap { flex-direction: column; gap: 10px; } /* Stacks cards to maintain clear typography spacing */
    .square-card { min-height: auto; padding: 14px; }
}    </style>
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
    <div class="dashboard">
        <div class="banner">
            <div class="banner-content">
                <!-- <h3>Cycle Insights</h3>
                <p>
                    Log your cycle details regularly to keep your tracking history updated. 
                    Your future cycle predictions are estimated based on the information you log, 
                    and prediction accuracy may improve over time with more records.
                </p>
                <div class="precision-tag">
                    ✦ Consistent tracking helps improve predictions
                </div> -->
                <h3>Cycle Insights</h3>

<p>
    Keep your cycle history up to date by logging each period consistently.
    Predicted future cycle dates are calculated from your recorded data and
    may become more accurate over time. These predictions are estimates and
    can vary due to natural changes in menstrual cycles.
</p>

<div class="precision-tag">
    ✦ Predictions are based on your recorded cycle history
</div>
            </div>
        </div>

        <div class="panel">
            <div id="panelLeftAlert" class="inline-alert"></div>

            <div class="calendar-header">
                <button class="month-btn" onclick="changeMonth(-1)">❮</button>
                <div id="monthDisplay"></div>
                <button class="month-btn" onclick="changeMonth(1)">❯</button>
            </div>

            <div class="calendar-grid" id="calendarGrid"></div>

            <form id="logSubmitForm" method="POST" action="track.php" style="display:none;">
                <input type="hidden" name="action" value="add_log">
                <input type="hidden" name="selected_date" id="formSelectedDateInput">
                <input type="hidden" name="is_warning_save" id="formIsWarningSaveInput" value="0">
            </form>

            <button class="track-btn" onclick="submitLogPayload()">
                Add Selected Date
            </button>

            <div id="logHistory">
                <label>Recent Logs</label>
                <?php if (empty($user_db_logs)): ?>
                    <div class="status-msg">No historical cycles synced to this account profile yet.</div>
                <?php else: ?>
                    <?php foreach ($user_db_logs as $row): ?>
                        <div class="log-row">
                            <span><?php echo date('d-m-Y', strtotime($row['period_date'])); ?></span>
                            <span style="font-size:0.8rem; color:var(--muted)"></span>
                            <a href="track.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="panel">
            <div id="panelRightAlert" class="inline-alert"></div>

            <div class="card-wrap">
                <div class="square-card">
                    <div class="num" id="cycleValue"><?php echo $dashboard_display_cycle; ?></div>
                    <div class="lab">Avg Cycle</div>
                </div>
                <div class="square-card highlight">
                    <div class="num" id="nextDateSmall"><?php echo $dashboard_display_next; ?></div>
                    <div class="lab">Predicted Date</div>
                </div>
            </div>

            <div class="toggle-container">
                <button class="toggle-btn active" id="autoBtn" onclick="setMode('auto')">Auto Precision</button>
                <button class="toggle-btn" id="manualBtn" onclick="setMode('manual')">Manual Input</button>
            </div>

            <form id="predictionForm" method="POST" action="track.php" style="display:none;">
                <input type="hidden" name="action" value="calculate_prediction">
                <input type="hidden" name="mode" id="predictModeInput" value="auto">
                <input type="hidden" name="manual_last_date" id="predictManualLastDateInput">
                <input type="hidden" name="manual_cycle" id="predictManualCycleInput">
            </form>

            <div id="autoInputs">
                <label>Cycle History</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px;">
                    <input type="text" id="pDate1" placeholder="dd-mm-yyyy" readonly>
                    <input type="text" id="pDate2" placeholder="dd-mm-yyyy" readonly>
                    <input type="text" id="pDate3" placeholder="dd-mm-yyyy" readonly>
                    <input type="text" id="pDate4" placeholder="dd-mm-yyyy" readonly>
                </div>
                <div class="status-msg" id="statusMsg">Syncing history...</div>
            </div>

            <div id="manualInputs" style="display:none;">
                <label>Last Period Date</label>
                <input type="text" id="manualLastDate" placeholder="dd-mm-yyyy" style="margin-bottom:20px;">
                <label>Cycle Length (Days)</label>
                <input type="number" id="manualCycle" value="28">
            </div>

            <button class="track-btn" onclick="executeCalculationPipeline()">
                Calculate Prediction & Save
            </button>
        </div>
    </div>

<script>
           function toggleMenu() {
            var navbar = document.getElementById("myTopnav");
            var icon = document.getElementById("hamburgerIcon");
            
            // Toggle between layout classes using the responsive trigger flag
            if (navbar.className === "nav") {
                navbar.className += " responsive";
                icon.className = "fas fa-times"; // Changes icon to an 'X' close button
            } else {
                navbar.className = "nav";
                icon.className = "fas fa-bars";  // Resets icon back to standard bars
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


    let logs = <?php echo json_encode($js_logged_array); ?>;
    let current = new Date();
    
    let selected = null; 
    let mode = 'auto';
    const profileDropdown = document.getElementById("profileDropdown");

    function toggleProfileDropdown() {
        if(profileDropdown) { profileDropdown.classList.toggle("show"); }
    }

    window.addEventListener("click", function (e) {
        if (profileDropdown && !profileDropdown.contains(e.target) && !e.target.closest('.signin-btn')) {
            profileDropdown.classList.remove("show");
        }
    });

    window.onload = () => {
        renderCalendar();
        updateAutoInputs();
        
        <?php if(!empty($status_action_msg)): ?>
            let actionType = "<?php echo $status_action_type; ?>";
            showInlineMessage("panelLeftAlert", "<?php echo $status_action_msg; ?>", actionType);
        <?php endif; ?>
    };

    function showInlineMessage(elementId, text, type = "error") {
        const target = document.getElementById(elementId);
        target.innerText = text;
        target.style.display = "block";
        
        if (type === "success") {
            target.style.backgroundColor = "#e6f4ea"; target.style.color = "#137333"; target.style.borderColor = "#c4eed0";
        } else if (type === "warning") {
            target.style.backgroundColor = "#fff9e6"; target.style.color = "#b76e00"; target.style.borderColor = "#ffe79a";
        } else {
            target.style.backgroundColor = "#ffebe6"; target.style.color = "#de350b"; target.style.borderColor = "#ffbdad";
        }
        setTimeout(() => { target.style.display = "none"; }, 6000);
    }

    function formatDateDisplay(dateInput) {
        if (!dateInput) return '';
        const d = new Date(dateInput);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        return `${day}-${month}-${year}`;
    }

    function renderCalendar() {
        const grid = document.getElementById('calendarGrid');
        const monthYear = document.getElementById('monthDisplay');
        grid.innerHTML = '';
        monthYear.innerText = current.toLocaleDateString('default', { month: 'long', year: 'numeric' });

        ['S','M','T','W','T','F','S'].forEach(d => grid.innerHTML += `<div class="cal-day">${d}</div>`);

        const first = new Date(current.getFullYear(), current.getMonth(), 1).getDay();
        const days = new Date(current.getFullYear(), current.getMonth()+1, 0).getDate();

        for(let i=0; i<first; i++) grid.innerHTML += `<div></div>`;

        for(let i=1; i<=days; i++) {
            let dStr = `${current.getFullYear()}-${(current.getMonth()+1).toString().padStart(2,'0')}-${i.toString().padStart(2,'0')}`;
            
            let isSelected = (selected === dStr) ? 'selected' : '';
            let isLogged = logs.includes(dStr) ? 'logged' : '';
            
            grid.innerHTML += `
                <div class="cal-date ${isLogged} ${isSelected}" onclick="select('${dStr}')">
                    ${i}
                </div>`;
        }
    }

    function changeMonth(o) { current.setMonth(current.getMonth()+o); renderCalendar(); }
    
    function select(d) { 
        selected = d; 
        renderCalendar(); 
        document.getElementById('manualLastDate').value = formatDateDisplay(d);
    }

    function updateAutoInputs() {
        for(let i=1; i<=4; i++) {
            document.getElementById(`pDate${i}`).value = logs[i-1] ? formatDateDisplay(logs[i-1]) : '';
        }
        const msg = document.getElementById('statusMsg');
        msg.innerText = logs.length >= 4 ? "Precision analytics available" : `${logs.length}/4 entries synced natively`;
        if(logs[0] && !selected) document.getElementById('manualLastDate').value = formatDateDisplay(logs[0]);
    }

    function setMode(m) {
        mode = m;
        document.getElementById('autoBtn').className = m === 'auto' ? 'toggle-btn active' : 'toggle-btn';
        document.getElementById('manualBtn').className = m === 'manual' ? 'toggle-btn active' : 'toggle-btn';
        document.getElementById('autoInputs').style.display = m === 'auto' ? 'block' : 'none';
        document.getElementById('manualInputs').style.display = m === 'manual' ? 'block' : 'none';
    }

    // Pipeline Subengine for Left-Side Logging Interaction ONLY
    function submitLogPayload() {
        if(!selected) {
            showInlineMessage("panelLeftAlert", "Please select a date on the calendar first.", "error");
            return;
        }
        
        // Setup strict local date mapping context to eliminate timezone drift
        const parts = selected.split('-');
        const targetDate = new Date(parts[0], parts[1] - 1, parts[2]);
        targetDate.setHours(0,0,0,0);
        
        const today = new Date();
        today.setHours(0,0,0,0);

        if(targetDate > today) {
            showInlineMessage("panelLeftAlert", "You cannot log a future date.", "error");
            return;
        }

        if(logs.includes(selected)) {
            showInlineMessage("panelLeftAlert", "This exact date coordinate already exists in your logs.", "error");
            return;
        }

        document.getElementById('formIsWarningSaveInput').value = "0";

        // --- AIRTIGHT ADVANCED LOGICAL GAP PROTECTION ---
        for (let i = 0; i < logs.length; i++) {
            const logParts = logs[i].split('-');
            const loggedDate = new Date(logParts[0], logParts[1] - 1, logParts[2]);
            loggedDate.setHours(0,0,0,0);
            
            const timeDiff = Math.abs(targetDate.getTime() - loggedDate.getTime());
            // Precise total tracking days conversion formula
            const dayGap = Math.round(timeDiff / (1000 * 3600 * 24));
            
            // 1-10 Days Range: Block Action
            if (dayGap >= 1 && dayGap <= 10) {
                showInlineMessage("panelLeftAlert", "This date is very close to your last recorded period. To maintain accurate tracking, please log only the first day of a new cycle.", "error");
                return; 
            }
            
            // 11-20 Days Range: Warning + Save Action
            if (dayGap >= 11 && dayGap <= 20) {
                document.getElementById('formIsWarningSaveInput').value = "1";
                break;
            }
        }
        // ----------------------------------------------
        
        document.getElementById('formSelectedDateInput').value = selected;
        document.getElementById('logSubmitForm').submit();
    }

    // Pipeline Subengine for Right-Side Computational Execution ONLY
    function executeCalculationPipeline() {
        if(mode === 'manual') {
            const mDate = document.getElementById('manualLastDate').value;
            const mCycle = document.getElementById('manualCycle').value;
            
            if(!mDate) {
                showInlineMessage("panelRightAlert", "Please select a base date reference context.", "error");
                return;
            }
            if(parseInt(mCycle) <= 0 || isNaN(mCycle)) {
                showInlineMessage("panelRightAlert", "Provide a valid historical length metric setup configuration value.", "error");
                return;
            }
            
            document.getElementById('predictModeInput').value = 'manual';
            document.getElementById('predictManualLastDateInput').value = mDate;
            document.getElementById('predictManualCycleInput').value = mCycle;
        } else {
            if(logs.length === 0) {
                showInlineMessage("panelRightAlert", "Please log at least one date on the left calendar before processing analytics.", "error");
                return;
            }
            document.getElementById('predictModeInput').value = 'auto';
        }
        document.getElementById('predictionForm').submit();
    }
</script>
</body>
</html>
