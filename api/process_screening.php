<?php
// process_screening.php - Handles user quiz submissions instantly!
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Official standard Composer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Phpml\ModelManager;

try {
    $modelPath = __DIR__ . '/../ml/pcod_model.phpml';
    if (!file_exists($modelPath)) {
        throw new Exception("Model engine not found! Run train_model.php first.");
    }

    // 1. Check if the form was actually submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // 2. Restore the pre-compiled trained logic instantly from disk
        $modelManager = new ModelManager();
        $classifier = $modelManager->restoreFromFile($modelPath);

        // 3. Map your form's $_POST data into the EXACT order of your 76 CSV columns
        // Replace 'age', 'q1', 'q2' with the actual 'name' attributes from your HTML input fields
        $userAnswers = [
    isset($_POST['age']) ? (int)$_POST['age'] : 0,                                             // Column A: Age

    isset($_POST['q1_no']) ? (int)$_POST['q1_no'] : 0,                                         // Column B: Q1 No
    isset($_POST['q1_yes']) ? (int)$_POST['q1_yes'] : 0,                                       // Column C: Q1 Yes

    isset($_POST['q2_every_21_35_days']) ? (int)$_POST['q2_every_21_35_days'] : 0,             // Column D: Q2 Every 21-35 days
    isset($_POST['q2_every_28_days']) ? (int)$_POST['q2_every_28_days'] : 0,                   // Column E: Q2 Every 28 days
    isset($_POST['q2_irregular']) ? (int)$_POST['q2_irregular'] : 0,                           // Column F: Q2 Irregular
    isset($_POST['q2_less_often_than_35_days']) ? (int)$_POST['q2_less_often_than_35_days'] : 0, // Column G: Q2 Less often than 35 days
    isset($_POST['q2_more_than_2_months_gap']) ? (int)$_POST['q2_more_than_2_months_gap'] : 0,   // Column H: Q2 More than 2 months gap
    isset($_POST['q2_not_sure']) ? (int)$_POST['q2_not_sure'] : 0,                             // Column I: Q2 Not sure

    isset($_POST['q3_no']) ? (int)$_POST['q3_no'] : 0,                                         // Column J: Q3 No
    isset($_POST['q3_yes']) ? (int)$_POST['q3_yes'] : 0,                                       // Column K: Q3 Yes

    isset($_POST['q4_1_time']) ? (int)$_POST['q4_1_time'] : 0,                                 // Column L: Q4 1 time
    isset($_POST['q4_2_3_times']) ? (int)$_POST['q4_2_3_times'] : 0,                           // Column M: Q4 2–3 times
    isset($_POST['q4_more_than_3_times']) ? (int)$_POST['q4_more_than_3_times'] : 0,           // Column N: Q4 More than 3 times
    isset($_POST['q4_not_applicable']) ? (int)$_POST['q4_not_applicable'] : 0,                 // Column O: Q4 Not Applicable

    isset($_POST['q5_no']) ? (int)$_POST['q5_no'] : 0,                                         // Column P: Q5 No
    isset($_POST['q5_yes']) ? (int)$_POST['q5_yes'] : 0,                                       // Column Q: Q5 Yes

    isset($_POST['q6_no']) ? (int)$_POST['q6_no'] : 0,                                         // Column R: Q6 No
    isset($_POST['q6_yes']) ? (int)$_POST['q6_yes'] : 0,                                       // Column S: Q6 Yes

    isset($_POST['q7_no']) ? (int)$_POST['q7_no'] : 0,                                         // Column T: Q7 No
    isset($_POST['q7_yes']) ? (int)$_POST['q7_yes'] : 0,                                       // Column U: Q7 Yes

    isset($_POST['q8_no']) ? (int)$_POST['q8_no'] : 0,                                         // Column V: Q8 No
    isset($_POST['q8_yes']) ? (int)$_POST['q8_yes'] : 0,                                       // Column W: Q8 Yes

    isset($_POST['q9_no']) ? (int)$_POST['q9_no'] : 0,                                         // Column X: Q9 No
    isset($_POST['q9_yes']) ? (int)$_POST['q9_yes'] : 0,                                       // Column Y: Q9 Yes

    isset($_POST['q10_no']) ? (int)$_POST['q10_no'] : 0,                                       // Column Z: Q10 No
    isset($_POST['q10_yes']) ? (int)$_POST['q10_yes'] : 0,                                     // Column AA: Q10 Yes

    isset($_POST['q11_no']) ? (int)$_POST['q11_no'] : 0,                                       // Column AB: Q11 No
    isset($_POST['q11_not_tested']) ? (int)$_POST['q11_not_tested'] : 0,                       // Column AC: Q11 Not tested
    isset($_POST['q11_yes']) ? (int)$_POST['q11_yes'] : 0,                                     // Column AD: Q11 Yes

    isset($_POST['q12_no']) ? (int)$_POST['q12_no'] : 0,                                       // Column AE: Q12 No
    isset($_POST['q12_not_tested']) ? (int)$_POST['q12_not_tested'] : 0,                       // Column AF: Q12 Not tested
    isset($_POST['q12_yes']) ? (int)$_POST['q12_yes'] : 0,                                     // Column AG: Q12 Yes

    // [MOVED HERE] Q13 must execute right after Q12 to match your CSV Columns AH & AI
    isset($_POST['q13_no']) ? (int)$_POST['q13_no'] : 0,                                       // Column AH: Q13 No
    isset($_POST['q13_yes']) ? (int)$_POST['q13_yes'] : 0,                                     // Column AI: Q13 Yes

    // [MOVED HERE] Q14 handles Columns AJ, AK, AL
    isset($_POST['q14_no']) ? (int)$_POST['q14_no'] : 0,                                       // Column AJ: Q14 No
    isset($_POST['q14_not_done']) ? (int)$_POST['q14_not_done'] : 0,                           // Column AK: Q14 Not done
    isset($_POST['q14_yes']) ? (int)$_POST['q14_yes'] : 0,                                     // Column AL: Q14 Yes

    isset($_POST['q15_both_exercise_and_household_work']) ? (int)$_POST['q15_both_exercise_and_household_work'] : 0,                   // Column AM
    isset($_POST['q15_household_work_cleaning_mopping_cooking_daily_chore']) ? (int)$_POST['q15_household_work_cleaning_mopping_cooking_daily_chore'] : 0, // Column AN
    isset($_POST['q15_low_physical_activity_mostly_inactive']) ? (int)$_POST['q15_low_physical_activity_mostly_inactive'] : 0,         // Column AO
    isset($_POST['q15_regular_exercise_gym_yoga_walking_sports']) ? (int)$_POST['q15_regular_exercise_gym_yoga_walking_sports'] : 0,   // Column AP

    isset($_POST['q16_1_2_times_week']) ? (int)$_POST['q16_1_2_times_week'] : 0,               // Column AQ
    isset($_POST['q16_daily']) ? (int)$_POST['q16_daily'] : 0,                                 // Column AR
    isset($_POST['q16_never']) ? (int)$_POST['q16_never'] : 0,                                 // Column AS
    isset($_POST['q16_rarely']) ? (int)$_POST['q16_rarely'] : 0,                               // Column AT
    isset($_POST['q16_more_than_3_times_week']) ? (int)$_POST['q16_more_than_3_times_week'] : 0, // Column AU

    isset($_POST['q17_no']) ? (int)$_POST['q17_no'] : 0,                                       // Column AV
    isset($_POST['q17_yes']) ? (int)$_POST['q17_yes'] : 0,                                     // Column AW

    isset($_POST['q18_6_8_hours']) ? (int)$_POST['q18_6_8_hours'] : 0,                         // Column AX
    isset($_POST['q18_less_than_6_hours']) ? (int)$_POST['q18_less_than_6_hours'] : 0,         // Column AY
    isset($_POST['q18_more_than_8_hours']) ? (int)$_POST['q18_more_than_8_hours'] : 0,         // Column AZ

    isset($_POST['q19_no']) ? (int)$_POST['q19_no'] : 0,                                       // Column BA
    isset($_POST['q19_yes']) ? (int)$_POST['q19_yes'] : 0,                                     // Column BB

    isset($_POST['q20_no']) ? (int)$_POST['q20_no'] : 0,                                       // Column BC
    isset($_POST['q20_not_sure']) ? (int)$_POST['q20_not_sure'] : 0,                           // Column BD
    isset($_POST['q20_yes']) ? (int)$_POST['q20_yes'] : 0,                                     // Column BE

    // [MOVED HERE] Q21 belongs right before Q22
    isset($_POST['q21_no']) ? (int)$_POST['q21_no'] : 0,                                       // Column BF
    isset($_POST['q21_yes']) ? (int)$_POST['q21_yes'] : 0,                                     // Column BG

    // [MOVED HERE] Q22 belongs after Q21
    isset($_POST['q22_no']) ? (int)$_POST['q22_no'] : 0,                                       // Column BH
    isset($_POST['q22_not_tested']) ? (int)$_POST['q22_not_tested'] : 0,                       // Column BI
    isset($_POST['q22_yes']) ? (int)$_POST['q22_yes'] : 0,                                     // Column BJ

    isset($_POST['q23_no']) ? (int)$_POST['q23_no'] : 0,                                       // Column BK
    isset($_POST['q23_yes']) ? (int)$_POST['q23_yes'] : 0,                                     // Column BL

    isset($_POST['q24_no']) ? (int)$_POST['q24_no'] : 0,                                       // Column BM
    isset($_POST['q24_not_applicable']) ? (int)$_POST['q24_not_applicable'] : 0,               // Column BN
    isset($_POST['q24_yes']) ? (int)$_POST['q24_yes'] : 0,                                     // Column BO

    isset($_POST['q25_no']) ? (int)$_POST['q25_no'] : 0,                                       // Column BP
    isset($_POST['q25_not_applicable']) ? (int)$_POST['q25_not_applicable'] : 0,               // Column BQ
    isset($_POST['q25_yes']) ? (int)$_POST['q25_yes'] : 0,                                     // Column BR

    isset($_POST['q26_no']) ? (int)$_POST['q26_no'] : 0,                                       // Column BS
    isset($_POST['q26_not_tested']) ? (int)$_POST['q26_not_tested'] : 0,                       // Column BT
    isset($_POST['q26_yes']) ? (int)$_POST['q26_yes'] : 0,                                     // Column BU

    isset($_POST['q27_no']) ? (int)$_POST['q27_no'] : 0,                                       // Column BV
    isset($_POST['q27_not_applicable']) ? (int)$_POST['q27_not_applicable'] : 0,               // Column BW
    isset($_POST['q27_not_sure']) ? (int)$_POST['q27_not_sure'] : 0,                           // Column BX
    isset($_POST['q27_yes']) ? (int)$_POST['q27_yes'] : 0                                      // Column BY
];

        // 4. Let the local Machine Learning engine predict the outcome
        $prediction = $classifier->predict($userAnswers);

        // 5. Output the result elegantly
        echo "<h3>Diagnostic Analysis Result</h3>";
        if (trim(strtolower($prediction)) === 'yes' || trim($prediction) === '1') {
            echo "<div style='color: #8B4513; font-weight: bold; padding: 15px; border: 1px solid #D2691E; background-color: #FFF8DC; border-radius: 5px;'>";
            echo "🚨 Elevated Risk Profile Detected. Based on your clinical markers, a formal consultation is recommended.";
            echo "</div>";
        } else {
            echo "<div style='color: green; font-weight: bold; padding: 15px; border: 1px solid green; background-color: #F0FFF0; border-radius: 5px;'>";
            echo "✔ Healthy Markers. Your answers show a standard low-risk classification threshold.";
            echo "</div>";
        }
    } else {
        echo "No data submitted.";
    }

} catch (\Exception $e) {
    echo "Processing Error: " . $e->getMessage();
}
?>