<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Request a Payment') }}
        </h2>
    </x-slot>
    <?php if($errors) {
        foreach ($errors as $error) {
            echo '<p class="text-red text-sm bg-white">'.$error.'</p>';
        }
    }
    ?>
    <div class="flex flex-col items-center w-full max-w-xl mx-auto py-20 px-6">
        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="block mt-4 space-y-1">
                <x-input-label class="pl-1 text-xl">Request From:</x-input-label>
                <x-text-input name="to" type="email" class="w-full text-lg" placeholder="user@example.com" value="{{ old('to') }}" required/>
                <x-input-error :messages="$errors->get('to')" class="mt-2" />
            </div>
            <div class="block mt-4 space-y-1">
                <x-input-label class="pl-1 text-xl">Amount:</x-input-label>
                <x-text-input name="amount" type="text" class="w-full text-lg" placeholder="$10,000" value="{{ old('amount') }}" required/>
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </div>
            <div class="block mt-4 space-y-1">
                <x-input-label class="pl-1 text-xl">Due Date:</x-input-label>
                <x-text-input name="due_date" type="date" class="w-full text-lg" placeholder="$10,000" value="{{ old('due_date') }}" required/>
                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
            </div>
            <div class="block mt-4 space-y-1">
                <x-input-label class="pl-1 text-xl">Details <span class="text-sm">(optional)</span> :</x-input-label>
                <textarea name="message" type="text"
                          class="w-full h-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm resize-none"
                          placeholder="Happy birthday">{{ old('message') }}</textarea>
                <x-input-error :messages="$errors->get('message')" class="mt-2" />
            </div>
            <div class="block mt-4 space-y-1">
                <x-secondary-button type="submit" class="w-full border-none">Send</x-secondary-button>
            </div>
        </form>
    </div>
</x-app-layout>
