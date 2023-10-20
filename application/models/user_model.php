<?php 
/**
 * User Model
 * Created by: arangde
 * Date: 11/21/13
 *
 */
class User_model extends Base_model {
	
	public function authenticate($email, $password) {
		$user = $this->getUserByEmail($email);
		
		if( !empty($user) ) {
			
			if(sha1($this->config->item('encryption_key'). $password) == $user['password']) {
// 				if($user['status'] != USER_ACTIVE)
// 					return AUTH_NOTACTIVE;
// 				else
					return $user;
			}
			else 
				return AUTH_FAIL;
		}
		
		return AUTH_NO_FOUND;
	}
	
	public function authenticateWithName($user_name, $password) {
		$user = $this->getUserByName($user_name);
	
		if( !empty($user) ) {
				
			if(sha1($this->config->item('encryption_key'). $password) == $user['password']) {
				if($user['status'] != USER_ACTIVE)
					return AUTH_NOTACTIVE;
				else
					return $user;
			}
			else
				return AUTH_FAIL;
		}
	
		return AUTH_NO_FOUND_NAME;
	}
	
	public function addUser($data, $signup = false) {
		
		$data['created'] = date('Y-m-d H:i:s');
		
// 		if($signup) {
// 			$data['status'] = USER_ACTIVE; //USER_CREATE;
// 			//$bind['active_code'] = time(). sha1($this->config->item('encryption_key'). $bind['email_address']);
// 		}
		
		if(isset($data['password']))
			$data['password'] = sha1($this->config->item('encryption_key'). $data['password']);
		
		return $this->add('users', $data);
	}
	
	public function updateUser($data, $id) {
		if(isset($data['user_id']))
			unset($data['user_id']);
		
		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
	}
	
	public function getUserNames($user_id) {
		$this->db->select('users.user_id, users.user_name, users.first_name, users.last_name, users.photo_url, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following', false);
		$this->db->from('users');
		$this->db->join('follows', 'follows.friend_id=users.user_id AND follows.user_id='. $user_id, 'left');
		$this->db->where('users.role !=', USER_ROLE_ADMIN);
		$this->db->where('users.user_id !=', $user_id);
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function getUsers($where='', $limit=-1, $start=0) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('status !=', USER_DELETE);
		$this->db->where('role !=', USER_ROLE_ADMIN);
		if($where != '')
			$this->db->where($where);
		if($limit>-1)
			$this->db->limit($limit, $start);
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	public function getUsersCount($where='') {
		$this->db->select('COUNT(user_id) AS cnt');
		$this->db->from('users');
		$this->db->where('status !=', USER_DELETE);
		$this->db->where('role !=', USER_ROLE_ADMIN);
		if($where != '')
			$this->db->where($where);
		
		$query = $this->db->get();
	
		if( $query->num_rows() > 0 ) {
			$row = $query->row_array();
			return intval($row['cnt']);
		}
		else {
			return 0;
		}
	}
	
	public function getUser($user_id, $detail = false) {
		$this->db->where('users.user_id', $user_id);
		
		if($detail) {
			$this->db->select('users.*,
				COUNT(likes.like_id) AS user_likes, COUNT(comments.comment_id) AS user_comments, COUNT(shares.share_id) AS user_shares', false);
			$this->db->join('likes', 'likes.user_id=users.user_id', 'left');
			$this->db->join('comments', 'comments.user_id=users.user_id', 'left');
			$this->db->join('shares', 'shares.user_id=users.user_id', 'left');
			$this->db->group_by("users.user_id");
		}
		
		$query = $this->db->get('users', 1);
		
		return $query->row_array();
	}
	
	public function getUserByEmail($email) {
		$this->db->where('email_address', $email);
		
		$query = $this->db->get('users', 1);
		
		return $query->row_array();
	}
	
	public function getUserByName($user_name) {
		$this->db->where('user_name', $user_name);
		
		$query = $this->db->get('users', 1);
		
		return $query->row_array();
	}
	
	public function checkDuplicationEmail($email, $except=false) {
		$this->db->where('email_address', $email);
		if($except)
			$this->db->where('user_id !=', $except);
			
		$query = $this->db->get('users', 1);
		
		if( $query->num_rows() > 0 ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function checkDuplicationName($user_name, $except=false) {
		$this->db->where('user_name', $user_name);
		if($except)
			$this->db->where('user_id !=', $except);
			
		$query = $this->db->get('users', 1);
		
		if( $query->num_rows() > 0 ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function activateUser($active_code) {
		$user = $this->getUserByActiveCode($active_code);
		
		if($user) {
			$user['status'] = USER_ACTIVE;
			
			$this->db->where('user_id', $user['user_id']);
        	$this->db->update('users', array('status'=>USER_ACTIVE));
			
			return $user;
		}
		else {
			return false;
		}
	}
	
	public function getUserByActiveCode($active_code) {
		$this->db->where('active_code', $active_code);
		$query = $this->db->get('users', 1);
		
		if( $query->num_rows() > 0 ) {
			return $query->row_array();
		}
		else {
			return false;
		}
	}
	
	public function changeStatus($id, $status) {
		$this->db->where('user_id', $id);
		return $this->db->update('users', array('status'=>$status)); 
	}
	
	public function getFollows($user_id, $my_id, $get_followings = true) {
		$this->db->from('follows');
		if($get_followings) {
			$this->db->select('follows.follow_id, first_name, last_name, user_name, gender, photo_url, status, location, specs.*, IF(follows2.follow_id IS NULL, 0, 1 ) AS is_following', false);
			$this->db->join('users', 'users.user_id=follows.friend_id', 'left');
			$this->db->join('follows AS follows2', 'users.user_id=follows2.friend_id AND follows2.user_id='. $my_id, 'left');
			$this->db->where('follows.user_id', $user_id);
		}
		else {
			$this->db->select('follows.follow_id, first_name, last_name, gender, user_name, photo_url, status, location, specs.*, IF(follows2.follow_id IS NULL, 0, 1 ) AS is_following', false);
			$this->db->join('users', 'users.user_id=follows.user_id', 'left');
			$this->db->join('follows AS follows2', 'users.user_id=follows2.friend_id AND follows2.user_id='. $my_id, 'left');
			$this->db->where('follows.friend_id', $user_id);
		}
		$this->db->join('specs', 'specs.user_id=users.user_id', 'left');
		
		$query = $this->db->get();
		
		return $query->result_array();
		
	}
	
	public function getSimilarUsers($user_id, $bsi_type, $gender, $users_limit, $percent_limit) {
		$height_times = "* 0.3937";
		$weight_times = "* 2.2";
		
		$select_simple_sum = "calcBase(specs.height, specs2.height, '".$gender."', 10, 0.3937, 0, 0.377, 0.65 )\r\n";
		$select_simple_sum .= " + calcBase(specs.weight, specs2.weight, '".$gender."', 10, 2.2, 0, 0.256, 0.35 )\r\n";
		
		$select_full_sum = "calcBase(specs.height, specs2.height, '".$gender."', 10, 0.3937, 0, 0.377, 0.2 )\r\n";
		$select_full_sum .= " + calcBase(specs.weight, specs2.weight, '".$gender."', 10, 2.2, 0, 0.256, 0.15 )\r\n";
		$select_full_sum .= " + calcBase(specs.chest, specs2.chest, '".$gender."', 10, 0.3937, 0.355, 0.357, 0.25 )\r\n";
		$select_full_sum .= " + calcBase(specs.waist, specs2.waist, '".$gender."', 10, 0.3937, 0.369, 0.353, 0.25 )\r\n";
		$select_full_sum .= " + calcBase(specs.hip, specs2.hip, '".$gender."', 2.7183, 0.3937, 0, 0.177, 0.15 )\r\n";
		
		$select_upper_sum = "calcBase(specs.chest, specs2.chest, '".$gender."', 10, 0.3937, 0.355, 0.357, 0.2 )\r\n";
		$select_upper_sum .= " + calcBase(specs.waist, specs2.waist, '".$gender."', 10, 0.3937, 0.369, 0.353, 0.2 )\r\n";
		$select_upper_sum .= " + calcBase(specs.neck, specs2.neck, '".$gender."', 2.7183, 0.3937, 0.263, 0, 0.05 )\r\n";
		$select_upper_sum .= " + calcBase(specs.shoulder, specs2.shoulder, '".$gender."', 2.7183, 0.3937, 0.319, 0.525, 0.2 )\r\n";
		$select_upper_sum .= " + calcBase(specs.arm_length, specs2.arm_length, '".$gender."', 2.7183, 0.3937, 0.223, 0.223, 0.1 )\r\n";
		$select_upper_sum .= " + calcBase(specs.torso_height, specs2.torso_height, '".$gender."', 2.7183, 0.3937, 0.226, 0, 0.1 )\r\n";
		$select_upper_sum .= " + calcBase(specs.upper_arm_size, specs2.upper_arm_size, '".$gender."', 2.7183, 0.3937, 0, 0, 0.1 )\r\n";
		$select_upper_sum .= " + calcBase(specs.belly, specs2.belly, '".$gender."', 2.7183, 0.3937, 0, 0, 0.05 )\r\n";
		
		$select_lower_sum = "calcBase(specs.waist, specs2.waist, '".$gender."', 10, 0.3937, 0.369, 0.353, 0.3 )\r\n";
		$select_lower_sum .= " + calcBase(specs.hip, specs2.hip, '".$gender."', 2.7183, 0.3937, 0, 0.177, 0.3 )\r\n";
		$select_lower_sum .= " + calcBase(specs.leg_length, specs2.leg_length, '".$gender."', 2.7183, 0.3937, 0.301, 0, 0.2 )\r\n";
		$select_lower_sum .= " + calcBase(specs.thigh, specs2.thigh, '".$gender."', 2.7183, 0.3937, 0, 0.206, 0.15 )\r\n";
		$select_lower_sum .= " + calcBase(specs.calf, specs2.calf, '".$gender."', 2.7183, 0.3937, 0, 0, 0.05 )\r\n";
				
		if($bsi_type == 'full') {
			$select_simulate = $select_full_sum. ' AS similar_percent';
		}
		elseif($bsi_type == 'upper') {
			$select_simulate = $select_upper_sum. ' AS similar_percent';
		}
		elseif($bsi_type == 'lower') {
			$select_simulate = $select_lower_sum. ' AS similar_percent';
		}
		else {
			$bsi_type = 'simple';
			$select_simulate = $select_simple_sum. ' AS similar_percent';
		}
		
		$this->db->select('users.*, "'. $bsi_type.'" AS bsi_type, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following, '. $select_simulate, false);
		$this->db->from('specs, specs AS specs2');
		$this->db->join('users', 'specs.user_id=users.user_id', 'left');
		$this->db->join('follows', 'users.user_id=follows.friend_id AND follows.user_id='.$user_id, 'left');
		$this->db->where('specs2.user_id', $user_id);
		$this->db->where('specs.user_id !=', $user_id);
		$this->db->where('users.gender', $gender);
		//$this->db->where('follows.follow_id IS NULL');
		$this->db->having('similar_percent >= '. $percent_limit);
		$this->db->order_by('similar_percent', 'DESC');
		$this->db->limit($users_limit);
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function getUserSimilar($user_id, $user_id2, $clothing_id) {
		$user = $this->getUser($user_id);
		$user2 = $this->getUser($user_id2);
		
		if(empty($user) || empty($user2) || $user['gender'] != $user2['gender'])
			return 0;
		
// 		global $ClothingTypeSpecs;
// 		$spec_keys = array();
		
// 		if($clothing_id == 0 || $clothing_id == "") {
// 			$spec_keys = $ClothingTypeSpecs['all'];
// 		}
// 		else {
// 			$clothing = $this->user_model->get('clothings', array('clothing_id' => $clothing_id), 1);
// 			if(empty($clothing)) {
// 				$spec_keys = $ClothingTypeSpecs['all'];
// 			}
// 			else {
// 				$spec_keys = isset($ClothingTypeSpecs[$clothing['bsi_type']])? $ClothingTypeSpecs[$clothing['bsi_type']] : $ClothingTypeSpecs['all'];
// 			}
			 
// 			//remove cup_size for male
// 			//if($user['gender'] == 'male' && isset($spec_keys['cup_size']))
// 			//	unset($spec_keys['cup_size']);
				
// 		}
		
		$bsi_type = '';
		if($clothing_id) {
			$clothing = $this->user_model->get('clothings', array('clothing_id' => $clothing_id), 1);
			$bsi_type = $clothing['bsi_type'];
		}
		
		if(empty($spec_keys)) {
			return 0;
		}
		else {
			$select_sum = "0";
			$select_count = "0";
			
			foreach($spec_keys as $key) {
				$select_sum .= " + getItemSimulate(specs.". $key. ", specs2.". $key. " )\r\n";
				$select_count .= " + IF(getItemSimulate(specs.". $key. ", specs2.". $key. " ) = 0, 0, 1 )\r\n";
			}
			
			$select_simulate = "IF(". $select_count. "=0, 0, ";
			$select_simulate .= "100 - ((". $select_sum. ") / (". $select_count. ") * 100)) AS similar_percent";
			
			$this->db->select($select_simulate, false);
			$this->db->from('specs, specs AS specs2');
			$this->db->where('specs2.user_id', $user_id2);
			$this->db->where('specs.user_id', $user_id);
			
			$query = $this->db->get();
			
			if($query->num_rows() > 0) {
				$result = $query->row_array();
				return floatval($result['similar_percent']);
			}
			else {
				return 0;
			}
		}
		
	}
	
	public function getRecentUsers($user_id, $where='', $limit=-1, $start=0) {
		$this->db->select('users.*, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following, COUNT(DISTINCT posts.post_id) + COUNT(DISTINCT shares.post_id) AS post_count', false);
		$this->db->from('users');
		$this->db->join('posts', 'users.user_id=posts.user_id', 'left');
		$this->db->join('shares', 'users.user_id=shares.user_id', 'left');
		$this->db->join('follows', 'users.user_id=follows.friend_id AND follows.user_id='.$user_id, 'left');
		$this->db->where('users.status !=', USER_DELETE);
		$this->db->where('users.role !=', USER_ROLE_ADMIN);
		//$this->db->having('post_count >', 0);
		$this->db->group_by('users.user_id');
		if($where != '')
			$this->db->where($where);
		if($limit>-1)
			$this->db->limit($limit, $start);
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	
}
