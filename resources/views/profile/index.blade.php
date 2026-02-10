    @extends('layouts.app')
@section('content')

<!-- Include Cropper.js CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-12 space-y-4 text-center">
            <div class="inline-block p-4 bg-yellow-500/10 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-4xl font-extrabold text-white tracking-tight">Edit Your Profile</h2>
            <p class="text-xl text-gray-400 max-w-xl mx-auto">Customize your personal information and preferences with ease.</p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-8 bg-green-500/20 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        <div class="grid md:grid-cols-[2fr_1fr] gap-8">
            <div class="bg-gray-800/60 backdrop-blur-lg border border-white/10 rounded-2xl p-8 shadow-2xl">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group relative">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full bg-gray-700/50 border border-gray-600/30 text-white rounded-lg px-4 py-3
                                focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                                transition duration-300 ease-in-out group-hover:border-yellow-500"
                                required>
                        </div>
                        <div class="group relative">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full bg-gray-700/50 border border-gray-600/30 text-white rounded-lg px-4 py-3
                                focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                                transition duration-300 ease-in-out group-hover:border-yellow-500"
                                required>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group relative">
                            <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank if unchanged"
                                class="w-full bg-gray-700/50 border border-gray-600/30 text-white rounded-lg px-4 py-3
                                focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                                transition duration-300 ease-in-out group-hover:border-yellow-500">
                        </div>
                        <div class="group relative">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password"
                                class="w-full bg-gray-700/50 border border-gray-600/30 text-white rounded-lg px-4 py-3
                                focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                                transition duration-300 ease-in-out group-hover:border-yellow-500">
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group relative">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                            <input type="text" name="alamat" value="{{ old('alamat', $user->profile->alamat ?? '') }}"
                                class="w-full bg-gray-700/50 border border-gray-600/30 text-white rounded-lg px-4 py-3
                                focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                                transition duration-300 ease-in-out group-hover:border-yellow-500">
                        </div>
                        <div class="group relative">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $user->profile->nomor_telepon ?? '') }}"
                                class="w-full bg-gray-700/50 border border-gray-600/30 text-white rounded-lg px-4 py-3
                                focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                                transition duration-300 ease-in-out group-hover:border-yellow-500">
                        </div>
                    </div>

                    {{-- Avatar Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Profile Picture</label>
                        <div class="flex items-center space-x-6">
                            <div class="flex-grow">
                                <input type="file" name="avatar" id="avatar-input" accept=".jpg, .jpeg, .png"
                                    class="w-full text-sm text-gray-300
                                    file:mr-4 file:rounded-lg file:border-0
                                    file:px-4 file:py-2 file:text-sm
                                    file:bg-yellow-500/20 file:text-yellow-400
                                    hover:file:bg-yellow-500/30
                                    cursor-pointer">
                                <input type="hidden" name="cropped_avatar" id="cropped-avatar">
                            </div>
                        {{-- Cropped Image Preview --}}
                        <div id="cropped-preview" class="mt-4 hidden">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cropped Preview</label>
                            <div class="flex items-center space-x-4">
                                <img id="cropped-image" class="w-24 h-24 rounded-full object-cover border-4 border-yellow-500/30" alt="Cropped Preview">
                                <button type="button" id="recrop-image" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                    Re-crop Image
                                </button>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-yellow-500 text-black font-semibold rounded-lg
                            hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2
                            transform transition duration-300 hover:scale-105 active:scale-95
                            flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Profile Preview --}}
            <div class="hidden md:block bg-gray-800/60 backdrop-blur-lg border border-white/10 rounded-2xl p-8">
                <div class="flex items-center space-x-6 mb-6">
                    <div class="relative">
                        @if(isset($user->profile->avatar))
                            <img src="{{ asset('avatars/' . $user->profile->avatar) }}"
                                alt="Profile Picture"
                                class="w-24 h-24 rounded-full object-cover border-4 border-yellow-500/30
                                shadow-2xl transition duration-500 hover:scale-110 hover:border-yellow-500">
                        @else
                            <div class="bg-gray-700/50 rounded-full w-24 h-24 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500 text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">{{ $user->name }}</h3>
                        <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if(!empty($user->profile->alamat))
                        <div class="flex items-center space-x-2 text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $user->profile->alamat }}</span>
                        </div>
                    @endif

                    @if(!empty($user->profile->nomor_telepon))
                        <div class="flex items-center space-x-2 text-gray-400">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{ $user->profile->nomor_telepon }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cropper-modal" class="fixed inset-0 bg-black bg-black/50 backdrop-blur-sm flex items-center justify-center hidden overflow-auto z-50">

    <div class="bg-gray-800 rounded-lg p-6 max-w-lg w-full max-h-screen overflow-y-auto">
        <h3 class="text-lg font-semibold text-white mb-4">Crop Your Image</h3>
        <div class="mb-4">
            <img id="image-to-crop" class="max-w-full h-auto" src="" alt="Image to crop">
        </div>
        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-700">
            <button id="cancel-crop" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">Cancel</button>
            <button id="crop-image" class="px-4 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 transition duration-200">Crop & Preview</button>
        </div>
    </div>
</div>

<script>
    let cropper;
    let originalFile; // Store the original file for re-cropping
    const avatarInput = document.getElementById('avatar-input');
    const cropperModal = document.getElementById('cropper-modal');
    const imageToCrop = document.getElementById('image-to-crop');
    const cancelCrop = document.getElementById('cancel-crop');
    const cropImage = document.getElementById('crop-image');
    const croppedAvatarInput = document.getElementById('cropped-avatar');
    const croppedPreview = document.getElementById('cropped-preview');
    const croppedImage = document.getElementById('cropped-image');
    const recropImage = document.getElementById('recrop-image');

    avatarInput.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files && files.length > 0) {
            originalFile = files[0]; // Store the original file
            const reader = new FileReader();
            reader.onload = (event) => {
                imageToCrop.src = event.target.result;
                cropperModal.classList.remove('hidden');
                croppedPreview.classList.add('hidden'); // Hide preview when new image is selected
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1, // Square crop for profile picture
                    viewMode: 1,
                    autoCropArea: 0.8,
                    responsive: true,
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    cancelCrop.addEventListener('click', () => {
        cropperModal.classList.add('hidden');
        avatarInput.value = ''; // Reset file input
        croppedPreview.classList.add('hidden'); // Hide preview
        if (cropper) {
            cropper.destroy();
        }
    });

    cropImage.addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({
            width: 200, // Desired width
            height: 200, // Desired height
        });
        canvas.toBlob((blob) => {
            const file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            avatarInput.files = dataTransfer.files;
            croppedAvatarInput.value = canvas.toDataURL('image/jpeg');
            croppedImage.src = canvas.toDataURL('image/jpeg');
            croppedPreview.classList.remove('hidden'); // Show preview
            cropperModal.classList.add('hidden');
            cropper.destroy();
        }, 'image/jpeg', 0.8);
    });

    recropImage.addEventListener('click', () => {
        if (originalFile) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imageToCrop.src = event.target.result;
                cropperModal.classList.remove('hidden');
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 0.8,
                    responsive: true,
                });
            };
            reader.readAsDataURL(originalFile);
        }
    });
</script>

@endsection

