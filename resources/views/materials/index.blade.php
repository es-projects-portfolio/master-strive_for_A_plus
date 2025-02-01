<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Material') }}
        </h2>
    </x-slot>
    
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="mt-6 bg-gray-100 rounded-lg divide-y">
            @foreach ($materials as $material)
                <div class="p-6 flex space-x-2 mt-6 bg-white shadow-sm rounded-lg divide-y">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $material->user->name }}</span>
                                <small class="ml-2 text-sm text-gray-600">{{ $material->created_at->format('j M Y, g:i a') }}</small>
                                @unless ($material->created_at->eq($material->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                                <div class="text-sm text-gray-600">
                                    @if ($material->visible_to_all)
                                        {{ __('Public') }}
                                    @else
                                        {{ $material->section->course->course_name }} | Section {{ $material->section->section_number }}
                                    @endif
                                </div>
                            </div>
                            @if ($material->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('materials.edit', $material)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('materials.destroy', $material) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('materials.destroy', $material)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        <p class="mt-4 text-lg text-gray-900">{{ $material->message }}</p>
                        @if ($material->image)
                            <img src="{{ asset('storage/' . $material->image) }}" alt="Material Image" class="mt-4">
                        @endif
                        @if ($material->video)
                            <video controls class="mt-4">
                                <source src="{{ asset('storage/' . $material->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                        @if ($material->file_path)
                            <div class="mt-4">
                                <x-secondary-button 
                                    tag="a"
                                    href="{{ asset('storage/' . $material->file_path) }}"
                                    download
                                    class="inline-flex items-center px-4 py-2 border rounded-md hover:bg-indigo-50 transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download File
                                </x-secondary-button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>