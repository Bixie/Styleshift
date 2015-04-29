<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: Zoofilter
		The controller class for zoofilter tasks
*/
class ZoofilterController extends AppController {
	
	protected $search_config = array();
	protected $items = array();
	protected $itemsTotal = 0;
	protected $search_params = null;
	protected $search_id = 0;
	
	/*
		Function: search
			Do the actual search

		Returns:
			HTML
	*/
	public function search( )
	{
		// Get basic classes
		$db = $this->app->database;
		
		// For ZOOorder
		$this->addDefaultParams();
		
		$search_id = $this->getSearchId();
		
		$this->setRedirect(JRoute::_('index.php?option=com_zoo&controller=zoofilter&task=dosearch&search_id='.$search_id.'&app_id='.JRequest::getInt('app_id', 1).'&Itemid='.JRequest::getInt('Itemid'), false));
		$this->redirect();
	}
	
	protected function addDefaultParams()
	{
		$category_id = JRequest::getVar('category_id', 0);
		
		$app				= $this->app->zoo->getApplication();
		$params	          	= $category_id ? $this->app->table->category->get($category_id)->getParams('site') : $app->getParams('frontpage');
		$items_per_page   	= $params->get('config.items_per_page', 15);
		
		// Ordering Layout
		jimport( 'joomla.plugin.plugin' );
		jimport( 'joomla.html.parameter' );
		$plugin =& JPluginHelper::getPlugin('system', 'zoofilter');
		$pluginParams = new JParameter($plugin->params);
		
		$ordering_layout = $pluginParams->get('ordering_layout', 'ordering');
		
		// Render Layout
		$layout = JRequest::getVar('result_layout', 'default');
	}
	
	protected function addDefaultParam($name, $value)
	{
		if(!JRequest::getVar($name, null))
		{
			JRequest::setVar($name, $value);
		}
	}
	
	public function dosearch( )
	{
		$search_id = JRequest::getInt('search_id', null);
		$this->search_params = $this->app->zf->getSearchParams($search_id);

		$search_params = $this->search_params;
			
		$app = $this->app->zoo->getApplication();
		$type = $app->getType(@$search_params['type']);
		
		// Request Variables
		$page = JRequest::getInt( 'page', 1 );
		
		// Get site params for the current application
		$params = $app->getParams( 'site' );
		
		// Search Configuration
		$elayout = JFile::stripExt(@$search_params['elayout']);

		// set renderer
		$search_render = $this->app->renderer->create('item')->addPath(array($this->app->path->path('modules:mod_zoofilter')));
		$search_config = $search_render->getConfig('item')->get($app->getGroup().'.'.$type->id.'.'.$elayout);
		$this->search_config = @$search_config['elements'];
		
		if(!$this->search_config)
		{
			$this->search_config = array();
		}
		
		// because logic is new feature in zoofilter 2.5, we make sure that logic is set even when 
		// user didn't go to layout and set logic (or just save it without changes so parameters are writen)
		// resolved ticket #7
		foreach ($this->search_config as $key => $row) 
		{
			if (!array_key_exists('logic', $row)) 
			{
				$this->search_config[$key]['logic'] = "AND";
			}
		}
		
		// The data passed by the search form
		$elements = array();
		if(isset($search_params['elements']))
		{
			$elements = $search_params['elements'];
		}
		
		// Apply filters
		$this->applyFilters( $elements );		
		
		$this->search_id = $search_id;
		
		$session_key = 'ZOOFILTER_SEARCH_FORM_' . @$search_params['module_id'] ;
		$this->app->system->application->setUserState($session_key, serialize($elements));
		
		// Display	
		$this->display( );
	}
		
	public function display( ){
		
		$search_params = $this->search_params;
	
		$items = $this->items;
		$app = $this->app->zoo->getApplication();
		
		// Get site params for the current application
		$params = $app->getParams( 'site' );
	
		if (count($items) == 1 && $search_params['redirect_if_one']) {
			
			$item = array_pop($items);
			$link = JRoute::_('index.php?option=com_zoo&task=item&item_id='.$item->id, false);
			JFactory::getApplication()->redirect($link);
		}
		
		// Pepare the view
		$view = new AppView( array(
			'name' => 'category'
		));
		
		$layout = strlen(@$search_params['page_layout']) ? @$search_params['page_layout'] : 'search';
		
		// Json support
		if(JRequest::getVar('format', '') == 'json')
		{
			$layout = 'json';
		}
		
		$item_layout = $search_params['layout'];
		
		$view->addTemplatePath( $app->getTemplate( )->getPath( ) );
		$view->addTemplatePath( $this->app->path->path('zoofilter:layouts') );
		$view->setLayout( $layout );

		// Add the necessary variables for the view
		$view->app = $this->app;
		$view->items = $items;
		$view->application = $app;
		$view->item_layout = $item_layout;
		
		$item_ids = array( );
		foreach ( $items as $item ) 
		{
			$item_ids[] = $item->id;
		}

		// get item pagination
		$items_per_page = $search_params['items_per_page'];
		$page = JRequest::getVar( 'page', 1 );
		$view->pagination = $this->app->pagination->create( $this->itemsTotal, $page, $items_per_page, 'page', 'app' );
		$view->pagination->setShowAll( $items_per_page == 0 );
		$uri = JURI::getInstance();
		$uri->setVar('page', null); 
		$view->total = $this->itemsTotal;
		
		$view->pagination_link = $uri->toString();
		
		// set template and params
		$view->assign('template', $app->getTemplate());
		$view->params = $params;
		$view->assign('search_params', $search_params);
		
		// set renderer
		$uri = JURI::getInstance();
		$permalink = $uri->toString(array('scheme', 'host', 'port')) . JRoute::_('index.php?option=com_zoo&controller=zoofilter&task=dosearch&app_id='.$app->id.'&search_id='.$this->search_id);
		
		$view->show_permalink = @$search_params['show_permalink'];
		$view->permalink = $permalink;
		$view->app_id = $app->id;
		$view->search_id = $this->search_id;
		$view->show_title = $search_params['show_title'];
		$view->show_ordering = $search_params['show_ordering'];
		$view->columns = $search_params['columns'];
		$view->page_title = $search_params['page_title'];
		$view->renderer = $this->app->renderer->create( 'item' );
		$view->renderer->addPath( array($app->getTemplate( )->getPath( ), $this->app->path->path('zoofilter:'), $this->app->path->path( 'component.site:' )) );
		
		// Add ordering
		$this->app->path->register($this->app->path->path('zoofilter:ordering/renderer'), 'renderer');
		$order_renderer = $this->app->renderer->create('ordering')->addPath(array( $this->app->path->path('zoofilter:ordering')) );
		$elements_layout = $search_params['ordering_layout'];
		$type = $app->getType($search_params['type']);
		
		$order_elements = $order_renderer->render('item.'.$elements_layout, compact('type') );
		$view->assign('order_elements', $order_elements);
		
		// display view				
		$view->display( );
	}
	
	/**
	 * Get element param
	 */
	protected function getParamFrom( $param, $element, $default='' )
	{
		// Search for the right element config
		foreach($this->search_config as $oc) if (@$oc['element'] == $element) {
			$params = $this->app->data->create($oc);
			return $params->find($param);
		}
		return '';
	}

	/**
	 * Apply the filters
	 */
	protected function applyFilters( $elements )
	{
		$search_params = $this->search_params;
		
		$type_id = $search_params['type'];
		$app_id = $search_params['app_id'];
		
		// Now thanks to the model
		$model = JModel::getInstance('Zoofilter', 'ZLModel');
		// $model = $this->app->zlmodel->getNew('zoofilter');
		
		$model->setState('select', 'DISTINCT a.*');
		$model->setState('app_id', $app_id);
		$model->setState('type', $type_id);
			  
		// Core: Name element
		if( strlen( trim( @$elements['_itemname'] ) ) )
		{
			$name = array(
				'value' => trim( @$elements['_itemname'] ),
				'type' => $this->getParamFrom('layout._search_type', '_itemname'),
				'logic' => $this->getParamFrom('search._logic', '_itemname')
			);
			
			$model->setState('itemname', $name);
		}
		unset($elements['_itemname']);
		
		/**
		 * Categories Core Element filter
		 */
		// retrieve Cat params
		$cat_search_config = array(); 
		$i = 0;
		
		foreach( $this->search_config as $key => $oc ) 
		{
			if ( @$oc['element'] == '_itemcategory' ) 
			{
				$cat_search_config['_itemcategory-'.$i] = $oc;
				$i++;
			}
		}
		
		// create the Category object
		$cat_elements = array(); $i = 0;

		foreach ( $elements as $key => $value ) 
		{
			if ( strpos( $key, "_itemcategory" ) !== false ) 
			{
				$el_key = '_itemcategory-'.$i;
				$cat_elements[$el_key]['values'] = $value;
				$cat_elements[$el_key]['params'] = @$cat_search_config[$el_key];
				unset($elements[$key]);
				$i++;
			}
		}
		
		if ( !empty($cat_elements) )
		{
			$cats_filter = array();
			foreach($cat_elements as $element)
			{
				$cat_filter = array();
				$params = $this->app->data->create($element['params']);

				$values = $element['values'];
				$cat_filter['logic'] = $params->find('search._logic', 'AND');
				$cat_filter['mode'] = $params->find('layout._mode', 'AND');
				$cat_filter['value'] = array();
				
				foreach ($values as $id) 
				{
					// Skip id if empty
					if (empty( $id )) continue;

					$id = explode(',', $id);
					$cat_filter['value'][] = array_pop($id);
				}
				
				$cats_filter[] = $cat_filter;
			}


			
			if( count($cat_filter) )
			{
				$model->setState('categories', $cats_filter);
			}			
		}
		
		/**
		 * Parse the other search values
		 */
		$filters = array();
		foreach ( $elements as $identifier => $value )
		{
			if(is_array($value))
			{
				$empty = true;
				// Skip unused elements
				foreach($value as $v)
				{
					if ( !empty( $v ) ) 
					{
						$empty = false;
					}	
				}
				
				if($empty)
				{
					continue;	
				}
			} 
			else 
			{
				if(empty($value))
				{
					continue;
				}
			}
			
			// Search for the right element config
			$search_type = $this->getParamFrom('layout._search_type', $identifier);
			$logic = $this->getParamFrom('search._logic', $identifier);
			$mode = $this->getParamFrom('layout._mode', $identifier);
			$convert = $this->getParamFrom('layout._convert', $identifier);
			
			// get Element type
			$el_class = $this->app->table->application->get($app_id)->getType($type_id)->getElement($identifier);
			
			$el_type = $el_class->config->type;
			
			// Options elements should always be searched exatcly, not partial, and we must decode them
			$is_select = ( $el_class instanceof ElementOption ) ? true : false;
			
			// is a multiple choice ?
			if ( is_array( $value ) && count( $value ) )
			{
				// more than one selected?
				if( count($value) > 1 )
				{
					// Search for 'value\nvalue\nvalue' pattern
					$selections = array();
					foreach ($value as $key => $option)
					{
						// Was something selected?
						if ( !empty( $option ) )
						{
							$selections[$key] = $option;
						}
					}
					
					$filters[$identifier] = array(
						'logic' => $logic,
						'value' => $selections,
						'mode' => $mode,
						'type' => $search_type,
						'el_type' => $el_type,
						'is_select' => $is_select,
						'convert' => $convert
					);
				}
				else // only one selection
				{
					$is_select ? $value = urldecode(array_shift($value)) : $value = array_shift( $value );
					$is_select ? $search_type = 'partial' : $search_type;
					$filters[$identifier] = array(
						'logic' => $logic,
						'value' => array($value),
						'mode' => $mode,
						'type' => $search_type,
						'el_type' => $el_type,
						'is_select' => $is_select,
						'convert' => $convert
					);
				}
			}
			else // is a single choice, multiselection is not allowed
			{
				if( strlen($value) )
				{					
					// Decode value
					if ($is_select) 
					{
						$value = urldecode($value);
					}
					
					$filters[$identifier] = array(
						'logic' => $logic,
						'value' => $value,
						'type' => $search_type,
						'el_type' => $el_type,
						'is_select' => $is_select,
						'convert' => $convert
					);
				}
			}
		}

		if( count( $filters ) )
		{
			$model->setState('elements', $filters);
		}
		
		$items_per_page = @$search_params['items_per_page'];
		$page = JRequest::getVar( 'page', 1 );
		
		$this->setOrder($model);
		
		if(!$items_per_page == 0)
		{
			$model->setState('limitstart', ($page - 1) * $items_per_page );
			$model->setState('limit', $items_per_page);
		}
		
		$this->items = $model->getList();
		$this->itemsTotal = $model->getResult();
		
		// Debug
		jimport( 'joomla.plugin.plugin' );
		jimport( 'joomla.html.parameter' );
		$plugin =& JPluginHelper::getPlugin('system', 'zoofilter');
		$pluginParams = new JParameter($plugin->params);
		
		if ($pluginParams->get('debug', false) && JRequest::getVar('format', '') != 'json')
		{
			// pretty print of sql
			$find = Array("FROM", "WHERE", "AND", "ORDER BY", "LIMIT", "OR");
			$replace = Array("<br />FROM", "<br />WHERE", "<br />AND", "<br />ORDER BY", "<br />LIMIT", "<br />OR");
			$in = $model->getQuery();
			echo '<b>Query</b>';
			echo str_replace($find, $replace, $in);
			
			$in = $model->getResultQuery();
			echo '<br /><b>Result Query</b>';
			echo str_replace($find, $replace, $in);
		}
	}
	
	protected function setOrder(&$model)
	{
		$search_params = $this->search_params;
		
		$order = JRequest::getVar('order', @$search_params['order']);
		$direction = JRequest::getVar('direction', @$search_params['direction']);
		
		// Default ordering with app config
		if(!$order)
		{
			$app = $this->app->zoo->getApplication();
			$order = array_values((array)$app->getParams()->get('global.config.item_order', array('_itemname')));

			$model->setState('order_by', $order);
		}
		else switch($order)
		{
			case '_random': 
				$model->setState('order_by', 'RAND()');
				break;
			case '_itemname': 
				$model->setState('order_by', 'a.name '.$direction);
				break;
			case '_itemaccess': 
				$model->setState('order_by', 'a.access '.$direction);
				break;
			case '_itemcreated': 
				$model->setState('order_by', 'a.created '.$direction);
				break;
			case '_itemmodified': 
				$model->setState('order_by', 'a.modified '.$direction);
				break;
			case '_itemhits': 
				$model->setState('order_by', 'a.hits '.$direction);
				break;
			case '_itempublish_down': 
				$model->setState('order_by', 'a.publish_down '.$direction);
				break;
			case '_itempublish_up': 
				$model->setState('order_by', 'a.publish_up '.$direction);
				break;
			case '_itemhits': 	
				$model->setState('order_by', 'a.hits '.$direction);
				break;
			default:
				 if($order){
				 	$model->setState('order', $order);
					$model->setState('direction', $direction);	
				} else{
					$model->setState('order_by', 'a.name ASC');	
				}
				break;
		}
	}

	/*
		Function: getSearchId
	*/
	protected function getSearchId()
	{
		$params = JRequest::get('request');
		$params = json_encode($params);
		$user_id = JFactory::getUser()->id;
		
		$uuid = md5($params);
		
		$db = $this->app->database;
		$query = new ZLQuery();
		$query->select('search_id')->from('#__zoo_zoofilter_searches')->where('search_uuid LIKE '.$db->Quote($uuid));
		$db->setQuery($query);
		
		$this->search_id = $db->loadResult();
		
		// Generate one if not already searched that
		if(!$this->search_id)
		{
			$query = "INSERT INTO #__zoo_zoofilter_searches (search_uuid, search_params, user_id, datetime) ";
			$query .= "VALUES (".$db->quote($uuid).", ".$db->quote($params).", ".$db->quote($user_id).", NOW())";
			$db->query($query);
			
			$this->search_id = $db->insertid();
		}
		
		return $this->search_id;
	}
	

	/***********************************************
	********** AJAX CATEGORY SEACH *****************
	*************************************************/
	/*
		Function: getCats
			Get the categories

	   Parameters:
            	root - root that will be used, subcategories are returned
            	allTheRest - if true all subcategories with their subcategories will be returned as formated tree
            		         otherwise only immediately subcategories are returned;
            		         used when max depth is set
            	app_id - application id

		Returns: JSON
	*/
	function getCats()
	{		
		$root = $this->app->request->getInt('root', 0);
		$allTheRest = $this->app->request->getInt('all', 0);
		$application = $this->app->request->getInt('app_id', 0);

		$search_id = JRequest::getInt('search_id', null);
		$search_params = $this->app->zf->getSearchParams($search_id);

		// if no instance, get app from session
		$application = ($application != 0) ? $this->app->table->application->get($application) : $this->app->getApplication();

		if (is_object($application))
		{
			if ($allTheRest) {
				$maxLevel = 9999;
			} else {
				$maxLevel = 0;	
			} 

			// get category tree list
			$list = $this->app->tree->buildList($root, $application->getCategoryTree(), array(), '-&nbsp;', '.&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;', 0, $maxLevel);

			// create options
			$categories = array();
			foreach ($list as $category) {
				$categories[$category->id] = $category->treename;
			}

			echo json_encode($categories);
		}
		return;
	}

}

/*
	Class: ZoofilterControllerException
*/
class ZoofilterControllerException extends AppException {}
