<?php

namespace App\Http\Controllers;
use App\Http\Controllers\CommonController ;
use App\Model\Common;
use Validator;

use Illuminate\Http\Request;

class BookController extends Controller
{

	public static function BookStore(Request $request){
		$inputs = $request->input();
    	$rules = [
            'bookName' => 'required|string|max:500|min:0'

        ];
        $messages = [
            'bookName.required' => 100,
            'bookName.string' => 104,
            'bookName.max' => 105
        ];
        $validator = Validator::make($request->input(), $rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
        }

        if (isset($error)) {
            // Error response
            $response = CommonController::responseCodeMessage($error);
           
            return $response;
        }
        
        $bookData = array();
        $bookData['name'] = $request->bookName;
        $insert_status = Common::insertBookData($bookData);
        if(isset($insert_status)){
        	$response = CommonController::responseCodeMessage(200);
        	return $response;
        }
        
	}
    public static function BookRemark(Request $request){
    	$inputs = $request->input();
    	$rules = [
            'bookName' => 'nullable|string',
            'bookId' => 'integer',
            'userId' => 'required|integer',
            'reviewDetail' => 'nullable',
            'rating' => 'nullable'

        ];
        $messages = [
            'bookName.string' => 104,
            'bookId.integer' => 101, 

            'userId.required' => 102,
            'userId.integer' => 103
        ];
        $validator = Validator::make($request->input(), $rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
        }

        if (isset($error)) {
            // Error response
            $response = CommonController::responseCodeMessage($error);
           
            return $response;
        }
        if(isset($request->reviewDetail)){
        	$review_data = array();
        	$review_data['book_id'] = $request->bookId;
        	$review_data['detail'] = $request->reviewDetail;
        	$review_data['user_id'] = $request->userId;
        	$review_status = Common::insertReview($review_data);

        }
        if(isset($request->rating)){
        	$rating_data = array();
        	$rating_data['book_id'] = $request->bookId;
        	$rating_data['user_id'] = $request->userId;
        	$rating_data['rating_no'] = $request->rating;
        	$rating = Common::checkUserBeforeRating($rating_data);
        	if($rating){
        		$rating_status = Common::insertRating($rating_data);
        	}else{
        		$response = CommonController::responseCodeMessage(107);
           		return $response;
        	}
        }

        if($review_status || $rating_status){
        	$response = CommonController::responseCodeMessage(200);
           
        	return $response;
        }
    	
    }
    public static function BookDetail(Request $request){
    	$inputs = $request->input();
    	$rules = [
            'bookId' => 'required|integer',
        ];
        $messages = [
            'bookId.integer' => 101,
            'bookId.required' => 106
        ];
        $validator = Validator::make($request->input(), $rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
        }

        if (isset($error)) {
            // Error response
            $response = CommonController::responseCodeMessage($error);
           
            return $response;
        }
        $bookData = Common::getBookData($request->bookId);
        $response = array();
        $response = CommonController::responseCodeMessage(200);
        $response['bookId'] = $request->bookId;
        $response['bookName'] = $bookData['name'];
        $response['totalReviewCount'] = Common::getTotalReviewCount($request->bookId);
        $response['totalRatingCount'] = Common::getTotalRatingCount($request->bookId);
        $response['totalReviewAndRatingCount'] = Common::getTotalReviewAndRatingCount($request->bookId);
        $response['totalUsers'] = Common::getTotalUsers($request->bookId);
        $response['avgRating'] = Common::getAverageRating($request->bookId);
        $response['review'] = Common::getReviewDetail($request->bookId);


        	return $response;
    }
}
