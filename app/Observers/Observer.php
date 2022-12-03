<?php
namespace App\Observers;

use App\Models\Crawler;
use Psr\Http\Message\UriInterface;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use Log;

use Cache;
use Session;
use Str;

/**
 * 
 */
class Observer extends CrawlObserver
{

	/**
     * Called when the crawler will crawl the url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     */
	public $serial_number;
	public $unique_number;
    public function willCrawl(UriInterface $url): void
    {
    	$this->serial_number=Session::get('new_query'); 
    	$this->unique_number=$this->generate_unique_number();
    	
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ): void{

    	$title=$this->getTitle($url);
    	$description=$this->getDescription($url);
    	$host=str_replace('.','-',parse_url($url)['host']);
    	// save crawler result
    	$new_crawl=Crawler::create([
    		'url'=>$url,
    		'host'=>$host,
    		'serial_number'=>$this->serial_number,
    		'status'=>$response->getStatusCode(),
    		'title'=>$title,
    		'description'=>$description,
    		'body'=>$response->getBody()
    	]);

    	
    	// if response is ok save screnshot
    	if($response->getStatusCode()==200){
    		$screenshot_file=uniqid().'_'.$host.'.png';
    			$saved_in_path=public_path('screenshots/'.$screenshot_file);
    			$public_path='screenshots/'.$screenshot_file;

    			Browsershot::url($url)->save($saved_in_path);

    			$new_crawl->update([
    				'screenshot'=>$public_path
    			]);

    	}


    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
     public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void{

    	Log::error('crawlFailed',['url'=>$url,'error'=>$requestException->getMessage()]);
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
    }

    // get the title of scrawled url
    public function getTitle($url){

    	$fp = file_get_contents($url);
        if (!$fp) 
            return null;

        $res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
        if (!$res) 
            return null; 

        // Clean up title: remove EOL's and excessive whitespace.
        $title = preg_replace('/\s+/', ' ', $title_matches[1]);
        $title = trim($title);
        return $title;

    }
    // get the description of scrawled url
    public function getDescription($url) {
    $tags = get_meta_tags($url);
    return @($tags['description'] ? $tags['description'] : "NULL");
	}

	public function generate_unique_number(){

		$latest_entry=Crawler::latest()->first();
		if (isset($latest_entry)) {
			
			$latest_number=(int)$latest_entry->unique_number+rand(1,9);
			$latest_number=(string)$latest_number;
			$preceeding_zeros="";
			for ($i=0; $i < 6 - strlen($latest_number); $i++) { 
				$preceeding_zeros.="0";
			}
			return $preceeding_zeros.$latest_number;


		}else{
			return "000001";
		}
	}


	
	
}

?>