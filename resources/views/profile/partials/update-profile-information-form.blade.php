<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Additional Info Display -->
        @if($user->role === 'superadmin' || $user->role === 'admin_dosen')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label :value="__('NIP')" />
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $user->nip ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label :value="__('No. HP')" />
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $user->phone ?? '-' }}</p>
                </div>
            </div>
        @elseif($user->role === 'pengawas_lapangan')
            <div>
                <x-input-label :value="__('No. HP')" />
                <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $user->phone ?? '-' }}</p>
            </div>
        @elseif($user->role === 'mahasiswa')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label :value="__('NPM')" />
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $user->npm ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label :value="__('No. HP')" />
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $user->phone ?? '-' }}</p>
                </div>
            </div>
        @endif

        <div>
            <x-input-label for="avatar" :value="__('Foto Profil')" />
            <div class="mt-2 flex items-center space-x-4">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Current Avatar" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-2xl"></i>
                    </div>
                @endif
                <div>
                    <input id="avatar" name="avatar" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar-input');
            const cropModal = document.getElementById('crop-modal');
            const cropImage = document.getElementById('crop-image');
            const cancelCrop = document.getElementById('cancel-crop');
            const applyCrop = document.getElementById('apply-crop');
            const croppedAvatar = document.getElementById('cropped-avatar');

            let cropper = null;

            // Handle file selection
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        cropImage.src = e.target.result;
                        cropModal.classList.remove('hidden');

                        // Initialize Cropper.js
                        if (cropper) {
                            cropper.destroy();
                        }
                        cropper = new Cropper(cropImage, {
                            aspectRatio: 1, // Square crop
                            viewMode: 1,
                            responsive: true,
                            restore: false,
                            checkCrossOrigin: false,
                            checkOrientation: false,
                            modal: true,
                            guides: true,
                            center: true,
                            highlight: false,
                            background: false,
                            autoCrop: true,
                            autoCropArea: 0.8,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Cancel cropping
            cancelCrop.addEventListener('click', function() {
                cropModal.classList.add('hidden');
                avatarInput.value = '';
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // Apply cropping
            applyCrop.addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                    });

                    // Convert canvas to blob and set as file
                    canvas.toBlob(function(blob) {
                        const file = new File([blob], 'cropped-avatar.jpg', { type: 'image/jpeg' });

                        // Create a DataTransfer to set the file
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        avatarInput.files = dt.files;

                        // Also set the cropped image data to hidden input (as base64 for now)
                        const croppedDataUrl = canvas.toDataURL('image/jpeg');
                        croppedAvatar.value = croppedDataUrl;

                        cropModal.classList.add('hidden');
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }
                    }, 'image/jpeg', 0.9);
                }
            });

            // Close modal when clicking outside
            cropModal.addEventListener('click', function(e) {
                if (e.target === cropModal) {
                    cancelCrop.click();
                }
            });
        });
    </script>
</section>
