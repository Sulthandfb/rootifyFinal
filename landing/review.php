<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Ulasan Pengalaman</title>
    <style>
        :root {
            --primary-color:rgb(0, 0, 0);
            --secondary-color: #f39c12;
            --background-color: #f4f7f9;
            --text-color: #333;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-color);
        }
        input[type="text"], 
        select, 
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, 
        select:focus, 
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }
        textarea {
            height: 120px;
            resize: vertical;
        }
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            cursor: pointer;
            width: 40px;
            height: 40px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23ddd' d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100%;
            transition: transform 0.2s ease;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23f39c12' d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
            transform: scale(1.1);
        }
        .companions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .companion-tag {
            padding: 8px 16px;
            border: 2px solid var(--primary-color);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            color: var(--primary-color);
        }
        .companion-tag:hover {
            background-color: rgba(74, 144, 226, 0.1);
        }
        .companion-tag.active {
            background-color: var(--primary-color);
            color: white;
        }
        .photo-upload {
            border: 2px dashed var(--primary-color);
            padding: 30px;
            text-align: center;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .photo-upload:hover {
            background-color: rgba(74, 144, 226, 0.1);
        }
        .char-count {
            text-align: right;
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button[type="submit"]:hover {
            background-color: #3a7bc0;
            transform: translateY(-2px);
        }
        .terms {
            font-size: 0.9em;
            color: #666;
            margin-top: 20px;
        }
        .terms input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Bagikan Pengalaman Anda</h1>
        <form id="reviewForm">
            <div class="form-group">
                <label>Bagaimana pengalaman Anda?</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Luar biasa"></label>
                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Sangat baik"></label>
                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Baik"></label>
                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Cukup"></label>
                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Buruk"></label>
                </div>
            </div>

            <div class="form-group">
                <label for="visitDate">Kapan Anda berkunjung?</label>
                <select id="visitDate" name="visitDate" required>
                    <option value="">Pilih bulan</option>
                    <option value="january">Januari</option>
                    <option value="february">Februari</option>
                    <option value="march">Maret</option>
                    <option value="april">April</option>
                    <option value="may">Mei</option>
                    <option value="june">Juni</option>
                    <option value="july">Juli</option>
                    <option value="august">Agustus</option>
                    <option value="september">September</option>
                    <option value="october">Oktober</option>
                    <option value="november">November</option>
                    <option value="december">Desember</option>
                </select>
            </div>

            <div class="form-group">
                <label>Dengan siapa Anda pergi?</label>
                <div class="companions">
                    <button type="button" class="companion-tag" data-value="business">Bisnis</button>
                    <button type="button" class="companion-tag" data-value="couples">Pasangan</button>
                    <button type="button" class="companion-tag" data-value="family">Keluarga</button>
                    <button type="button" class="companion-tag" data-value="friends">Teman</button>
                    <button type="button" class="companion-tag" data-value="solo">Sendiri</button>
                </div>
            </div>

            <div class="form-group">
                <label for="review">Tulis ulasan Anda</label>
                <textarea id="review" name="review" placeholder="Pemandangannya luar biasa. Kami mengambil banyak foto!..." required></textarea>
                <div class="char-count"><span id="charCount">0</span>/2000 karakter maksimum</div>
            </div>

            <div class="form-group">
                <label for="title">Judul ulasan Anda</label>
                <input type="text" id="title" name="title" placeholder="Berikan inti dari pengalaman Anda" required>
            </div>

            <div class="form-group">
                <label>Tambahkan beberapa foto</label>
                <div class="photo-upload" id="photoUpload">
                    <p>Klik untuk menambahkan foto</p>
                    <p><small>(Opsional)</small></p>
                </div>
            </div>

            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    Saya menyatakan bahwa ulasan ini berdasarkan pengalaman pribadi saya dan merupakan opini jujur saya. Saya tidak memiliki hubungan pribadi atau bisnis dengan tempat ini, dan tidak ditawarkan insentif atau pembayaran dari tempat ini untuk menulis ulasan ini.
                </label>
            </div>

            <button type="submit">Kirim Ulasan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reviewForm');
            const reviewTextarea = document.getElementById('review');
            const charCount = document.getElementById('charCount');
            const photoUpload = document.getElementById('photoUpload');
            const companionTags = document.querySelectorAll('.companion-tag');

            // Character count
            reviewTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                if (count > 2000) {
                    this.value = this.value.slice(0, 2000);
                    charCount.textContent = 2000;
                }
            });

            // Photo upload (simulated)
            photoUpload.addEventListener('click', function() {
                alert('Fungsi unggah foto akan diimplementasikan di sini.');
            });

            // Companion tag selection
            companionTags.forEach(tag => {
                tag.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const rating = form.querySelector('input[name="rating"]:checked');
                if (!rating) {
                    alert('Silakan pilih rating.');
                    return;
                }

                const companions = Array.from(document.querySelectorAll('.companion-tag.active'))
                    .map(tag => tag.dataset.value);

                const formData = {
                    rating: rating.value,
                    visitDate: form.visitDate.value,
                    companions: companions,
                    review: form.review.value,
                    title: form.title.value,
                    terms: form.terms.checked
                };

                console.log('Data formulir:', formData);
                alert('Terima kasih atas ulasan Anda!');
                form.reset();
                companionTags.forEach(tag => tag.classList.remove('active'));
            });
        });
    </script>
</body>
</html>