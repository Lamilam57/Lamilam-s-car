@props(['bodyClass' => '', 'title' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ $title }} | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link
      href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css"
      rel="stylesheet"
    /> -->

    <link rel="stylesheet" href="/css/app.css" />
    <!-- <link rel="stylesheet" href="css/output.css" /> -->
</head>

<body @if ($bodyClass) class="{{ $bodyClass }}" @endif>

    {{ $slot }}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/scrollReveal.js/4.0.9/scrollreveal.js"
        integrity="sha512-XJgPMFq31Ren4pKVQgeD+0JTDzn0IwS1802sc+QTZckE6rny7AN2HLReq6Yamwpd2hFe5nJJGZLvPStWFv5Kww=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/js/app.js"></script>

    <script>
        // console.log('favourite script loaded');

        if (document.querySelector('[name="maker_id"]')) {
            document.querySelector('[name="maker_id"]').addEventListener('change', e => {
                fetch(`/api/models/${e.target.value}`)
                    .then(r => r.json())
                    .then(models => {
                        const select = document.getElementById('modelSelect');
                        select.innerHTML = '<option value="">Select Model</option>';
                        models.forEach(m => {
                            select.innerHTML += `<option value="${m.id}">${m.name}</option>`;
                        });
                    });
            });
        }


        if (document.querySelector('[name="state_id"]')) {
            document.querySelector('[name="state_id"]').addEventListener('change', e => {
                const stateId = e.target.value;
                const citySelect = document.getElementById('citySelect');

                citySelect.innerHTML = '<option value="">Select City</option>';

                // alert(stateId)
                if (!stateId) return;

                fetch(`/api/cities/${stateId}`)
                    .then(r => r.json())
                    .then(cities => {
                        cities.forEach(c => {
                            citySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                        });
                    })
                    .catch(() => {
                        citySelect.innerHTML = '<option value="">Failed to load cities</option>';
                    });
            });

        }
    </script>



    <script>
        // console.log('favourite script loaded');

        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.favourite-btn').forEach(button => {

                button.addEventListener('click', async function() {

                    const wrapper = button.closest('.car-favourite');
                    const carId = wrapper.dataset.carId;
                    // alert(carId);

                    const heartOutline = wrapper.querySelector('.heart-outline');
                    const heartFilled = wrapper.querySelector('.heart-filled');

                    try {
                        const res = await fetch(`/car/${carId}/favourite-toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                        // alert(res)
                        if (res.status === 403) {
                            const data = await res.json();
                            alert(data.error); // show error to user
                            return;
                        }

                        if (!res.ok) {
                            throw new Error(`HTTP error ${res.status}`);
                        }

                        const data = await res.json();

                        heartOutline.style.display = data.favourited ? 'none' : '';
                        heartFilled.style.display = data.favourited ? '' : 'none';

                    } catch (err) {
                        console.error('Favourite toggle failed', err);
                    }
                });

            });

        });
    </script>

    <script>
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.car-details-phone');
            if (!link) return;

            e.preventDefault();

            const phoneText = link.querySelector('.phone-text');
            const toggleText = link.querySelector('.toggle-text');

            if (link.classList.contains('revealed')) {
                phoneText.textContent = link.dataset.masked;
                toggleText.textContent = 'view full number';
                link.classList.remove('revealed');
            } else {
                phoneText.textContent = link.dataset.phone;
                toggleText.textContent = 'hide number';
                link.classList.add('revealed');
            }
        });
    </script>

    <script>
        const resetBtn = document.getElementById('resetBtn');
        const carForm = document.getElementById('searchCarForm');

        if (carForm && resetBtn) {
            resetBtn.addEventListener('click', () => {
                carForm.reset();
            });
        }
    </script>

    <script>
        const fileInput = document.getElementById('carFormImageUpload');
        const previewContainer = document.getElementById('imagePreviews');

        if (fileInput && previewContainer) {
            let allFiles = [];
            const MAX_IMAGES = 20;

            fileInput.addEventListener('change', e => {
                const newFiles = Array.from(e.target.files);

                // 1. Add new files without duplicates
                newFiles.forEach(file => {
                    const isDuplicate = allFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!isDuplicate && allFiles.length < MAX_IMAGES) {
                        allFiles.push(file);
                    }
                });

                // 2. Trim array to MAX_IMAGES
                if (allFiles.length > MAX_IMAGES) {
                    allFiles = allFiles.slice(0, MAX_IMAGES);
                }

                // 3. Clear previous previews
                previewContainer.innerHTML = '';

                // 4. Render one preview per file
                allFiles.forEach(file => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = event => {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.classList.add('preview-img'); // style in CSS
                        // previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });

                // 5. Update input files for backend submission
                const dataTransfer = new DataTransfer();
                allFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sortDropdown = document.getElementById('sortDropdown');
            const carList = document.getElementById('carList');

            if (sortDropdown && carList) {
                sortDropdown.addEventListener('change', () => {
                    const sort = sortDropdown.value;

                    const form = document.getElementById('searchCarForm');
                    const formData = new FormData(form);
                    formData.set('sort', sort); // add sort parameter
                    const queryString = new URLSearchParams(formData).toString();

                    fetch(`{{ route('car.search') }}?${queryString}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.text())
                        .then(html => {
                            carList.innerHTML = html;
                        })
                        .catch(err => console.error(err));
                });

            }
        });
    </script>

</body>

</html>
