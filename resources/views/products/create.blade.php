<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Name -->
                            <div>
                                <x-label for="name" :value="__('Name')" />

                                <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')"  autofocus />
                            </div>
                            @error('name')
                                <span style="color: red">{{$message}}</span>
                            @enderror

                             <!-- Media -->
                             <div class="mt-4">
                                <x-label for="media" :value="__('Media')" />

                                <x-input id="media" class="block mt-1 " type="file" name="media"
                                    :value="old('media')"  autofocus />
                            </div>
                            @error('media')
                                <span style="color: red">{{$message}}</span>
                            @enderror

                            <!-- Price -->
                            <div class="mt-4">
                                <x-label for="price" :value="__('Price')" />

                                <x-input id="price" class="block mt-1 w-full" type="number" step="0.01"
                                    name="price" :value="old('price')"  />
                            </div>
                            @error('price')
                            <span style="color: red">{{$message}}</span>
                        @enderror

                            <div class="flex items-center mt-4">
                                <x-button>
                                    {{ __('Save') }}
                                </x-button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
