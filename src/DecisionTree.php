<?php namespace Jincongho\DecisionTree;

class DecisionTree {
	protected $tree;

	protected $attrnum;

	protected $training_set = array();

	protected $outputs = array();

	protected $gain = array();

	public function setAttrNum($num) {
		$this->attrnum = $num;
		return $this;
	}

	public function getAttrNum($num) {
		return $this->attrnum;
	}

	public function getTree() {
		return $this->tree;
	}

	public function getGain() {
		return $this->gain;
	}

	public function addTrainingSet($training_array = array()) {
		foreach ($training_array as $instance) {
			$this->training_set[] = $instance;
		}

		$this->outputs = $this->getOutputs($this->training_set);

		return $this;
	}

	public function getTrainingSet() {
		return $this->training_set;
	}

	public function startTraining() {

		$this->gain = $this->getGains($this->training_set, $this->attrnum);
		$gain = array_keys($this->gain);
		$this->tree = $this->buildTree(
			$this->training_set,
			array_pop($gain),
			$gain
		);

		return $this->tree;

	}

	public function classify($instance = array()) {
		if ((count($instance) < $this->attrnum)) {
			throw new \BadFunctionCallException('Attribute\'s number in instance passed for labelling is unmatched.');
		}

		if (!isset($this->gain)) {
			throw new \BadFunctionCallException('Must run training before labelling.');
		}

		$route = array();
		foreach($this->gain as $key=>$gain){
			$route[] = $instance[$key];
		}

		return $this->transverseTree($this->tree, $route);
	}

	protected function buildTree($training_set, $target_attr, $attr = array()) {
		$column  = $target_attr;
		$outputs = $this->getOutputs($training_set);
		if (count($outputs) === 1) {
			return new TreeLabel($outputs[0]);
		} elseif (count($attr) === 0) {
			$values = $this->getValues($training_set, $column);
			$prob   = $this->countProb($training_set, $column);
			$node   = array();
			foreach ($values as $value) {
				end($prob[$value]);
				$label        = key($prob[$value]);
				$node[$value] = new TreeLabel($label);
			}

			return new TreeNode($node);
		} else {
			$values = $this->getValues($training_set, $column);
			$node   = array();
			foreach ($values as $value) {
				$node[$value] = $this->buildTree(
					$this->getSetsOf($training_set, $column, $value),
					array_pop($attr),
					$attr
				);
			}
			return new TreeNode($node);
		}
	}

	protected function transverseTree($tree, $values) {
		/*if (is_a($tree, 'Jincongho\DecisionTree\TreeLabel')) {
			return $tree;
		} else {
			try{
				
			}catch(\Exception $e){
				//@TODO
			}
			$tree = $tree->{array_pop($values)};

			return $this->transverseTree($tree, $values);
		}*/
		while($values){
			if(is_a($tree, 'Jincongho\DecisionTree\TreeLabel')){
				break;
			}else{
				try{
					$tree = $tree->{array_pop($values)};
				}catch(\Exception $e){

				}
			}
		}
		return $tree;
	}

	protected function getOutputs($training_set) {
		$outputs = array();
		foreach ($training_set as $set) 
			if (!in_array($set[1], $outputs)) 
				$outputs[] = $set[1];

		return $outputs;
	}

	protected function getValues($training_set, $column) {
		$values = array();
		foreach ($training_set as $set) 
			if (!in_array($set[0][$column], $values)) 
				$values[] = $set[0][$column];

		return $values;
	}

	protected function getGains($training_set, $attrnum) {
		$gain = array();
		for ($i = 0; $i < $attrnum; $i++) {
			$gain[$i] = $this->countGain($training_set, $i);
		}
		asort($gain);

		return $gain;
	}

	protected function getSetsOf($training_set, $column, $value, $output = null) {
		return array_filter($training_set, function ($input) use ($column, $value, $output) {
			if ($output == null) {
				return ($input[0][$column] == $value);
			} else {
				return (($input[0][$column] == $value) and ($input[1] == $output));
			}
		});
	}

	protected function countEntropy($data) {
		$count = array();
		foreach ($data as $set) {
			if (!isset($count[$set[1]])) 
				$count[$set[1]] = 1;
			else
				$count[$set[1]]++;
		}

		$entropy = 0;
		$total   = array_sum($count);
		foreach ($count as $value) 
			$entropy += -($value / $total) * log($value / $total, 2);

		return $entropy;
	}

	protected function countGain($data, $column) {
		$entropy = $this->countEntropy($data);

		$count = array();
		foreach ($data as $set) {
			if (!isset($count[$set[0][$column]])) 
				$count[$set[0][$column]] = array();

			if (!isset($count[$set[0][$column]][$set[1]])) 
				$count[$set[0][$column]][$set[1]] = 0;

			$count[$set[0][$column]][$set[1]]++;
		}

		$gain  = 0;
		$total = count($data);
		foreach ($count as $key => $values) {
			$gain -= (array_sum($values) / $total) * $this->countEntropy(array_filter($data, function ($set) use ($column, $key) {
					return ($set[0][$column] == $key);
				}
			));
		}

		return $entropy + $gain;
	}

	public function countProb($data, $column) {
		$values  = $this->getValues($data, $column);
		$outputs = $this->getOutputs($data);
		$prob    = array();
		foreach ($values as $value) {
			$prob[$value] = array();
			foreach ($this->getSetsOf($data, $column, $value) as $set) {
				if(!isset($prob[$value][$set[1]]))
					$prob[$value][$set[1]] = 1;
				else
					$prob[$value][$set[1]]++;
			}
			foreach ($outputs as $out) {
				$total = count($this->getSetsOf($data, $column, $value, $out));
				$prob[$value][$out] = 0;
				if ($total != 0)  
					$prob[$value][$out] = $prob[$value][$out] / $total;
			}
			asort($prob[$value]);
		}

		return $prob;

	}
}