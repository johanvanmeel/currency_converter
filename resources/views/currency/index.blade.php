<x-guest-layout>
    <div class="py-12">
        <div class="max-w-[90%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Currency Converter</h1>
                    <form action="{{ route('currency.convert') }}" method="GET" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="currency_id" class="block text-sm font-medium text-gray-700">From
                                    Currency</label>
                                <select name="currency_id" id="currency_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach($currencies as $currency)
                                        <option
                                            value="{{ $currency->id }}" {{ (isset($fromCurrency) && $fromCurrency->id == $currency->id) ? 'selected' : '' }}>
                                            {{ $currency->code }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" step="any" name="amount" id="amount" value="{{ $amount ?? 1 }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                        class="w-full bg-indigo-600 border rounded-md py-2 px-4 flex items-center justify-center text-base font-medium text-black hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Convert Now
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(isset($conversions) && $conversions->count() > 0)
                        <h2 class="text-xl font-semibold mb-4">Results for {{ $amount }} {{ $fromCurrency->code }}</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Currency
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rate
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Converted Amount
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($conversions as $conversion)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $conversion['code'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $conversion['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($conversion['rate'], 6) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">{{ number_format($conversion['converted_amount'], 2) }} {{ $conversion['code'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif(isset($fromCurrency))
                        <p class="text-gray-500">
                            No conversions found for this currency. Try running the fetch command.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
