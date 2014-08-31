<?php namespace Jincongho\DecisionTree;

class TreeNode {
	protected $values;

	public function __construct($values) {
		$this->values = &$values;
	}

	public function __get($value) {
		return $this->values[$value];
	}
}