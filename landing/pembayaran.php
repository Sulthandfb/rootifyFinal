<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-step Booking Form</title>
    <style>
        :root {
            --primary-blue: #0064D2;
            --border-color: #e0e0e0;
            --text-gray: #687176;
            --background-gray: #f6f7f8;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--background-gray);
            color: #1d2329;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 800px;
            width: 90%;
            margin: 20px;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 2;
            background: var(--background-gray);
            padding: 0 10px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-weight: 600;
            transition: var(--transition);
        }

        .step.active .step-number {
            background-color: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
            transform: scale(1.1);
        }

        .step.active .step-text {
            color: var(--primary-blue);
            font-weight: 600;
        }

        /* Form sections */
        .form-section {
            display: none;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section.active {
            display: block;
        }

        .form-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        /* Radio buttons styling */
        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .radio-button {
            display: none;
        }

        .radio-label {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        .radio-button:checked + .radio-label {
            border-color: var(--primary-blue);
            background-color: rgba(0, 100, 210, 0.1);
            color: var(--primary-blue);
        }

        /* Form controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 100, 210, 0.1);
        }

        .number-input {
            width: 120px;
            text-align: center;
        }

        /* Buttons */
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-next {
            background-color: var(--primary-blue);
            color: white;
        }

        .btn-next:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        .btn-prev {
            background-color: #f0f0f0;
            color: var(--text-gray);
        }

        .btn-prev:hover {
            background-color: #e0e0e0;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                width: 95%;
                margin: 10px;
            }

            .form-section {
                padding: 20px;
            }

            .radio-group {
                flex-direction: column;
                gap: 10px;
            }

            .step-text {
                display: none;
            }

            .progress-steps {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-text">Detail Pesanan</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-text">Jumlah Tamu</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-text">Pembayaran</div>
            </div>
        </div>

        <!-- Form Sections -->
        <form id="bookingForm">
            <!-- Step 1: Detail Pesanan -->
            <div class="form-section active" data-step="1">
                <h2 class="form-title">Detail Pemesan</h2>
                
                <div class="radio-group">
                    <input type="radio" id="tuan" name="title" value="Tuan" class="radio-button" required>
                    <label for="tuan" class="radio-label">Tuan</label>

                    <input type="radio" id="nyonya" name="title" value="Nyonya" class="radio-button">
                    <label for="nyonya" class="radio-label">Nyonya</label>

                    <input type="radio" id="nona" name="title" value="Nona" class="radio-button">
                    <label for="nona" class="radio-label">Nona</label>
                </div>

                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-navigation">
                    <div></div>
                    <button type="button" class="btn btn-next" onclick="nextStep(1)">Lanjutkan</button>
                </div>
            </div>

            <!-- Step 2: Jumlah Tamu -->
            <div class="form-section" data-step="2">
                <h2 class="form-title">Jumlah Tamu</h2>
                
                <div class="form-group">
                    <label for="adults">Jumlah Dewasa</label>
                    <input type="number" id="adults" name="adults" min="1" value="1" class="form-control number-input" required>
                </div>

                <div class="form-group">
                    <label for="children">Jumlah Anak-anak</label>
                    <input type="number" id="children" name="children" min="0" value="0" class="form-control number-input" required>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-prev" onclick="prevStep(2)">Kembali</button>
                    <button type="button" class="btn btn-next" onclick="nextStep(2)">Lanjutkan</button>
                </div>
            </div>

            <!-- Step 3: Pembayaran -->
            <div class="form-section" data-step="3">
                <h2 class="form-title">Pilih Metode Pembayaran</h2>
                
                <div class="form-group">
                    <label for="payment_method">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="credit_card">Kartu Kredit</option>
                        <option value="bank_transfer">Transfer Bank</option>
                        <option value="e_wallet">E-Wallet</option>
                    </select>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-prev" onclick="prevStep(3)">Kembali</button>
                    <button type="submit" class="btn btn-next">Selesai</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function validateSection(step) {
            const section = document.querySelector(`.form-section[data-step="${step}"]`);
            const inputs = section.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (input.type === 'radio') {
                    const radioGroup = section.querySelectorAll(`input[name="${input.name}"]`);
                    const checked = Array.from(radioGroup).some(radio => radio.checked);
                    if (!checked) {
                        isValid = false;
                        input.closest('.radio-group').style.borderColor = 'red';
                    } else {
                        input.closest('.radio-group').style.borderColor = '';
                    }
                } else if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });

            return isValid;
        }

        function nextStep(currentStep) {
            if (!validateSection(currentStep)) {
                alert('Mohon lengkapi semua field yang diperlukan');
                return;
            }

            const currentSection = document.querySelector(`.form-section[data-step="${currentStep}"]`);
            const nextSection = document.querySelector(`.form-section[data-step="${currentStep + 1}"]`);
            
            currentSection.classList.remove('active');
            nextSection.classList.add('active');

            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep + 1}"]`).classList.add('active');
        }

        function prevStep(currentStep) {
            const currentSection = document.querySelector(`.form-section[data-step="${currentStep}"]`);
            const prevSection = document.querySelector(`.form-section[data-step="${currentStep - 1}"]`);
            
            currentSection.classList.remove('active');
            prevSection.classList.add('active');

            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep - 1}"]`).classList.add('active');
        }

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateSection(3)) {
                alert('Mohon lengkapi semua field yang diperlukan');
                return;
            }
            alert('Form berhasil disubmit!');
        });
    </script>
</body>
</html>