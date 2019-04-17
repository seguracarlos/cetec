<?php
namespace Horus\Services;

use Horus\Model\JobsModel;

class JobsServices
{
	private $jobsModel;
	
	public function getJobsModel()
	{
		return $this->jobsModel = new JobsModel();
	}
	
	public function fetchAll()
	{
		$jobs = $this->getJobsModel()->fetchAll();
		return $jobs;
	}
}