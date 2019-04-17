<?php 
	namespace Usermanual\Services;

	interface ContentsService{

		public function getContentById($id);
		public function addContent($formData);
		public function editContent($formData);
	}