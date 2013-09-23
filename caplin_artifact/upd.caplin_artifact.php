<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Caplin_artifact Module Install/Update File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Toby Batch
 * @link		http://www.neontribe.co.uk
 */

class Caplin_artifact_upd {

	public $version = '1.0';

	private $EE;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}

	// ----------------------------------------------------------------

	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		$mod_data = array(
			'module_name'			=> 'Caplin_artifact',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "y",
			'has_publish_fields'	=> 'n'
		);

		$this->EE->db->insert('modules', $mod_data);

		ee()->load->dbforge();

		if ( ! ee()->db->table_exists('caplin_artifactory_artifact'))
		{
			ee()->dbforge->add_field(array(
				'uid' => array('type' => 'varchar', 'constraint' => 255),
				'created' => array('type' => 'varchar', 'constraint' => 255),
				'lastModified' => array('type' => 'varchar', 'constraint' => 255),
				'lastUpdated' => array('type' => 'varchar', 'constraint' => 255),
				'metadataUri' => array('type' => 'varchar', 'constraint' => 255),
				'modifiedBy' => array('type' => 'varchar', 'constraint' => 255),
				'path' => array('type' => 'varchar', 'constraint' => 255),
				'repo' => array('type' => 'varchar', 'constraint' => 255),
				'uri' => array('type' => 'varchar', 'constraint' => 255),
				'json' => array('type' => 'text'),
			));

			ee()->dbforge->add_key('uid', TRUE);
			ee()->dbforge->create_table('caplin_artifactory_artifact');
		}

		if ( ! ee()->db->table_exists('caplin_artifactory_release'))
		{
			ee()->dbforge->add_field(array(
				'puid' => array('type' => 'varchar', 'constraint' => 255),
				'ruid' => array('type' => 'varchar', 'constraint' => 255),
			));

			ee()->dbforge->create_table('caplin_artifactory_release');
		}

		return TRUE;
	}

	// ----------------------------------------------------------------

	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */
	public function uninstall()
	{
		$mod_id = $this->EE->db->select('module_id')
								->get_where('modules', array(
									'module_name'	=> 'Caplin_artifact'
								))->row('module_id');

		$this->EE->db->where('module_id', $mod_id)
					 ->delete('module_member_groups');

		$this->EE->db->where('module_name', 'Caplin_artifact')
					 ->delete('modules');

		// $this->EE->load->dbforge();
		// Delete your custom tables & any ACT rows
		// you have in the actions table

		return TRUE;
	}

	// ----------------------------------------------------------------

	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */
	public function update($current = '')
	{
		// If you have updates, drop 'em in here.
		return TRUE;
	}

}
/* End of file upd.caplin_artifact.php */
/* Location: /system/expressionengine/third_party/caplin_artifact/upd.caplin_artifact.php */