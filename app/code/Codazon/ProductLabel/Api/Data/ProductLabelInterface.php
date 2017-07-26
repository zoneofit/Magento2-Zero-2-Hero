<?php
namespace Codazon\ProductLabel\Api\Data;

interface ProductLabelInterface
{
	const ENTITY_ID = 'entity_id';
	const TITLE = 'title';
	const CONTENT = 'content';
	const CREATION_TIME = 'creation_time';
	const UPDATE_TIME = 'update_time';
	const IS_ACTIVE = 'is_active';
	const CUSTOM_CLASS = 'custom_class';
	const CUSTOM_CSS = 'custom_css';
	const LABEL_IMAGE = 'label_image';
	const LABEL_BACKGROUND = 'label_background';
	
	
    //public function getId();
	public function getTitle();
	public function getContent();
	public function getCreationTime();
	public function getUpdateTime();
	public function isActive();
	/*public function getCustomClass();
	public function getCustomCss();
	public function getLabelImage();
	public function getLabelBackground();*/

	//public function setId($id);
	public function setTitle($title);
	public function setContent($content);
	public function setCreationTime($creationTime);
	public function setUpdateTime($updateTime);
	public function setIsActive($isActive);
	/*public function setCustomClass($customClass);
	public function setCustomCss($customCss);
	public function setLabelImage($labelImage);
	public function setLabelBackground($labelBackground);*/

}