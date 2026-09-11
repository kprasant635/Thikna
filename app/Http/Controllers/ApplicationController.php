<?php
namespace App\Http\Controllers;
use App\Models\YellowPage; use App\Models\YellowPageApplication; use Illuminate\Http\Request; use Illuminate\Support\Str;
class ApplicationController extends Controller {
 private function owner(Request $r){return $r->session()->get('yellow_pages_owner') ?? tap((string)Str::uuid(),fn($v)=>$r->session()->put('yellow_pages_owner',$v));}
 public function store(Request $r, YellowPage $yellowPage){abort_unless($yellowPage->is_active,422,'This service is not available.');$data=$r->validate(['applicant_name'=>'required|string|max:120','mobile'=>'required|string|max:20','email'=>'required|email','address'=>'required|string|max:1000','documents.*'=>'file|max:5120']);$files=[];foreach($r->file('documents',[]) as $file)$files[]=$file->store('yellow-pages','private');$app=YellowPageApplication::create([...$data,'documents'=>$files,'yellow_page_id'=>$yellowPage->id,'owner_key'=>$this->owner($r),'reference'=>'YP-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),'amount'=>$yellowPage->application_fee,'status'=>'payment_pending']);return response()->json($app->load('listing'),201);}
 public function show(Request $r, YellowPageApplication $application){abort_unless($application->owner_key===$this->owner($r),403);return $application->load('listing','payment','receipt');}
}
