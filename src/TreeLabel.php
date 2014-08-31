<?php namespace Jincongho\DecisionTree;

class TreeLabel {
	protected $label;

	public function __construct($label) {
		$this->label = &$label;
	}

	public function __toString() {
		return (string) $this->label;
	}

	public function getLabel() {
		return $this->label;
	}
}
