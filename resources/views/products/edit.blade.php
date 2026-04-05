<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            પ્રોડક્ટ સુધારો (Edit Product)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('products.update', $product) }}" x-data="{ variants: @json(old('variants', $product->variants)) || [] }">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('પ્રોડક્ટનું નામ (Product Name)')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Variants -->
                        <div class="mb-4 bg-gray-50 border border-gray-100 rounded p-4">
                            <h3 class="font-medium text-lg mb-4">વેરિઅન્ટ્સ (Variants)</h3>
                            <x-input-error :messages="$errors->get('variants')" class="mb-2" />

                            <div class="space-y-4">
                                <template x-for="(variant, index) in variants" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end border-b pb-4 md:border-0 md:pb-0">
                                        <div>
                                            <x-input-label x-bind:for="'h-'+index" :value="__('ઊંચાઈ (Height)')" />
                                            <x-text-input x-bind:id="'h-'+index" class="block mt-1 w-full" type="text" x-bind:name="'variants['+index+'][height]'" x-model="variant.height" />
                                        </div>
                                        <div>
                                            <x-input-label x-bind:for="'b-'+index" :value="__('થેલી સાઈઝ (Bag Size)')" />
                                            <x-text-input x-bind:id="'b-'+index" class="block mt-1 w-full" type="text" x-bind:name="'variants['+index+'][bag_size]'" x-model="variant.bag_size" />
                                        </div>
                                        <div>
                                            <x-input-label x-bind:for="'p-'+index" :value="__('કિંમત (Price)')" />
                                            <x-text-input x-bind:id="'p-'+index" class="block mt-1 w-full" type="number" step="0.01" x-bind:name="'variants['+index+'][price]'" x-model="variant.price" />
                                        </div>
                                        <div>
                                            <button type="button" class="text-red-500 font-bold hover:text-red-700" @click="variants.splice(index, 1)" x-show="variants.length > 1">
                                                દૂર કરો (Remove)
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="variants.push({ height: '', bag_size: '', price: '' })" class="mt-4 px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">
                                + વેરિઅન્ટ ઉમેરો (Add Variant)
                            </button>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                સુધારો સાચવો (Update)
                            </x-primary-button>
                            <a href="{{ route('products.index') }}" class="ml-4 text-sm text-gray-600 hover:text-gray-900">
                                કેન્સલ (Cancel)
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
