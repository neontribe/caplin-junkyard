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
 * Caplin_artifact Module Control Panel File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Toby Batch
 * @link		http://www.neontribe.co.uk
 */

/**
 * http://artifactory.caplin.com/api/search/artifact?name=*
 * HTTP Status 400 - Search term containing only wildcards is not permitted
 *
 * http://artifactory.caplin.com/artifactory/simple/caplin-release/com/caplin/motifs/fxmotif/server/RETAdapterSuite/1.3.3-271575/RETAdapterSuite-1.3.3-271575.zip
 */
class Caplin_artifact_mcp extends CI_Controller {

	public $return_data;

	private $_base_url;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=caplin_artifact';

		ee()->cp->set_right_nav(array(
			'module_home'	=> $this->_base_url,
			// Add more right nav items here.
		));
	}

	// ----------------------------------------------------------------

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function index()
	{
		ee()->cp->set_variable('cp_page_title',
								lang('caplin_artifact_module_name'));

		$vars = array();
		$baseurl = 'http://artifactory.caplin.com';
		$product_path = '/api/storage/caplin-release/com/caplin/motifs/fxmotif/server/RETAdapterSuite';

		if (!class_exists("Product_model")) {
			require_once __DIR__ . '/models/product_model.php';
		}
		$product = Product_model::fetch_from_uri($baseurl . '/artifactory/api/storage/caplin-release/com/caplin/motifs/fxmotif/server/RETAdapterSuite/');
		$vars['product'] = $product;

		// What do I gain from using CI models, they seem quite primitive
		// ee()->load->model('Product_model');
		$product->persist();

		return ee()->load->view('index', $vars, TRUE);
	}

	/**
	 * Start on your custom code here...
	 */

}
/* End of file mcp.caplin_artifact.php */
/* Location: /system/expressionengine/third_party/caplin_artifact/mcp.caplin_artifact.php */