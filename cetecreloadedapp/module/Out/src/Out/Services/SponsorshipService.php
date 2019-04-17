<?php

namespace Out\Services;

use Out\Model\SponsorshipModel;

class SponsorshipService
{
	public function getSponsors($id_presentation){
		$sponsorModel = new SponsorshipModel();
		$allSponsors = $sponsorModel->getSponsors($id_presentation);
		$data = array(
			"top"    => "",
			"left"   => "",
			"right"  => "",
			"buttom" => "",
			"status" => "",
		);

		if($allSponsors){
			foreach($allSponsors as $sponsor){
				if($sponsor['position']=="top"){
					$data['top'] = $sponsor;
					$data['status'] = 1;
				}elseif($sponsor['position']=="left"){
					$data['left'] = $sponsor;
					$data['status'] = 1;
				}elseif($sponsor['position']=="right"){
					$data['right'] = $sponsor;
					$data['status'] = 1;
				}elseif($sponsor['position']=="buttom"){
					$data['buttom'] = $sponsor;
					$data['status'] = 1;
						
				}
			
		}
	
		}


		return $data;
	}
	
	public function addSponsorship($formData){
		$type=array_pop(explode('/',$formData['type']));
		$aleatorio1 = rand() * (1 - 1000 + 1) + 1;
		$aleatorio2 = rand() * (1 - 1000 + 1) + 2;
		$aleatorio = $aleatorio1 * $aleatorio2/2;
		$imageName=md5($aleatorio).".".$type;
		$file=base64_decode(str_replace('data:image/'.$type.';base64,', '', $formData['valueImagen']));
		file_put_contents('public/img/SponsorImages/'.$imageName,$file);
		$data = array(
				"imagepath" => "/img/SponsorImages/".$imageName,
				"position"  => $formData['position'],
				"id_presentation" => $formData['id_presentation']
		);
		$sponsorModel = new SponsorshipModel();
		$sponsorImage=$sponsorModel->addSponsor($data);
		return $sponsorImage;
	}
	
	public function updateSponsorship($formData){
		
		unlink('public'.$formData['imagePath']);
		$type=array_pop(explode('/',$formData['type']));
		$aleatorio1 = rand() * (1 - 1000 + 1) + 1;
		$aleatorio2 = rand() * (1 - 1000 + 1) + 2;
		$aleatorio = $aleatorio1 * $aleatorio2/2;
		$imageName=md5($aleatorio).".".$type;
		$file=base64_decode(str_replace('data:image/'.$type.';base64,', '', $formData['valueImagen']));
		file_put_contents('public/img/SponsorImages/'.$imageName,$file);

		$data = array(
				"id_sponsor" => $formData['idSponsor'],
				"imagepath" => "/img/SponsorImages/".$imageName
		);
		
		$sponsorModel = new SponsorshipModel();

		$sponsorImage = $sponsorModel->updateSponsor($data);
		return $sponsorImage;
	}
	
	public function deleteSponsorship($data){
		$sponsorModel = new SponsorshipModel();
		$deleteSponsor = $sponsorModel->deleteSponsor($data);
		unlink('public'.$data['imagePath']);
		return $deleteSponsor;
	}
	
}