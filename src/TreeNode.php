<?php namespace Jincongho\DecisionTree;

class TreeNode {
	protected $values;

	public function __construct($values) {
		$this->values = &$values;
	}

	public function __get($value) {
		if(isset($this->values[$value]) == false){
			//die(var_dump($value).var_dump(array_keys($this->values)));
			throw new \Exception('Node requested not exists.');
		}
		
		return $this->values[$value];
	}

	public function __isset($value){
		return isset($this->values[$value]);
	}
}