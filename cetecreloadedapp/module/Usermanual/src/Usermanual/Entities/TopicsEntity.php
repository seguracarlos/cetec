<?php 
	namespace Usermanual\Entities;

	class TopicsEntity{
		
		private $id_topic;
		private $id_user;
		private $topic_name;
		private $parent;
		private $type;
		private $project_name;
		private $description;
		private $artefact_type;
		private $creation_date;
		private $value;
		private $startDate;
		private $endDate;
		private $time;
		private $prerequisites;
		
		public function setIdTopic($id_topic){
			$this->id_topic=$id_topic;
		}
		
		public function getIdTopic(){
			return $this->id_topic;
		}
		
		public function setIdUser($id_user){
			$this->id_user = $id_user;
		}
		public function getIdUser(){
			return $this->id_user;
		}
		
		public function setTopicName($topic_name){
			$this->topic_name=$topic_name;
		}
		
		public function getTopicName(){
			return $this->topic_name;
		}
		
		public function setParent($parent){
			$this->parent=$parent;
		}
		
		public function getParent(){
			return $this->parent;
		}
		
		public function setType($type){
			$this->type=$type;
		}
		
		public function getType(){
			return $this->type;
		}
		
		public function setProjectName($project_name){
			$this->project_name=$project_name;
		}
		
		public function getProjectName(){
			return $this->project_name;
		}
		
		public function setDescription($description){
			$this->description=$description;
		}
		
		public function getDescription(){
			return $this->description;
		}
		
		public function setArtefact_type($artefact_type){
			$this->artefact_type=$artefact_type;
		}
		
		public function getArtefact_type(){
			return $this->artefact_type;
		}
		
		public function setCreationDate($creationDate){
			$this->creation_date=$creationDate;
		}
		
		public function getCreationDate(){
			return $this->creationDate;
		}
		
		public function setValue($value){
			$this->value=$value;
		}
		
		public function getValue(){
			return $this->value;
		}
		
		public function setStartDate($startDate){
			$this->startDate=$startDate;
		}
		
		public function getStartDate(){
			return $this->startDate;
		}
		public function setEndDate($endDate){
			$this->endDate=$endDate;
		}
		
		public function getEndDate(){
			return $this->endDate;
		}
		
		public function setTime($time){
			$this->time=$time;
		}
		
		public function getTime(){
			return $this->time;
		}
		
		public function setPrerequisites($prerequisites){
			$this->prerequisites = $prerequisites;
		}
		
		public function getPrerequisites(){
			return $this->prerequisites;
		}
		
	} 