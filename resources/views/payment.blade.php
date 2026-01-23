<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Payment</title>
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        /* contoh styling untuk tombol bayar*/
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        #pay-button {
            background-color: #007bff;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
      // Ambil tombol bayar
      var payButton = document.getElementById('pay-button');
      payButton.addEventListener('click', function () {
        // Panggil snap.pay() dengan snap token dari controller
        window.snap.pay('{{ $snap_token }}', {
          onSuccess: function(result){
            /* You may add your own implementation here */
            alert("payment success!"); console.log(result);
          },
          onPending: function(result){
            /* You may add your own implementation here */
            alert("wating your payment!"); console.log(result);
          },
          onError: function(result){
            /* You may add your own implementation here */
            alert("payment failed!"); console.log(result);
          },
          onClose: function(){
            /* You may add your own implementation here */
            alert('you closed the popup without finishing the payment');
          }
        })
      });
    </script>

</body>
</html>
