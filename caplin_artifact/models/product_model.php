<?php


if (!class_exists("Artifactory_model")) {
	require_once __DIR__ . '/artifactory_model.php';
}

if (!class_exists("Release_model")) {
	require_once __DIR__ . '/release_model.php';
}

class Product_model extends Artifactory_model {

	protected $releases = array();

	public static function fetch_from_uri($uri, $model = NULL) {
		if (! $model) {
			$model = new Product_model();
		}
		$model = parent::fetch_from_uri($uri, new self());
		$json = $model->get_json();

		if (isset($json['children']) && is_array($json['children'])) {
			foreach ($json['children'] as $child) {
				$_uri = sprintf(
					'%s/%s',
					rtrim($model->get_uri(), '/'),
					ltrim($child['uri'], '/')
				);
				$release = parent::fetch_from_uri($_uri, new Release_model());
				$model->add_release($release);
			}
		}

		return $model;
	}

	public function persist() {
		parent::persist();

		// This is a little innefficient
		$this->db->delete(
			'exp_caplin_artifactory_release',
			array('puid' => $this->get_uid())
		);

		foreach ($this->get_all_releases() as $release) {
			$release->persist();
			$this->db->insert(
			'exp_caplin_artifactory_release',
				array(
					'puid' => $this->get_uid(),
					'ruid' => $release->get_uid(),
				)
			);
		}

		// TODO, this code may leave orphaned releases that are no longer attached to a product
	}

	// TODO this should be replaced with add/remove/get

	public function add_release($release) {
		$this->releases[$release->get_uid()] = $release;
	}

	public function get_all_releases() {
		return $this->releases;
	}

	public function __toString() {
		return
			$this->get_path() . "[\n\t" . implode("\n\t", $this->get_all_releases()) . "\n]";
	}
}