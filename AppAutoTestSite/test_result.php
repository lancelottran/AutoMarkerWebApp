<?php

class TestResult
{
	private $resultPath;

	function __construct($user, $lab)
	{
		$this->resultPath = $this->getResultsFilePath($user, $lab);

	}

	private function getResultsFilePath($user, $lab)
	{
		$resultFileDir = getConfigProperty('results_directory');
		$resultFilename = $user . '_Lab_' . $lab . '_feedback.txt';
		//var_dump($resultFileDir . $resultFilename);
		return $resultFileDir . $resultFilename;
	}

	public function GetResultsFromFile()
	{
		if (!$this->areResultsAvailable()) {
			return "No results.";
		}

		return file_get_contents($this->resultPath);
	}

	public function areResultsAvailable()
	{
		return (file_exists($this->resultPath));
	}

}