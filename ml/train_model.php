<?php
// train_model.php
ini_set('memory_limit', '2048M'); 
set_time_limit(600);             

// Disable displaying errors directly to output so HTML error strings don't contaminate the JSON payload
ini_set('display_errors', 0);    
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING); 

// Tell the browser/JavaScript that this script outputs pure JSON
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\ArrayDataset;
use Phpml\CrossValidation\StratifiedRandomSplit; 
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Metric\Accuracy;     
use Phpml\ModelManager;

$response = [
    'success' => false,
    'message' => '',
    'accuracy' => 0
];

try {
    $csvPath = __DIR__ . '/../data/PCOD_dataset.csv';
    $modelPath = __DIR__ . '/pcod_model.phpml';

    if (!file_exists($csvPath)) {
        throw new Exception("Dataset file missing!");
    }

    // 1. Load dataset with 77 feature columns
    $dataset = new CsvDataset($csvPath, 77, true);
    $samples = $dataset->getSamples();
    $labels  = $dataset->getTargets();

    // 2. Clean out empty rows
    foreach ($samples as $key => $sample) {
        if (empty($sample) || !isset($labels[$key]) || $labels[$key] === '') {
            unset($samples[$key]);
            unset($labels[$key]);
        }
    }
    
    $samples = array_values($samples);
    $labels = array_values($labels);

    $cleanedDataset = new ArrayDataset($samples, $labels);

    // 3. Train-Test Split (80/20)
    $split = new StratifiedRandomSplit($cleanedDataset, 0.2, 1234); 
    
    $trainSamples = $split->getTrainSamples();
    $trainLabels  = $split->getTrainLabels();
    $testSamples  = $split->getTestSamples();
    $testLabels   = $split->getTestLabels();

    // 4. Initialize & Train Classifier
    $classifier = new SVC(Kernel::LINEAR, $cost = 1.0);
    $classifier->train($trainSamples, $trainLabels);

    // 5. Generate predictions and evaluate accuracy
    $predictedLabels = [];
    foreach ($testSamples as $sample) {
        $predictedLabels[] = $classifier->predict($sample);
    }

    $accuracyScore = Accuracy::score($testLabels, $predictedLabels);
    $accuracyPercentage = round($accuracyScore * 100, 2);

    // 6. Save model to disk
    $modelManager = new ModelManager();
    $modelManager->saveToFile($classifier, $modelPath);

    // Build successful JSON response
    $response['success'] = true;
    $response['message'] = "Model trained and saved successfully!";
    $response['accuracy'] = $accuracyPercentage;

} catch (\Throwable $e) {
    // Catch any errors or exceptions cleanly
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = "Engine Training Error: " . $e->getMessage();
}

// Return JSON payload exclusively
echo json_encode($response);
exit();
?>