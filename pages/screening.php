<?php
ini_set('memory_limit', '2048M');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . DIRECTORY_SEPARATOR . '../config/db.php';
if (!isset($_SESSION['user'])) {
    $_SESSION['open_modal'] = "login";
    $_SESSION['login_notice'] = "Please sign in to access this feature.";
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$status_action_msg = "";
$status_action_type = "error";

// Handle dynamic logout requests straight from the internal page navbar dropdown link
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';
// Safety mapping check for custom database link structures
if (!isset($conn) && isset($mysqli)) {
    $conn = $mysqli;
}
if (!isset($conn) && isset($link)) {
    $conn = $link;
}

use Phpml\ModelManager;

// Detect the AJAX form submission from your JavaScript questionnaire frontend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_screening') {

    // Clear out any stray output text or echo statements to protect JSON transmission stability
    ob_clean();
    header('Content-Type: application/json');

    try {
        $modelPath = __DIR__ . '/../ml/pcod_model_deploy.phpml.gz';

        if (!file_exists($modelPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Trained model file pcod_model.phpml missing!']);
            exit;
        }

        // Check if database variable is populated correctly before executing math frames
        if (!isset($conn) || !$conn) {
            throw new Exception("Database connection variable (\$conn) is missing or null. Check your db.php setup.");
        }

        // =========================================================================
        // 1. EXTRACT DATA & MANAGE BASE FORM PARAMETERS
        // =========================================================================
        $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
        $userId = $_SESSION['user']['id'] ?? 1; // Pull logged-in ID, fall back to 1

        // Process Answers Array into binary format required by your Machine Learning Dataset structure
        $q1_no   = (isset($_POST['q1']) && $_POST['q1'] === 'No') ? 1 : 0;
        $q1_yes  = (isset($_POST['q1']) && $_POST['q1'] === 'Yes') ? 1 : 0;
        $q2_21_35 = (isset($_POST['q2']) && $_POST['q2'] === 'Every 21-35 days') ? 1 : 0;
        $q2_28    = (isset($_POST['q2']) && $_POST['q2'] === 'Every 28 days') ? 1 : 0;
        $q2_less   = (isset($_POST['q2']) && $_POST['q2'] === 'Less often than 35 days') ? 1 : 0;
        $q2_more   = (isset($_POST['q2']) && $_POST['q2'] === 'More than 2 months gap') ? 1 : 0;
        $q2_irreg  = (isset($_POST['q2']) && $_POST['q2'] === 'Irregular') ? 1 : 0;
        $q2_not    = (isset($_POST['q2']) && $_POST['q2'] === 'Not sure') ? 1 : 0;
        $q3_no   = (isset($_POST['q3']) && $_POST['q3'] === 'No') ? 1 : 0;
        $q3_yes  = (isset($_POST['q3']) && $_POST['q3'] === 'Yes') ? 1 : 0;
        $q4_1     = (isset($_POST['q4']) && $_POST['q4'] === '1 time') ? 1 : 0;
        $q4_2_3   = (isset($_POST['q4']) && $_POST['q4'] === '2-3 time') ? 1 : 0;
        $q4_more  = (isset($_POST['q4']) && $_POST['q4'] === 'More than 3 times') ? 1 : 0;
        $q4_na    = (isset($_POST['q4']) && $_POST['q4'] === 'Not applicable') ? 1 : 0;
        $q5_no   = (isset($_POST['q5']) && $_POST['q5'] === 'No') ? 1 : 0;
        $q5_yes  = (isset($_POST['q5']) && $_POST['q5'] === 'Yes') ? 1 : 0;
        $q6_no   = (isset($_POST['q6']) && $_POST['q6'] === 'No') ? 1 : 0;
        $q6_yes  = (isset($_POST['q6']) && $_POST['q6'] === 'Yes') ? 1 : 0;
        $q7_no   = (isset($_POST['q7']) && $_POST['q7'] === 'No') ? 1 : 0;
        $q7_yes  = (isset($_POST['q7']) && $_POST['q7'] === 'Yes') ? 1 : 0;
        $q8_no   = (isset($_POST['q8']) && $_POST['q8'] === 'No') ? 1 : 0;
        $q8_yes  = (isset($_POST['q8']) && $_POST['q8'] === 'Yes') ? 1 : 0;
        $q9_no   = (isset($_POST['q9']) && $_POST['q9'] === 'No') ? 1 : 0;
        $q9_yes  = (isset($_POST['q9']) && $_POST['q9'] === 'Yes') ? 1 : 0;
        $q10_no  = (isset($_POST['q10']) && $_POST['q10'] === 'No') ? 1 : 0;
        $q10_yes = (isset($_POST['q10']) && $_POST['q10'] === 'Yes') ? 1 : 0;

        $q11_no         = (isset($_POST['q11']) && $_POST['q11'] === 'No') ? 1 : 0;
        $q11_not_tested = (isset($_POST['q11']) && $_POST['q11'] === 'Not tested') ? 1 : 0;
        $q11_yes        = (isset($_POST['q11']) && $_POST['q11'] === 'Yes') ? 1 : 0;
        $q12_no         = (isset($_POST['q12']) && $_POST['q12'] === 'No') ? 1 : 0;
        $q12_not_tested = (isset($_POST['q12']) && $_POST['q12'] === 'Not tested') ? 1 : 0;
        $q12_yes        = (isset($_POST['q12']) && $_POST['q12'] === 'Yes') ? 1 : 0;
        $q13_no  = (isset($_POST['q13']) && $_POST['q13'] === 'No') ? 1 : 0;
        $q13_yes = (isset($_POST['q13']) && $_POST['q13'] === 'Yes') ? 1 : 0;

        $q14_no         = (isset($_POST['q14']) && $_POST['q14'] === 'No') ? 1 : 0;
        $q14_not_tested = (isset($_POST['q14']) && $_POST['q14'] === 'Not tested') ? 1 : 0;
        $q14_yes        = (isset($_POST['q14']) && $_POST['q14'] === 'Yes') ? 1 : 0;
        $q15_both      = (isset($_POST['q15']) && $_POST['q15'] === 'Both exercise and household work') ? 1 : 0;
        $q15_household = (isset($_POST['q15']) && $_POST['q15'] === 'Household work') ? 1 : 0;
        $q15_low       = (isset($_POST['q15']) && $_POST['q15'] === 'Low physical activity / mostly inactive') ? 1 : 0;
        $q15_reg       = (isset($_POST['q15']) && $_POST['q15'] === 'Regular exercise') ? 1 : 0;

        $q16_1_2  = (isset($_POST['q16']) && $_POST['q16'] === '1-2 times a week') ? 1 : 0;
        $q16_daily = (isset($_POST['q16']) && $_POST['q16'] === 'Daily / Almost daily') ? 1 : 0;
        $q16_never = (isset($_POST['q16']) && $_POST['q16'] === 'never') ? 1 : 0;
        $q16_rare  = (isset($_POST['q16']) && $_POST['q16'] === 'Rarely') ? 1 : 0;
        $q16_more  = (isset($_POST['q16']) && $_POST['q16'] === 'more than 3 times/week') ? 1 : 0;
        $q17_no  = (isset($_POST['q17']) && $_POST['q17'] === 'No') ? 1 : 0;
        $q17_yes = (isset($_POST['q17']) && $_POST['q17'] === 'Yes') ? 1 : 0;

        $q18_6_8  = (isset($_POST['q18']) && $_POST['q18'] === '6-8 hours') ? 1 : 0;
        $q18_less = (isset($_POST['q18']) && $_POST['q18'] === 'Less than 6 hours') ? 1 : 0;
        $q18_more  = (isset($_POST['q18']) && $_POST['q18'] === 'More than 8 hours') ? 1 : 0;
        $q19_no   = (isset($_POST['q19']) && $_POST['q19'] === 'No') ? 1 : 0;
        $q19_yes  = (isset($_POST['q19']) && $_POST['q19'] === 'Yes') ? 1 : 0;
        $q20_no  = (isset($_POST['q20']) && $_POST['q20'] === 'No') ? 1 : 0;
        $q20_yes = (isset($_POST['q20']) && $_POST['q20'] === 'Yes') ? 1 : 0;
        $q20_not = (isset($_POST['q20']) && $_POST['q20'] === 'Not sure') ? 1 : 0;
        $q21_no  = (isset($_POST['q21']) && $_POST['q21'] === 'No') ? 1 : 0;
        $q21_yes = (isset($_POST['q21']) && $_POST['q21'] === 'Yes') ? 1 : 0;

        $q22_no  = (isset($_POST['q22']) && $_POST['q22'] === 'No') ? 1 : 0;
        $q22_yes = (isset($_POST['q22']) && $_POST['q22'] === 'Yes') ? 1 : 0;
        $q22_not_tested = (isset($_POST['q22']) && $_POST['q22'] === 'Not tested') ? 1 : 0;

        $q23_no  = (isset($_POST['q23']) && $_POST['q23'] === 'No') ? 1 : 0;
        $q23_yes = (isset($_POST['q23']) && $_POST['q23'] === 'Yes') ? 1 : 0;

        $q24_no  = (isset($_POST['q24']) && $_POST['q24'] === 'No') ? 1 : 0;
        $q24_not = (isset($_POST['q24']) && $_POST['q24'] === 'Not applicable') ? 1 : 0;
        $q24_yes = (isset($_POST['q24']) && $_POST['q24'] === 'Yes') ? 1 : 0;

        $q25_no  = (isset($_POST['q25']) && $_POST['q25'] === 'No') ? 1 : 0;
        $q25_not = (isset($_POST['q25']) && $_POST['q25'] === 'Not applicable') ? 1 : 0;
        $q25_yes = (isset($_POST['q25']) && $_POST['q25'] === 'Yes') ? 1 : 0;

        $q26_no  = (isset($_POST['q26']) && $_POST['q26'] === 'No') ? 1 : 0;
        $q26_not_tested = (isset($_POST['q26']) && $_POST['q26'] === 'Not tested') ? 1 : 0;
        $q26_yes = (isset($_POST['q26']) && $_POST['q26'] === 'Yes') ? 1 : 0;

        $q27_no  = (isset($_POST['q27']) && $_POST['q27'] === 'No') ? 1 : 0;
        $q27_not = (isset($_POST['q27']) && $_POST['q27'] === 'Not sure') ? 1 : 0;
        $q27_yes = (isset($_POST['q27']) && $_POST['q27'] === 'Yes') ? 1 : 0;
        $q27_not_applicable = (isset($_POST['q27']) && $_POST['q27'] === 'Not applicable') ? 1 : 0;

        // =========================================================================
        // 2. CONSTRUCT FEATURE MATRIX ORDERED ARRAY MATCHING CSV DATASET
        // =========================================================================
        $matrixData = [
            $age,
            $q1_no,
            $q1_yes,
            $q2_21_35,
            $q2_28,
            $q2_less,
            $q2_more,
            $q2_irreg,
            $q2_not,
            $q3_no,
            $q3_yes,
            $q4_1,
            $q4_2_3,
            $q4_more,
            $q4_na,
            $q5_no,
            $q5_yes,
            $q6_no,
            $q6_yes,
            $q7_no,
            $q7_yes,
            $q8_no,
            $q8_yes,
            $q9_no,
            $q9_yes,
            $q10_no,
            $q10_yes,
            $q11_no,
            $q11_not_tested,
            $q11_yes,
            $q12_no,
            $q12_not_tested,
            $q12_yes,
            $q13_no,
            $q13_yes,
            $q14_no,
            $q14_not_tested,
            $q14_yes,
            $q15_both,
            $q15_household,
            $q15_low,
            $q15_reg,
            $q16_1_2,
            $q16_daily,
            $q16_never,
            $q16_rare,
            $q16_more,
            $q17_no,
            $q17_yes,
            $q18_6_8,
            $q18_less,
            $q18_more,
            $q19_no,
            $q19_yes,
            $q20_no,
            $q20_yes,
            $q20_not,
            $q21_no,
            $q21_yes,
            $q22_no,
            $q22_yes,
            $q22_not_tested,
            $q23_no,
            $q23_yes,
            $q24_no,
            $q24_not,
            $q24_yes,
            $q25_no,
            $q25_not,
            $q25_yes,
            $q26_no,
            $q26_not_tested,
            $q26_yes,
            $q27_no,
            $q27_not,
            $q27_yes,
            $q27_not_applicable
        ];

        if (count($matrixData) !== 77) {
            $matrixData = array_slice(array_pad($matrixData, 77, 0), 0, 77);
        }

        // =========================================================================
        // 3. EXECUTE MACHINE LEARNING INFERENCE
        // =========================================================================
        $compressedModel = file_get_contents($modelPath);
        $serializedModel = gzuncompress($compressedModel);

        $tempModelPath = __DIR__ . '/../ml/compressed_model_temp.phpml';
        file_put_contents($tempModelPath, $serializedModel);

        $modelManager = new ModelManager();
        $classifier = $modelManager->restoreFromFile($tempModelPath);

        unlink($tempModelPath);
        $prediction = $classifier->predict($matrixData);

        // --- NEW UPDATE: OFFICIAL REAL-TIME SCORE MATRIX WEIGHT CALCULATION ---
        $total_score = 0;

        // Age Point Assignment
        if ($age >= 14 && $age <= 17) $total_score += 1;
        elseif ($age >= 18 && $age <= 30) $total_score += 2;
        elseif ($age >= 31 && $age <= 40) $total_score += 1;
        elseif ($age >= 41 && $age <= 60) $total_score += 1;

        // Question Response Weights Mapping
        if (isset($_POST['q1']) && $_POST['q1'] === 'No') $total_score += 3;

        if (isset($_POST['q2'])) {
            if ($_POST['q2'] === 'Less often than 35 days') $total_score += 2;
            elseif ($_POST['q2'] === 'More than 2 months gap') $total_score += 3;
            elseif ($_POST['q2'] === 'Irregular') $total_score += 3;
            elseif ($_POST['q2'] === 'Not sure') $total_score += 1;
        }

        if (isset($_POST['q3']) && $_POST['q3'] === 'Yes') $total_score += 3;

        if (isset($_POST['q4'])) {
            if ($_POST['q4'] === '1 time') $total_score += 1;
            elseif ($_POST['q4'] === '2-3 time') $total_score += 2;
            elseif ($_POST['q4'] === 'More than 3 times') $total_score += 3;
        }

        if (isset($_POST['q5']) && $_POST['q5'] === 'Yes') $total_score += 2;
        if (isset($_POST['q6']) && $_POST['q6'] === 'Yes') $total_score += 2;
        if (isset($_POST['q7']) && $_POST['q7'] === 'Yes') $total_score += 2;
        if (isset($_POST['q8']) && $_POST['q8'] === 'Yes') $total_score += 2;
        if (isset($_POST['q9']) && $_POST['q9'] === 'Yes') $total_score += 3;
        if (isset($_POST['q10']) && $_POST['q10'] === 'Yes') $total_score += 2;
        if (isset($_POST['q11']) && $_POST['q11'] === 'Yes') $total_score += 2;
        if (isset($_POST['q12']) && $_POST['q12'] === 'Yes') $total_score += 2;
        if (isset($_POST['q13']) && $_POST['q13'] === 'Yes') $total_score += 2;
        if (isset($_POST['q14']) && $_POST['q14'] === 'Yes') $total_score += 3;

        if (isset($_POST['q15'])) {
            if ($_POST['q15'] === 'Household work') $total_score += 1;
            elseif ($_POST['q15'] === 'Low physical activity / mostly inactive') $total_score += 2;
        }

        if (isset($_POST['q16'])) {
            if ($_POST['q16'] === 'Daily / Almost daily') $total_score += 3;
            elseif ($_POST['q16'] === '1-2 times a week') $total_score += 1;
            elseif ($_POST['q16'] === 'more than 3 times/week') $total_score += 2;
        }

        if (isset($_POST['q17']) && $_POST['q17'] === 'Yes') $total_score += 2;
        // if (isset($_POST['q18']) && $_POST['q18'] === 'Less than 6 hours') $total_score += 2;
        if (isset($_POST['q18'])) {
            if ($_POST['q18'] === 'Less than 6 hours') $total_score += 2;
            elseif ($_POST['q18'] === 'More than 8 hours') $total_score += 1;
        }
        if (isset($_POST['q19']) && $_POST['q19'] === 'Yes') $total_score += 1;

        if (isset($_POST['q20'])) {
            if ($_POST['q20'] === 'Yes') $total_score += 2;
            elseif ($_POST['q20'] === 'Not sure') $total_score += 1;
        }

        if (isset($_POST['q21']) && $_POST['q21'] === 'Yes') $total_score += 1;
        if (isset($_POST['q22']) && $_POST['q22'] === 'Yes') $total_score += 2;
        if (isset($_POST['q23']) && $_POST['q23'] === 'Yes') $total_score += 3;
        if (isset($_POST['q24']) && $_POST['q24'] === 'Yes') $total_score += 1;
        if (isset($_POST['q25']) && $_POST['q25'] === 'Yes') $total_score += 3;
        if (isset($_POST['q26']) && $_POST['q26'] === 'Yes') $total_score += 3;

        if (isset($_POST['q27'])) {
            if ($_POST['q27'] === 'Yes') $total_score += 1;
            elseif ($_POST['q27'] === 'Not sure') $total_score += 1;
        }

        // Mathematical conversion to percentage against sheet max possible ceiling value (68)
        $risk_percentage = round(($total_score / 63) * 100);
        if ($risk_percentage > 100) $risk_percentage = 100;
        if ($risk_percentage < 0) $risk_percentage = 0;

        // =========================================================================
        // 4. DATABASE SEAMLESS INTEGRATION (MySQLi)
        // =========================================================================
        $db_q1  = $_POST['q1']  ?? null;
        $db_q2  = $_POST['q2']  ?? null;
        $db_q3  = $_POST['q3']  ?? null;
        $db_q4  = $_POST['q4']  ?? null;
        $db_q5  = $_POST['q5']  ?? null;
        $db_q6  = $_POST['q6']  ?? null;
        $db_q7  = $_POST['q7']  ?? null;
        $db_q8  = $_POST['q8']  ?? null;
        $db_q9  = $_POST['q9']  ?? null;
        $db_q10 = $_POST['q10'] ?? null;
        $db_q11 = $_POST['q11'] ?? null;
        $db_q12 = $_POST['q12'] ?? null;
        $db_q13 = $_POST['q13'] ?? null;
        $db_q14 = $_POST['q14'] ?? null;
        $db_q15 = $_POST['q15'] ?? null;
        $db_q16 = $_POST['q16'] ?? null;
        $db_q17 = $_POST['q17'] ?? null;
        $db_q18 = $_POST['q18'] ?? null;
        $db_q19 = $_POST['q19'] ?? null;
        $db_q20 = $_POST['q20'] ?? null;
        $db_q21 = $_POST['q21'] ?? null;
        $db_q22 = $_POST['q22'] ?? null;
        $db_q23 = $_POST['q23'] ?? null;
        $db_q24 = $_POST['q24'] ?? null;
        $db_q25 = $_POST['q25'] ?? null;
        $db_q26 = $_POST['q26'] ?? null;
        $db_q27 = $_POST['q27'] ?? null;
        $currentTimestamp = date('Y-m-d H:i:s');

        $sql = "INSERT INTO screening (
                    user_id, age, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, 
                    q11, q12, q14, q22, q13, q21, q15, q16, q17, q18, q19, 
                    q20, q23, q24, q25, q26, q27, prediction_result, risk_score, risk_percentage, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Database SQL compilation error: " . $conn->error);
        }

        // UPDATED: Now 33 total characters ("ii" + 27 questions + "s" for prediction + "ii" for score & percentage integers + "s" for timestamp)
        $bindTypes = "ii" . str_repeat("s", 27) . "siis";

        // Aligned 33 parameters perfectly to match the updated placeholders array list
        $stmt->bind_param(
            $bindTypes,
            $userId,
            $age,
            $db_q1,
            $db_q2,
            $db_q3,
            $db_q4,
            $db_q5,
            $db_q6,
            $db_q7,
            $db_q8,
            $db_q9,
            $db_q10,
            $db_q11,
            $db_q12,
            $db_q14,
            $db_q22,
            $db_q13,
            $db_q21,
            $db_q15,
            $db_q16,
            $db_q17,
            $db_q18,
            $db_q19,
            $db_q20,
            $db_q23,
            $db_q24,
            $db_q25,
            $db_q26,
            $db_q27,
            $prediction,
            $total_score,
            $risk_percentage, // Stores the percentage value directly in the row entry
            $currentTimestamp
        );
        if (!$stmt->execute()) {
            throw new Exception("Database record entry commit failure: " . $stmt->error);
        }
        $stmt->close();

        // =========================================================================
        // 5. RESPOND BACK TO JAVASCRIPT AJAX INTERFACE FOR CARD ANIMATION
        // =========================================================================
        echo json_encode(['status' => 'success', 'prediction' => $prediction, 'percentage' => $risk_percentage]);
        exit;
    } catch (\Exception $e) {
        // FIXED: Relays precise SQL execution failures straight to console log inspector fields
        echo json_encode(['status' => 'error', 'message' => 'Processing system failure: ' . $e->getMessage()]);
        exit;
    }
}
?>
<!-- need to update and check the code -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCOD360</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght=300;400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

        :root {
            --primary-pink: #ff85b3;
            --primary-purple: #a385ff;
            --completed-purple: #dcd3ff;
            --deep-plum: #4A2B63;
            --bg-gradient: linear-gradient(135deg, #fff5f8 0%, #f3efff 100%);
            --glass: rgba(255, 255, 255, 0.7);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --risk-none: #2ecc71;
            --risk-low: #f1c40f;
            --risk-moderate: #e67e22;
            --risk-high: #e74c3c;
            --plum: #3B165C;

            --subtle: #E2CEF3;
            --lilac: #CCAAE6;
            --lavender-clr: #A788DC;
            --wisteria: #9673D2;
            --thistle: #7D45C6;
            --mauve: #7E42AC;
            --orchid: #6B297C;
            --amethyst: #4F1176;

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

            --primary-pink: var(--mauve);
            --primary-purple: var(--thistle);
            --completed-purple: var(--subtle);
            --glass: rgba(255, 255, 255, 0.72);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;

        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, var(--subtle) 0%, transparent 35%),
                radial-gradient(circle at bottom right, #eadfff 0%, transparent 25%),
                #faf9ff;
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.6;
            margin: 0;
            padding: 0;
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

        /* ==========================================
   LOADING SCREEN WRAPPER & CARD
   ========================================== */
        #loadingScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;

            background:
                radial-gradient(circle at top left, var(--subtle) 0%, transparent 35%),
                radial-gradient(circle at bottom right, #eadfff 0%, transparent 25%),
                rgba(250, 249, 255, 0.96);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            display: none;
            /* Controlled via JS ('flex' when visible) */
            align-items: center;
            justify-content: center;
        }

        .loader-card {
            background: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(59, 22, 92, 0.08);
            /* Updated shadow color to match your purple palette */
            text-align: center;
            width: 90%;
            max-width: 400px;

            /* Ensure the card layout handles internal elements gracefully */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .loader-card h2 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--text);
            font-weight: 700;
        }

        .loader-card p {
            color: var(--muted);
            font-size: 0.92rem;
            margin-bottom: 20px;
        }

        /* ==========================================
   ANIMATED SPINNER
   ========================================== */
        .circular-loader {
            width: 80px;
            height: 80px;
            border: 5px solid var(--subtle);
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        /* ==========================================
   STATUS CHECKLIST SYSTEM
   ========================================== */
        .loader-status-list {
            text-align: left;
            margin-top: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            font-weight: 500;
            font-size: 0.92rem;
            transition: var(--transition);

            /* FIXED: Changed from faint #ccc to your rich palette text variables */
            color: var(--text);
            opacity: 1 !important;
        }

        .status-item i {
            /* FIXED: Ensures icon is crisp and clearly visible */
            color: var(--muted);
            opacity: 1 !important;
        }

        /* State changes when JavaScript targets an active processing frame */
        .status-item.active {
            color: var(--deep-plum);
            font-weight: 600;
        }

        .status-item.active i {
            color: var(--primary-purple);
        }

        /* Last child margin cleanup */
        .status-item:last-child {
            margin-bottom: 0;
        }

        .category-stepper {
            display: flex;
            gap: 12px;
            padding: 25px 20px;
            overflow-x: auto;
            justify-content: center;
        }

        .cat-step {
            background: rgba(255, 255, 255, 0.5);
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 0.80rem;
            font-weight: 600;
            color: #8a7ea3;
            border: 1px solid rgba(255, 255, 255, 0.5);
            white-space: nowrap;
        }

        .cat-step.active {
            background: var(--primary-purple);
            color: white;
        }

        .cat-step.completed {
            background: var(--completed-purple);
            color: var(--deep-plum);
        }

        .progress-container {
            width: 90%;
            max-width: 600px;
            margin: 10px auto;
        }

        .progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, var(--lavender-clr), var(--thistle));
            transition: width 0.4s ease;
        }

        .main-wrapper {
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border-radius: 35px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 15px 40px rgba(79, 17, 118, 0.08);
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .question-block {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .question-block.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            font-size: 1.6rem;
            margin-bottom: 30px;
            font-weight: 700;
            line-height: 1.3;
        }

        input[type="number"] {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 2px solid #ddd;
            padding: 15px 0;
            font-size: 1.4rem;
            color: var(--deep-plum);
            outline: none;
        }

        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .options-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .option-card {
            background: var(--white);
            padding: 20px;
            border-radius: 22px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: 600;
            color: var(--text);
            box-shadow: 0 6px 18px rgba(125, 69, 198, 0.06);
        }

        .option-card img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .list-option {
            background: white;
            padding: 18px 25px;
            border-radius: 20px;
            margin-bottom: 12px;
            cursor: pointer;
            border: 2px solid transparent;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .option-card:hover,
        .list-option:hover {
            border-color: var(--primary);
            background: #fbf8ff;
            transform: translateY(-3px);
        }

        .selected {
            background: linear-gradient(135deg, var(--thistle), var(--orchid)) !important;
            color: white !important;
            border-color: var(--thistle) !important;
        }

        .next-btn {
            background: #ccc;
            color: white;
            border: none;
            padding: 18px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 18px;
            cursor: pointer;
            margin-top: 30px;
            width: 100%;
        }

        .next-btn.ready {
            background: linear-gradient(135deg, var(--thistle), var(--orchid));
        }

        .action-btn {
            background: linear-gradient(135deg, var(--thistle), var(--orchid));
            color: white;
        }

        /* .nav-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 10px;
        } */
        .nav-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding: 0 10px;
            width: 100%;
            /* Ensures it spans the full card width */
        }

        .nav-left,
        .nav-right {
            display: flex;
            align-items: center;
        }

        .btn-nav {
            background: none;
            border: none;
            font-weight: 600;
            cursor: pointer;
            color: var(--primary-purple);
        }

        #resultWrapper {
            max-width: 1100px;
            width: 95%;
            margin: 40px auto;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        #resultWrapper.show {
            display: block;
            opacity: 1;
        }

        .result-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 35px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 20px 50px rgba(74, 43, 99, 0.05);
            text-align: center;
            margin-bottom: 30px;
        }

        .icon-circle {
            background: #f3e5f5;
            color: var(--primary-purple);
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .subtitle {
            color: var(--deep-plum);
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.8;
        }

        .recommendation-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .reco-item {
            background: white;
            padding: 25px;
            border-radius: 20px;
            border-left: 5px solid transparent;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
            text-align: left;
            transition: var(--transition);
        }

        .reco-item:hover {
            transform: translateY(-5px);
        }

        .reco-item.pink {
            border-left-color: var(--primary-pink);
        }

        .reco-item.pink i {
            color: var(--primary-pink);
        }

        .reco-item.purple {
            border-left-color: var(--primary-purple);
        }

        .reco-item.purple i {
            color: var(--primary-purple);
        }

        .reco-item h3 {
            margin-bottom: 10px;
            font-size: 1.1rem;
            color: var(--deep-plum);
        }

        .reco-item p {
            font-size: 0.9rem;
            color: #636e72;
            line-height: 1.5;
        }

        .disclaimer {
            font-size: 13px;
            color: #856404;
            background: #fff9db;
            border: 1px solid #f1c40f;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            line-height: 1.4;
        }

        .action-btn {
            background: var(--deep-plum);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Outfit';
        }

        .age-error {
            margin-top: 12px;
            color: #e74c3c;
            background: #ffeaea;
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 500;
            display: none;
        }

        #statusMessage {
            display: none;
            margin-top: 15px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        #statusMessage.error {
            background: #ffe5e5;
            color: #d93025;
            border: 1px solid #f5b5b5;
        }

        @media (max-width: 600px) {

            .options-grid,
            .recommendation-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Container for Action Buttons */
        .result-actions {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Base Styles for Both Buttons */
        .result-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 26px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            font-family: inherit;
            transition: var(--transition);
            outline: none;
            text-decoration: none;
        }

        /* Primary Button Style - Go to Track Page */
        .result-actions .btn-primary {
            background: var(--btn-gradient);
            color: var(--white);
            border: 1px solid transparent;
            box-shadow: 0 4px 15px rgba(125, 69, 198, 0.2);
            /* Soft shadow tint based on --thistle */
        }

        /* Primary Button Hover State */
        .result-actions .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
            opacity: 0.95;
            /* Subtle smooth blend effect on hover */
        }

        /* Secondary Button Style - Retake Test */
        .result-actions .btn-secondary {
            background-color: transparent;
            color: var(--muted);
            border: 1.5px solid var(--border);
        }

        /* Secondary Button Hover State */
        .result-actions .btn-secondary:hover {
            background-color: var(--light-purple);
            color: var(--text);
            border-color: var(--lavender-clr);
            transform: translateY(-2px);
        }

        /* Subtle Active Click Feedback for Both Buttons */
        .result-actions .btn:active {
            transform: translateY(0);
        }

        /* Icon Interactivity Styles */
        .result-actions .btn i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        /* Rotate back-arrow icon slightly on hover */
        .result-actions .btn-secondary:hover i {
            transform: rotate(-45deg);
        }

        /* Move charts icon forward slightly on hover */
        .result-actions .btn-primary:hover i {
            transform: translateX(3px);
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 480px) {
            .result-actions {
                flex-direction: column;
                gap: 12px;
            }

            .result-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- <div class="container"> -->
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

    <div class="container">
        <div id="ui-stepper-container">
            <div class="category-stepper" id="stepper">
                <div class="cat-step active">About You</div>
                <div class="cat-step">Menstrual History</div>
                <div class="cat-step">Body Changes</div>
                <div class="cat-step">Medical & Hormonal</div>
                <div class="cat-step">Energy & Well-being</div>
                <div class="cat-step">Lifestyle & Habits</div>
                <div class="cat-step">Family History</div>
                <div class="cat-step">Reproductive & Fertility</div>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="fill"></div>
                </div>
            </div>
        </div>

        <div class="main-wrapper">
            <div class="glass-card" id="qWrapper">

                <div class="question-block" data-cat="0" id="block-age">
                    <h2>How old are you?</h2>
                    <input type="number" id="input-age" placeholder="Age" min="14" max="60" oninput="handleAgeInput(this)">
                    <div class="age-error" style="display:none;"> Please enter age between 14 and 60. </div>
                    <button class="next-btn" onclick="nextQuestion()">Continue</button>
                </div>

                <div class="question-block" data-cat="1" data-q="Q1">
                    <h2>Do you get your periods regularly every month?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="https://cdn-icons-png.flaticon.com/512/5290/5290058.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="1" data-q="Q2">
                    <h2>How often do you usually get your periods?</h2>
                    <div class="list-container" style="margin-top: 20px;">
                        <div class="list-option" onclick="selectOption(this, 'Every 28 days')">Every 28 days</div>
                        <div class="list-option" onclick="selectOption(this, 'Every 21-35 days')">Every 21-35 days</div>
                        <div class="list-option" onclick="selectOption(this, 'Less often than 35 days')">Less often than 35 days</div>
                        <div class="list-option" onclick="selectOption(this, 'More than 2 months gap')">More than 2 months gap</div>
                        <div class="list-option" onclick="selectOption(this, 'Irregular')">Irregular</div>
                        <div class="list-option" onclick="selectOption(this, 'Not sure')">Not sure</div>
                    </div>
                </div>

                <div class="question-block" data-cat="1" data-q="Q3">
                    <h2>Have you missed your periods for 2 months or more in the past year?(Not applicable for pregnant women)</h2>
                    <div class="options-grid">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <span>Yes</span>
                            <img src="../assets/images/Yes2.png" class="option-img" alt="Yes">
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <span>No</span>
                            <img src="../assets/images/No5.png" class="option-img" alt="No">
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="1" data-q="Q4">
                    <h2>If yes, how many times have you missed your periods for 2 months or more in the past year?</h2>
                    <div class="list-container" style="margin-top: 20px;">
                        <div class="list-option" onclick="selectOption(this, '1 time')">1 time</div>
                        <div class="list-option" onclick="selectOption(this, '2-3 time')">2-3 time</div>
                        <div class="list-option" onclick="selectOption(this, 'More than 3 times')">More than 3 times</div>
                        <div class="list-option" onclick="selectOption(this, 'Not Applicable')">Not applicable</div>
                    </div>
                </div>

                <div class="question-block" data-cat="1" data-q="Q5">
                    <h2>Do you experience very heavy or prolonged bleeding during periods?</h2>
                    <div class="options-grid">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <span>Yes</span>
                            <img src="../assets/images/Yes2.png" class="option-img" alt="Yes">
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <span>No</span>
                            <img src="../assets/images/No5.png" class="option-img" alt="No">
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="2" data-q="Q6">
                    <h2>Have you noticed sudden or excessive weight gain?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="2" data-q="Q7">
                    <h2>Do you find it difficult to lose weight even with effort?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="2" data-q="Q8">
                    <h2>Do you have acne or oily skin beyond teenage years?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="2" data-q="Q9">
                    <h2>Do you notice excess hair growth on the face, chest, or abdomen?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="2" data-q="Q10">
                    <h2>Have you experienced hair thinning or hair fall?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="3" data-q="Q11">
                    <h2>Have you ever been told by a doctor that you have a hormonal imbalance?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not tested')">
                            <img src="../assets/images/Not tested3.png" alt="No">
                            <span>Not tested</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="3" data-q="Q12">
                    <h2>Have you been diagnosed with insulin resistance or high blood sugar?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not tested')">
                            <img src="../assets/images/Not tested3.png" alt="No">
                            <span>Not tested</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="3" data-q="Q14">
                    <h2>Have you undergone ultrasound and been told you have cysts on ovaries?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not tested')">
                            <img src="../assets/images/Not tested3.png" alt="No">
                            <span>Not tested</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="3" data-q="Q22">
                    <h2>Have you been diagnosed with thyroid problems?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not tested')" data-score="0">
                            <img src="../assets/images/Not tested3.png" alt="No">
                            <span>Not tested</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="4" data-q="Q13">
                    <h2>Do you often feel tired or low in energy without clear reason?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="4" data-q="Q21">
                    <h2>Do you have a family history of diabetes?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="5" data-q="Q15">
                    <h2>How would you describe your daily physical activity?</h2>
                    <div class="options-grid">
                        <div class="option-card" onclick="selectOption(this, 'Regular exercise')" data-score="0">
                            <span>Regular Exercise</span>

                            <img src="../assets/images/Exercise3.png" class="option-img" alt="Exercise">
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Household work')" data-score="1">
                            <span>Household work</span>
                            <img src="../assets/images/Household2.png" class="option-img" alt="Household">

                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Both exercise and household work')" data-score="0">
                            <span>Both exercise and household work</span>
                            <img src="../assets/images/Both3.png" class="option-img" alt="Both">

                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Low physical activity / mostly inactive')" data-score="2">
                            <span>Low physical activity/mostly inactive</span>
                            <img src="../assets/images/Low activity2.png" class="option-img" alt="Low activity">

                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="5" data-q="Q16">
                    <h2>How often do you consume fast food or processed food?</h2>
                    <div class="list-container" style="margin-top: 20px;">
                        <div class="list-option" onclick="selectOption(this, 'Rarely')" data-score="0">Rarely</div>
                        <div class="list-option" onclick="selectOption(this, '1-2 times a week')" data-score="1">1-2 times per week</div>
                        <div class="list-option" onclick="selectOption(this, 'more than 3 times/week')" data-score="2">More than 3 times per week </div>
                        <div class="list-option" onclick="selectOption(this, 'never')" data-score="0">Never</div>
                        <div class="list-option" onclick="selectOption(this, 'Daily / Almost daily')" data-score="3">Daily</div>
                    </div>
                </div>

                <div class="question-block" data-cat="5" data-q="Q17">
                    <h2>Do you experience frequent stress or anxiety?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="2">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="5" data-q="Q18">
                    <h2>How many hours do you usually sleep per day?</h2>
                    <div class="list-container" style="margin-top: 20px;">
                        <div class="list-option" onclick="selectOption(this, 'Less than 6 hours')" data-score="2">Less than 6 hours</div>
                        <div class="list-option" onclick="selectOption(this, '6-8 hours')" data-score="0">6-8 hours</div>
                        <div class="list-option" onclick="selectOption(this, 'More than 8 hours')" data-score="1">More than 8 hours</div>
                    </div>
                </div>

                <div class="question-block" data-cat="5" data-q="Q19">
                    <h2>Do you feel your sleep is disturbed or not refreshing?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="1">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="6" data-q="Q20">
                    <h2>Has anyone in your family (mother or sister) been diagnosed with PCOD?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="2">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not sure')" data-score="0">
                            <img src="../assets/images/not applicable2.png" alt="Not sure">
                            <span>Not sure</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="7" data-q="Q23">
                    <h2>Are you currently on long-term medication for hormonal or metabolic issues?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="2">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="7" data-q="Q24">
                    <h2>Are you currently trying to conceive?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="1">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not applicable')" data-score="0">
                            <img src="../assets/images/not applicable2.png" alt="Not sure">
                            <span>Not applicable</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="7" data-q="Q25">
                    <h2>Have you faced difficulty in getting pregnant for more than 1 year?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="3">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not applicable')" data-score="0">
                            <img src="../assets/images/not applicable2.png" alt="Not sure">
                            <span>Not applicable</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="7" data-q="Q26">
                    <h2>Have you ever been told that you have ovulation problems?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="3">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not tested')" data-score="0">
                            <img src="../assets/images/Not tested3.png" alt="Not tested">
                            <span>Not tested</span>
                        </div>
                    </div>
                </div>

                <div class="question-block" data-cat="7" data-q="Q27">
                    <h2>Are you worried about future fertility due to menstrual or hormonal issues?</h2>
                    <div class="options-container">
                        <div class="option-card" onclick="selectOption(this, 'Yes')" data-score="1">
                            <img src="../assets/images/Yes2.png" alt="Yes">
                            <span>Yes</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'No')" data-score="0">
                            <img src="../assets/images/No5.png" alt="No">
                            <span>No</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not sure')" data-score="1">
                            <img src="../assets/images/Not sure2.png" alt="Not sure">
                            <span>Not sure</span>
                        </div>
                        <div class="option-card" onclick="selectOption(this, 'Not applicable')" data-score="0">
                            <img src="../assets/images/not applicable2.png" alt="Not sure">
                            <span>Not applicable</span>
                        </div>
                    </div>
                    <button class="next-btn" id="finalSubmitBtn" style="display:none; margin-top:25px;" onclick="triggerMLProcessing()">Submit Screening</button>
                </div>

                <div class="nav-footer" id="navFooter">
                    <div class="nav-left">
                        <button class="btn-nav" id="backBtn" onclick="prevQuestion()" style="display: none;">← PREVIOUS</button>
                        <a href="home.php" class="btn-nav" id="exitBtnLeft" style="color: #ff6b6b; text-decoration:none;">EXIT ✕</a>
                    </div>

                    <div class="nav-right">
                        <a href="home.php" class="btn-nav" id="exitBtnRight" style="color: #ff6b6b; text-decoration:none; display: none;">EXIT ✕</a>
                    </div>
                </div>
                <div id="statusMessage"></div>
            </div>
        </div>

        <div id="resultWrapper" style="display: none;">
            <div class="result-card">
                <div class="icon-circle" id="resIconCircle">
                    <i class="fas fa-shield-alt" id="resIcon"></i>
                </div>

                <h1 id="resTitle">Assessment Done</h1>
                <p class="subtitle" id="resDesc">Processing machine model algorithms response mapping targets details...</p>

                <div class="gauge-wrapper" style="margin: 35px auto 25px auto; width: 180px; height: 180px; position: relative; display: flex; align-items: center; justify-content: center;">
                    <svg width="180" height="180" viewBox="0 0 180 180" style="transform: rotate(-90deg); width: 100%; height: 100%;">
                        <circle cx="90" cy="90" r="75" stroke="#f3efff" stroke-width="14" fill="transparent" />
                        <circle class="gauge-fill" cx="90" cy="90" r="75" stroke="var(--mauve, #7b2cbf)" stroke-width="14" fill="transparent"
                            stroke-dasharray="471.2" stroke-dashoffset="471.2" stroke-linecap="round"
                            style="transition: stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1), stroke 0.4s ease;" />
                    </svg>

                    <div class="gauge-data" style="position: absolute; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; width: 100%; height: 100%; top: 0; left: 0;">
                        <span style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.2px; color: var(--muted, #6b5a82); font-weight: 700; margin-bottom: 2px;">Risk Level</span>
                        <span id="riskPercentageLabel" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.6rem; font-weight: 800; color: var(--deep-plum, #4A2B63); line-height: 1;">0%</span>
                    </div>
                </div>
                <div class="recommendation-grid">
                    <div class="reco-item pink">
                        <i class="fas fa-apple-alt" style="font-size:24px; margin-bottom:15px;"></i>
                        <h3>Balanced Nutrition</h3>
                        <p>Choose nutritious meals with plenty of vegetables, fruits, whole grains, and lean proteins.</p>
                    </div>
                    <div class="reco-item purple">
                        <i class="fas fa-running" style="font-size:24px; margin-bottom:15px;"></i>
                        <h3>Active Lifestyle</h3>
                        <p>Stay physically active with regular walking, cycling, or other moderate exercises.</p>
                    </div>
                </div>

                <div class="disclaimer">
                    <strong>Disclaimer:</strong> This assessment tool provides indications based on patterns learned by our machine learning model. It does not replace professional clinical evaluation or comprehensive laboratory medical diagnosis.
                </div>

                <div class="result-actions" style="display: flex; gap: 15px; margin-top: 30px; justify-content: center;">
                    <button id="retakeBtn" class="btn btn-secondary" onclick="resetAndRetakeQuiz()" style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; cursor: pointer;">
                        <i class="fas fa-undo"></i> Retake Test
                    </button>
                    <button id="trackBtn" class="btn btn-primary" onclick="goToTrackPage()" style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; cursor: pointer;">
                        <i class="fas fa-chart-line"></i> Go to Track Page
                    </button>
                </div>
            </div>
        </div>
        <div id="loadingScreen">
            <div class="loader-card">
                <div class="circular-loader"></div>

                <h2>Processing Your Screening</h2>
                <p>Please wait a moment while we process your responses.</p>

                <ul class="loader-status-list">
                    <li class="status-item active" id="status-0">
                        <i class="fas fa-circle-notch fa-spin"></i> Checking your responses...
                    </li>
                    <li class="status-item" id="status-1">
                        <i class="fas fa-circle-notch"></i> Analyzing your information...
                    </li>
                    <li class="status-item" id="status-2">
                        <i class="fas fa-circle-notch"></i> Generating your results...
                    </li>
                </ul>
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

            let currentBlockIdx = 0;
            const blocks = document.querySelectorAll('.question-block');
            const steps = document.querySelectorAll('.cat-step');
            const fill = document.getElementById('fill');
            const btnBack = document.getElementById('btnBack');

            let userAnswers = {
                age: null,
                q1: null,
                q2: null,
                q3: null,
                q4: null,
                q5: null,
                q6: null,
                q7: null,
                q8: null,
                q9: null,
                q10: null,
                q11: null,
                q12: null,
                q14: null,
                q22: null,
                q13: null,
                q21: null,
                q15: null,
                q16: null,
                q17: null,
                q18: null,
                q19: null,
                q20: null,
                q23: null,
                q24: null,
                q25: null,
                q26: null,
                q27: null
            };

            // function toggleProfileDropdown() {
            //     document.getElementById('profileDropdown').classList.toggle('show');
            // }

            window.onclick = function(event) {
                if (!event.target.closest('.profile-container')) {
                    const drop = document.getElementById('profileDropdown');
                    if (drop) drop.classList.remove('show');
                }
            }

            function initScreening() {
                blocks.forEach((b, idx) => {
                    b.classList.toggle('active', idx === 0);
                });
                updateProgress();
            }

            function handleAgeInput(input) {
                const val = parseInt(input.value);
                const nextBtn = input.nextElementSibling.nextElementSibling;
                const errDiv = input.nextElementSibling;

                if (val >= 14 && val <= 60) {
                    userAnswers.age = val;
                    nextBtn.classList.add('ready');
                    errDiv.style.display = 'none';
                } else {
                    userAnswers.age = null;
                    nextBtn.classList.remove('ready');
                    if (input.value.length > 0) errDiv.style.display = 'block';
                }
            }

            function selectOption(element, val) {
                const block = element.closest('.question-block');
                const qKey = block.getAttribute('data-q').toLowerCase();

                block.querySelectorAll('.option-card, .list-option').forEach(el => el.classList.remove('selected'));
                element.classList.add('selected');

                userAnswers[qKey] = val;

                if (currentBlockIdx < blocks.length - 1) {
                    setTimeout(nextQuestion, 350);
                } else {
                    const fBtn = document.getElementById('finalSubmitBtn');
                    if (fBtn) {
                        fBtn.style.display = 'block';
                        fBtn.classList.add('ready');
                    }
                }
            }

            function nextQuestion() {
                if (currentBlockIdx === 0 && !userAnswers.age) {
                    document.querySelector('.age-error').style.display = 'block';
                    return;
                }
                if (currentBlockIdx < blocks.length - 1) {
                    blocks[currentBlockIdx].classList.remove('active');
                    currentBlockIdx++;
                    blocks[currentBlockIdx].classList.add('active');
                    updateProgress();
                }
            }

            function prevQuestion() {
                if (currentBlockIdx > 0) {
                    blocks[currentBlockIdx].classList.remove('active');
                    currentBlockIdx--;
                    blocks[currentBlockIdx].classList.add('active');
                    updateProgress();
                }
            }

            const backBtn = document.getElementById('backBtn');
            const exitBtnLeft = document.getElementById('exitBtnLeft');
            const exitBtnRight = document.getElementById('exitBtnRight');

            function updateProgress() {
                if (currentBlockIdx === 0) {
                    if (backBtn) backBtn.style.display = 'none';
                    if (exitBtnLeft) exitBtnLeft.style.display = 'block';
                    if (exitBtnRight) exitBtnRight.style.display = 'none';
                } else {
                    if (backBtn) backBtn.style.display = 'block';
                    if (exitBtnLeft) exitBtnLeft.style.display = 'none';
                    if (exitBtnRight) exitBtnRight.style.display = 'block';
                }

                const currentBlock = blocks[currentBlockIdx];
                if (!currentBlock) return;

                const catId = parseInt(currentBlock.getAttribute('data-cat'), 10);
                steps.forEach((st, idx) => {
                    st.className = 'cat-step';
                    if (idx < catId) st.classList.add('completed');
                    if (idx === catId) st.classList.add('active');
                });

                const totalSteps = blocks.length - 1;
                const pct = totalSteps > 0 ? (currentBlockIdx / totalSteps) * 100 : 0;
                if (typeof fill !== 'undefined' && fill) fill.style.width = `${pct}%`;
            }

            function triggerMLProcessing() {
                const loader = document.getElementById('loadingScreen');
                loader.style.display = 'flex';

                setTimeout(() => {
                    document.getElementById('status-0').innerHTML = '<i class="fas fa-check-circle" style="color:var(--risk-none, #388e3c)"></i> Checking your responses...';
                    const s1 = document.getElementById('status-1');
                    if (s1) {
                        s1.classList.add('active');
                        s1.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Analyzing your information...';
                    }
                }, 1200);

                setTimeout(() => {
                    document.getElementById('status-1').innerHTML = '<i class="fas fa-check-circle" style="color:var(--risk-none, #388e3c)"></i> Analyzing your information...';
                    const s2 = document.getElementById('status-2');
                    if (s2) {
                        s2.classList.add('active');
                        s2.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Generating your results...';
                    }
                }, 2400);

                setTimeout(() => {
                    document.getElementById('status-2').innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Generating your results... ';
                    sendDataToBackendML();
                }, 3500);
            }

            function sendDataToBackendML() {
                document.getElementById('loadingScreen').style.display = 'flex';

                const formData = new FormData();
                formData.append('action', 'submit_screening');
                for (const key in userAnswers) {
                    formData.append(key, userAnswers[key]);
                }

                fetch('screening.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("HTTP error, status code: " + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('loadingScreen').style.display = 'none';

                        if (data.status === 'success') {
                            const currentUserId = "<?php echo $_SESSION['user']['id']; ?>";
                            localStorage.setItem('saved_prediction_user_' + currentUserId, data.prediction);
                            localStorage.setItem('saved_percentage_user_' + currentUserId, data.percentage);

                            showMLResultCard(data.prediction, data.percentage);
                        } else {
                            throw new Error(data.message || "Unknown backend processing error.");
                        }
                    })
                    .catch(err => {
                        document.getElementById('loadingScreen').style.display = 'none';
                        alert("Error running screening model analysis. Check console log details.");
                        console.error(err);
                    });
            }


            function showMLResultCard(prediction, percentage) {
                // Hide the question quiz blocks and stepper indicators cleanly
                if (document.getElementById('ui-stepper-container')) {
                    document.getElementById('ui-stepper-container').style.display = 'none';
                }
                if (document.getElementById('qWrapper')) {
                    document.getElementById('qWrapper').style.display = 'none';
                }

                const wrapper = document.getElementById('resultWrapper');
                const iconCircle = document.getElementById('resIconCircle');
                const icon = document.getElementById('resIcon');
                const title = document.getElementById('resTitle');
                const desc = document.getElementById('resDesc');

                // Core DOM hooks for the new SVG Circular Progress Ring graphic elements
                const gaugeFill = document.querySelector('.gauge-fill');
                const percentageLabel = document.getElementById('riskPercentageLabel');

                if (!wrapper) return;
                wrapper.style.display = 'block';
                wrapper.classList.add('show');

                const riskLevel = prediction.trim().toLowerCase();
                let gaugeColor = "var(--mauve, #7b2cbf)"; // Fallback theme color configuration

                // Determine status, icons, context text descriptions, and colors dynamically
                if (riskLevel === "high") {
                    title.innerText = "High Risk Detected";
                    title.style.color = "var(--risk-high, #d32f2f)";
                    icon.className = "fas fa-exclamation-circle";
                    if (iconCircle) {
                        iconCircle.style.background = "#ffebee";
                        iconCircle.style.color = "var(--risk-high, #d32f2f)";
                    }
                    desc.innerText = "Your screening results suggest that you may be at higher risk. We recommend consulting a healthcare professional for further evaluation.";
                    gaugeColor = "var(--risk-high, #d32f2f)"; // 🔴 Crimson Alert Red
                } else if (riskLevel === "moderate") {
                    title.innerText = "Moderate Risk Detected";
                    title.style.color = "var(--risk-moderate, #f57c00)";
                    icon.className = "fas fa-exclamation-triangle";
                    if (iconCircle) {
                        iconCircle.style.background = "#fff3e0";
                        iconCircle.style.color = "var(--risk-moderate, #f57c00)";
                    }
                    desc.innerText = "Your screening results indicate moderate risk. Maintaining healthy habits and consulting a healthcare professional can help.";
                    gaugeColor = "var(--risk-moderate, #f57c00)"; // 🟡 Warnings Orange
                } else if (riskLevel === "low") {
                    title.innerText = "Low Risk Detected";
                    title.style.color = "var(--risk-low, #fbc02d)";
                    icon.className = "fas fa-info-circle";
                    if (iconCircle) {
                        iconCircle.style.background = "#fffde7";
                        iconCircle.style.color = "var(--risk-low, #fbc02d)";
                    }
                    desc.innerText = "Your screening results indicate a low risk based on the information provided. Continue maintaining healthy habits.";
                    gaugeColor = "var(--risk-low, #fbc02d)"; // 🟡 Amber/Yellow
                } else {
                    title.innerText = "No Significant Risk";
                    title.style.color = "var(--risk-none, #388e3c)";
                    icon.className = "fas fa-check-circle";
                    if (iconCircle) {
                        iconCircle.style.background = "#e8f5e9";
                        iconCircle.style.color = "var(--risk-none, #388e3c)";
                    }
                    desc.innerText = "Your screening results indicate no significant risk. Continue maintaining a healthy lifestyle.";
                    gaugeColor = "var(--risk-none, #388e3c)"; // 🟢 Healthy Green
                }

                // =========================================================================
                // ANIMATION SYSTEM: SVG DASH PATH OFFSET + NUMERIC TICK INCREMENTER
                // =========================================================================
                const percentageValue = parseInt(percentage) || 0;

                // 1. Update the color of the SVG ring stroke path and animate the dash line fill
                if (gaugeFill) {
                    gaugeFill.style.stroke = gaugeColor;

                    // Circumference of our SVG circle path formula is: 2 * PI * r (2 * 3.14159 * 75 = ~471.2)
                    const circumference = 471.2;
                    const offset = circumference - (percentageValue / 100) * circumference;

                    // Triggers structural smooth transition delay
                    setTimeout(() => {
                        gaugeFill.style.strokeDashoffset = offset;
                    }, 150);
                }

                // 2. Incremental progressive counter animation looping up from 0% to target value
                if (percentageLabel) {
                    let startCount = 0;
                    const totalDuration = 1200; // Animation lifecycle completion run speed window in ms (1.2 seconds)

                    // Determine layout pacing separation slices based on calculated value metrics
                    const stepTime = percentageValue > 0 ? Math.floor(totalDuration / percentageValue) : 25;

                    // Clear any lingering asynchronous counter loops to prevent race performance bugs
                    if (window.gaugeCounterInterval) {
                        clearInterval(window.gaugeCounterInterval);
                    }

                    if (percentageValue === 0) {
                        percentageLabel.innerText = "0%";
                    } else {
                        window.gaugeCounterInterval = setInterval(() => {
                            startCount++;
                            percentageLabel.innerText = startCount + "%";

                            if (startCount >= percentageValue) {
                                clearInterval(window.gaugeCounterInterval);
                            }
                        }, Math.max(stepTime, 10)); // Caps interval processing execution threshold safely
                    }
                }
            }

            function goToTrackPage() {
                window.location.href = 'track.php';
            }

            function resetAndRetakeQuiz() {
                const currentUserId = "<?php echo $_SESSION['user']['id']; ?>";
                localStorage.removeItem('saved_prediction_user_' + currentUserId);
                localStorage.removeItem('saved_percentage_user_' + currentUserId);

                for (const key in userAnswers) {
                    userAnswers[key] = null;
                }

                document.getElementById('resultWrapper').style.display = 'none';
                document.getElementById('resultWrapper').classList.remove('show');

                if (document.getElementById('ui-stepper-container')) {
                    document.getElementById('ui-stepper-container').style.display = 'block';
                }
                if (document.getElementById('qWrapper')) {
                    document.getElementById('qWrapper').style.display = 'block';
                }

                currentBlockIdx = 0;
                initScreening();
            }

            // Clean database check alignment block
            window.onload = function() {
                const currentUserId = "<?php echo $_SESSION['user']['id']; ?>";
                const storageKeyPred = 'saved_prediction_user_' + currentUserId;
                const storageKeyPct = 'saved_percentage_user_' + currentUserId;

                <?php
                // UPDATED: Now selecting 'risk_percentage' directly from your database
                $check_query = "SELECT prediction_result, risk_percentage FROM screening WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
                $check_stmt = $conn->prepare($check_query);
                $db_has_record = false;
                $db_prediction = '';
                $db_percentage = 0;

                if ($check_stmt) {
                    // FIXED: Using the clean explicit session ID variable directly here
                    $check_stmt->bind_param("i", $_SESSION['user']['id']);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    if ($row = $check_result->fetch_assoc()) {
                        $db_has_record = true;
                        $db_prediction = $row['prediction_result'];
                        // UPDATED: Reads the exact stored percentage directly from your database row column
                        $db_percentage = isset($row['risk_percentage']) ? intval($row['risk_percentage']) : 0;
                    }
                    $check_stmt->close();
                }
                ?>

                const userHasDatabaseRecord = <?php echo $db_has_record ? 'true' : 'false'; ?>;
                const serverPredictionValue = "<?php echo trim($db_prediction); ?>";
                const serverPercentageValue = <?php echo $db_percentage; ?>;

                if (userHasDatabaseRecord && serverPredictionValue !== '') {
                    // Account matches real database files -> load safely with stored values
                    localStorage.setItem(storageKeyPred, serverPredictionValue);
                    localStorage.setItem(storageKeyPct, serverPercentageValue);
                    showMLResultCard(serverPredictionValue, serverPercentageValue);
                } else {
                    // Brand new user profile -> clear stray cache data and load questions cleanly
                    localStorage.removeItem(storageKeyPred);
                    localStorage.removeItem(storageKeyPct);

                    if (document.getElementById('resultWrapper')) {
                        document.getElementById('resultWrapper').style.display = 'none';
                    }
                    initScreening();
                }
            };
        </script>
</body>

</html>