<?php

if (!class_exists("Artifactory_model")) {
	require_once __DIR__ . '/artifactory_model.php';
}

class Release_model extends Artifactory_model {

	public function get_release_name() {
		return basename($this->get_path());
	}

	public function __toString() {
		return $this->get_release_name();
	}
}