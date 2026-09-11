<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Receipt extends Model { protected $fillable=['yellow_page_application_id','reference','verification_token']; public function application(){return $this->belongsTo(YellowPageApplication::class,'yellow_page_application_id');} }
