<?php
namespace App\Http\Controllers;
use App\Models\YellowPage;
use Illuminate\Http\Request;
class YellowPageController extends Controller {
 public function index(Request $request){$q=YellowPage::query(); foreach(['category','location'] as $f){if($request->$f)$q->where($f,$request->$f);} if($request->search)$q->where(fn($x)=>$x->where('name','like','%'.$request->search.'%')->orWhere('description','like','%'.$request->search.'%')); return $q->latest()->paginate(9);}
 public function categories(){return ['categories'=>YellowPage::query()->distinct()->orderBy('category')->pluck('category'),'locations'=>YellowPage::query()->distinct()->orderBy('location')->pluck('location')];}
 public function show(YellowPage $yellowPage){return $yellowPage;}
}
