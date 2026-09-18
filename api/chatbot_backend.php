<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../config/db.php";

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    echo "Please log in to use the chatbot.";
    exit();
}

$user_id = $_SESSION['user']['id'];
$raw_user_input = trim($_POST['message'] ?? '');
$user_input = strtolower($raw_user_input);

// Handle session ID parameter safely
if (isset($_POST['session_id']) && !empty($_POST['session_id'])) {
    $sessionId = trim($_POST['session_id']);
} else {
    $sessionId = "sess_" . bin2hex(random_bytes(8)) . "_" . time();
}


// ------------------ 1. INITIAL WELCOME (DO NOT SAVE TO DB) ------------------
if ($user_input === "init_chat") {
    
    // Check upcoming tracking projection
    $track_sql = "SELECT next_period_date FROM track WHERE user_id = ? ORDER BY period_date DESC LIMIT 1";
    $t_stmt = $conn->prepare($track_sql);
    $t_stmt->bind_param("i", $user_id);
    $t_stmt->execute();
    $t_res = $t_stmt->get_result()->fetch_assoc();
    $t_stmt->close();
    
    if ($t_res && !empty($t_res['next_period_date'])) {
        $next_date = strtotime($t_res['next_period_date']);
        $today = strtotime(date("Y-m-d"));
        $days_left = round(($next_date - $today) / (60 * 60 * 24));
        
        if ($days_left > 0 && $days_left <= 3) {
            $welcome = "👋 Hello! Welcome to your PCOD Health Assistant.<br><br>";
            $welcome .= "📌 <b>Reminder:</b> Your next predicted period is approaching in <b>" . $days_left . " days</b>.<br><br>";
            $welcome .= "How may I assist you today? You can ask me about:<br>";
            $welcome .= "• <b>PCOD Causes & Symptoms</b><br>";
            $welcome .= "• <b>Hormones in PCOD</b><br>";
            $welcome .= "• <b>Difference between PCOD & PCOS</b><br>";
            $welcome .= "• <b>Diet & Weight Management</b><br>";
            $welcome .= "• <b>Show my screening / tracking results</b>";
            
            echo $welcome;
            // DO NOT SAVE TO DB so sidebar title won't say INIT_CHAT
            exit();
        }
    }
    
    // Standard Welcome greeting
    $welcome = "👋 Hello! Welcome to your PCOD Health Assistant.<br><br>";
    $welcome .= "How may I assist you today? You can ask me about:<br>";
    $welcome .= "• <b>PCOD Causes & Symptoms</b><br>";
    $welcome .= "• <b>Hormones in PCOD</b><br>";
    $welcome .= "• <b>Difference between PCOD & PCOS</b><br>";
    $welcome .= "• <b>Diet & Weight Management</b><br>";
    $welcome .= "• <b>Show my screening / tracking results</b>";
    
    echo $welcome;
    // DO NOT SAVE TO DB
    exit();
}


// ------------------ 2. SHORT INPUT & GREETING INTERCEPTORS ------------------

if (strlen($user_input) < 3) {
    $response = "Hello! Could you please provide a few more details so I can assist you better?";
    echo $response;
    saveChatLog($user_id, $sessionId, $raw_user_input, $response, $conn);
    exit();
}

// Gratitude Interceptor
$thanks_keywords = ['thanks', 'thank you', 'thx', 'awesome', 'great', 'thanku'];
if (in_array($user_input, $thanks_keywords)) {
    $response = "You're very welcome! 😊 Please feel free to ask if you have any other questions about PCOD, diet, or your period tracking.";
    echo $response;
    saveChatLog($user_id, $sessionId, $raw_user_input, $response, $conn);
    exit();
}

// Standard Greetings
if (preg_match('/^(hi|hello|hey|heyy|hiii|good morning|good afternoon|good evening|gm|gn)$/i', $user_input)) {
    $response = "Hello! 👋 How may I assist you today?<br><br>Feel free to ask me about:<br>• <b>PCOD Causes</b><br>• <b>Hormones in PCOD</b><br>• <b>PCOD vs PCOS</b><br>• <b>Weight Management & Diet Tips</b>";
    echo $response;
    saveChatLog($user_id, $sessionId, $raw_user_input, $response, $conn);
    exit();
}


// ------------------ 3. BROAD INTENT DETECTION ------------------
// Add this in the intent detection section:
$isSymptomsIntent = (
    strpos($user_input, "symptom") !== false || 
    strpos($user_input, "sign") !== false ||
    strpos($user_input, "problem") !== false
);


$isScreeningIntent = (
    strpos($user_input, "screen") !== false || 
    strpos($user_input, "risk") !== false || 
    strpos($user_input, "score") !== false || 
    strpos($user_input, "prediction") !== false || 
    strpos($user_input, "result") !== false || 
    strpos($user_input, "report") !== false
);

$isTrackingIntent = (
    strpos($user_input, "track") !== false || 
    strpos($user_input, "period") !== false || 
    strpos($user_input, "cycle") !== false || 
    strpos($user_input, "log") !== false
);

$isPcodPcosIntent = (
    strpos($user_input, "pcod vs pcos") !== false || 
    strpos($user_input, "pcos vs pcod") !== false || 
    (strpos($user_input, "pcod") !== false && strpos($user_input, "pcos") !== false) ||
    strpos($user_input, "difference") !== false
);

$isCausesIntent = (
    strpos($user_input, "cause") !== false || 
    strpos($user_input, "reason") !== false || 
    strpos($user_input, "why pcod") !== false
);

$isHormoneIntent = (
    strpos($user_input, "hormone") !== false || 
    strpos($user_input, "imbalance") !== false
);

$isWeightDietIntent = (
    strpos($user_input, "weight") !== false || 
    strpos($user_input, "diet") !== false || 
    strpos($user_input, "food") !== false || 
    strpos($user_input, "eat") !== false || 
    strpos($user_input, "nutrition") !== false || 
    strpos($user_input, "fat") !== false
);
// Expanded Causes/Reasons Intent detection (handles typos & variations)
$isCausesIntent = (
    strpos($user_input, "cause") !== false || 
    strpos($user_input, "reason") !== false || 
    strpos($user_input, "reson") !== false ||  // Catch "resons" typo
    strpos($user_input, "why pcod") !== false ||
    strpos($user_input, "why does") !== false ||
    strpos($user_input, "happen") !== false
);

if ($isCausesIntent) {
    $response = "
    <b><u>Primary Causes & Reasons for PCOD</u></b><br><br>
    While the exact single cause isn't fully known, PCOD is primarily driven by:<br><br>
    • <b>Insulin Resistance:</b> Higher insulin levels signal the ovaries to produce excess male hormones (androgens).<br>
    • <b>Hormonal Imbalances:</b> Disrupted ratios between Luteinizing Hormone (LH) and Follicle Stimulating Hormone (FSH).<br>
    • <b>Genetics & Family History:</b> A higher likelihood if immediate family members have irregular cycles or diabetes.<br>
    • <b>Lifestyle & Stress:</b> Sedentary habits, poor diet, and chronic stress elevate cortisol and disrupt hormonal signals.
    ";
}


// ======================= INTENT HANDLERS =======================


elseif ($isPcodPcosIntent) {
    $response = "
    <b><u>Difference Between PCOD and PCOS</u></b><br><br>
    
    • <b>PCOD (Polycystic Ovarian Disease):</b><br>
    A common ovarian condition where immature eggs turn into cysts. It involves mild hormonal shifts, rarely disrupts long-term metabolic health, and can usually be managed or reversed through balanced nutrition, exercise, and lifestyle changes.<br><br>
    
    • <b>PCOS (Polycystic Ovary Syndrome):</b><br>
    A complex endocrine and metabolic disorder marked by high androgen (male hormone) levels and insulin resistance. It frequently prevents normal ovulation, impacts fertility, and requires ongoing medical supervision alongside lifestyle adjustments.<br><br>
    
    <b>Key Takeaway:</b> PCOD mainly affects the ovaries and is lifestyle-driven, whereas PCOS is a systemic metabolic disorder requiring clinical management.
    ";
}elseif ($isSymptomsIntent) {
    $response = "
    <b><u>Common Symptoms of PCOD</u></b><br><br>
    • <b>Irregular Periods:</b> Infrequent, delayed, or unpredictable menstrual cycles.<br>
    • <b>Weight Gain:</b> Unexplained weight gain, particularly around the abdomen.<br>
    • <b>Acne & Oily Skin:</b> Persistent breakouts caused by elevated androgen levels.<br>
    • <b>Excess Hair Growth (Hirsutism):</b> Facial or body hair growth.<br>
    • <b>Hair Thinning:</b> Scalp hair loss or thinning.<br>
    • <b>Mood Swings:</b> Hormonal fluctuations affecting energy and mood.
    ";
}
elseif ($isWeightDietIntent) {
    $response = "
    <b><u>PCOD Diet & Weight Management Tips</u></b><br><br>
    • <b>Low-GI Diet:</b> Focus on complex carbohydrates (whole grains, veggies) to stabilize sugar spikes.<br>
    • <b>Protein Focus:</b> Pair protein with meals to improve satiety.<br>
    • <b>Healthy Fats:</b> Include seeds (chia, flax), nuts, and olive oil.<br>
    • <b>Exercise:</b> Combine strength training with cardio to manage insulin levels.<br>
    • <b>Stay Hydrated:</b> Drink 2–3 liters of water daily.
    ";
}
elseif ($isCausesIntent) {
    $response = "
    <b><u>Primary Causes of PCOD</u></b><br><br>
    • <b>Insulin Resistance:</b> Elevated insulin levels trigger excess androgen production.<br>
    • <b>Hormonal Imbalance:</b> Disrupted LH/FSH ratios hinder ovulation.<br>
    • <b>Genetics:</b> Family history of PCOD or metabolic issues.<br>
    • <b>Lifestyle Factors:</b> Sedentary habits and processed food diets.
    ";
}
elseif ($isHormoneIntent) {
    $response = "
    <b><u>Key Hormones Affected in PCOD</u></b><br><br>
    • <b>Insulin:</b> High levels signal ovaries to produce male hormones (androgens).<br>
    • <b>Androgens:</b> Elevated levels cause facial hair, acne, and disrupt ovulation.<br>
    • <b>LH & FSH:</b> An unbalanced LH-to-FSH ratio prevents normal follicle growth.<br>
    • <b>Progesterone:</b> Often drops when ovulation becomes irregular.
    ";
}
elseif ($isScreeningIntent) {
    $sql = "SELECT prediction_result, risk_percentage FROM screening WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $risk = strtoupper($row['prediction_result']);
        $badge_class = ($risk == "HIGH") ? "bg-high" : "bg-low";
        $card_class  = ($risk == "HIGH") ? "status-high" : "status-low";

        $response = "
        <div class='health-status-card {$card_class}'>
            <div class='card-header-title'>
                <i class='fas fa-chart-bar'></i> Your Latest Screening Result
            </div>
            <div class='card-metrics-grid'>
                Risk Level: <span class='badge-status {$badge_class}'>{$risk}</span><br><br>
                Risk Score: <strong>{$row['risk_percentage']}%</strong><br><br>
                <small>This is an early screening assessment and not a medical diagnosis.</small>
            </div>
        </div>";
    } else {
        $response = "You haven't completed the PCOD screening yet. Please complete the assessment from your dashboard.";
    }
    $stmt->close();
}
elseif ($isTrackingIntent) {
$sql = "SELECT period_date, cycle_length, next_period_date 
        FROM track 
        WHERE user_id = ? 
        AND cycle_length > 0 
        AND next_period_date <> '0000-00-00'
        ORDER BY period_date DESC 
        LIMIT 1";    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = "
        📅 <b>Your Latest Cycle Details</b><br><br>
        <b>Last Logged Period:</b> {$row['period_date']}<br>
        <b>Average Cycle Length:</b> {$row['cycle_length']} days<br>
        <b>Next Expected Period:</b> {$row['next_period_date']}
        ";
    } else {
        $response = "No menstrual tracking records found. You can start logging your cycle dates in the tracking section.";
    }
    $stmt->close();
}
else {
    $response = findAdvancedMatch($user_input, $raw_user_input, $conn, $user_id);
}

echo $response;

// Save actual user inputs and responses to history
saveChatLog($user_id, $sessionId, $raw_user_input, $response, $conn);
$conn->close();


// ------------------ ADVANCED NLP MATCHING LOGIC ------------------

function findAdvancedMatch($user_input, $raw_user_input, $conn, $user_id) {
    $sql = "SELECT question_keywords, answer FROM faq";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $keyword = strtolower(trim($row['question_keywords']));
            
            if (!empty($keyword) && (strpos($user_input, $keyword) !== false || strpos($keyword, $user_input) !== false)) {
                return nl2br($row['answer']);
            }
        }
    }

    logUnmatchedQuery($user_id, $raw_user_input, $conn);

    $fallback  = "I apologize, but I don't have a specific answer for that query.<br><br>";
    $fallback .= "As your health assistant, I can best answer questions about:<br>";
    $fallback .= "• <b>PCOD Basics:</b> 'What causes PCOD?' or 'Hormones in PCOD'<br>";
    $fallback .= "• <b>Comparison:</b> 'Difference between PCOD and PCOS'<br>";
    $fallback .= "• <b>Diet & Lifestyle:</b> 'Diet for PCOD' or 'Weight management tips'<br>";
    $fallback .= "• <b>Your Data:</b> 'Show my risk score' or 'Show my tracking'<br><br>";
    $fallback .= "Please try asking using one of these topics!";

    return $fallback;
}

// ------------------ HELPER FUNCTIONS ------------------

function saveChatLog($user_id, $sessionId, $user_msg, $bot_reply, $conn) {
    $stmt = $conn->prepare("INSERT INTO chat_history (user_id, session_id, sender, message) VALUES (?, ?, ?, ?)");
    
    $sender_user = "user";
    $stmt->bind_param("isss", $user_id, $sessionId, $sender_user, $user_msg);
    $stmt->execute();
    
    $sender_bot = "bot";
    $stmt->bind_param("isss", $user_id, $sessionId, $sender_bot, $bot_reply);
    $stmt->execute();
    
    $stmt->close();
}

function logUnmatchedQuery($user_id, $raw_user_input, $conn) {
    try {
        $create_sql = "CREATE TABLE IF NOT EXISTS unmatched_queries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            query VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($create_sql);

        $stmt = $conn->prepare("INSERT INTO unmatched_queries (user_id, query) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("is", $user_id, $raw_user_input);
            $stmt->execute();
            $stmt->close();
        }
    } catch (Throwable $e) {
        error_log("Logging unmatched query failed: " . $e->getMessage());
    }
}
?>
