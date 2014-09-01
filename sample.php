<h1>Decision Tree for Or Function</h1>
<pre>
<?php

/*
 * Decision Tree Learning
 * Problem Solving: Predicting the output of instance (OR Function)
 */

include_once __DIR__ . '/vendor/autoload.php';

$training_set = array(
	array(array(0, 'n', 'f'), 0),
	array(array(0, 'n', 't'), 1),
	array(array(0, 'y', 'f'), 1),
	array(array(0, 'y', 't'), 1),
	array(array(1, 'n', 'f'), 1),
	array(array(1, 'n', 't'), 1),
	array(array(1, 'y', 'f'), 1),
	array(array(1, 'y', 't'), 1),
);

$orTree = new Jincongho\DecisionTree\DecisionTree;
$orTree->setAttrNum(3)->addTrainingSet($training_set)->startTraining();

print_r($orTree->getTree());

echo "\nInformation Gain per Attribute(column#, ordered by the ascending): \n";
var_dump($orTree->getGain());

echo "\nClassify: (0, n, f) is ", ($orTree->classify(array(0, 'n', 'f')));
?>
</pre>