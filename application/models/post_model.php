<?php
/**
 * Post Model
 * Created by: arangde
 * Date: 10/15/14
 *
 */
class Post_model extends Base_model {

	public function getBrands() {
		$this->db->order_by("brand_name");
		$query = $this->db->get('brands');
		return $query->result_array();
	}
	
	public function getClothings($gender = '') {
		if($gender != '') {
			$this->db->where("gender", $gender);
			$this->db->or_where("gender", "all");
		}
		$this->db->order_by("clothing_id");
		$query = $this->db->get('clothings');
		return $query->result_array();
	}
	
	public function getPost($post_id, $user_id) {
		$this->db->select('posts.*, clothings.clothing_type, ratings.*, posts.post_id AS post_id,  
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following,
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares', false);
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('ratings', 'ratings.post_id=posts.post_id', 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('follows', 'follows.friend_id=posts.user_id AND follows.user_id='. $user_id, 'left');
		$this->db->join('likes AS likes2', 'likes2.post_id=posts.post_id AND likes2.user_id='. $user_id, 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->where("posts.post_id", $post_id);
		$this->db->group_by("posts.post_id");
		$this->db->limit(1);
	
		$query = $this->db->get();
	
		return $query->row_array();
	}
	
	public function getNewPosts($user_id, $gender, $in_follows=false, $where='', $limit=-1, $start=0) {
		/*
		$this->db->select('posts.*, clothings.clothing_type, ratings.*, posts.post_id AS post_id, specs.*, posts.user_id AS user_id, 
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following, 
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				sharer.user_name AS sharer_name, sharer.first_name AS sharer_first_name, sharer.last_name AS sharer_last_name, 
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares', true);
		
		$this->db->from('('. $query2. ') AS posts');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('specs', 'posts.user_id=specs.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('ratings', 'ratings.post_id=posts.post_id', 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('follows', 'follows.friend_id=posts.user_id AND follows.user_id='. $user_id, 'left');
		$this->db->join('likes AS likes2', 'likes2.post_id=posts.post_id AND likes2.user_id='. $user_id, 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->join('users AS sharer', 'sharer.user_id=posts.last_sharer_id', 'left');
		//$this->db->where('posts.user_id != ', $user_id);
		//$this->db->where('users.gender', $gender);
		//if($in_follows) {
		//	$this->db->join('post_views', 'post_views.post_id=posts.post_id AND post_views.user_id='. $user_id, 'left');
		//	$this->db->where('post_views.post_view_id > 0');
		//}
		if($where != '')
			$this->db->where($where);
		$this->db->group_by(array("posts.post_id", "posts.sharer_id"));
		if($limit>-1)
			$this->db->limit($limit, $start);
		//$this->db->order_by("posts.post_date", "DESC");
		
		$query = $this->db->get();
		*/
		
		$query1 = "SELECT posts.* FROM posts LEFT JOIN follows ON posts.user_id=follows.friend_id "
				. "WHERE posts.user_id=". $user_id. " OR follows.user_id=". $user_id;
		
		$query2 = "SELECT posts1.*, posts1.created AS updated, '0' AS sharer_id FROM (". $query1. ") AS posts1 "
				. "UNION (SELECT posts2.*, shares.created, shares.user_id "
				. "FROM posts AS posts2 LEFT JOIN shares ON posts2.post_id=shares.post_id "
				. "LEFT JOIN follows ON shares.user_id=follows.friend_id "
				. " WHERE shares.user_id=". $user_id. " OR follows.user_id=". $user_id
				. ") ORDER BY updated DESC";
		
		$query3 = 'SELECT DISTINCT posts3.*, clothings.clothing_type, ratings.*, posts3.post_id AS post_id, specs.*, posts3.user_id AS user_id, 
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following, 
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				sharer.user_name AS sharer_name, sharer.first_name AS sharer_first_name, sharer.last_name AS sharer_last_name, 
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares';
		
		$query3 .= ' FROM ('. $query2. ') AS posts3';
		$query3 .= ' LEFT JOIN users ON posts3.user_id=users.user_id';
		$query3 .= ' LEFT JOIN specs ON posts3.user_id=specs.user_id';
		$query3 .= ' LEFT JOIN clothings ON clothings.clothing_id=posts3.clothing_id';
		$query3 .= ' LEFT JOIN ratings ON ratings.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN likes ON likes.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN follows ON follows.friend_id=posts3.user_id AND follows.user_id='. $user_id;
		$query3 .= ' LEFT JOIN likes AS likes2 ON likes2.post_id=posts3.post_id AND likes2.user_id='. $user_id;
		$query3 .= ' LEFT JOIN comments ON comments.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN shares ON shares.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN users AS sharer ON sharer.user_id=posts3.sharer_id';
		
		$query3 .= ' WHERE posts3.post_id>0';
		if($where != '')
			$query3 .= ' AND '. $where;
		
		$query3 .= ' GROUP BY posts3.post_id, posts3.sharer_id';
		$query3 .= ' ORDER BY posts3.updated DESC';
		
		if($limit>-1)
			$query3 .= ' LIMIT '. $start. ', '. $limit;
		
		$query = $this->db->query($query3);
		
		return $query->result_array();
	}
	
	public function getPosts($user_id, $where='', $limit=-1, $start=0) {
		$this->db->select('posts.*, clothings.clothing_type, ratings.*, posts.post_id AS post_id, specs.*, posts.user_id AS user_id,
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following, 
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares', false);
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('specs', 'posts.user_id=specs.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('ratings', 'ratings.post_id=posts.post_id', 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('follows', 'follows.friend_id=posts.user_id AND follows.user_id='. $user_id, 'left');
		$this->db->join('likes AS likes2', 'likes2.post_id=posts.post_id AND likes2.user_id='. $user_id, 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->order_by("posts.created", "DESC");
		$this->db->group_by("posts.post_id");
		if($where != '')
			$this->db->where($where);
		if($limit>-1)
			$this->db->limit($limit, $start);
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	public function getPostsByBrandName($user_id, $brand_name, $where='', $limit=-1, $start=0) {
		$this->db->select('posts.*, clothings.clothing_type, ratings.*, posts.post_id AS post_id, specs.*, posts.user_id AS user_id,
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following,
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares', false);
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('specs', 'posts.user_id=specs.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('ratings', 'ratings.post_id=posts.post_id', 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('follows', 'follows.friend_id=posts.user_id AND follows.user_id='. $user_id, 'left');
		$this->db->join('likes AS likes2', 'likes2.post_id=posts.post_id AND likes2.user_id='. $user_id, 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->like('posts.brand_name', $brand_name);
		$this->db->order_by("posts.created", "DESC");
		$this->db->group_by("posts.post_id");
		if($where != '')
			$this->db->where($where);
		if($limit>-1)
			$this->db->limit($limit, $start);
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	public function getPostsAll($where='', $limit=-1, $start=0) {
		$this->db->select('posts.*, clothings.clothing_type, posts.post_id AS post_id, posts.user_id AS user_id, users.user_name, users.first_name, 
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares,
				MAX(comments.created) AS last_comment', false);
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->order_by("posts.created", "DESC");
		$this->db->group_by("posts.post_id");
		if($where != '')
			$this->db->where($where);
		if($limit>-1)
			$this->db->limit($limit, $start);
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	public function getPostsCount($where='') {
		$this->db->select('COUNT(post_id) AS cnt');
		$this->db->from('posts');
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
	
	public function getUserPosts($user_id, $poster_id, $where='', $limit=-1, $start=0) {
		/*
		$this->db->select('posts.*, clothings.clothing_type, ratings.*, posts.post_id AS post_id, specs.*, posts.user_id AS user_id,
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following, 
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				sharer.user_name AS sharer_name, sharer.first_name AS sharer_first_name, sharer.last_name AS sharer_last_name, 
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares', false);
		$this->db->from('posts');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('specs', 'posts.user_id=specs.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('ratings', 'ratings.post_id=posts.post_id', 'left');
		$this->db->join('follows', 'follows.friend_id=posts.user_id AND follows.user_id='. $user_id, 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('likes AS likes2', 'likes2.post_id=posts.post_id AND likes2.user_id='. $user_id, 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->join('shares AS shares2', 'shares2.post_id=posts.post_id AND shares2.user_id='. $poster_id, 'left');
		$this->db->join('users AS sharer', 'sharer.user_id=posts.last_sharer_id', 'left');
		$this->db->where('posts.user_id', $poster_id);
		$this->db->or_where('shares2.share_id > 0');
		$this->db->group_by("posts.post_id");
		$this->db->order_by("posts.created", "DESC");
		
		$query = $this->db->get();
		*/
		$query1 = "SELECT posts.* FROM posts WHERE posts.user_id=". $poster_id;
		
		$query2 = "SELECT posts1.*, posts1.created AS updated, '0' AS sharer_id FROM (". $query1. ") AS posts1 "
				. "UNION (SELECT posts2.*, shares.created, shares.user_id "
				. "FROM posts AS posts2 LEFT JOIN shares ON posts2.post_id=shares.post_id "
				. " WHERE shares.user_id=". $poster_id
				. ") ORDER BY updated DESC";
		
		$query3 = 'SELECT DISTINCT posts3.*, clothings.clothing_type, ratings.*, posts3.post_id AS post_id, specs.*, posts3.user_id AS user_id,
				IF(likes2.like_id IS NULL, 0, 1 ) AS post_liked, IF(follows.follow_id IS NULL, 0, 1 ) AS is_following,
				users.gender, users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				sharer.user_name AS sharer_name, sharer.first_name AS sharer_first_name, sharer.last_name AS sharer_last_name,
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares';
		
		$query3 .= ' FROM ('. $query2. ') AS posts3';
		$query3 .= ' LEFT JOIN users ON posts3.user_id=users.user_id';
		$query3 .= ' LEFT JOIN specs ON posts3.user_id=specs.user_id';
		$query3 .= ' LEFT JOIN clothings ON clothings.clothing_id=posts3.clothing_id';
		$query3 .= ' LEFT JOIN ratings ON ratings.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN likes ON likes.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN follows ON follows.friend_id=posts3.user_id AND follows.user_id='. $user_id;
		$query3 .= ' LEFT JOIN likes AS likes2 ON likes2.post_id=posts3.post_id AND likes2.user_id='. $user_id;
		$query3 .= ' LEFT JOIN comments ON comments.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN shares ON shares.post_id=posts3.post_id';
		$query3 .= ' LEFT JOIN users AS sharer ON sharer.user_id=posts3.sharer_id';
		
		$query3 .= ' WHERE posts3.post_id>0';
		if($where != '')
			$query3 .= ' AND '. $where;
		
		$query3 .= ' GROUP BY posts3.post_id, posts3.sharer_id';
		$query3 .= ' ORDER BY posts3.updated DESC';
		
		if($limit>-1)
			$query3 .= ' LIMIT '. $start. ', '. $limit;
		
		$query = $this->db->query($query3);
		
		return $query->result_array();
	}
	
	public function getUserLikePosts($user_id) {
		$this->db->select('posts.*, clothings.clothing_type, ratings.*, posts.post_id AS post_id,
				specs.*, posts.user_id AS user_id,
				users.user_name, users.first_name, users.last_name, users.email_address, users.photo_url AS user_photo_url, users.status, users.location,
				COUNT(DISTINCT likes.like_id) AS post_likes, COUNT(DISTINCT comments.comment_id) AS post_comments, COUNT(DISTINCT shares.share_id) AS post_shares');
		$this->db->from('likes AS likes2');
		$this->db->join('posts', 'posts.post_id=likes2.post_id', 'left');
		$this->db->join('users', 'posts.user_id=users.user_id', 'left');
		$this->db->join('specs', 'posts.user_id=specs.user_id', 'left');
		$this->db->join('clothings', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->join('ratings', 'ratings.post_id=posts.post_id', 'left');
		$this->db->join('likes', 'likes.post_id=posts.post_id', 'left');
		$this->db->join('comments', 'comments.post_id=posts.post_id', 'left');
		$this->db->join('shares', 'shares.post_id=posts.post_id', 'left');
		$this->db->order_by("posts.created", "DESC");
		$this->db->group_by("posts.post_id");
		$this->db->where('likes2.user_id', $user_id);
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	public function getPostComments($post_id, $user_id) {
		$this->db->select('comments.*, users.photo_url, users.first_name, users.last_name, users.user_name, IF(comment_likes.comment_like_id IS NULL, 0 , 1 ) AS comment_liked', false);
		$this->db->join('users', 'comments.user_id=users.user_id', 'left');
		$this->db->join('comment_likes', 'comments.comment_id=comment_likes.comment_id AND comment_likes.user_id = '. $user_id, 'left');
		$this->db->order_by("comments.created", "ASC");
		$this->db->where('comments.post_id', $post_id);
		
		$query = $this->db->get('comments');
		
		return $query->result_array();
	}
	
	public function getComment($comment_id) {
		$this->db->select('comments.*, users.photo_url, users.first_name, users.last_name, users.user_name');
		$this->db->join('users', 'comments.user_id=users.user_id', 'left');
		$this->db->where('comments.comment_id', $comment_id);
		
		$query = $this->db->get('comments');
		
		return $query->row_array();
	}
	
	public function countByBrand() {
		$this->db->select('brands.brand_name, COUNT(post_id) AS post_count');
		$this->db->from('posts');
		$this->db->join('brands', 'brands.brand_name=posts.brand_name', 'left');
		$this->db->group_by("brands.brand_name");
		$this->db->having("post_count > 0");
		$this->db->order_by("brands.brand_name");
		$this->db->where("posts.brand_name != ''");
		$this->db->where("brands.brand_id != ''");
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function countBySearchBrand($brand_name) {
		$this->db->select('posts.brand_name, COUNT(post_id) AS post_count');
		$this->db->from('posts');
		$this->db->group_by("posts.brand_name");
		$this->db->having("post_count > 0");
		$this->db->like("posts.brand_name", $brand_name);
		$this->db->order_by("posts.brand_name");
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	public function countByClothing() {
		$this->db->select('clothings.*, COUNT(posts.post_id) AS post_count');
		$this->db->join('posts', 'clothings.clothing_id=posts.clothing_id', 'left');
		$this->db->group_by("clothings.clothing_id");
		$this->db->having("post_count > 0");
		$this->db->order_by("clothings.clothing_id");
		$query = $this->db->get('clothings');
		
		return $query->result_array();
	}
	
	public function addPostView($user_id, $post_id) {
		$result = $this->get("post_views", array("user_id" => $user_id, "post_id" => $post_id), 1);
		
		if(empty($result)) {
			$this->add("post_views", array("user_id" => $user_id, "post_id" => $post_id));
		}
	}
	
}
