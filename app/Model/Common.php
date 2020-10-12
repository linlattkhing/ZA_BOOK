<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Common extends Model
{
	public static $tbl_response_codes = "response_codes";
	public static $tbl_book = "books";
	public static $tbl_review = "reviews";
	public static $tbl_rating = "rating";
	
    public static function getMessageByCode($code) {
    	$result = DB::table(self::$tbl_response_codes)
                    ->where('code', $code)
                    ->first();
        $result = get_object_vars($result);
        return $result;
    }
     /**
     * Data Insertion
     *
     * @param  $data
     * @return boolean
     */
    public static function insertReview($data) {
        $insert_id = DB::table(self::$tbl_review)
                     ->insertGetId($data);
        if (isset($insert_id)) {
        	return true;
        }
        else{
        	return false;
        }
    }
     /**
     * Data Insertion
     *
     * @param  $data
     * @return boolean
     */
    public static function insertBookData($data){
    	 $insert_id = DB::table(self::$tbl_book)
                     ->insertGetId($data);
        if (isset($insert_id)) {
        	return true;
        }
        else{
        	return false;
        }
    }
    /**
     * Data Insertion
     *
     * @param  $data, $table
     * @return boolean
     */
    public static function insertRating($data) {
        $insert_id = DB::table(self::$tbl_rating)
                     ->insertGetId($data);
        if (isset($insert_id)) {
        	return true;
        }
        else{
        	return false;
        }
    }

    /**
     * Data Select
     *
     * @param  $data
     * @return boolean
     */
    public static function checkUserBeforeRating($data){
    	$result = DB::table(self::$tbl_rating)
                    ->where('user_id', $data['user_id'])
                    ->where('book_id', $data['book_id'])
                    ->first();
        if (!isset($result)) {
        	return true;
        }
        else{
        	return false;
        }
       
    }
     /**
     * Data Select
     *
     * @param  $book_id
     * @return book data
     */
    public static function getBookData($book_id){
    	$result = DB::table(self::$tbl_book)
                    ->where('id', $book_id)
                    ->first();
        $result = get_object_vars($result);
        return $result;
    }
     /**
     * Data Select
     *
     * @param  $book_id
     * @return totalReviewCount
     */
    public static function getTotalReviewCount($book_id){
    	$result = DB::table(self::$tbl_review)
    				->select(DB::raw('count(id) as totalReviewCount'))           
                    ->where('book_id', $book_id)
                    ->get();
        foreach ($result as $key => $value) {
        	$result = $value->totalReviewCount;
        }
       
        return $result;
    }
    /**
     * Data Select
     *
     * @param  $book_id
     * @return totalRatingCount
     */
    public static function getTotalRatingCount($book_id){
    	$result = DB::table(self::$tbl_rating)
    				->select(DB::raw('count(id) as totalRatingCount'))           
                    ->where('book_id', $book_id)
                    ->get();
        foreach ($result as $key => $value) {
        	$result = $value->totalRatingCount;
        }
       
        return $result;
    }
    /**
     * Data Select
     *
     * @param  $book_id
     * @return totalReviewAndRatingCount
     */
    public static function getTotalReviewAndRatingCount($book_id){
    	
    	$reviews = DB::table(self::$tbl_review)
    				->select("id","book_id")
    				->where("book_id", $book_id);
    	$rating = DB::table(self::$tbl_rating)
    				->select("id","book_id")
    				->where("book_id", $book_id)
        			->unionAll($reviews)
        			->get();
        $result = count($rating);

    	return $result;
    }
    /**
     * Data Select
     *
     * @param  $book_id
     * @return totalUsers
     */
    public static function getTotalUsers($book_id){
    	//DB::enableQueryLog(); 

    	$reviews = DB::table(self::$tbl_review)
    				->select("user_id","book_id")
    				->distinct()
    				->where("book_id", $book_id);
    	$rating = DB::table(self::$tbl_rating)
    				->select("user_id","book_id")
    				->distinct()
    				->where("book_id", $book_id)
        			->unionAll($reviews)
        			->get()
        			->toArray();
        //dd(DB::getQueryLog());			
        			
       $result = array_unique($rating, SORT_REGULAR);
	   return count($result);
    }
    /**
     * Data Select
     *
     * @param  $book_id
     * @return avgRating
     */
    public static function getAverageRating($book_id){
    	$result = DB::table(self::$tbl_rating)
    				->select(DB::raw('avg(rating_no) as avgRating'))           
                    ->where('book_id', $book_id)
                    ->get();
        foreach ($result as $key => $value) {
        	$result = (double)$value->avgRating;
        }
       
        return $result;
    }
    /**
     * Data Select
     *
     * @param  $book_id
     * @return detail review
     */
    public static function getReviewDetail($book_id){
    	$result = DB::table(self::$tbl_review)
    				->select("detail as reviewDetail","user_id as userId","created_date as createdDate")
    				->where('book_id', $book_id)
                    ->get();
       
       return $result;
        
    }
}
