<?php
namespace App\Http\Controllers;
use App\Models\Receipt;use App\Models\YellowPageApplication;use Illuminate\Http\Request;
class ReceiptController extends Controller {public function show(Request $r,YellowPageApplication $application){abort_unless($application->owner_key===$r->session()->get('yellow_pages_owner'),403);abort_unless($application->status==='paid',404);return $application->load('listing','payment','receipt');}public function verify(string $reference){$receipt=Receipt::where('reference',$reference)->firstOrFail();return ['verified'=>true,'receipt'=>$receipt->load('application.listing','application.payment')];}}
