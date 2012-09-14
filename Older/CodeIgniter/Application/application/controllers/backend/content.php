<?php

class Content extends Controller {

	function Content()
	{
		parent::Controller();	
		$this->load->model('backend/content_model');
		$this->load->model('backend/content_label_model');
		$this->load->model('backend/content_label_text_model');
		$this->load->model('backend/content_label_link_model');
		$this->load->model('backend/content_label_image_model');
	}

	protected function getIndexData()
	{
		$data = array();
		$data['content'] = $this->content_model->readTable();
		return $data;
	}

	function index()
	{
		// layout basic data
		$data['title'] = "TinyCMS";
		$data['link_tag_list'] = array('styles/backend.css');

		// render action
		$data['content'] = $this->load->view('backend/content/index_html', $this->getIndexData(), true );

		// render layout
		$this->load->view('layout/default_html', $data );
	}

	protected function getEditData( $contentID, $langID )
	{
		// read content data
		$content = $this->content_model->read( $contentID );
		$content['lang_id'] = "de";
		$content['label_ids'] = array();
		$content['label_text_ids'] = array();
		$content['label_image_ids'] = array();
		$content['label_link_ids'] = array();
		$content['action_url'] = site_url("backend/content/update/".$contentID );
		$content['action_target'] = "_self";
		$content['simple_labels'] = array();
		$content['paragraph_labels'] = array();

		// read content labels
		$labelFieldsTable = $this->content_label_model->readIndexedTableByContent( $contentID );
		foreach( $labelFieldsTable as &$labelFields )
		{
			$content['label_ids'][] = $labelFields['id'];
			$labelFields['children'] = array();
			$labelFields['images'] = array();
			$labelFields['links'] = array();
			switch( $labelFields['parent_type'] )
			{
				case 'DEF':
				{
					if('SIMP' === $labelFields['type'] ) $content['simple_labels'][] = &$labelFields;
					elseif('PARA' === $labelFields['type'] ) $content['paragraph_labels'][] = &$labelFields;
					break;
				}
				case 'SELF':
				{
					$parentLabelFields = &$labelFieldsTable[$labelFields['parent_id']];
					$parentLabelFields['children'][] = &$labelFields;
					break;
				}
			}
		}

		// read content images
		$imageFieldsTable = $this->content_label_image_model->readTableByContent( $contentID );
		foreach( $imageFieldsTable as $imageFields )
		{
			$content['label_image_ids'][] = $imageFields['id'];
			$imageFileName = $imageFields['file_id'].".".$imageFields['file_format'];
			$imageFields['src_thumb'] = base_url()."content/home/images/s120x90/".$imageFileName;
			$imageFields['src'] = base_url()."content/home/images/".$imageFields['size']."/".$imageFileName;
			$labelFields = &$labelFieldsTable[$imageFields['label_id']];
			$labelFields['images'][$imageFields['id']] = $imageFields;
		}

		// read content label links
		$linkFieldsTable = $this->content_label_link_model->readTableByContent( $contentID );
		foreach( $linkFieldsTable as &$linkFields )
		{
			$content['label_link_ids'][] = $linkFields['id'];
			$labelFields = &$labelFieldsTable[$linkFields['label_id']];
			$labelFields['links'][$linkFields['id']] = $linkFields;
		}

		// read content label texts
		$textFieldsTable = $this->content_label_text_model->readTableByContentAndLanguage( $contentID, $langID );
		foreach( $textFieldsTable as &$textFields )
		{
			$content['label_text_ids'][] = $textFields['id'];
			switch( $textFields['parent_type'] )
			{
				case 'DEF':
				{
					$labelFields = &$labelFieldsTable[$textFields['label_id']];
					$labelFields['text'] = $textFields;
					break;
				}
				case 'IMG':
				{
					$labelFields = &$labelFieldsTable[$textFields['label_id']];
					$imageFields = &$labelFields['images'][$textFields['parent_id']];
					$imageFields['title'] = $textFields;
					break;
				}
				case 'LNK':
				{
					$labelFields = &$labelFieldsTable[$textFields['label_id']];
					$linkFields = &$labelFields['links'][$textFields['parent_id']];
					$linkFields['title'] = $textFields;
					break;
				}
			}
		}
		return $content;
	}

	function edit()
	{
		// layout basic data
		$data['title'] = "TinyCMS Edit";
		$data['script_tag_list'] = array('js/jquery.js');
		$data['link_tag_list'] = array('styles/backend.css');

		// render <onready> scripts
		$data['script_onready_list'][] = $this->load->view('backend/content/_edit_js', null, true );

		// render action
		$langID = 'de';
		$contentID = $this->uri->segment( 4 );
		$data['content'] = $this->load->view('backend/content/edit_html', $this->getEditData( $contentID, $langID ), true );

		// render layout
		$this->load->view('layout/default_html', $data );
	}

	function update()
	{
		$contentID = $this->uri->segment( 4 );
		$stringLabelIDs = $this->input->post('label_ids');
		if( $stringLabelIDs && preg_match('/^[0-9]+(,[0-9]+)*/', $stringLabelIDs ) )
		{
			$labelIDs = explode(',', $stringLabelIDs );
		}

		// process text updates
		$langID = $this->input->post('lang_id', TRUE);
		$stringLabelTextIDs = $this->input->post('label_text_ids');
		if( $stringLabelTextIDs && preg_match('/^[0-9]+(,[0-9]+)*/', $stringLabelTextIDs ) )
		{
			$labelTextIDs = explode(',', $stringLabelTextIDs );
			$textFieldsTable = $this->content_label_text_model->readIndexedTableByWhereIn('id', $labelTextIDs );
			foreach( $labelTextIDs as $labelTextID )
			{
				$textPostKey = 'text_'.$labelTextID;
				$textValue = $this->input->post( $textPostKey, TRUE );
				if( isset( $textValue ) && $textValue !== $textFieldsTable[$labelTextID]['text'] )
				{
					if( strlen( $textValue ) )
					{
						$labelText = array('text' =>  $textValue );
						$this->content_label_text_model->update( $labelTextID, $labelText );
					}
					else
					{
						$this->content_label_text_model->delete( $labelTextID );
					}
				}
			}
		}

		// process image updates
		$stringLabelImageIDs = $this->input->post('label_image_ids');
		if( $stringLabelImageIDs && preg_match('/^[0-9]+(,[0-9]+)*/', $stringLabelImageIDs ) )
		{
			$labelImageIDs = explode(',', $stringLabelImageIDs );
			$imageFieldsTable = $this->content_label_image_model->readIndexedTableByWhereIn('id', $labelImageIDs );
			foreach( $labelImageIDs as $labelImageID )
			{
				// update image properties
				$labelImage = array();
				$labelImageCmp = &$imageFieldsTable[$labelImageID];

				$imageSrcPostKey = 'image_'.$labelImageID;
				$imageSrc = $this->input->post( $imageSrcPostKey, TRUE );
				if( $imageSrc && $imageSrc !== $labelImageCmp['src'] )
				{
					$labelImage['src'] = $imageSrc;
				}

				$imageListIDPostKey = 'image_listid_'.$labelImageID;
				$imageListID = $this->input->post( $imageListIDPostKey, TRUE );
				if( $imageListID && $imageListID !== $labelImageCmp['listid'] )
				{
					$labelImage['listid'] = $imageListID;
				}

				$imageHRefPostKey = 'image_href_'.$labelImageID;
				$imageHRef = $this->input->post( $imageHRefPostKey, TRUE );
				if( $imageHRef && $imageHRef !== $labelImageCmp['href'] )
				{
					$labelImage['href'] = $imageHRef;
				}

				$imageAlignPostKey = 'image_align_'.$labelImageID;
				$imageAlign = $this->input->post( $imageAlignPostKey, TRUE );
				if( $imageAlign && $imageAlign !== $labelImageCmp['align'] )
				{
					$labelImage['align'] = $imageAlign;
				}

				if( count( $labelImage ) )
				{
					$this->content_label_image_model->update( $labelImageID, $labelImage );
				}

				// create image title child
				$imageIDTitlePostKey = 'image_create_title_'.$labelImageID;
				$imageTitle = $this->input->post( $imageIDTitlePostKey, TRUE );
				if( $imageTitle )
				{
					$labelText = array(
						'content_id' => $labelImageCmp['content_id'],
						'label_id' => $labelImageCmp['label_id'],
						'parent_type' => 'IMG',
						'parent_id' => $labelImageID,
						'lang_id' => $langID,
						'text' => $imageTitle
						);
					$this->content_label_text_model->create( $labelText );
				}
			}
		}

		// process link updates
		$stringLabelLinkIDs = $this->input->post('label_link_ids');
		if( $stringLabelLinkIDs && preg_match('/^[0-9]+(,[0-9]+)*/', $stringLabelLinkIDs ) )
		{
			$labelLinkIDs = explode(',', $stringLabelLinkIDs );
			$linkFieldsTable = $this->content_label_link_model->readIndexedTableByWhereIn('id', $labelLinkIDs );
			foreach( $labelLinkIDs as $labelLinkID )
			{
				// update link properties
				$labelLink = array();
				$labelLinkCmp = &$linkFieldsTable[$labelLinkID];

				$linkHRefPostKey = 'link_'.$labelLinkID;
				$linkHRef = $this->input->post( $linkHRefPostKey, TRUE );
				if( $linkHRef && $linkHRef !== $labelLinkCmp['href'] )
				{
					$labelLink['href'] = $linkHRef;
				}

				$linkListIDPostKey = 'link_listid_'.$labelLinkID;
				$linkListID = $this->input->post( $linkListIDPostKey, TRUE );
				if( $linkListID && $linkListID !== $labelLinkCmp['listid'] )
				{
					$labelLink['listid'] = $linkListID;
				}

				if( count( $labelLink ) )
				{
					$this->content_label_link_model->update( $labelLinkID, $labelLink );
				}

				// create link title child
				$linkIDTitlePostKey = 'link_create_title_'.$labelLinkID;
				$linkTitle = $this->input->post( $linkIDTitlePostKey, TRUE );
				if( $linkTitle )
				{
					$labelText = array(
						'content_id' => $labelLinkCmp['content_id'],
						'label_id' => $labelLinkCmp['label_id'],
						'parent_type' => 'LNK',
						'parent_id' => $labelLinkID,
						'lang_id' => $langID,
						'text' => $linkTitle
						);
					$this->content_label_text_model->create( $labelText );
				}
			}
		}

		// redirect to edit site
		redirect( site_url("backend/content/edit/".$contentID ) );
	}

	function createlabel()
	{
		// redirect to edit site
		redirect( site_url("backend/content/edit/".$contentID ) );
	}

	function create()
	{
		redirect();
	}
}

?>