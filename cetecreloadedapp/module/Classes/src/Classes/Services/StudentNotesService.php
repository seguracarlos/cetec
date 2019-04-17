<?php
namespace Classes\Services;

use Classes\Model\StudentNotesModel;

class StudentNotesService
{
	
	
	public function getNotesByTopicAndUser($idSubtopic,$id_user)
	{

		$notesModel = new StudentNotesModel();
		$notes = array();
		foreach($idSubtopic as $id){
			$notesByTopic = $notesModel->getNotesByTopicAndUser($id['id_topic'],$id_user);
			$notes[] = array(
					'id_topic' => $id['id_topic'],
					'topic_name' => $id['topic_name'],
					'notes'      => $notesByTopic
			);
		}
	
		return $notes;
	}
	
	public function countNotes($subtopics,$id_user)
	{
		$notesModel = new StudentNotesModel();
		$countNotes = 0;
		
		foreach($subtopics as $subtopic){
			$notesByTopic = $notesModel->counNotesByTopic($subtopic['id_topic'],$id_user);
			$countNotes += $notesByTopic;
		}
		
		return $countNotes;
	}
	
	public function addNote($formData)
	{	
		$notesModel = new StudentNotesModel();
		$data = array(
				'note_text' => $formData['note'],
				'id_subtopic' => $formData['idSubtopic'],
				'id_student' => $formData['id_user']
				
		);
		$addNote = $notesModel->addNote($data);
		return $addNote;
	}
	
	//Editar un gasto
	public function updateNote($formData)
	{

		$notesModel = new StudentNotesModel();
		$data = array(
				'id_note' => $formData['idNote'],
				'note_text' => $formData['note'],
						
		);
		$updateNote = $notesModel->updateNote($data);
		return $updateNote;
	}
	
	public function deleteNote($formData)
	{
		$notesModel = new StudentNotesModel;
		$deleteNote = $notesModel->deleteNote($formData['id_note']);
		return $deleteNote;
	}
}