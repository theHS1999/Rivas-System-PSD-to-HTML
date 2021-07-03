<?php

/**
 *
 * illustrator.php
 * Illustrator class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once 'smarty/Smarty.class.php';

class Illustrator {
	private $smartyViewer;

	public function __construct() {
		$this -> smartyViewer = new Smarty;
		$this -> smartyViewer -> debugging = false;
		$this -> smartyViewer -> caching = false;
		$this -> smartyViewer -> force_compile = true;
	}

	public function illustrate($template) {
		$this -> smartyViewer -> display($template);
	}

	public function setPageTitle($title) {
		$this -> smartyViewer -> assign("title", $title);
	}

	public function setRightBlock($input) {
		$this -> smartyViewer -> assign("rightBlock", $input);
	}

	public function appendRightBlock($input) {
		$this -> smartyViewer -> append("rightBlock", $input);
	}

	public function appendDateBlock($input) {
		$this -> smartyViewer -> append("dateBlock", $input);
	}

	public function setCenterBlock($input) {
		$this -> smartyViewer -> assign("centerBlock", $input);
	}

	public function appendCenterBlock($input) {
		$this -> smartyViewer -> append("centerBlock", $input);
	}

	public function setHeader($input) {
		$this -> smartyViewer -> append("headerBlock", $input);
	}

	public function setFooter($input) {
		$this -> smartyViewer -> append("footerBlock", $input);
	}

	public function setMainStylePath($path) {
		$this -> smartyViewer -> assign("mainStyle", $path);
	}

	public function setMainPrintStylePath($path) {
		$this -> smartyViewer -> assign("mainPrintStyle", $path);
	}

	public function setFormStylePath($path) {
		$this -> smartyViewer -> assign("formStyle", $path);
	}

	public function setJQUIStylePath($path) {
		$this -> smartyViewer -> assign("JQUIStyle", $path);
	}

	public function setJQUICustomizedStylePath($path) {
		$this -> smartyViewer -> assign("CustomizeJQUIStyle", $path);
	}

	public function setJQueryPath($path) {
		$this -> smartyViewer -> assign("jQuery", $path);
	}

	public function setFormJSPath($path) {
		$this -> smartyViewer -> assign("formJS", $path);
	}

	public function setMainJSPath($path) {
		$this -> smartyViewer -> assign("mainJS", $path);
	}

	public function setCalendarStylePath($path) {
		$this -> smartyViewer -> assign("calendarStyle", $path);
	}

	public function setAJAXPath($path) {
		$this -> smartyViewer -> assign("AJAX", $path);
	}

	public function setJPlotPath($path) {
		$this -> smartyViewer -> assign("jqPlotStyle", $path);
	}

	public function setJQUIPath($path) {
		$this -> smartyViewer -> assign("JQUI", $path);
	}

	public function setJSCalendarPath($path) {
		$this -> smartyViewer -> assign("JSCalendar", $path);
	}

	public function setJSShamsiPath($path) {
		$this -> smartyViewer -> assign("JSShamsi", $path);
	}

}
?>