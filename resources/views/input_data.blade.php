<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vending Machine Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-6">Vending Machine</h1>
            <form id="payment-form">
                @csrf
                <div class="mb-4">
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <button type="submit" id="pay-button" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Pay</button>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(event){
            event.preventDefault();

            var product_id = document.getElementById('product_id').value;
            var quantity = document.getElementById('quantity').value;
            var token = document.querySelector('meta[name="csrf-token"]');

            // Log to console to ensure variables are captured correctly
            console.log('Product ID:', product_id);
            console.log('Quantity:', quantity);

            fetch("{{ route('transactions.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: product_id,
                    quantity: quantity
                })
            }).then(res => res.json()).then(data => {
                console.log('Response from server:', data);
                if(data.snap_token){
                    snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            alert("payment success!");
                            console.log(result);
                            window.location.href = '/';
                        },
                        onPending: function(result){
                            alert("wating your payment!");
                            console.log(result);
                        },
                        onError: function(result){
                            alert("payment failed!");
                            console.log(result);
                        },
                        onClose: function(){
                            alert('you closed the popup without finishing the payment');
                        }
                    });
                } else {
                    alert('Failed to get payment token.');
                }
            });
        };
    </script>
</body>
</html>
