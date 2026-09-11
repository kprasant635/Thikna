<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class YellowPage extends Model { protected $fillable=['name','category','location','description','eligibility','required_documents','application_fee','is_active']; protected function casts(): array { return ['required_documents'=>'array','is_active'=>'boolean']; } }
