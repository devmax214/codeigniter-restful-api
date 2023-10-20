<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin Controller
 * Created by: arangde
* Date: 07/25/2014
 * 
 */
class Admin extends CI_Controller {

	protected $logged;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('settings_model');
		$this->session->set_userdata($this->settings_model->getSettings('system'));
				
		$logged = $this->session->userdata('loggedin');
		if (empty($logged)) {
			redirect('/index', 'refresh');
		}
		
		//$role = $this->session->userdata('user_role');
		//if($role != 9) {
		//	redirect('/index', 'refresh');
		//}

		$this->load->model('user_model');
		$this->load->model('post_model');
		
		$this->load->library('pagination');
		$this->config_pagination = array(
			'per_page' => 5,
	    	'last_link' => "Last &rsaquo;",
	    	'last_tag_open' => "<li>",
	    	'last_tag_close' => "</li>",
	    	'first_link' => "&lsaquo; First",
	    	'first_tag_open' => "<li>",
	    	'first_tag_close' => "</li>"
    	);
	}

    public function index() {
    	redirect('/user/index', 'refresh');
    	
    	$data = array();
    	
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'admin/users', $data)
	    	->render('layouts/admin');
    }
    
    public function resetPass($user_id) {
    	$password = $this->input->post("password");
    	if($this->user_model->update("users", array("password"=>sha1($this->config->item('encryption_key'). $password)), array("user_id"=>$user_id)))
    		echo json_encode(array("success" => "It has been successed!"));
    	else
    		echo json_encode(array("error" => translate("It has been failed to reset password", "admin")));
    	exit();
    }

    public function orders($view_by='all', $start=0) {
    	$data = array();
    
    	if($this->session->userdata("msg_success") != "") {
    		$data['msg_success'] = $this->session->userdata("msg_success");
    		$this->session->unset_userdata("msg_success");
    	}
    	if($this->session->userdata("msg_error") != "") {
    		$data['msg_error'] = $this->session->userdata("msg_error");
    		$this->session->unset_userdata("msg_error");
    	}
    
    	$where = '';
    	if($view_by == 'confirmed')
    		$where = 'orders.status ='. ORDER_STATUS_CONFIRMED;
    	elseif($view_by == 'not-confirmed')
    		$where = 'orders.status ='. ORDER_STATUS_PUBLIC;
    	elseif($view_by == 'completed')
    		$where = 'orders.status ='. ORDER_STATUS_SUCCESSED;
    	 
    	$orders = $this->order_model->getOrders($where, $this->config_pagination['per_page'], $start);
    
    	foreach($orders as $i=>$order) {
    
    		$orders[$i] = $order;
    	}
    
    	$data['orders'] = $orders;
    	$data['view_by'] = $view_by;
    
    	$pagination_admin= clone($this->pagination);
    
    	$this->config_pagination['base_url'] = base_url('/admin/orders/'. $view_by);
    	$this->config_pagination['total_rows'] = $this->order_model->getOrdersCount($where);
    	$this->config_pagination['uri_segment'] = 4;
    
    	$pagination_admin->initialize($this->config_pagination);
    
    	$data['pagination'] = $pagination_admin;
    
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'admin/orders', $data)
	    	->render('layouts/admin');
    }
    
    public function completeOrder($order_id) {
    	$this->order_model->update("orders", array('status' => ORDER_STATUS_SUCCESSED), array("order_id"=>$order_id));
    	
    	echo json_encode(array('success' => "true"));
    }
    
    public function deleteOrder($order_id) {
    	$this->order_model->delete("addresses", array("order_id"=>$order_id));
    	$this->order_model->delete("photos", array("order_id"=>$order_id));
    	$this->order_model->delete("orders", array("order_id"=>$order_id));
    	 
    	redirect('/admin/orders');
    }
    
    public function products($start=0) {
    	$data = array();
    
    	if($this->session->userdata("msg_success") != "") {
    		$data['msg_success'] = $this->session->userdata("msg_success");
    		$this->session->unset_userdata("msg_success");
    	}
    	if($this->session->userdata("msg_error") != "") {
    		$data['msg_error'] = $this->session->userdata("msg_error");
    		$this->session->unset_userdata("msg_error");
    	}
    
    	$where = '';
    	 
    	$products = $this->product_model->getProducts($where, $this->config_pagination['per_page'], $start);
    
    	$data['products'] = $products;
    	
    	$pagination_admin= clone($this->pagination);
    
    	$this->config_pagination['base_url'] = base_url('/admin/products/');
    	$this->config_pagination['total_rows'] = $this->product_model->getProductsCount($where);
    	$this->config_pagination['uri_segment'] = 4;
    
    	$pagination_admin->initialize($this->config_pagination);
    
    	$data['pagination'] = $pagination_admin;
    
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'admin/products', $data)
	    	->render('layouts/admin');
    }
    
    public function editProduct($product_id = '') {
    	$data = array();
    	$cmd = $this->input->post("cmd");
    	 
    	if($cmd == "add" || $cmd == "update") {
    		$product = array(
    			'product_name' => $this->input->post('product_name'),
    			'description' => $this->input->post('description')
    		);
    
    		$errors = array();
    		$upload_data = array();
    		
    		if($this->input->post("upload") == "1") {
	    		$upload = $this->config->item('upload');
	    		$upload_folder = 'product/'. time(). '/';
	    		$upload['upload_path'] = $upload['upload_base']. $upload_folder;
	    
	    		if(!is_dir($upload['upload_path'])) {
	    			mkdir(rtrim($upload['upload_path'], '/'), 0777, true);
	    		}
	    
	    		$this->load->library('upload', $upload);
	    		
    			if (!$this->upload->do_multi_upload('product_photo')) {
    				$errors['upload'] = $this->upload->display_errors('', '');
    			}
    			else {
    				$upload_data = $this->upload->get_multi_upload_data();
    			}
    		}
    		
    		if(empty($errors)) {
    			if($cmd == "add" || $product_id == '') {
    				$product['created'] = date("Y-m-d H:i:s");
    				$product_id = $this->product_model->add('products', $product);
    			}
    			else {
    				$this->product_model->update('products', $product, array('product_id' => $product_id));
    				
    				$product_photo_ids = $this->input->post("product_photo_ids");
    				if($product_photo_ids == "") {
    					$this->product_model->delete('product_photos', array('product_id' => $product_id));
    				}
    				else {
    					$this->product_model->delete('product_photos', "product_id = ". $product_id. " AND product_photo_id NOT IN(". $product_photo_ids. ")");
    				}
    			}
    			
    			foreach($upload_data as $i => $upload_photo) {
    				$photo = array(
    					"photo_url" => $upload['upload_url']. $upload_folder. $upload_photo['file_name'],
    					"sort_order" => $i,
    					"product_id" => $product_id
    				);
    				
    				$this->product_model->add('product_photos', $photo);
    			}
    			
    			$this->session->set_userdata("msg_success", "It has been successed to save product!");
    			 
    			redirect("/admin/products", "refresh");
    		}
    		else {
    			$data['msg_error'] = $errors['upload'];
    			$data['product'] = $product;
    		}
    	}
    	 
    	if($product_id != "") {
    		$data['product'] = $this->product_model->get('products', array('product_id' => $product_id), 1);
    		$data['product_photos'] = $this->product_model->get('product_photos', array('product_id' => $product_id));
    		$data['cmd'] = "update";
    	}
    	else
    		$data['cmd'] = "add";
    	
    	$data['product_id'] = $product_id;
    
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'admin/editProduct', $data)
	    	->render('layouts/admin');
    }
    
    public function deleteProduct($product_id) {
    	$this->product_model->delete("sub_products", array("product_id"=>$product_id));
    	$this->product_model->delete("products", array("product_id"=>$product_id));
    
    	redirect('/admin/products');
    }
    
    public function subProducts($product_id) {
    	$data = array();
    
    	if($this->session->userdata("msg_success") != "") {
    		$data['msg_success'] = $this->session->userdata("msg_success");
    		$this->session->unset_userdata("msg_success");
    	}
    	if($this->session->userdata("msg_error") != "") {
    		$data['msg_error'] = $this->session->userdata("msg_error");
    		$this->session->unset_userdata("msg_error");
    	}
    
    	$where = '';
    	
    	$product = $this->product_model->get('products', array("product_id" => $product_id), 1);
    
    	$sub_products = $this->product_model->get('sub_products', array("product_id" => $product_id));
    
    	$data['sub_products'] = $sub_products;
    	$data['product'] = $product;
    	
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'admin/subProducts', $data)
	    	->render('layouts/admin');
    }
    
    public function editSubProduct($product_id, $sub_product_id = '') {
    	if($product_id == '') {
    		$this->session->set_userdata("msg_error", "You have selected not exist product!");
    		redirect("/admin/products", "refresh");
    	}
    	
    	$data = array();
    	$cmd = $this->input->post("cmd");
    
    	if($cmd == "add" || $cmd == "update") {
    		$sub_product = array(
    			'sub_product_name' => $this->input->post('sub_product_name'),
    			'description' => $this->input->post('description'),
    			'price' => $this->input->post('price'),
    			'photo_count' => $this->input->post('photo_count')
    		);
    		
    		if($this->input->post("upload") == "1") {
    			$upload = $this->config->item('upload');
    			$upload_folder = 'product/'. time(). '/';
    			$upload['upload_path'] = $upload['upload_base']. $upload_folder;
    			 
    			if(!is_dir($upload['upload_path'])) {
    				mkdir(rtrim($upload['upload_path'], '/'), 0777, true);
    			}
    			 
    			$this->load->library('upload', $upload);
    			 
    			if ( !$this->upload->do_upload('sub_product_photo')) {
    				$data['msg_error'] = $this->upload->display_errors('', '');
    			}
    			else {
    				$upload_data = $this->upload->data();
    				$sub_product['sub_product_photo'] = $upload['upload_url']. $upload_folder. $upload_data['file_name'];
    			}
    		}
    		
    		if(!isset($data['msg_error'])) {
        		if($cmd == "add" || $sub_product_id == '') {
	    			$sub_product['product_id'] = $product_id;
	    			$sub_product_id = $this->product_model->add('sub_products', $sub_product);
	    		}
	    		else
	    			$this->product_model->update('sub_products', $sub_product, array('sub_product_id' => $sub_product_id));
    			 
	    		$this->session->set_userdata("msg_success", "It has been successed to save sub product!");
	    
	    		redirect("/admin/subProducts/". $product_id, "refresh");
    		}
    		else {
    			$data['sub_product'] = $sub_product;
    		}
    	}
    
    	if($sub_product_id != "") {
    		if(!isset($data['sub_product']))
    			$data['sub_product'] = $this->product_model->get('sub_products', array('sub_product_id' => $sub_product_id), 1);
    		
    		$data['cmd'] = "update";
    	}
    	else
    		$data['cmd'] = "add";
    	 
    	$data['sub_product_id'] = $sub_product_id;
    
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'admin/editSubProduct', $data)
	    	->render('layouts/admin');
    }

    public function deleteSubProduct($product_id, $sub_product_id) {
    	$this->product_model->delete("sub_products", array("sub_product_id"=>$sub_product_id));
    	
    	redirect('/admin/subProducts/'. $product_id);
    }
    
	public function exportOrders() {
		$order_ids = $this->input->post("export_order");
		
		if(count($order_ids) == 0) {
			$this->session->set_userdata("msg_error", "No orders has been selected");
			redirect('/admin/ordes', 'refresh');
		}
		
		$this->load->library('zip');
		
		$time = time();
		$upload = $this->config->item('upload');
		$upload_base = $upload['upload_base']. 'order/'. $time. '/';
		if(!is_dir($upload_base)) {
			mkdir(rtrim($upload_base, '/'), 0777, true);
		}
		
		$upload_images =$upload_base. 'images/';
		if(!is_dir($upload_images)) {
			mkdir(rtrim($upload_images, '/'), 0777, true);
		}
		
		$j= 0;
		foreach($order_ids as $i => $order_id) {
			$order = $this->order_model->getOrder($order_id);
			$photos = $this->order_model->getOrderPhotos($order_id);
			
			$images = array("Image" => array());
			
			foreach($photos as $photo) {
				$j++;
				$photo_name = $photo['photo_url'];
				$ext = explode(".", $photo_name);
				$ext = end($ext);
				$photo_name = $j. ".". $ext;
				
				copy($photo['photo_url'], $upload_images. $photo_name);
				
				$images["Image"][] = array("@value" => "", "@attributes" => array("href" => "file://images/".$photo_name));
			}
			
			Array2XML::init('1.0', 'UTF-8');
			$xml = Array2XML::createXML('Root', $images);
			
			$this->zip->add_data('order'. ($i+1). '.xml', $xml->saveXML());
		}
		
		$this->zip->read_dir($upload_images, FALSE);
		$this->zip->download('orders_'. $time. '.zip');
		
		exit();
	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */