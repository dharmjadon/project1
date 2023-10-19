<?php

use App\Models\User;
use App\Models\MoreInfo;
use App\Models\Job;
use App\Models\Blog;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Venue;
use App\Models\Banner;
use App\Models\Events;
use App\Models\UserType;
use App\Models\BuySell;
use App\Models\Gallery;
use App\Models\Tickets;
use App\Models\AlertNews;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\BookArtist;
use App\Models\Influencer;
use App\Models\Attraction;
use App\Models\Accommodation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


function propertyCard($obj)
{
	$dir = (isset($obj->dir)) ? $obj->dir : 'accommodation';
	$href = (isset($obj->href)) ? $obj->href : '';
	$isActive = (isset($obj->isActive)) ? $obj->isActive : '';
	if ($dir == 'motors') {
		$str = view('user.common.motorCard', get_defined_vars());
	} else {
		$str = view('user.common.spaceCard', get_defined_vars());
	}
	return $str;
}
function isJSON($string)
{
	return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}
function optionTagdata($data, $key_id, $mrow_id = 0)
{
	$str = '';
	if (isset($data) && !empty($data)) {
		foreach ($data as $row) {
			if ($row->id == $key_id) {
				if (!empty($row)) {
					foreach ($row->subCategory as $r) {

						$selected = ($r->id == $mrow_id) ? 'selected' : '';
						$str .= '<option ' . $selected . ' value="' . $r->id . '">' . $r->name . '</option>';
					}
				}
			}
		}
	}
	return $str;
}
function saveCommoncomponent($request)
{
	try {
		ini_set('upload_max_filesize', '500M');
		ini_set('post_max_size', '500M');
		ini_set('max_input_time', 300);
		ini_set('max_execution_time', 300);

		DB::beginTransaction();

		if (!File::exists(public_path('uploads/more_info'))) {
			File::makeDirectory(public_path('uploads/more_info'), 0777, true, true);
		}
		if ($request->hasfile('offer_img')) {
			$files = [];
			foreach ($request->file('offer_img') as $file) {
				// $name_image_path = time() . rand(1, 100) . '.' . $file->extension();
				// $file->move(public_path('uploads/more_info/'), $name);
				// $imageFullPath = 'uploads/more_info/' . $name;

                    $name_image_path = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                    $featureimagePath = config('app.upload_other_path') . $name_image_path;
                    Storage::disk('s3')->put($featureimagePath, file_get_contents($file));


				$obj = new MoreInfo();
				$obj->section_name = 'offer';
                $obj->section_heading = $request->offer_section_heading ?? '';
                $obj->section_summary = $request->offer_section_summary ?? '';
				$obj->file_type = 'image';
				$obj->file_path = $name_image_path;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				$obj->save();
			}
		}

		if ($request->hasfile('qrcode_img')) {

			$app_platform_list = $request->app_platform_list;

			foreach ($request->file('qrcode_img') as $key => $file) {

				// $name = time() . rand(1, 100) . '.' . $file->extension();
				// $file->move(public_path('uploads/more_info/'), $name);
				// $imageFullPath = 'uploads/more_info/' . $name;

                $name_image_path = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $featureimagePath = config('app.upload_other_path') . $name_image_path;
                Storage::disk('s3')->put($featureimagePath, file_get_contents($file));

				$obj = new MoreInfo();
				$obj->section_name = 'download_app';
                $obj->section_heading = $request->qrcode_section_heading ?? '';
                $obj->section_summary = $request->qrcode_section_summary ?? '';
				$obj->file_type = 'image';
				$obj->file_name = $app_platform_list[$key];
				$obj->file_path = $name_image_path;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');

				$obj->save();
			}
		}

		if ($request->social_media_list && !empty($request->social_media_list)) {
			$link_list = $request->social_media_url;
			/*$data=MoreInfo::where(['module_id'=>$request->module_id,'section_name'=>'followus','module_name'=>$request->module_name]);
        	$data->delete();

        	Log::info('Media List :'.json_encode($request->social_media_list));*/
			foreach ($request->social_media_list as $key => $file) {

				if ($file == '') {
					continue;
				}
				$obj = new MoreInfo();
				$obj->section_name = 'followus';
                $obj->section_heading = $request->social_section_heading ?? '';
                $obj->section_summary = $request->social_section_summary ?? '';
				$obj->file_type = 'text';
				$obj->file_name = $file;
				$obj->file_path = $link_list[$key];
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');

				$obj->save();
			}
		}

		if ($request->hasfile('gallary_img')) {
			foreach ($request->file('gallary_img') as $key => $file) {

				// $name = time() . rand(1, 100) . '.' . $file->extension();
				// $file->move(public_path('uploads/more_info/'), $name);
				// $imageFullPath = 'uploads/more_info/' . $name;

                $name_image_path = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $featureimagePath = config('app.upload_other_path') . $name_image_path;
                Storage::disk('s3')->put($featureimagePath, file_get_contents($file));

				$obj = new MoreInfo();
				$obj->section_name = 'gallary';
                $obj->section_heading = $request->gallery_section_heading ?? '';
                $obj->section_summary = $request->gallery_section_summary ?? '';
				$obj->file_type = 'image';
				$obj->file_path = $name_image_path;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				$obj->save();
			}
		}

		/*if($request->hasfile('gallary_videos'))
	    {

            foreach ($request->file('gallary_videos') as $file)
            {
                $name = time().rand(1,100).'.'.$file->extension();
                $file->move(public_path('uploads/more_info/'), $name);
                $imageFullPath = 'uploads/more_info/'.$name;

                $obj=new MoreInfo();
                $obj->section_name='gallary';
	            $obj->file_type='videos';
	            $obj->file_path=$imageFullPath;
	            $obj->module_name=$request->module_name;
	            $obj->module_id=$request->module_id;
	            $obj->user_id=Auth::user()->id;
	            $obj->user_type=$request->user_type;
	            $obj->created_at=date('Y-m-d H:i:s');
			    $obj->save();
            }

        }*/
		if (isset($request->videoLink) && count($request->videoLink) > 0) {

			foreach ($request->videoLink as $file) {
				if ($file == '') {
					continue;
				}
				$obj = new MoreInfo();
				$obj->section_name = 'gallary';
                $obj->section_heading = $request->gallery_section_heading ?? '';
                $obj->section_summary = $request->gallery_section_summary ?? '';
				$obj->file_type = 'videos';
				$obj->file_path = $file;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				$obj->save();
			}
		}
		if (isset($request->positionlist) && !empty($request->positionlist)) {

			foreach ($request->positionlist as $file) {
				if ($file == null) {
					continue;
				}
				$obj = new MoreInfo();
				$obj->section_name = 'career';
                $obj->section_heading = $request->career_section_heading ?? '';
                $obj->section_summary = $request->career_section_summary ?? '';
				$obj->file_type = 'text';
				$obj->file_name = $file;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				$obj->save();
			}
		}
		if ($request->hasfile('storeImage')) {

			$app_platform_list = $request->app_platform_list;
			foreach ($request->file('storeImage') as $key => $file) {
				// $name = time() . rand(1, 100) . '.' . $file->extension();
				// $file->move(public_path('uploads/more_info/'), $name);
				// $imageFullPath = 'uploads/more_info/' . $name;

                $name_image_path = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $featureimagePath = config('app.upload_other_path') . $name_image_path;
                Storage::disk('s3')->put($featureimagePath, file_get_contents($file));



				$obj = new MoreInfo();
				$obj->section_name = 'store';
                $obj->section_heading = $request->store_section_heading ?? '';
                $obj->section_summary = $request->store_section_summary ?? '';
				$obj->file_type = 'image';
				$obj->file_name = ''; //$app_platform_list[$key];
				$obj->file_path = $name_image_path;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				$obj->save();
			}
		}

		if ($request->hasfile('serv_images')) {

			$serv_img_name = $request->serv_img_name;
			foreach ($request->file('serv_images') as $key => $file) {

				// $name = time() . rand(1, 100) . '.' . $file->extension();
				// $file->move(public_path('uploads/more_info/'), $name);
				// $imageFullPath = 'uploads/more_info/' . $name;

                $name_image_path = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $featureimagePath = config('app.upload_other_path') . $name_image_path;
                Storage::disk('s3')->put($featureimagePath, file_get_contents($file));


				$obj = new MoreInfo();
				$obj->section_name = 'services';
                $obj->section_heading = $request->services_section_heading ?? '';
                $obj->section_summary = $request->services_section_summary ?? '';
				$obj->file_type = 'image';
				$obj->file_name = $serv_img_name[$key];
				$obj->file_path = $name_image_path;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				$obj->save();
			}
		}

		if (isset($request->career_email) && $request->career_email != '') {
			$data = MoreInfo::where(['file_type' => 'email', 'module_id' => $request->module_id, 'section_name' => 'career', 'module_name' => $request->module_name]);

			if (!empty($data->first())) {
				$data->delete();
			}
			$obj = new MoreInfo();
			$obj->section_name = 'career';
            $obj->section_heading = $request->career_section_heading ?? '';
            $obj->section_summary = $request->career_section_summary ?? '';
			$obj->file_type = 'email';
			$obj->file_name = $request->career_email;
			$obj->module_name = $request->module_name;
			$obj->module_id = $request->module_id;
			$obj->user_id = Auth::user()->id;
			$obj->user_type = $request->user_type;
			$obj->created_at = date('Y-m-d H:i:s');
			$obj->save();
		}
		if (isset($request->web_url) && $request->web_url != '') {
			$data = MoreInfo::where(['module_id' => $request->module_id, 'section_name' => 'website', 'module_name' => $request->module_name]);
			if (!empty($data->first())) {
				$data->delete();
			}
			$obj = new MoreInfo();
			$obj->section_name = 'website';
			$obj->file_type = 'text';
			$obj->file_name = $request->web_url;
			$obj->module_name = $request->module_name;
			$obj->module_id = $request->module_id;
			$obj->user_id = Auth::user()->id;
			$obj->user_type = $request->user_type;
			$obj->created_at = date('Y-m-d H:i:s');
			$obj->save();
		}

		if ($request->hasfile('dPdf')) {
//dd($request->file('dPdf'));
			$pdf_name = $request->dPdf_name;
			foreach ($request->file('dPdf') as $key => $file) {

				// $name = time() . rand(1, 100) . '.' . $file->extension();
				// $file->move(public_path('uploads/more_info/'), $name);
				// $imageFullPath = 'uploads/more_info/' . $name;


                $name_image_path = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $featureimagePath = config('app.upload_other_path') . $name_image_path;
                Storage::disk('s3')->put($featureimagePath, file_get_contents($file));


				$obj = new MoreInfo();
				$obj->section_name = 'download';
                $obj->section_heading = $request->download_section_heading ?? '';
                $obj->section_summary = $request->download_section_summary ?? '';
				$obj->file_type = 'pdf';
				$obj->file_name = $pdf_name[$key];
				$obj->file_path = $name_image_path;
				$obj->module_name = $request->module_name;
				$obj->module_id = $request->module_id;
				$obj->user_id = Auth::user()->id;
				$obj->user_type = $request->user_type;
				$obj->created_at = date('Y-m-d H:i:s');
				//dd($obj);
				$obj->save();
			}
		}
		DB::commit();
		return true;
	} catch (\Exception $e) {

		DB::rollback();
		Log::info('More Info failed ID:' . $request->module_id . ' Date:' . date('Y-m-d H:i:s') . ' Response: ' . json_encode($e->getMessage()));
		return false;
	}
}
function delCommoncomponent($request)
{
	$obj = MoreInfo::where(['module_id' => $request->id, 'section_name' => $request->section, 'module_name' => $request->module])->get();
	if (!empty($obj)) {
		foreach ($obj as $row) {
			File::delete(public_path($row->path));
		}
	}
}
function searchComparray($arr, $key)
{
	if (is_array($arr) && !empty($arr)) {
		foreach ($arr as $row) {
			if ($row->section_name == $key && !empty($row->section_name)) {
				return $row;
			}
		}
	}
	return false;
}
function visitorStats()
{

	$totalVenues = Venue::count();
	$totalEvents = Events::count();
	$totalBuySell = BuySell::count();
	$totalDirectory = Directory::count();
	$totalConcierge = Concierge::count();
	$totalInfluencer = Influencer::count();
	$totalJob = Job::count();
	$totalTicket = Tickets::count();
	$totalAccommodation = Accommodation::count();
	$totalAttraction = Attraction::count();
	$totalArtist = BookArtist::count();

	$totalAds = $totalVenues + $totalEvents + $totalBuySell + $totalDirectory + $totalConcierge + $totalInfluencer + $totalJob + $totalTicket +
		$totalAccommodation + $totalAttraction + $totalArtist;
	$totalUsers = User::where('user_type', '!=', UserType::PUBLISHER)->count();
	$totalCompanies = User::where('user_type', UserType::PUBLISHER)->count();

	$viewsVenue =  Venue::sum('views');
	$viewsEvents = Events::sum('views');
	$viewsBuySell = BuySell::sum('view_count');
	$viewsDirectory = Directory::sum('views_counter');
	$viewsConcierge = Concierge::sum('views');
	$viewsInfluencer = Influencer::sum('views');
	$viewsJob = Job::sum('views');
	$viewsAccommodation = Accommodation::sum('views');
	$viewsAttraction = Attraction::sum('views');
	$viewsArtist = BookArtist::sum('views');

	$totalViews = $viewsVenue + $viewsEvents + $viewsBuySell + $viewsDirectory + $viewsConcierge + $viewsInfluencer + $viewsJob +
		$viewsAccommodation + $viewsAttraction + $viewsArtist;

	$data = [
		"totalAds" => $totalAds,
		"totalUsers" => $totalUsers,
		"totalCompanies" => $totalCompanies,
		"totalViews" => $totalViews
	];

	return $data;
}
function deleteMorinfo($ids_arr)
{
	if (is_array($ids_arr) && !empty($ids_arr)) {
		$data = MoreInfo::whereIn('id', $ids_arr);
		try {
			foreach ($data->get() as $row) {
				File::delete(public_path($row->file_path));
			}
			$data->delete();
		} catch (\Exception $e) {
			Log::info('More info delete files faild : IDs :' . json_encode($ids_arr) . ' Message:' . json_encode($e->getMessage()));
			return false;
		}
		return true;
	}
	return false;
}

function otherImage($img){
    if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
        return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
    }
    return '/v2/images/image-placeholder.jpeg';
}

function getAmenityImage($img){
    return Storage::disk('s3')->url(config('app.upload_other_path'). 'amenties_icon/'. $img);
}

function getUserIpAddr()
{
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function userIPLocation()
{
    $position = \Stevebauman\Location\Facades\Location::get(getUserIpAddr());
    return $position;
}

if (!function_exists('createSocialShareIcons')) {
    function createSocialShareIcons($module, $route_name)
    {
        if (!empty($module->featureImage)) {
            $shareImage = $module->getStoredImage($module->featureImage->image, 'feature_image');
        } else {
            $shareImage = config('app.url') . '/v2/images/image-placeholder.jpeg';
        }
        $socialShare = [
            'title' => e($module->title),
            'description' => Str::limit(preg_replace("/\r|\n/", " ", $module->description), 150),
            'url' => route($route_name, $module->slug),
            'image' => $shareImage
        ];
        // Construct sharing URL without using any script
        $twitterURL = 'https://twitter.com/intent/tweet?text=' . urlencode($socialShare['title']) . '&amp;url=' . urlencode($socialShare['url']);
        $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($socialShare['url']);
        $googleURL = 'https://plus.google.com/share?url=' . urlencode($socialShare['url']);
        $bufferURL = 'https://bufferapp.com/add?url=' . urlencode($socialShare['url']) . '&amp;text=' . urlencode($socialShare['title']);
        $whatsappURL = 'whatsapp://send?text=' . urlencode($socialShare['title']) . ' ' . urlencode($socialShare['url']);
        $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($socialShare['url']) . '&amp;title=' . urlencode($socialShare['title']);

        // Based on popular demand added Pinterest too
        $pinterestURL = 'https://pinterest.com/pin/create/button/?url=' . urlencode($socialShare['url']) . '&amp;media=' . $socialShare['image'] . '&amp;description=' . urlencode($socialShare['title']);
        $vkURL = 'https://vk.com/share.php?url=' . urlencode($socialShare['url']);

        // Add sharing button at the end of page/page content
        $shareIcons = '<div class="dropdown-menu text-center">';
        $shareIcons .= '
                        <div class="p2">
                            <a data-original-title="Twitter" title="Twitter" href="' . $twitterURL . '" rel="nofollow noopener" target="_blank" class="droptown-item p-2" data-placement="left">
                                Twitter
                            </a>
                        </div>
                        <div class="p2">
                            <a data-original-title="Facebook" title="Facebook" href="' . $facebookURL . '" rel="nofollow noopener" target="_blank" class="droptown-item p-2" data-placement="left">
                                Facebook
                            </a>
                        </div>
                        <div class="p2">
                            <a data-original-title="Google+" title="Google Plus" href="' . $googleURL . '" rel="nofollow noopener" target="_blank" class="droptown-item p-2" data-placement="left">
                                Google Plus
                            </a>
                        </div>
                        <div class="p2">
                            <a data-original-title="LinkedIn" title="Linkedin" href="' . $linkedInURL . '" rel="nofollow noopener" target="_blank" class="droptown-item p-2" data-placement="left">
                                LinkedIn
                            </a>
                        </div>
                        <div class="p2">
                            <a data-original-title="Pinterest" title="Pinterest" href="' . $pinterestURL . '" rel="nofollow" data-pin-custom="true" target="_blank" class="droptown-item p-2" data-placement="left">
                                Pinterest
                            </a>
                        </div>';
        $shareIcons .= '</div>';
        return $shareIcons;
    }

    function getConvertAmount($input)
    {
        $input = number_format($input);
        $input_count = substr_count($input, ',');
        if ($input_count != '0') {
            if ($input_count == '1') {
                return substr($input, 0, -4) . 'k';
            } else if ($input_count == '2') {
                return substr($input, 0, -8) . 'm';
            } else if ($input_count == '3') {
                return substr($input, 0, -12) . 'b';
            } else {
                return;
            }
        } else {
            return $input;
        }
    }
}

