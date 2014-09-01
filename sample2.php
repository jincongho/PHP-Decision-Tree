<h1>Decision Tree for Disease Hepatitis</h1>
<pre>
<?php

/*
 * Decision Tree Learning
 * Problem Solving: Predicting the output of instance (Hepatitis Function)
 * Additional: Testing Set for testing Accuracy
 * Source: http://pages.cs.wisc.edu/~shavlik/cs540/HWs/HW1.html
 */

include_once __DIR__ . '/vendor/autoload.php';

//tidy up data
$training_set = tidyHepatitisData(__DIR__.'/hepatitis.data');

$hepTree = new Jincongho\DecisionTree\DecisionTree;
$hepTree->setAttrNum(22)->addTrainingSet($training_set)->startTraining();

//print_r($hepTree->getTree());

//echo "\nInformation Gain per Attribute(column#, ordered by the ascending): \n";
//var_dump($hepTree->getGain());

//testing set
$testing_set = tidyHepatitisData(__DIR__.'/hepatitis.data');

$correct = 0;
$missing = 0;
foreach($testing_set as $set){
	if($hepTree->classify($set[0]) == $set[1]){
		$correct++;
	}else{
		$missing++;
	}
}

echo "\nRunning testing sets(", $correct + $missing, "):\n Correct: ", $correct, "\nMissing: ", $missing, "\nAccuracy: ", $correct/($correct+$missing); 

function tidyHepatitisData($file){
	$training_set = file($file);

	foreach ($training_set as $key => $value) {
		$filter = array_filter(explode(" ", $value), function($input){
			return strlen($input) > 0;
		});
		$filter = array_map('trim', $filter);

		$training_set[$key] = array(array_slice($filter, 2), $filter[1]);
	}

	return $training_set;
}
?>
</pre>