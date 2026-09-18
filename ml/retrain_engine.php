<?php
// retrain_engine.php
ini_set('memory_limit', '2048M');
set_time_limit(600);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);

// require_once __DIR__ . '/vendor/autoload.php';
// require_once __DIR__ . '/db.php'; // Make sure this path to your db file is correct
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

use Phpml\Dataset\CsvDataset;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;

try {
    $csvPath = __DIR__ . '/../data/PCOD_dataset.csv';
$modelPath = __DIR__ . '/pcod_model.phpml';

    if (!file_exists($csvPath)) {
        throw new Exception("Original dataset file missing!");
    }

    echo "--- STARTING RESOURCE RETRAINING PIPELINE ---\n";

    // =========================================================================
    // STEP 1: LOAD AND CLEAN THE ORIGINAL 10,326 CSV DATASET (Fixed your error)
    // =========================================================================
    echo "Loading original clinical baseline records...\n";
    $dataset = new CsvDataset($csvPath, 77, true);
    $originalSamples = $dataset->getSamples();
    $originalLabels  = $dataset->getTargets();

    // Clean out empty lines or spacer rows from the CSV exactly like train_model.php
    foreach ($originalSamples as $key => $sample) {
        if (empty($sample) || !isset($originalLabels[$key]) || $originalLabels[$key] === '') {
            unset($originalSamples[$key]);
            unset($originalLabels[$key]);
        }
    }
    $originalSamples = array_values($originalSamples);
    $originalLabels = array_values($originalLabels);


    // =========================================================================
    // STEP 2: FETCH LIVE PRODUCTION DATA FROM MYSQL DATABASE
    // =========================================================================
    echo "Extracting new user inference logs from MySQL database...\n";

    // Select age and all 27 questionnaire response columns plus the calculated prediction
    // NOTE: Replace q1, q2, q3... with your actual column names from your screening table
    $query = "SELECT user_id, age, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, 
                    q11, q12, q14, q22, q13, q21, q15, q16, q17, q18, q19, 
                    q20, q23, q24, q25, q26, q27, prediction_result, created_at FROM screening";
    $result = $conn->query($query);

    $liveSamples = [];
    $liveLabels = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            // PERFORM ONE-HOT ENCODING MANUALLY TO CONVERT MOCK USER DATA INTO THE 77-FEATURE VECTOR
            // This structure MUST precisely mirror how your screening.php creates features!
            $encodedRow = [
    intval($row['age']),
    ($row['q1'] === 'No') ? 1 : 0,
    ($row['q1'] === 'Yes') ? 1 : 0,
    ($row['q2'] === 'Every 21-35 days') ? 1 : 0,
    ($row['q2'] === 'Every 28 days') ? 1 : 0,
    ($row['q2'] === 'Less often than 35 days') ? 1 : 0,
    ($row['q2'] === 'More than 2 months gap') ? 1 : 0,
    ($row['q2'] === 'Irregular') ? 1 : 0,
    ($row['q2'] === 'Not sure') ? 1 : 0,
    ($row['q3'] === 'No') ? 1 : 0,
    ($row['q3'] === 'Yes') ? 1 : 0,
    ($row['q4'] === '1 time') ? 1 : 0,
    ($row['q4'] === '2-3 time') ? 1 : 0,
    ($row['q4'] === 'More than 3 times') ? 1 : 0,
    ($row['q4'] === 'Not applicable') ? 1 : 0,
    ($row['q5'] === 'No') ? 1 : 0,
    ($row['q5'] === 'Yes') ? 1 : 0,
    ($row['q6'] === 'No') ? 1 : 0,
    ($row['q6'] === 'Yes') ? 1 : 0,
    ($row['q7'] === 'No') ? 1 : 0,
    ($row['q7'] === 'Yes') ? 1 : 0,
    ($row['q8'] === 'No') ? 1 : 0,
    ($row['q8'] === 'Yes') ? 1 : 0,
    ($row['q9'] === 'No') ? 1 : 0,
    ($row['q9'] === 'Yes') ? 1 : 0,
    ($row['q10'] === 'No') ? 1 : 0,
    ($row['q10'] === 'Yes') ? 1 : 0,
    ($row['q11'] === 'No') ? 1 : 0,
    ($row['q11'] === 'Not tested') ? 1 : 0,
    ($row['q11'] === 'Yes') ? 1 : 0,
    ($row['q12'] === 'No') ? 1 : 0,
    ($row['q12'] === 'Not tested') ? 1 : 0,
    ($row['q12'] === 'Yes') ? 1 : 0,
    ($row['q13'] === 'No') ? 1 : 0,
    ($row['q13'] === 'Yes') ? 1 : 0,
    ($row['q14'] === 'No') ? 1 : 0,
    ($row['q14'] === 'Not tested') ? 1 : 0,
    ($row['q14'] === 'Yes') ? 1 : 0,
    ($row['q15'] === 'Both exercise and household work') ? 1 : 0,
    ($row['q15'] === 'Household work') ? 1 : 0,
    ($row['q15'] === 'Low physical activity / mostly inactive') ? 1 : 0,
    ($row['q15'] === 'Regular exercise') ? 1 : 0,
    ($row['q16'] === '1-2 times a week') ? 1 : 0,
    ($row['q16'] === 'Daily / Almost daily') ? 1 : 0,
    ($row['q16'] === 'never') ? 1 : 0,
    ($row['q16'] === 'Rarely') ? 1 : 0,
    ($row['q16'] === 'more than 3 times/week') ? 1 : 0,
    ($row['q17'] === 'No') ? 1 : 0,
    ($row['q17'] === 'Yes') ? 1 : 0,
    ($row['q18'] === '6-8 hours') ? 1 : 0,
    ($row['q18'] === 'Less than 6 hours') ? 1 : 0,
    ($row['q18'] === 'More than 8 hours') ? 1 : 0,
    ($row['q19'] === 'No') ? 1 : 0,
    ($row['q19'] === 'Yes') ? 1 : 0,
    ($row['q20'] === 'No') ? 1 : 0,
    ($row['q20'] === 'Yes') ? 1 : 0,
    ($row['q20'] === 'Not sure') ? 1 : 0,
    ($row['q21'] === 'No') ? 1 : 0, // <-- Fixed: q21 comes first now!
    ($row['q21'] === 'Yes') ? 1 : 0,
    ($row['q22'] === 'No') ? 1 : 0, // <-- Fixed: q22 follows after cleanly
    ($row['q22'] === 'Yes') ? 1 : 0,
    ($row['q22'] === 'Not tested') ? 1 : 0,
    ($row['q23'] === 'No') ? 1 : 0,
    ($row['q23'] === 'Yes') ? 1 : 0,
    ($row['q24'] === 'No') ? 1 : 0,
    ($row['q24'] === 'Not applicable') ? 1 : 0,
    ($row['q24'] === 'Yes') ? 1 : 0,
    ($row['q25'] === 'No') ? 1 : 0,
    ($row['q25'] === 'Not applicable') ? 1 : 0,
    ($row['q25'] === 'Yes') ? 1 : 0,
    ($row['q26'] === 'No') ? 1 : 0,
    ($row['q26'] === 'Not tested') ? 1 : 0,
    ($row['q26'] === 'Yes') ? 1 : 0,
    ($row['q27'] === 'No') ? 1 : 0,
    ($row['q27'] === 'Not sure') ? 1 : 0,
    ($row['q27'] === 'Yes') ? 1 : 0,
    ($row['q27'] === 'Not applicable') ? 1 : 0
];

            // Fallback safety padding just in case the feature count isn't exactly 77
            if (count($encodedRow) !== 77) {
                $encodedRow = array_slice(array_pad($encodedRow, 77, 0), 0, 77);
            }

            $liveSamples[] = $encodedRow;
            $liveLabels[] = $row['prediction_result'];

            // 3. You can read the timestamp here if you want to log it, 
            // but just don't push it into $liveSamples!
            $submissionDate = $row['created_at'];
        }
        echo "Successfully processed " . count($liveSamples) . " new real-world records from database.\n";
    } else {
        echo "No live records found in database yet. Training strictly on baseline data.\n";
    }


    // =========================================================================
    // STEP 3: MERGE DATASETS (The exact lines where your error happened)
    // =========================================================================
    echo "Merging datasets into a single training matrix...\n";
    $finalSamples = array_merge($originalSamples, $liveSamples);
    $finalLabels = array_merge($originalLabels, $liveLabels);

    echo "Total combined training size: " . count($finalSamples) . " records.\n";


    // =========================================================================
    // STEP 4: RETRAIN THE MODEL & HOT-SWAP THE PHPML FILE
    // =========================================================================
    echo "Recalculating Support Vector Classifier decision hyperplanes...\n";
    $classifier = new SVC(Kernel::LINEAR, $cost = 1.0);
    $classifier->train($finalSamples, $finalLabels);

    echo "Compiling and overwriting active production model file...\n";
    $modelManager = new ModelManager();
    $modelManager->saveToFile($classifier, $modelPath);

    echo "✔ PIPELINE SUCCESS! The model has been fine-tuned and updated live!\n";
} catch (\Exception $e) {
    echo "\n❌ Pipeline Error: " . $e->getMessage() . "\n";
}
