<?php

namespace NDLA;


class Image {

	private $id, $previewUrl, $metaUrl, $license;
	private $titles, $altTexts, $imageUrl, $size, $contentType, $copyright, $tags, $captions;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getPreviewUrl() {
		return $this->previewUrl;
	}

	/**
	 * @param mixed $previewUrl
	 */
	public function setPreviewUrl( $previewUrl ) {
		$this->previewUrl = $previewUrl;
	}

	/**
	 * @return mixed
	 */
	public function getMetaUrl() {
		return $this->metaUrl;
	}

	/**
	 * @param mixed $metaUrl
	 */
	public function setMetaUrl( $metaUrl ) {
		$this->metaUrl = $metaUrl;
	}

	/**
	 * @return mixed
	 */
	public function getLicense() {
		return $this->license;
	}

	/**
	 * @param mixed $license
	 */
	public function setLicense( $license ) {
		$this->license = $license;
	}

	/**
	 * @return mixed
	 */
	public function getTitles() {
		return $this->titles;
	}

	/**
	 * @param mixed $titles
	 */
	public function setTitles( $titles ) {
		$this->titles = $titles;
	}

	/**
	 * @return mixed
	 */
	public function getAltTexts() {
		return $this->altTexts;
	}

	/**
	 * @param mixed $altTexts
	 */
	public function setAltTexts( $altTexts ) {
		$this->altTexts = $altTexts;
	}

	/**
	 * @return mixed
	 */
	public function getImageUrl() {
		return $this->imageUrl;
	}

	/**
	 * @param mixed $imageUrl
	 */
	public function setImageUrl( $imageUrl ) {
		$this->imageUrl = $imageUrl;
	}

	/**
	 * @return mixed
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param mixed $size
	 */
	public function setSize( $size ) {
		$this->size = $size;
	}

	/**
	 * @return mixed
	 */
	public function getContentType() {
		return $this->contentType;
	}

	/**
	 * @param mixed $contentType
	 */
	public function setContentType( $contentType ) {
		$this->contentType = $contentType;
	}

	/**
	 * @return mixed
	 */
	public function getCopyright() {
		return $this->copyright;
	}

	/**
	 * @param mixed $copyright
	 */
	public function setCopyright( $copyright ) {
		$this->copyright = $copyright;
	}

	/**
	 * @return mixed
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param mixed $tags
	 */
	public function setTags( $tags ) {
		$this->tags = $tags;
	}

	/**
	 * @return mixed
	 */
	public function getCaptions() {
		return $this->captions;
	}

	/**
	 * @param mixed $captions
	 */
	public function setCaptions( $captions ) {
		$this->captions = $captions;
	}



}