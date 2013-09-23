<?php

class Artifactory_model extends CI_Model {

    protected $uid = NULL;

    protected $created = NULL;
    protected $createdBy = NULL;
    protected $lastModified = NULL;
    protected $lastUpdated = NULL;
    protected $metadataUri = NULL;
    protected $modifiedBy = NULL;
    protected $path = NULL;
    protected $repo = NULL;
    protected $uri = NULL;

	protected $json = NULL;

	public function __construct() {
		parent::__construct();
	}

	// Static instantiators

	public static function fetch_from_uri($uri, $model = NULL) {
		$rawjson = file_get_contents($uri);
		$json = json_decode($rawjson, TRUE);

		if (!$json) {
			// todo log error
			return FALSE;
		}

		if (! $model) {
			$prodmod = new self();
		}
		else {
			$prodmod = $model;
		}
		$prodmod->json = $json;
		$prodmod->set_created(self::get_value($json, 'created'));
		$prodmod->set_createdBy(self::get_value($json, 'createdBy'));
		$prodmod->set_lastModified(self::get_value($json, 'lastModified'));
		$prodmod->set_metadataUri(self::get_value($json, 'metadataUri'));
		$prodmod->set_modifiedBy(self::get_value($json, 'modifiedBy'));
		$prodmod->set_path(self::get_value($json, 'path'));
		$prodmod->set_repo(self::get_value($json, 'repo'));
		$prodmod->set_uri(self::get_value($json, 'uri'));

		return $prodmod;
	}

	private static function get_value($data, $key) {
		if (isset($data[$key])) {
			return $data[$key];
		}
		else {
			return NULL;
		}
	}

	// Util methods

	public function equals($obj) {
		if (method_exists($obj, 'get_uid')) {
			return strcmp($obj->get_uid(), $this->get_uid());
		}

		return FALSE;
	}

	public function get_json() {
		return $this->json;
	}

	// database

	public function persist() {
		$this->db->get('caplin_artifactory_artifact');
		$query = $this->db->get_where(
			'caplin_artifactory_artifact',
			array(
				'uid' => $this->get_uid()
			),
			1
		);
        $result = $query->result();

		$data = array(
			'uid' => $this->get_uid(),
			'created' => $this->get_created(),
			'lastModified' => $this->get_createdBy(),
			'lastUpdated' => $this->get_lastUpdated(),
			'metadataUri' => $this->get_metadataUri(),
			'modifiedBy' => $this->get_modifiedBy(),
			'path' => $this->get_path(),
			'repo' => $this->get_repo(),
			'uri' => $this->get_uri(),
			'json' => json_encode($this->get_json()),
		);

		if (count($result)) {
			// do update
			$this->db->where('uid', $this->get_uid());
			$this->db->update('caplin_artifactory_artifact', $data);
		}
		else {
			$this->db->insert('caplin_artifactory_artifact', $data);
		}
	}

	public function __toString() {
		return $this->get_path();
	}

	// Fluent Setters and getters ///////////////////////////

	public function get_created() {
		return $this->created;
	}

	public function set_created($created) {
		$this->created = $created;
		return $this;
	}

	public function get_createdBy() {
		return $this->createdBy;
	}

	public function set_createdBy($createdBy) {
		$this->createdBy = $createdBy;
		return $this;
	}

	public function get_lastModified() {
		return $this->lastModified;
	}

	public function set_lastModified($lastModified) {
		$this->lastModified = $lastModified;
		return $this;
	}

	public function get_lastUpdated() {
		return $this->lastUpdated;
	}

	public function set_lastUpdated($lastUpdated) {
		$this->lastUpdated = $lastUpdated;
		return $this;
	}

	public function get_metadataUri() {
		return $this->metadataUri;
	}

	public function set_metadataUri($metadataUri) {
		$this->metadataUri = $metadataUri;
		return $this;
	}

	public function get_modifiedBy() {
		return $this->modifiedBy;
	}

	public function set_modifiedBy($modifiedBy) {
		$this->modifiedBy = $modifiedBy;
		return $this;
	}

	public function get_path() {
		return $this->path;
	}

	public function set_path($path) {
		$this->path = $path;
		return $this;
	}

	public function get_repo() {
		return $this->repo;
	}

	public function set_repo($repo) {
		$this->repo = $repo;
		return $this;
	}

	public function set_uid($uid) {
		$this->uid = $uid;
		return $this;
	}

	public function get_uid() {
		return $this->uid;
	}

	public function get_uri() {
		return $this->uri;
	}

	public function set_uri($uri) {
		$this->uri = $uri;
		$this->uid = md5($uri);
		return $this;
	}
}