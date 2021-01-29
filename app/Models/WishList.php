<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;
    protected $table = 'tbl_wish_list';
	protected $fillable = [
        'id',
        'user_id',
        'site_id'
    ];

    /**
    *	Get wish list
    */
    public static function getWishListId($userId=null,$siteId=null)
    {
    	$wishListId = '';
    	if(isset($userId) && !empty($userId) && isset($siteId) && !empty($siteId))
    	{
    		$wishListId = self::where(['user_id'=>$userId,'site_id'=>$siteId])->first();
    		if(isset($wishListId->id))
    		{
    			$wishListId = $wishListId->id;
    		}
    		else
    		{
    			$wishListId = '';
    		}
    	}
    	return $wishListId;
    }
}
