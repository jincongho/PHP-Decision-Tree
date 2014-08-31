<h1>Decision Tree for Or Function</h1>
<pre>
<?php

/*
 * Decision Tree Learning
 * Problem Solving: Predicting the output of instance (OR Function)
 */

include_once __DIR__ . '/vendor/autoload.php';

$training_set = array(
	array(array(0, 0, 0), 0),
	array(array(0, 0, 1), 1),
	array(array(0, 1, 0), 1),
	array(array(0, 1, 1), 1),
	array(array(1, 0, 0), 1),
	array(array(1, 0, 1), 1),
	array(array(1, 1, 0), 1),
	array(array(1, 1, 1), 1),
);

$orTree = new Jincongho\MachineLearning\DecisionTree;
$orTree->setAttrNum(3)->addTrainingSet($training_set)->startTraining();

print_r($orTree->getTree());

echo "\nInformation Gain per Attribute(column#, ordered by the ascending): \n";
var_dump($orTree->getGain());

echo "\nClassify: (1, 1, 0) is ", $orTree->classify(array(1, 1, 0));
?>
</pre>