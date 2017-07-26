<?php
namespace Codazon\MegaMenu\Api\Data;

interface MegamenuInterface
{
	const MENU_ID = 'menu_id';
	const IDENTIFIER = 'identifier';
	const TITLE = 'title';
	const CONTENT = 'content';
	const IS_ACTIVE = 'is_active';
	const CSS_CLASS = 'css_class';
	const TYPE = 'type';
	
    public function getMenuId();
	public function getTitle();
	public function getIdentifier();
	public function getContent();
	public function getIsActive();
	public function getCssClass();
	public function getType();
	
	public function setMenuId($menuId);
	public function setTitle($title);
	public function setIdentifier($identifier);
	public function setContent($content);
	public function setIsActive($isActive);
	public function setCssClass($cssClass);
	public function setType($type);
	
}