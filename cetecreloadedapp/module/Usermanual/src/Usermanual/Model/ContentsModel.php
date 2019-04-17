<?php
namespace Usermanual\Model;

use Usermanual\Entities\ContentsEntity;

interface ContentsModel{
	public function getContentById($id);
	public function addContent(ContentsEntity $contentEntity);
	public function editContent(ContentsEntity $contentEntity);
}
?>