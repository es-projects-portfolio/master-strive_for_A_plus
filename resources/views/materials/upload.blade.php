<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Material') }}
        </h2>
    </x-slot>
    
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('materials.store') }}" enctype="multipart/form-data">
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />

            <div class="flex space-x-2 mt-2">
                <!-- Image upload button -->
                <button type="button" class="group relative cursor-pointer transition-all duration-200 hover:scale-105" onclick="document.getElementById('image-input').click();">
                    <input type="file" name="image" class="hidden" accept="image/*" id="image-input">
                    <div class="p-2 rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-indigo-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12a4 4 0 100-8 4 4 0 000 8zm0 0v4m0 4h.01" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-xs text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Image</span>
                </button>
                <!-- Video upload button -->

                <button type="button" class="group relative cursor-pointer transition-all duration-200 hover:scale-105" onclick="document.getElementById('video-input').click();">
                    <input type="file" name="video" class="hidden" accept="video/*" id="video-input">
                    <div class="p-2 rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-indigo-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 0l-4.553 2.276A1 1 0 013 15.382V8.618a1 1 0 011.447-.894L9 10m6 0v4m0 4H9m6-4H9" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-xs text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Video</span>
                </button>
                <!-- File upload button -->

                <button type="button" class="group relative cursor-pointer transition-all duration-200 hover:scale-105" onclick="document.getElementById('file-input').click();">
                    <input type="file" name="file_path" class="hidden" id="file-input">
                    <div class="p-2 rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-indigo-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-xs text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200">File</span>
                </button>
            </div>

            <div class="mt-4">
                <label for="visible_to_all" class="block text-sm font-medium text-gray-700">{{ __('Visibility') }}</label>
                <div class="flex items-center">
                    <input type="checkbox" name="visible_to_all" id="visible_to_all" value="1" class="text-sm text-gray-700 mr-4 px-2" checked>
                    <label for="visible_to_all" class="text-sm text-gray-700 px-2">{{ __('All') }}</label>
                </div>
            </div>

            <div class="mt-4" id="section-field" style="display: none;">
                <label for="section_id" class="block text-sm font-medium text-gray-700">{{ __('Section') }}</label>
                <select id="section_id" name="section_id" class="rounded">
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->course->course_name }} | Section {{ $section->section_number }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Error messages and preview section -->
            <div id="image-preview" class="mt-4 space-y-2"></div>
            
            <x-primary-button class="mt-4">{{ __('Upload') }}</x-primary-button>
        </form>
    </div>
    <script>
        document.getElementById('visible_to_all').addEventListener('change', function(event) {
            const sectionField = document.getElementById('section-field');
            if (event.target.checked) {
                sectionField.style.display = 'none';
            } else {
                sectionField.style.display = 'block';
            }
        });

        document.getElementById('image-input').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'mt-4';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });

        document.querySelectorAll('button.cursor-pointer').forEach(button => {
            button.addEventListener('click', function() {
                this.querySelector('input[type="file"]').click();
            });
        });
    </script>
</x-app-layout>
