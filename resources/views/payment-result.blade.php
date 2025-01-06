<!-- resources/views/payment-result.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Result</title>
</head>
<body>
    <h1>Payment Result</h1>
    
    <!-- Display the image and other data from JavaScript -->
    <div id="payment-details"></div>

    <script>
        // Retrieve the payment response from sessionStorage
        const paymentResponse = JSON.parse(sessionStorage.getItem('payment_response'));

        // Display the image URI and other details if available
        if (paymentResponse && paymentResponse.image_uri) {
            document.getElementById('payment-details').innerHTML = `
                <p><strong>Image URI:</strong></p>
                <img src="${paymentResponse.image_uri}" alt="Digital Check Image">
                <p><strong>Amount:</strong> ${paymentResponse.amount || 'N/A'}</p>
                <p><strong>Description:</strong> ${paymentResponse.description || 'N/A'}</p>
            `;
            // Clear the session storage after loading
            sessionStorage.removeItem('payment_response');
        } else {
            document.getElementById('payment-details').innerHTML = '<p>No payment details available.</p>';
        }
    </script>
</body>
</html>
