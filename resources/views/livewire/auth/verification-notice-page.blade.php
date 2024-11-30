<div style="min-height: 100vh; display: flex; justify-content: center; align-items: center; background-color: #f3f4f6;">
    <div style="background-color: white; padding: 32px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px;">
        <h1 style="font-size: 24px; font-weight: 600; text-align: center; margin-bottom: 24px;">Verifikasi Email Anda</h1>
        <p style="font-size: 18px; text-align: center; margin-bottom: 32px;">Silakan cek email Anda untuk menyelesaikan proses verifikasi.</p>
        <form method="POST" action="{{ route('verification.resend') }}" id="resend-form">
            @csrf
            <div style="display: flex; justify-content: space-between;">
                <button id="resend-button" type="submit" style="width: 100%; background-color: #48bb78; color: white; padding: 12px 32px; border-radius: 8px; border: none; cursor: pointer;">
                    Kirim Ulang Email Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('#resend-button').addEventListener('click', function(event) {
        const button = event.target;

        // Disable button and change appearance
        button.disabled = true;
        button.style.backgroundColor = '#e2e8f0';
        button.style.cursor = 'not-allowed';
        
        // Send POST request to resend verification email
        fetch("{{ route('verification.resend') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                // You can add extra data here if needed
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tautan verifikasi telah dikirim!');
            } else {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });

        // Prevent default form submission (no page refresh)
        event.preventDefault();

        // Re-enable the button immediately after clicking
        setTimeout(function() {
            button.disabled = false;
            button.style.backgroundColor = '#48bb78';
            button.style.cursor = 'pointer';
        }, 10000);  // Button re-enabled after 1 second
    });
</script>
