<?php
namespace Usermanual\Entities;

class ContentsEntity{
	
	private $id_content;
	private $content;
	private $id_topic;
	
		public function setIdContent($id_content){
			$this->id_content=$id_content;
		}
		public function getIdContent(){
			return $this->id_content;
		}
		
		public function setContent($content){
			$this->content=$content;
		}
		public function getContent(){
			return $this->content;
		}
		
		public function setIdTopic($id_topic){
			$this->id_topic=$id_topic;
		}
		public function getIdTopic(){
			return $this->id_topic;
		}
	
}