<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateRequest;
use App\Policies\Dashboard\ProfilePolicy;
use App\Services\Dashboard\ProfileService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use Authorizable;

    /**
     * Methods that should skip automatic authorization from Authorizable trait.
     * Authorization for these methods is handled manually inside the method.
     */
    protected array $skipAuthorization = ['update', 'validateCurrentPassword'];

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ProfileService $service,
        private $policy = ProfilePolicy::class,
        private $abilities = [
            'index' => 'viewAny',
        ]
    ) {}

    /**
     * Override callAction to skip authorization for certain methods.
     */
    public function callAction($method, $parameters): mixed
    {
        if (in_array($method, $this->skipAuthorization)) {
            // Skip Authorizable trait and directly call the method
            return $this->{$method}(...array_values($parameters));
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.profile', $this->service->index());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $profileType)
    {
        if (!in_array($profileType, ['basic', 'other', 'credential', 'all'])) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe profil tidak valid.'
                ], 400);
            }
            session()->flash('error', 'Invalid profile type.');
            return back()->withInput();
        }

        // Manual authorization check for profile edit
        $permission = $profileType === 'all' ? 'profile.edit.basic' : "profile.edit.{$profileType}";
        if (!auth()->user()->can($permission)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        // Handle 'all' type for combined update
        if ($profileType === 'all') {
            $rules = $this->getValidationRules($profileType);
            $validated = $request->validate($rules);
            
            $result = $this->service->updateAll($validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => $result,
                    'message' => $result ? 'Profil berhasil diperbarui.' : 'Gagal memperbarui profil.'
                ]);
            }

            session()->flash($result ? 'success' : 'error', 
                $result ? 'Profil berhasil diperbarui.' : 'Gagal memperbarui profil.');
            return to_route('profile.index');
        }

        // Validate based on profile type
        $rules = $this->getValidationRules($profileType);
        $validated = $request->validate($rules);

        $result = $this->service->update($validated, $profileType);

        if ($request->wantsJson()) {
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => $profileType === 'credential' 
                        ? 'Kata sandi saat ini tidak sesuai.' 
                        : 'Gagal memperbarui profil.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $this->getSuccessMessage($profileType)
            ]);
        }

        if (!$result) {
            session()->flash('error', 'Failed to update profile.');
            return back()->withInput();
        }

        session()->flash('success', 'Profile updated successfully.');
        return to_route('profile.index');
    }

    /**
     * Validate current password before allowing password change.
     */
    public function validateCurrentPassword(Request $request)
    {
        // Manual authorization check
        if (!auth()->user()->can('profile.edit.credential')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'
            ], 403);
        }

        $request->validate([
            'current_password' => 'required|string',
        ]);

        $user = auth()->user();
        
        // Verify current password using Laravel's Hash facade
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini tidak sesuai.'
            ], 400);
        }

        // TODO: Implement real OTP sending via email
        // Example:
        // $otp = rand(1000, 9999);
        // Cache::put('password_change_otp_' . $user->id, $otp, now()->addMinutes(5));
        // Mail::to($user->email)->send(new PasswordChangeOTP($otp));

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi valid. Silakan lanjutkan.'
        ]);
    }

    /**
     * Get validation rules based on profile type.
     */
    private function getValidationRules(string $profileType): array
    {
        return match ($profileType) {
            'basic' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string|max:500',
                'familiar_name' => 'nullable|string|max:255',
            ],
            'other' => [
                'domicile' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:255',
                'other_phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date',
            ],
            'credential' => [
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ],
            'all' => [
                'nama_lengkap' => 'required|string|max:100',
                'nomor_whatsapp' => 'required|string|max:150',
                'email' => 'required|email|max:255',
                'gender' => 'nullable|string|in:Laki-laki,Perempuan,',
                'alamat' => 'nullable|string|max:255',
                'negara' => 'nullable|string|max:100',
                'provinsi' => 'nullable|string|max:100',
                'kota' => 'nullable|string|max:100',
                'kode_pos' => 'nullable|string|max:20',
            ],
            default => [],
        };
    }

    /**
     * Get success message based on profile type.
     */
    private function getSuccessMessage(string $profileType): string
    {
        return match ($profileType) {
            'basic' => 'Informasi pribadi berhasil diperbarui.',
            'other' => 'Informasi domisili berhasil diperbarui.',
            'credential' => 'Kata sandi berhasil diubah.',
            'all' => 'Profil berhasil diperbarui.',
            default => 'Profil berhasil diperbarui.',
        };
    }
}
