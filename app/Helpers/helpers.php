<?php
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use App\Models\DoctorWallet;
use App\Models\WalletTransaction;
use App\Models\UserWallet;
use App\Models\UserNotification;
use App\Models\DoctorNotification;
use App\Models\AdminNotification;
// use Intervention\Image\ImageManagerStatic as Image;

    function priceicon(): string
    {
        return '$';
    }

    function projectName(): string
    {
        return 'Telemedicine';
    }

    function helplinenumber(): string
    {
        return '1800-0101-7890';
    }

    function commistion_charge(): string
    {
        return '10';
    }

    function userinfo()
    {
        return Auth::guard('web')->user();
    }
    function admininfo()
    {
        return Auth::guard('admin')->user();
    }

    function sellerinfo()
    {
        return Auth::guard('seller')->user();
    }


    function auditLog($actorType, $actorId, $event, $meta = [])
    {
        AuditLog::create([
            'actor_type' => $actorType,
            'actor_id'   => $actorId,
            'event'      => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'meta'       => $meta
        ]);
    }

    function uploadWebp($file, $folder)
    {
        if (!$file) return null;

        // Ensure folder exists in the 'public' disk
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        // Generate unique filename (without extension)
        $uniqueName = uniqid($folder . '_');

        // Save original image
        $originalExtension = $file->getClientOriginalExtension();
        $originalFilename = "$uniqueName.$originalExtension";
        Storage::disk('public')->putFileAs($folder, $file, $originalFilename);

        // Save webp version
        $webpFilename = "$uniqueName.webp";

        $manager = new ImageManager();
        $webpImage = $manager->make($file)->encode('webp', 90);        

        Storage::disk('public')->put("$folder/$webpFilename", (string) $webpImage);

        // Return both paths if needed
        return [
            'original' => "$folder/$originalFilename",
            'webp'     => "$folder/$webpFilename",
        ];
    }

    function variantImage($webpPath = null, $originalPath = null, $width = 60, $height = 60, $fallback = 'no-image.png')
    {
        // Determine image URLs from storage
        $webpUrl     = $webpPath && Storage::disk('public')->exists($webpPath) ? asset('storage/' . $webpPath) : null;
        $originalUrl = $originalPath && Storage::disk('public')->exists($originalPath) ? asset('storage/' . $originalPath) : null;

        // Fallback if none exist
        $finalImg = $originalUrl ?? asset('images/' . $fallback);

        // Output HTML <picture> tag
        $html = '<picture>';
        if ($webpUrl) {
            $html .= '<source srcset="' . $webpUrl . '" type="image/webp">';
        }
        $html .= '<img src="' . $finalImg . '" width="' . $width . '" height="' . $height . '" style="object-fit:cover; border-radius:6px;">';
        $html .= '</picture>';

        return $html;
    }

    if (!function_exists('categoryImage')) {
        function categoryImage($path = null, $width = 50, $height = 50, $fallback = 'no-image.png')
        {
            $imageUrl = $path && Storage::disk('public')->exists($path)
                ? asset('storage/' . $path)
                : asset('images/' . $fallback);

            return '<img src="' . $imageUrl . '" width="' . $width . '" height="' . $height . '" style="object-fit:cover; border-radius:8px;">';
        }
    }

    if (!function_exists('productImage')) {
        
        function productImage($webpPath = null, $originalPath = null, $width = 152, $height = 152, $fallback = 'no-image.png')
        {
            $webpUrl = $webpPath && Storage::disk('public')->exists($webpPath)
                ? asset('storage/' . $webpPath)
                : null;

            $originalUrl = $originalPath && Storage::disk('public')->exists($originalPath)
                ? asset('storage/' . $originalPath)
                : asset('images/' . $fallback);

            // Final image URL (if no webp available)
            $finalImg = $webpUrl ?? $originalUrl;

            // HTML structure
            $html = '<div class="product-img-wrap" style="position:relative;">';
            $html .= '<picture>';
            if ($webpUrl) {
                $html .= '<source srcset="' . $webpUrl . '" type="image/webp">';
            }
            $html .= '<img src="' . $finalImg . '" alt="Product Image" width="' . $width . '" height="' . $height . '" style="object-fit:cover; border-radius:10px;">';
            $html .= '</picture>';
            $html .= '</div>';

            return $html;
        }
    }


    if (!function_exists('whshlistImage')) {
        function whshlistImage($path = null, $width = 100, $height = 100, $fallback = 'no-image.png')
        {
            $imageUrl = $path && Storage::disk('public')->exists($path)
                ? asset('storage/' . $path)
                : asset('images/' . $fallback);

            return '<img src="' . $imageUrl . '" width="' . $width . '" height="' . $height . '" style="object-fit:cover; border-radius:8px;">';
        }
    }


    if (!function_exists('singleProduct')) {
        
        function singleProduct($webpPath = null, $originalPath = null, $width = 250, $height = 300, $fallback = 'no-image.png')
        {
            $webpUrl = $webpPath && Storage::disk('public')->exists($webpPath)
                ? asset('storage/' . $webpPath)
                : null;

            $originalUrl = $originalPath && Storage::disk('public')->exists($originalPath)
                ? asset('storage/' . $originalPath)
                : asset('images/' . $fallback);

            // Final image URL (if no webp available)
            $finalImg = $webpUrl ?? $originalUrl;

            // HTML structure
            $html = '<div class="product-img-wrap" style="position:relative;">';
            $html .= '<picture>';
            if ($webpUrl) {
                $html .= '<source srcset="' . $webpUrl . '" type="image/webp">';
            }
            $html .= '<img src="' . $finalImg . '" alt="Product Image" width="' . $width . '" height="' . $height . '" style="object-fit:cover; border-radius:10px;">';
            $html .= '</picture>';
            $html .= '</div>';

            return $html;
        }
    }


    if (!function_exists('slideProduct')) {
        
        function slideProduct($webpPath = null, $originalPath = null, $width = 100, $height = 70, $fallback = 'no-image.png')
        {
            $webpUrl = $webpPath && Storage::disk('public')->exists($webpPath)
                ? asset('storage/' . $webpPath)
                : null;

            $originalUrl = $originalPath && Storage::disk('public')->exists($originalPath)
                ? asset('storage/' . $originalPath)
                : asset('images/' . $fallback);

            // Final image URL (if no webp available)
            $finalImg = $webpUrl ?? $originalUrl;

            // HTML structure
            $html = '<div class="product-img-wrap" style="position:relative;">';
            $html .= '<picture>';
            if ($webpUrl) {
                $html .= '<source srcset="' . $webpUrl . '" type="image/webp">';
            }
            $html .= '<img src="' . $finalImg . '" alt="Product Image" width="' . $width . '" height="' . $height . '" style="object-fit:cover; border-radius:10px;">';
            $html .= '</picture>';
            $html .= '</div>';

            return $html;
        }
    }


    function walletCredit($walletType, $walletId, $amount, $reason = null, $meta = [])
    {
        $wallet = $walletType === 'user'
            ? UserWallet::findOrFail($walletId)
            : DoctorWallet::findOrFail($walletId);

        $wallet->increment('balance', $amount);

        WalletTransaction::create([
            'wallet_type' => $walletType,
            'wallet_id'   => $wallet->id,
            'type'        => 'credit',
            'amount'      => $amount,
            'balance'      => $wallet->balance ?? '0',
            'reason'      => $reason,
            'meta'        => $meta
        ]);
    }


    function walletDebit($walletType, $walletId, $amount, $reason = null, $meta = [])
    {
        $wallet = $walletType === 'user'
            ? UserWallet::findOrFail($walletId)
            : DoctorWallet::findOrFail($walletId);

        if ($wallet->balance < $amount) {
            throw new Exception('Insufficient wallet balance');
        }

        $wallet->decrement('balance', $amount);

        WalletTransaction::create([
            'wallet_type' => $walletType,
            'wallet_id'   => $wallet->id,
            'type'        => 'debit',
            'amount'      => $amount,
            'balance'      => $wallet->balance ?? '0',
            'reason'      => $reason,
            'meta'        => $meta
        ]);
    }


    function notifyUser($userId, $type, $title, $note = null)
    {
        UserNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'note' => $note,
        ]);
    }

    function notifyDoctor($doctorId, $type, $title, $note = null)
    {
        DoctorNotification::create([
            'doctor_id' => $doctorId,
            'type' => $type,
            'title' => $title,
            'note' => $note,
        ]);
    }

    function notifyAdmin($type, $title, $note = null)
    {
        AdminNotification::create([
            'type' => $type,
            'title' => $title,
            'note' => $note,
        ]);
    }

?>