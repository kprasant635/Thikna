<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class YellowPageApplication extends Model { protected $fillable=['yellow_page_id','owner_key','reference','applicant_name','mobile','email','address','documents','amount','status']; protected function casts(): array { return ['documents'=>'array']; } public function listing(){return $this->belongsTo(YellowPage::class,'yellow_page_id');} public function payment(){return $this->hasOne(Payment::class);} public function receipt(){return $this->hasOne(Receipt::class);} }
