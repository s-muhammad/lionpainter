<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesUploads;

    public function edit()
    {
        return view('admin.settings.edit', [
            'values' => Setting::allValues(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'brand' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'phone_href' => ['nullable', 'string', 'max:40'],
            'mobile' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:190'],
            'address' => ['nullable', 'string', 'max:190'],
            'service_area' => ['nullable', 'string', 'max:190'],
            'hours' => ['nullable', 'string', 'max:190'],
            'abn' => ['nullable', 'string', 'max:60'],
            'instagram' => ['nullable', 'url', 'max:190'],
            'facebook' => ['nullable', 'url', 'max:190'],
            'hero_eyebrow' => ['nullable', 'string', 'max:190'],
            'hero_title' => ['nullable', 'string', 'max:190'],
            'hero_text' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
            'home_why_image' => ['nullable', 'image', 'max:4096'],
            'home_before_image' => ['nullable', 'image', 'max:4096'],
            'home_after_image' => ['nullable', 'image', 'max:4096'],
            'intro_heading' => ['nullable', 'string', 'max:190'],
            'intro_text' => ['nullable', 'string', 'max:500'],
            'why_heading' => ['nullable', 'string', 'max:190'],
            'why_text' => ['nullable', 'string', 'max:500'],
            'about_heading' => ['nullable', 'string', 'max:190'],
            'about_text' => ['nullable', 'string', 'max:1200'],
            'years' => ['nullable', 'string', 'max:10'],
            'projects_count' => ['nullable', 'string', 'max:10'],
            'suburbs_count' => ['nullable', 'string', 'max:10'],
            'rating' => ['nullable', 'string', 'max:10'],
            'review_count' => ['nullable', 'string', 'max:10'],
            'cta_heading' => ['nullable', 'string', 'max:190'],
            'cta_text' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]);

        $images = ['hero_image', 'home_why_image', 'home_before_image', 'home_after_image'];

        foreach ($images as $image) {
            unset($data[$image]);
        }

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        foreach ($images as $image) {
            $path = $this->storeImage($request, $image, Setting::get($image, config("site.defaults.{$image}")));

            if ($path !== null) {
                Setting::put($image, $path);
            }
        }

        return back()->with('status', 'تنظیمات سایت ذخیره شد.');
    }
}
