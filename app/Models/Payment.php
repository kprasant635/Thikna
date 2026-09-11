<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model { protected $fillable=['yellow_page_application_id','order_reference','gateway_transaction_id','amount','status','paid_at','gateway_payload']; protected function casts(): array{return ['paid_at'=>'datetime','gateway_payload'=>'array'];} public function application(){return $this->belongsTo(YellowPageApplication::class,'yellow_page_application_id');} }
