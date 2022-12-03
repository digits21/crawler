<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Observers\Observer;
use Spatie\Crawler\Crawler;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Session;
use Cache;

use Str;
use Validator;

class CrawlerController extends Controller
{
    //

    public function crawl(Request $request)
    {

        //validate request url is reachable

        list($status) = get_headers($request->url);
        if (strpos($status, '200') == FALSE) {
         return redirect()->back()->withInput()->with('error_msg','URL is not reachable');
        
        }

        // if the request was already made within 1 hour retrieve from Cache
        $crawl_results=NULL;
        if (Cache::has($request->url)) {
            $crawl_results=Cache::get($request->url);
            return view('welcome')->with('crawl_results',$crawl_results);
        }
        // Crawl if not in CACHE
        Session::put('new_query',Str::random(10));
    	Crawler::create()->ignoreRobots()->setCrawlObserver(new Observer)->startCrawling($request->url);
        
        if (Session::has('new_query')) {
            $crawl_results=DB::table('crawlers')->where('serial_number',Session::get('new_query'))->orderBy('created_at','desc')->get();
            Session::forget('new_query');
        }
        if (isset($crawl_results)) {
            Cache::put($request->url,$crawl_results,Carbon::now()->addMinutes(60));
        }

        return view('welcome')->with('crawl_results',$crawl_results);

    }

    // view detail page

    public function viewCrawl($id){

        $crawl=\App\Models\Crawler::findOrFail($id);

        return view('detail')->with('crawl',$crawl);

    }
}
